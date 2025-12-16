# Sistem Login & Register PHP OOP

Aplikasi web sederhana dengan fitur autentikasi (login/register) menggunakan PHP OOP, MySQL, dan Bootstrap 5.

## Fitur

- **Login** dengan username & password
- **Register** akun baru
- Validasi form (wajib diisi, konfirmasi password, dll)
- Proteksi halaman (harus login untuk akses `/artikel/*`)
- Password di-hash menggunakan `password_hash()`
- Tampilan responsif dengan Bootstrap 5

## Instalasi

### 1. Persyaratan
- PHP 7.4+
- MySQL 5.7+ / MariaDB 10.3+
- Web server (XAMPP/Laragon/WAMP)
- Ekstensi PHP: `mysqli`, `session`

### 2. Setup Database
1. Buat database baru (contoh: `lab11_php_oop`)
2. Import tabel `users_login` dengan menjalankan query berikut:

```sql
CREATE TABLE users_login (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Akun admin default
INSERT INTO users_login (username, password, nama)
VALUES (
  'admin',
  '$2y$10$uhdZ2x.hQfGqGz/..q7wue.3/a/e/e/e/e/e/e/e/e/e/e/e/e/e/e/',
  'Administrator'
);
```

### 3. Konfigurasi
Edit file `config.php` sesuai pengaturan database Anda:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');      // Ganti dengan username database

define('DB_PASS', '');          // Ganti dengan password database
define('DB_NAME', 'lab11_php_oop'); // Nama database
```

## Cara Menggunakan

### Halaman Login
- URL: `http://localhost/lab11_php_oop/auth/login`
- Default akun:
  - Username: `admin`
  - Password: `admin123`

### Halaman Register
- URL: `http://localhost/lab11_php_oop/auth/login?tab=register`
- Isi form dengan data yang valid
- Password minimal 6 karakter
- Setelah berhasil register, akan diarahkan ke halaman login

### Halaman yang Dilindungi
- Semua halaman di `/artikel/*` membutuhkan login
- Jika belum login, akan diarahkan ke halaman login
- Setelah login berhasil, akan diarahkan ke halaman yang diminta

## Struktur File

```
lab11_php_oop/
├── class/
│   └── Database.php     # Kelas untuk koneksi database
├── module/
│   └── auth/
│       └── login.php    # Halaman login & register
├── template/
│   ├── header.php       # Header halaman
│   ├── footer.php       # Footer halaman
│   └── sidebar.php      # Menu sidebar
├── config.php           # Konfigurasi database
└── index.php            # Router utama
```

## Keamanan

1. **Password Hashing**
   - Menggunakan `password_hash()` dan `password_verify()`
   - Hash menggunakan algoritma `PASSWORD_DEFAULT` (bcrypt)

2. **SQL Injection**
   - Menggunakan prepared statement untuk query database

3. **XSS Protection**
   - Output di-escape dengan `htmlspecialchars()`

4. **Session Security**
   - Session dimulai di awal aplikasi
   - Data sensitif disimpan di server

## Troubleshooting

### Tabel users_login tidak ditemukan
Pastikan:
1. Database sudah dibuat
2. Tabel `users_login` sudah di-import
3. Nama database di `config.php` benar

### Gagal koneksi database
Periksa:
1. Kredensial database di `config.php`
2. Service MySQL/MariaDB berjalan
3. Port database benar (default: 3306)

## Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).

---
Dibuat dengan ❤️ untuk keperluan belajar.
