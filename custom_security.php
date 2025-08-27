<?php
/**
 * File Helper Keamanan Kustom untuk SLiMS
 * Berisi fungsi untuk enkripsi, dekripsi, dan masking data.
 * Dibuat oleh: Fakhri Alfarisi (sesuaikan dengan nama Aa)
 * Tanggal: 27 Agustus 2025
 */

// Pastikan file ini tidak diakses secara langsung
if (defined('INDEX_AUTH') === false) {
    die("Cannot access this file directly!");
}

// -----------------------------------------------------------------------------
// PENGATURAN KUNCI DAN METODE ENKRIPSI
// -----------------------------------------------------------------------------
// Ambil kunci dari environment variable. Pastikan file .env sudah ada!
// Jika tidak ditemukan, gunakan nilai default (TIDAK DISARANKAN UNTUK PRODUKSI)
$encryption_key = getenv('APP_ENCRYPTION_KEY') ?: 'kunci-default-yang-sangat-tidak-aman';

if (strlen($encryption_key) !== 32) {
    die("Error: Kunci enkripsi harus 32 bytes (256 bit).");
}

define('ENCRYPTION_KEY', $encryption_key);
define('ENCRYPTION_CIPHER', 'aes-256-gcm');


// -----------------------------------------------------------------------------
// FUNGSI ENKRIPSI DAN DEKRIPSI
// -----------------------------------------------------------------------------

/**
 * Mengenkripsi data plaintext menggunakan AES-256-GCM.
 * @param string|null $plaintext Data yang akan dienkripsi.
 * @return string|null Data terenkripsi dalam format base64, atau null jika input kosong.
 */
function encryptData($plaintext)
{
    if (empty($plaintext)) {
        return null;
    }
    try {
        $iv_len = openssl_cipher_iv_length(ENCRYPTION_CIPHER);
        $iv = openssl_random_pseudo_bytes($iv_len);
        $tag = null; // Tag akan diisi oleh openssl_encrypt dengan mode GCM
        $ciphertext = openssl_encrypt($plaintext, ENCRYPTION_CIPHER, ENCRYPTION_KEY, OPENSSL_RAW_DATA, $iv, $tag, '', 16);

        // Gabungkan IV, ciphertext, dan tag untuk disimpan
        return base64_encode($iv . $ciphertext . $tag);
    } catch (Exception $e) {
        // Handle error, jangan tampilkan pesan error detail ke user
        error_log('Encryption failed: ' . $e->getMessage());
        return null;
    }
}

/**
 * Mendekripsi data yang dienkripsi dengan encryptData().
 * @param string|null $encrypted_data Data terenkripsi dalam format base64.
 * @return string|null Data asli hasil dekripsi, atau null jika gagal/input kosong.
 */
function decryptData($encrypted_data)
{
    if (empty($encrypted_data)) {
        return null;
    }
    try {
        $decoded_data = base64_decode($encrypted_data);
        $iv_len = openssl_cipher_iv_length(ENCRYPTION_CIPHER);
        
        // Ekstrak komponen dari data terenkripsi
        $iv = substr($decoded_data, 0, $iv_len);
        $ciphertext = substr($decoded_data, $iv_len, -16);
        $tag = substr($decoded_data, -16);

        return openssl_decrypt($ciphertext, ENCRYPTION_CIPHER, ENCRYPTION_KEY, OPENSSL_RAW_DATA, $iv, $tag);
    } catch (Exception $e) {
        error_log('Decryption failed: ' . $e->getMessage());
        return null; // Kembalikan null jika dekripsi gagal
    }
}


// -----------------------------------------------------------------------------
// FUNGSI MASKING DATA
// -----------------------------------------------------------------------------

/**
 * Mem-masking NIK, menampilkan beberapa digit awal dan akhir.
 * @param string|null $nik Nomor Induk Kependudukan.
 * @return string NIK yang sudah di-masking.
 */
function maskNik($nik)
{
    if (empty($nik) || strlen($nik) < 8) {
        return 'NIK tidak valid';
    }
    // Tampilkan 4 digit pertama dan 4 digit terakhir
    return substr($nik, 0, 4) . '********' . substr($nik, -4);
}

/**
 * Mem-masking alamat email.
 * @param string|null $email Alamat email.
 * @return string Email yang sudah di-masking.
 */
function maskEmail($email)
{
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 'Email tidak valid';
    }
    // Regex untuk menyembunyikan sebagian karakter sebelum dan sesudah @
    return preg_replace('/(.).*?@(.).*?\./', '$1***@$2***.', $email);
}