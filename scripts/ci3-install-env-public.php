<?php

defined('STDIN') || exit("This script must be run from CLI.\n");

$options = getopt('', ['target:', 'force', 'force-mytools', 'force-ci-tools']);
$target = isset($options['target']) ? $options['target'] : getcwd();
$force = array_key_exists('force', $options);
$forceMytools = array_key_exists('force-mytools', $options) || array_key_exists('force-ci-tools', $options) || $force;

$root = realpath($target);
if ($root === false) {
    fwrite(STDERR, "Target path not found: {$target}\n");
    exit(1);
}

if (!is_dir($root . DIRECTORY_SEPARATOR . 'application') || !is_dir($root . DIRECTORY_SEPARATOR . 'system')) {
    fwrite(STDERR, "Target is not a valid CI3 root (missing application/ or system/): {$root}\n");
    exit(1);
}

$backupDir = $root . DIRECTORY_SEPARATOR . '.ci3-migration-backup' . DIRECTORY_SEPARATOR . date('Ymd_His');
$changedFiles = [];

function ensureDir($path)
{
    if (!is_dir($path) && !mkdir($path, 0777, true) && !is_dir($path)) {
        throw new RuntimeException("Failed to create directory: {$path}");
    }
}

function backupFile($path, $root, $backupDir)
{
    if (!file_exists($path)) {
        return;
    }

    $relative = ltrim(str_replace($root, '', $path), DIRECTORY_SEPARATOR);
    $dest = $backupDir . DIRECTORY_SEPARATOR . $relative;
    ensureDir(dirname($dest));

    if (!copy($path, $dest)) {
        throw new RuntimeException("Failed to backup file: {$path}");
    }
}

function writeIfChanged($path, $newContent, $root, $backupDir, &$changedFiles)
{
    $oldContent = file_exists($path) ? file_get_contents($path) : null;
    if ($oldContent === $newContent) {
        return;
    }

    backupFile($path, $root, $backupDir);
    ensureDir(dirname($path));

    if (file_put_contents($path, $newContent) === false) {
        throw new RuntimeException("Failed to write file: {$path}");
    }

    $changedFiles[] = $path;
}

function normalizeNewline($text)
{
    return str_replace(["\r\n", "\r"], "\n", $text);
}

function ensureEnvKeys($path, array $pairs, $root, $backupDir, &$changedFiles)
{
    $content = file_exists($path) ? (string) file_get_contents($path) : '';
    $content = normalizeNewline($content);
    $original = $content;

    foreach ($pairs as $key => $value) {
        $pattern = '/^' . preg_quote($key, '/') . '=.*/m';
        if (!preg_match($pattern, $content)) {
            $content = rtrim($content, "\n") . "\n" . $key . '=' . $value . "\n";
        }
    }

    if ($content !== $original) {
        writeIfChanged($path, $content, $root, $backupDir, $changedFiles);
    }
}

function removeEnvKeys($path, array $keys, $root, $backupDir, &$changedFiles)
{
    if (!file_exists($path)) {
        return;
    }

    $content = normalizeNewline((string) file_get_contents($path));
    $original = $content;

    foreach ($keys as $key) {
        $content = preg_replace('/^' . preg_quote($key, '/') . '=.*\n?/m', '', $content);
    }

    $content = preg_replace("/\n{3,}/", "\n\n", trim($content) . "\n");

    if ($content !== $original) {
        writeIfChanged($path, $content, $root, $backupDir, $changedFiles);
    }
}

