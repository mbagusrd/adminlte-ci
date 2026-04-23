# CI3 Env + Public Installer

Script ini memasang pola konfigurasi CI3 agar:
- `public/index.php` menjadi front controller
- aplikasi bisa membaca `.env` lewat `getenv()`
- root `.htaccess` me-rewrite trafik ke `public/`
- `application/config/config.php` memakai `BASE_URL` dan `SESSION_NAME` dari env
- `application/config/database.php` memakai variabel DB dari env

## Menjalankan script

Jalankan dari project ini:

```bash
php scripts/ci3-install-env-public.php --target="D:/path/ke/project-ci3-lain"
```

Opsional:

```bash
php scripts/ci3-install-env-public.php --target="D:/path/ke/project-ci3-lain" --force
```

`--force` dipakai jika Anda ingin menimpa `public/index.php` dengan salinan dari `index.php` root pada project target.

Untuk memaksa menimpa `application/controllers/Mytools.php` dengan template bawaan installer:

```bash
php scripts/ci3-install-env-public.php --target="D:/path/ke/project-ci3-lain" --force-mytools
```

Catatan:
- `--force` otomatis juga mengaktifkan penimpaan `Mytools.php`.
- `--force-ci-tools` masih didukung sebagai alias lama.

## Perubahan yang dilakukan

1. Membuat folder `public/` jika belum ada.
2. Membuat `public/index.php` dari `index.php` root jika belum ada.
3. Menambahkan loader `.env` pada `public/index.php`.
4. Mengubah path CI menjadi:
   - `$system_path = $root_path . '/system';`
   - `$application_folder = $root_path . '/application';`
5. Mengubah environment loader jadi:
   - `define('ENVIRONMENT', getenv('CI_ENV') ? getenv('CI_ENV') : 'development');`
6. Memperbarui `application/config/config.php`:
   - `base_url` dari `BASE_URL`
   - `sess_cookie_name` dari `SESSION_NAME`
7. Memperbarui `application/config/database.php` agar membaca env:
   - `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS`
8. Menambah atau membuat root `.htaccess` untuk rewrite ke `public/`.
9. Membuat `.env.example` (jika belum ada) dan `.env` (jika belum ada), lalu memastikan key berikut tersedia:
   - `CI_ENV`, `BASE_URL`, `SESSION_NAME`
   - `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS`
10. Membuat `application/controllers/Mytools.php` (jika belum ada) berisi command CLI:
   - `php public/index.php mytools generate_session_name`
   - `php public/index.php mytools generate_all_keys`

Template `.env.example` akan ditulis dengan format seperti ini:

```env
# Application
CI_ENV=development
BASE_URL=http://localhost/
SESSION_NAME=ci3_session

# Database
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=ci3_db
DB_USER=root
DB_PASS=
```

## Backup otomatis

Setiap file yang diubah akan dibackup ke:

`.ci3-migration-backup/YYYYmmdd_HHMMSS/`

Jika ada masalah, Anda bisa restore dari folder backup ini.
