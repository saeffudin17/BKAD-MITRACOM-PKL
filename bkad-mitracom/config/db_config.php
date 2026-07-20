<?php
/**
 * config/db_config.php
 *
 * KERANGKA KONEKSI DATABASE — BELUM AKTIF PADA TAHAP 1.
 * File ini hanya berisi rencana struktur untuk tahap pengembangan
 * berikutnya (otentikasi, panel admin, upload berkas, filter otomatis).
 *
 * -----------------------------------------------------------------
 * RENCANA KONEKSI (aktifkan saat backend mulai dikerjakan):
 *
 * define('DB_HOST', 'localhost');
 * define('DB_NAME', 'bkad_mitracom');
 * define('DB_USER', 'root');
 * define('DB_PASS', '');
 *
 * try {
 *     $pdo = new PDO(
 *         "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
 *         DB_USER, DB_PASS,
 *         [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
 *     );
 * } catch (PDOException $e) {
 *     die('Koneksi database gagal: ' . $e->getMessage());
 * }
 *
 * -----------------------------------------------------------------
 * RENCANA SKEMA TABEL UTAMA:
 *
 * users
 *   id, nama, username, email, password_hash, role ENUM('admin','user'),
 *   created_at
 *
 * pengumuman
 *   id, judul, ringkasan, isi, tanggal_publish, dibuat_oleh (FK users.id)
 *
 * berkas  (untuk fitur upload PDF/Excel)
 *   id, nama_file, path_file, jenis_file ENUM('pdf','xlsx'),
 *   entitas_pt VARCHAR   -- hasil filter otomatis nama PT (mis. "PT Mitracom
 *                            Solusi Teknologi" atau anak perusahaan terkait)
 *   kategori_laporan     -- mis. "Laporan Keuangan", "Data Aset", dll
 *   uploaded_by (FK users.id), uploaded_at
 *
 * -----------------------------------------------------------------
 * RENCANA ALUR "OTOMATISASI FILTER BERKAS":
 * 1. User/Admin mengunggah file PDF/Excel lewat Panel Admin.
 * 2. Sistem membaca metadata file (nama file, sheet/judul dokumen).
 * 3. Sistem mencocokkan teks terhadap daftar nama PT/entitas terdaftar
 *    (mis. tabel `entitas`), lalu mengisi kolom `entitas_pt` secara
 *    otomatis pada tabel `berkas`.
 * 4. Berkas otomatis tampil terkelompok per entitas di halaman
 *    Portal Dokumen (filter dropdown berdasarkan `entitas_pt`).
 */
