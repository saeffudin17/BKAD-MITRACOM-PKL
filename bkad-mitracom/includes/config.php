<?php
/**
 * =========================================================
 * FILE: includes/config.php
 * Konfigurasi dasar aplikasi & koneksi database
 * BKAD - PT Mitracom Solusi Teknologi
 * =========================================================
 *
 * Tahap 1: struktur disiapkan untuk pengembangan lanjutan
 * (Sistem Otentikasi, Panel Admin, Upload & Filter Berkas).
 * Saat ini koneksi DB belum diaktifkan (masih tahap statis).
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ---------------------------------------------------------
// Konfigurasi Database (isi sesuai environment server)
// ---------------------------------------------------------
define('DB_HOST', 'localhost');
define('DB_NAME', 'db_bkad_mitracom');
define('DB_USER', 'root');
define('DB_PASS', '');

// ---------------------------------------------------------
// Konfigurasi Umum Aplikasi
// ---------------------------------------------------------
define('APP_NAME', 'BKAD - PT Mitracom Solusi Teknologi');
define('APP_URL', 'http://localhost/bkad-mitracom');
define('UPLOAD_DIR', __DIR__ . '/../uploads/');       // Lokasi penyimpanan file PDF/Excel
define('ALLOWED_EXT', ['pdf', 'xls', 'xlsx']);         // Ekstensi file yang diizinkan
define('MAX_FILE_SIZE', 10 * 1024 * 1024);             // Maks 10 MB

/**
 * Daftar entitas/PT untuk fitur "Otomatisasi Filter Berkas" (tahap berikutnya).
 * Dipakai sebagai referensi pengelompokan metadata file saat diunggah,
 * dan nantinya disinkronkan dengan tabel `perusahaan` di database.
 */
$DAFTAR_ENTITAS = [
    'PT Mitracom Solusi Teknologi',
    'Anak Perusahaan A',
    'Anak Perusahaan B',
    'Mitra/Afiliasi Lainnya',
];

/**
 * getConnection()
 * Fungsi koneksi database (PDO) — siap dipakai saat tahap backend berikutnya.
 * Dibungkus try/catch agar landing page & login (tahap 1) tetap bisa
 * dijalankan meski database belum dikonfigurasi.
 */
function getConnection() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        return $pdo;
    } catch (PDOException $e) {
        error_log('Koneksi DB gagal: ' . $e->getMessage());
        return null;
    }
}

/**
 * isLoggedIn()
 * Helper cek status login (dipakai di tahap Panel Admin selanjutnya).
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * isAdmin()
 * Helper cek hak akses admin (dipakai di tahap Panel Admin selanjutnya).
 */
function isAdmin() {
    return isLoggedIn() && ($_SESSION['role'] ?? '') === 'admin';
}
