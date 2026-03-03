<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Tools Controller
 * Controller untuk berbagai utilitas sistem
 * Hanya dapat dijalankan via CLI
 */
class Tools extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        // Cek apakah dijalankan dari CLI
        if (PHP_SAPI !== 'cli') {
            echo "Akses ditolak. Controller ini hanya dapat dijalankan via CLI.\n";
            exit(1);
        }
    }

    /**
    /**
     * Generate random string untuk SESSION_NAME
     * dan update ke file .env
     */
    public function generate_session_name()
    {
        // Generate random string (16 karakter alphanumeric)
        $random_string = $this->_generate_random_string(16);
        $session_name = 'sess_' . $random_string;

        // Update file .env
        $result = $this->_update_env_file('SESSION_NAME', $session_name);

        if ($result) {
            echo "Sukses! SESSION_NAME telah diupdate.\n";
            echo "Nilai baru: " . $session_name . "\n";
            echo "\nSilakan refresh aplikasi untuk menerapkan perubahan.";
        } else {
            echo "Gagal mengupdate file .env\n";
            echo "Pastikan file .env ada dan writable.";
        }
    }

    /**
     * Generate random string
     * @param int $length Panjang string
     * @return string
     */
    private function _generate_random_string($length = 16)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characters_length = strlen($characters);
        $random_string = '';

        for ($i = 0; $i < $length; $i++) {
            $random_string .= $characters[mt_rand(0, $characters_length - 1)];
        }

        return $random_string;
    }

    /**
     * Update nilai variable di file .env
     * @param string $key Nama variable
     * @param string $value Nilai baru
     * @return bool
     */
    private function _update_env_file($key, $value)
    {
        $env_path = FCPATH . '.env';

        // Cek apakah file .env ada
        if (!file_exists($env_path)) {
            // Buat file .env dari .env.example jika belum ada
            $env_example_path = FCPATH . '.env.example';
            if (file_exists($env_example_path)) {
                copy($env_example_path, $env_path);
            } else {
                return false;
            }
        }

        // Baca konten file .env
        $env_content = file_get_contents($env_path);

        // Pattern untuk mencari key
        $pattern = '/^' . preg_quote($key, '/') . '=.*/m';

        // Cek apakah key sudah ada
        if (preg_match($pattern, $env_content)) {
            // Update nilai yang sudah ada
            $env_content = preg_replace($pattern, $key . '=' . $value, $env_content);
        } else {
            // Tambahkan key baru
            $env_content .= "\n" . $key . '=' . $value;
        }

        // Tulis kembali ke file
        return file_put_contents($env_path, $env_content) !== false;
    }

    /**
     * Generate semua security key
     * Untuk keamanan tambahan
     */
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
            echo "\nSemua key berhasil diupdate!";
            echo "\nSilakan refresh aplikasi untuk menerapkan perubahan.";
        } else {
            echo "\nBeberapa key gagal diupdate.";
        }
    }
}
