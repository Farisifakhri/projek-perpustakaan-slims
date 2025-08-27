<?php
/**
 * Halaman Registrasi Anggota Baru
 */

// Langkah 1: Definisikan INDEX_AUTH dan muat file konfigurasi SLiMS
// Ini PENTING agar kita bisa terhubung ke database ($dbs) dan menggunakan fungsi keamanan kita.
define('INDEX_AUTH', 1);
require_once __DIR__ . '/sysconfig.inc.php';

// Inisialisasi pesan untuk ditampilkan ke pengguna
$message = '';

// Langkah 2: Proses data HANYA jika form sudah di-submit (metode POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Langkah 3: Ambil data dari form dengan aman
    $nama = trim($_POST['Nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm'] ?? '';

    // Langkah 4: Lakukan validasi data
    if (empty($nama) || empty($email) || empty($password)) {
        $message = '<div style="color: red;">Semua field wajib diisi!</div>';
    } elseif ($password !== $confirm_password) {
        $message = '<div style="color: red;">Password dan Konfirmasi Password tidak cocok!</div>';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = '<div style="color: red;">Format email tidak valid!</div>';
    } else {
        // Langkah 5: Hash password dan enkripsi email
        $hashed_password = password_hash($password, PASSWORD_ARGON2ID);
        $encrypted_email = encryptData($email); // Gunakan fungsi enkripsi kita!
        
        // Cek jika enkripsi berhasil
        if (is_null($encrypted_email)) {
            $message = '<div style="color: red;">Terjadi kesalahan sistem saat mengenkripsi data.</div>';
        } else {
            // Langkah 6: Gunakan PREPARED STATEMENTS untuk query yang aman dari SQL Injection
            // Perhatikan nama kolomnya: member_name, member_email, user_password (sesuaikan jika berbeda)
            $sql = "INSERT INTO member (member_name, member_email, user_password) VALUES (?, ?, ?)";
            
            // Siapkan statement menggunakan objek database SLiMS ($dbs)
            $stmt = $dbs->prepare($sql);
            
            // Ikat parameter ke statement ('sss' artinya 3 variabel adalah string)
            $stmt->bind_param('sss', $nama, $encrypted_email, $hashed_password);
            
            // Eksekusi statement
            if ($stmt->execute()) {
                $message = '<div style="color: green;">Registrasi berhasil! Silakan login.</div>';
            } else {
                // Cek apakah email sudah terdaftar
                if ($dbs->errno === 1062) { // 1062 adalah kode error untuk duplicate entry
                    $message = '<div style="color: red;">Registrasi gagal! Email sudah terdaftar.</div>';
                } else {
                    $message = '<div style="color: red;">Registrasi gagal! Terjadi kesalahan pada database.</div>';
                }
            }
            
            // Tutup statement
            $stmt->close();
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrasi Anggota</title>
    </head>
<body>

    <h2>Form Registrasi</h2>

    <?php echo $message; ?>

    <form method="POST" action="">
        <input type="text" name="Nama" placeholder="Nama Lengkap" required><br>
        <input type="email" name="email" placeholder="Alamat Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <input type="password" name="confirm" placeholder="Konfirmasi Password" required><br>
        <button type="submit">Daftar</button>
    </form>

</body>
</html>