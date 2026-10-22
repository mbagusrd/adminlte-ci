<?php
/**
 * Load Environment Variables from .env file
 *
 * @param string $path Path to .env file or directory containing .env
 * @return void
 */
function load_env($path)
{
    $env_file = is_dir($path) ? rtrim($path, '/\\') . DIRECTORY_SEPARATOR . '.env' : $path;

    if (!file_exists($env_file)) {
        header('HTTP/1.1 503 Service Unavailable.', true, 503);
        echo '<h1>Konfigurasi Tidak Ditemukan</h1>';
        echo '<p>File <b>.env</b> tidak ditemukan di root direktori aplikasi.</p>';
        echo '<p>Silakan copy file <b>.env.example</b> menjadi <b>.env</b>:</p>';
        echo '<code>cp .env.example .env</code>';
        echo '<p>Kemudian jalankan perintah CLI untuk generate session:</p>';
        echo '<code>php index.php mytools generate_session_name</code>';
        exit(1);
    }

    $lines = file($env_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '#') === 0) {
            continue;
        }

        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);

            if (
                (strpos($value, '"') === 0 && strrpos($value, '"') === strlen($value) - 1) ||
                (strpos($value, "'") === 0 && strrpos($value, "'") === strlen($value) - 1)
            ) {
                $value = substr($value, 1, -1);
            }

            putenv("$key=$value");
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }

    if (empty(getenv('SESSION_NAME'))) {
        header('HTTP/1.1 503 Service Unavailable.', true, 503);
        echo '<h1>Konfigurasi Tidak Lengkap</h1>';
        echo '<p>SESSION_NAME belum diatur di file <b>.env</b>.</p>';
        echo '<p>Silakan jalankan perintah CLI berikut untuk generate session:</p>';
        echo '<code>php index.php mytools generate_session_name</code>';
        exit(1);
    }
}