try {
    ensureDir($backupDir);

    $publicDir = $root . DIRECTORY_SEPARATOR . 'public';
    ensureDir($publicDir);

    $rootIndex = $root . DIRECTORY_SEPARATOR . 'index.php';
    $publicIndex = $publicDir . DIRECTORY_SEPARATOR . 'index.php';

    if (!file_exists($publicIndex)) {
        if (!file_exists($rootIndex)) {
            throw new RuntimeException('No index.php found in root or public/.');
        }
        if (!copy($rootIndex, $publicIndex)) {
            throw new RuntimeException('Failed to create public/index.php from root index.php.');
        }
        $changedFiles[] = $publicIndex;
    } elseif ($force && file_exists($rootIndex)) {
        backupFile($publicIndex, $root, $backupDir);
        if (!copy($rootIndex, $publicIndex)) {
            throw new RuntimeException('Failed to overwrite public/index.php from root index.php.');
        }
        $changedFiles[] = $publicIndex;
    }

    $indexContent = file_get_contents($publicIndex);
    if ($indexContent === false) {
        throw new RuntimeException('Failed to read public/index.php.');
    }

    $indexContent = normalizeNewline($indexContent);
    $originalIndex = $indexContent;

    if (strpos($indexContent, '$root_path = dirname(__DIR__);') === false) {
        $indexContent = preg_replace(
            '/<\?php\n/',
            "<?php\n\n// Base path points to CI root when this file lives in public/.\n\$root_path = dirname(__DIR__);\n",
            $indexContent,
            1
        );
    }

    $envLoader = <<<'PHP'

/*
 * ---------------------------------------------------------------
 * CI3_ENV_LOADER_START
 * ---------------------------------------------------------------
 * Read key=value pairs from .env (if present) and expose via getenv().
 */
$env_file = $root_path . '/.env';
if (is_file($env_file) && is_readable($env_file)) {
    $lines = file($env_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '#') === 0 || strpos($line, '=') === false) {
            continue;
        }

        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);

        if (
            (strpos($value, '"') === 0 && strrpos($value, '"') === strlen($value) - 1) ||
            (strpos($value, "'") === 0 && strrpos($value, "'") === strlen($value) - 1)
        ) {
            $value = substr($value, 1, -1);
        }

        putenv($key . '=' . $value);
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}
/* CI3_ENV_LOADER_END */
PHP;

    if (strpos($indexContent, 'CI3_ENV_LOADER_START') === false) {
        if (preg_match('/define\(\s*["\']ENVIRONMENT["\']\s*,/m', $indexContent)) {
            $indexContent = preg_replace(
                '/define\(\s*["\']ENVIRONMENT["\']\s*,/m',
                $envLoader . "\n\ndefine('ENVIRONMENT',",
                $indexContent,
                1
            );
        } else {
            $indexContent .= "\n" . $envLoader . "\n";
        }
    }

    $indexContent = preg_replace(
        '/\$system_path\s*=\s*["\'][^"\']*["\']\s*;/',
        "\$system_path = \$root_path . '/system';",
        $indexContent,
        1
    );

    $indexContent = preg_replace(
        '/\$application_folder\s*=\s*["\'][^"\']*["\']\s*;/',
        "\$application_folder = \$root_path . '/application';",
        $indexContent,
        1
    );

    $indexContent = preg_replace(
        '/define\(\s*["\']ENVIRONMENT["\']\s*,\s*[^\)]*\)\s*;/',
        "define('ENVIRONMENT', getenv('CI_ENV') ? getenv('CI_ENV') : 'development');",
        $indexContent,
        1
    );

    if ($indexContent !== $originalIndex) {
        writeIfChanged($publicIndex, $indexContent, $root, $backupDir, $changedFiles);
    }

    $configPath = $root . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
    if (file_exists($configPath)) {
        $configContent = file_get_contents($configPath);
        if ($configContent === false) {
            throw new RuntimeException('Failed to read application/config/config.php.');
        }

        $configContent = normalizeNewline($configContent);
        $originalConfig = $configContent;

        $configContent = preg_replace(
            '/\$config\[\'base_url\'\]\s*=\s*.*?;/',
            "\$config['base_url'] = getenv('BASE_URL') ? getenv('BASE_URL') : 'http://localhost/';",
            $configContent,
            1
        );

        $configContent = preg_replace(
            '/\$config\[\'sess_cookie_name\'\]\s*=\s*.*?;/',
            "\$config['sess_cookie_name'] = getenv('SESSION_NAME') ? getenv('SESSION_NAME') : 'ci3_session';",
            $configContent,
            1
        );

        if ($configContent !== $originalConfig) {
            writeIfChanged($configPath, $configContent, $root, $backupDir, $changedFiles);
        }
    }

    $htaccessPath = $root . DIRECTORY_SEPARATOR . '.htaccess';
    $rewriteBlock = <<<HTACCESS
# CI3_PUBLIC_REDIRECT_START
<IfModule mod_rewrite.c>
  RewriteEngine On
  RewriteBase /

  RewriteRule ^public/ - [L]
  RewriteCond %{REQUEST_URI} !^/public/
  RewriteRule ^(.*)$ public/$1 [L,QSA]
</IfModule>

<IfModule mod_autoindex.c>
  Options -Indexes
