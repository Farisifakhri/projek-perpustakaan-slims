# 📖 Proyek Perpustakaan Digital - SLiMS

Ini adalah proyek sistem manajemen perpustakaan digital yang dibangun menggunakan SLiMS (Senayan Library Management System) sebagai bagian dari tugas [Sebutkan nama mata kuliah atau proyek PKL]. Sistem ini bertujuan untuk mengelola data buku, keanggotaan, dan sirkulasi peminjaman di [Sebutkan nama institusi/sekolah, misal: FST UIN Jakarta].

---

## 🚀 Teknologi yang Digunakan

* **SLiMS (Senayan Library Management System)** Versi 9 Bulian
* **PHP**
* **MySQL / MariaDB**
* **Apache Web Server**

---

## 🛠️ Panduan Instalasi dan Konfigurasi

Untuk menjalankan proyek ini di komputer lokal, ikuti langkah-langkah berikut:

1.  **Prasyarat**:
    * Pastikan sudah menginstall **XAMPP** (atau web server sejenis) yang sudah mencakup Apache, PHP, dan MySQL.

2.  **Clone Repository**:
    * Buka Terminal/CMD, lalu clone repository ini ke dalam folder `htdocs` XAMPP Anda.
    ```bash
    cd C:\xampp\htdocs
    git clone [https://github.com/FarisFakhri1/projek-perpustakaan-slims.git](https://github.com/FarisFakhri1/projek-perpustakaan-slims.git)
    ```

3.  **Setup Database**:
    * Buka **phpMyAdmin** (`http://localhost/phpmyadmin`).
    * Buat database baru dengan nama, contohnya: `db_perpustakaan_slims`.
    * (Opsional) Jika Anda memiliki file `.sql`, impor file tersebut ke database yang baru saja dibuat.

4.  **Konfigurasi Koneksi**:
    * Di dalam folder proyek, cari file `sysconfig.inc.php`.
    * Duplikat file tersebut dan beri nama `sysconfig.local.inc.php`.
    * Buka file `sysconfig.local.inc.php` dan sesuaikan konfigurasinya:
    ```php
    <?php
    // database host
    $db_host = 'localhost';
    // database port
    $db_port = '3306';
    // database name
    $db_name = 'db_perpustakaan_slims'; // <-- Ganti dengan nama database Anda
    // database username
    $db_user = 'root'; // <-- Ganti jika username database Anda berbeda
    // database password
    $db_pass = ''; // <-- Ganti jika database Anda menggunakan password
    ```

5.  **Akses Aplikasi**:
    * Buka browser dan akses alamat `http://localhost/projek-perpustakaan-slims`.

---

## 📸 Tampilan Aplikasi (Opsional)

_(Anda bisa menambahkan screenshot aplikasi di sini untuk membuatnya lebih menarik)_

![Tampilan Halaman Utama](link-gambar-anda.png)
![Tampilan Halaman Admin](link-gambar-anda.png)

---

Dibuat dengan ❤️ oleh **Muhammad Fakhri Alfarisi**.
