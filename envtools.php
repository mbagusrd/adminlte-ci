<?php
/**
 * CLI Tools Utility
 * Run via terminal:
 *   php envtools.php copy_env
 *   php envtools.php generate_session_name
 *   php envtools.php generate_all_keys
 */

if (PHP_SAPI !== 'cli') {
    echo "Akses ditolak. Script ini hanya dapat dijalankan via CLI.\n";
    exit(1);
}

class EnvTools
{
    private $base_dir;

    public function __construct()
    {
        $this->base_dir = __DIR__ . DIRECTORY_SEPARATOR;
    }

    public function copy_env()
    {
        $env_path = $this->base_dir . '.env';
        $env_example_path = $this->base_dir . '.env.example';

        if (file_exists($env_path)) {
            echo "File .env sudah ada. Tidak dilakukan perubahan.\n";
            return;
        }

        if (!file_exists($env_example_path)) {
            echo "Gagal: file .env.example tidak ditemukan.\n";
            return;
        }

        if (copy($env_example_path, $env_path)) {
            echo "Sukses: .env berhasil dibuat dari .env.example\n";
        } else {
            echo "Gagal menduplikasi file .env.example ke .env\n";
        }
    }

    public function generate_session_name()
    {
        $random_string = $this->_generate_random_string(16);
        $session_name = 'sess_' . $random_string;

        $result = $this->_update_env_file('SESSION_NAME', $session_name);

        if ($result) {
            echo "Sukses! SESSION_NAME telah diupdate.\n";
            echo "Nilai baru: " . $session_name . "\n";
            echo "Silakan refresh aplikasi untuk menerapkan perubahan.\n";
        } else {
            echo "Gagal mengupdate file .env\n";
            echo "Pastikan file .env ada dan writable.\n";
        }
    }

    public function generate_all_keys()
    {
        $keys = [
            'SESSION_NAME' => 'sess_' . $this->_generate_random_string(16),
        ];

        $success = true;
        foreach ($keys as $key => $value) {
            if (!$this->_update_env_file($key, $value)) {
                $success = false;
                echo "Gagal mengupdate: " . $key . "\n";
            } else {
                echo "Sukses: " . $key . " = " . $value . "\n";
            }
        }

        if ($success) {
            echo "\nSemua key berhasil diupdate!\n";
            echo "Silakan refresh aplikasi untuk menerapkan perubahan.\n";
        } else {
            echo "\nBeberapa key gagal diupdate.\n";
        }
    }

    private function _generate_random_string($length = 16)
    {
        return bin2hex(random_bytes((int) ceil($length / 2)));
    }

    private function _update_env_file($key, $value)
    {
        $env_path = $this->base_dir . '.env';

        if (!file_exists($env_path)) {
            $env_example_path = $this->base_dir . '.env.example';
            if (file_exists($env_example_path)) {
                copy($env_example_path, $env_path);
            } else {
                return false;
            }
        }

        $env_content = file_get_contents($env_path);
        $pattern = '/^' . preg_quote($key, '/') . '=.*/m';

        if (preg_match($pattern, $env_content)) {
            $env_content = preg_replace($pattern, $key . '=' . $value, $env_content);
        } else {
            $env_content .= "\n" . $key . '=' . $value;
        }

        return file_put_contents($env_path, $env_content) !== false;
    }
}

// ponytail: basic CLI router, upgrade to getopt/symfony-console if options expand.
$action = isset($argv[1]) ? $argv[1] : 'generate_session_name';
$tools = new EnvTools();

if (method_exists($tools, $action)) {
    $tools->$action();
} else {
    echo "Command tidak dikenali: {$action}\n";
    echo "Pilihan command: copy_env, generate_session_name, generate_all_keys\n";
    exit(1);
}