</IfModule>
# CI3_PUBLIC_REDIRECT_END
HTACCESS;

    if (file_exists($htaccessPath)) {
        $htaccessContent = normalizeNewline((string) file_get_contents($htaccessPath));
        if (strpos($htaccessContent, 'CI3_PUBLIC_REDIRECT_START') === false) {
            $newHtaccess = rtrim($htaccessContent) . "\n\n" . $rewriteBlock . "\n";
            writeIfChanged($htaccessPath, $newHtaccess, $root, $backupDir, $changedFiles);
        }
    } else {
        writeIfChanged($htaccessPath, $rewriteBlock . "\n", $root, $backupDir, $changedFiles);
    }

    $envDefaults = array(
        'APP_NAME' => 'ADMINLTE CI',
        'BASE_URL' => 'http://localhost/adminlte-ci/',
        'SESSION_NAME' => '',
        'CI_ENV' => 'development',
        'DB_HOST' => '127.0.0.1',
        'DB_PORT' => '3306',
        'DB_USER' => 'root',
        'DB_PASS' => '',
        'DB_NAME' => 'adminlte_ci',
    );

    $envDefault = <<<ENV
# Application Configuration
APP_NAME=ADMINLTE CI
BASE_URL=http://localhost/adminlte-ci
SESSION_NAME=

# Environment: development, testing, production
CI_ENV=development

# Database Configuration
DB_HOST=127.0.0.1
DB_PORT=3306
DB_USER=root
DB_PASS=
DB_NAME=adminlte_ci
ENV;
    $envDefault .= "\n";

    $envExamplePath = $root . DIRECTORY_SEPARATOR . '.env.example';

    if (!file_exists($envExamplePath)) {
        writeIfChanged($envExamplePath, $envDefault, $root, $backupDir, $changedFiles);
    }

    $envPath = $root . DIRECTORY_SEPARATOR . '.env';
    if (!file_exists($envPath)) {
        $seedEnv = file_exists($envExamplePath)
            ? (string) file_get_contents($envExamplePath)
            : $envDefault;
        writeIfChanged($envPath, $seedEnv, $root, $backupDir, $changedFiles);
    }

    ensureEnvKeys($envExamplePath, $envDefaults, $root, $backupDir, $changedFiles);
    ensureEnvKeys($envPath, $envDefaults, $root, $backupDir, $changedFiles);
    removeEnvKeys($envExamplePath, array('DB_DRIVER', 'DB_PREFIX', 'DB_CHARSET', 'DB_COLLATION'), $root, $backupDir, $changedFiles);
    removeEnvKeys($envPath, array('DB_DRIVER', 'DB_PREFIX', 'DB_CHARSET', 'DB_COLLATION'), $root, $backupDir, $changedFiles);

    $databasePath = $root . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'database.php';
    if (file_exists($databasePath)) {
        $databaseContent = file_get_contents($databasePath);
        if ($databaseContent === false) {
            throw new RuntimeException('Failed to read application/config/database.php.');
        }

        $databaseContent = normalizeNewline($databaseContent);
        $originalDatabase = $databaseContent;

        $dbReplacements = array(
            '/([\'\"]port[\'\"]\s*=>\s*)[^,]+,/m' => "$1(getenv('DB_PORT') ? getenv('DB_PORT') : '3306'),",
            '/([\'\"]hostname[\'\"]\s*=>\s*)[^,]+,/m' => "$1(getenv('DB_HOST') ? getenv('DB_HOST') : '127.0.0.1'),",
            '/([\'\"]username[\'\"]\s*=>\s*)[^,]+,/m' => "$1(getenv('DB_USER') ? getenv('DB_USER') : 'root'),",
            '/([\'\"]password[\'\"]\s*=>\s*)[^,]+,/m' => "$1(getenv('DB_PASS') ? getenv('DB_PASS') : ''),",
            '/([\'\"]database[\'\"]\s*=>\s*)[^,]+,/m' => "$1(getenv('DB_NAME') ? getenv('DB_NAME') : ''),",
        );

        foreach ($dbReplacements as $pattern => $replacement) {
            $databaseContent = preg_replace($pattern, $replacement, $databaseContent, 1);
        }

        if ($databaseContent !== $originalDatabase) {
            writeIfChanged($databasePath, $databaseContent, $root, $backupDir, $changedFiles);
        }
    }

    $mytoolsControllerPath = $root . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . 'Mytools.php';
    $mytoolsControllerTemplate = <<<'PHP'
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mytools extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (PHP_SAPI !== 'cli') {
            echo "Akses ditolak. Controller ini hanya dapat dijalankan via CLI.\n";
            exit(1);
        }
    }

    public function generate_session_name()
    {
        $sessionName = 'sess_' . $this->generateRandomString(16);

        if ($this->upsertEnvValue('SESSION_NAME', $sessionName)) {
            echo "Sukses! SESSION_NAME telah diupdate.\n";
            echo "Nilai baru: " . $sessionName . "\n";
            echo "Silakan refresh aplikasi untuk menerapkan perubahan.\n";
            return;
        }

        echo "Gagal mengupdate file .env\n";
        echo "Pastikan file .env ada dan writable.\n";
    }

    public function generate_all_keys()
    {
        $updates = array(
            'SESSION_NAME' => 'sess_' . $this->generateRandomString(16),
        );

        $failed = false;
        foreach ($updates as $key => $value) {
            if ($this->upsertEnvValue($key, $value)) {
                echo "Sukses: {$key} = {$value}\n";
                continue;
            }

            $failed = true;
            echo "Gagal mengupdate: {$key}\n";
        }

        if ($failed) {
            echo "Beberapa key gagal diupdate.\n";
            return;
        }

        echo "Semua key berhasil diupdate!\n";
        echo "Silakan refresh aplikasi untuk menerapkan perubahan.\n";
    }

    private function generateRandomString($length)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $maxIndex = strlen($characters) - 1;
        $result = '';

        for ($i = 0; $i < (int) $length; $i++) {
            $result .= $characters[random_int(0, $maxIndex)];
        }

        return $result;
    }

    private function upsertEnvValue($key, $value)
    {
        $projectRoot = rtrim(realpath(APPPATH . '..'), DIRECTORY_SEPARATOR);
        if (!$projectRoot) {
            return false;
        }

        $envPath = $projectRoot . DIRECTORY_SEPARATOR . '.env';
        $envExamplePath = $projectRoot . DIRECTORY_SEPARATOR . '.env.example';

        if (!file_exists($envPath)) {
            if (file_exists($envExamplePath)) {
                if (!copy($envExamplePath, $envPath)) {
                    return false;
                }
            } else {
                return false;
            }
        }

        $content = file_get_contents($envPath);
        if ($content === false) {
            return false;
        }

        $content = str_replace(array("\r\n", "\r"), "\n", $content);
        $pattern = '/^' . preg_quote($key, '/') . '=.*/m';

        if (preg_match($pattern, $content)) {
            $content = preg_replace($pattern, $key . '=' . $value, $content);
        } else {
            $content = rtrim($content, "\n") . "\n" . $key . '=' . $value . "\n";
        }

        return file_put_contents($envPath, $content) !== false;
    }
}
PHP;

    if (!file_exists($mytoolsControllerPath)) {
        writeIfChanged($mytoolsControllerPath, $mytoolsControllerTemplate . "\n", $root, $backupDir, $changedFiles);
    } elseif ($forceMytools) {
        writeIfChanged($mytoolsControllerPath, $mytoolsControllerTemplate . "\n", $root, $backupDir, $changedFiles);
    }

    $legacyCiToolsControllerPath = $root . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . 'Ci_tools.php';
    if (file_exists($legacyCiToolsControllerPath) && $forceMytools) {
        backupFile($legacyCiToolsControllerPath, $root, $backupDir);
        if (!unlink($legacyCiToolsControllerPath)) {
            throw new RuntimeException('Failed to remove legacy controller: application/controllers/Ci_tools.php');
        }
        $changedFiles[] = $legacyCiToolsControllerPath;
    }

    $legacyToolsControllerPath = $root . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . 'Tools.php';
    if (file_exists($legacyToolsControllerPath) && $forceMytools) {
        backupFile($legacyToolsControllerPath, $root, $backupDir);
        if (!unlink($legacyToolsControllerPath)) {
            throw new RuntimeException('Failed to remove legacy controller: application/controllers/Tools.php');
        }
        $changedFiles[] = $legacyToolsControllerPath;
    }

    if (empty($changedFiles)) {
        echo "No changes needed. CI3 env/public setup already present.\n";
    } else {
        echo "Setup complete. Updated files:\n";
        foreach ($changedFiles as $file) {
            echo ' - ' . str_replace($root . DIRECTORY_SEPARATOR, '', $file) . "\n";
        }
        echo "Backup saved at: " . str_replace($root . DIRECTORY_SEPARATOR, '', $backupDir) . "\n";
    }

    echo "\nNext steps:\n";
    echo "1) Review .env values (BASE_URL and SESSION_NAME).\n";
    echo "2) (Optional) Run CLI: php public/index.php mytools generate_session_name\n";
    echo "3) Point web server document root to project root (with mod_rewrite enabled), or directly to public/.\n";
    echo "4) Test URL access and CI CLI command.\n";
} catch (Throwable $e) {
    fwrite(STDERR, 'Error: ' . $e->getMessage() . "\n");
    fwrite(STDERR, "If needed, restore files from: {$backupDir}\n");
    exit(1);
}
