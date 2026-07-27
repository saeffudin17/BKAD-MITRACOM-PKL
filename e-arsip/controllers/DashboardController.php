<?php
// Pastikan file ini di-include dari index.php sehingga $pdo sudah tersedia

// 1. Ambil Statistik Umum
$stmtArsip = $pdo->query("SELECT COUNT(*) FROM arsip_sp2d WHERE is_deleted = 0");
$total_arsip = $stmtArsip->fetchColumn();

$stmtInstansi = $pdo->query("SELECT COUNT(*) FROM instansi WHERE status_aktif = 'Aktif'");
$total_instansi = $stmtInstansi->fetchColumn();

$stmtBindex = $pdo->query("SELECT COUNT(*) FROM bindex");
$total_bindex = $stmtBindex->fetchColumn();

$stmtHariIni = $pdo->query("SELECT COUNT(*) FROM arsip_sp2d WHERE DATE(tanggal_arsip) = CURDATE() AND is_deleted = 0");
$arsip_hari_ini = $stmtHariIni->fetchColumn();

$stmtBulanIni = $pdo->query("SELECT COUNT(*) FROM arsip_sp2d WHERE MONTH(tanggal_arsip) = MONTH(CURDATE()) AND YEAR(tanggal_arsip) = YEAR(CURDATE()) AND is_deleted = 0");
$arsip_bulan_ini = $stmtBulanIni->fetchColumn();

$stmtDipinjam = $pdo->query("SELECT COUNT(*) FROM arsip_sp2d WHERE status_pengembalian = 'Dipinjam' AND is_deleted = 0");
$arsip_dipinjam = $stmtDipinjam->fetchColumn();

// 2. Data Grafik Arsip per Bulan (Tahun Berjalan)
$grafik_bulan = [];
for ($i = 1; $i <= 12; $i++) {
    $stmtGrafik = $pdo->prepare("SELECT COUNT(*) FROM arsip_sp2d WHERE MONTH(tanggal_arsip) = :bulan AND YEAR(tanggal_arsip) = YEAR(CURDATE()) AND is_deleted = 0");
    $stmtGrafik->execute([':bulan' => $i]);
    $grafik_bulan[] = $stmtGrafik->fetchColumn();
}
$grafik_bulan_json = json_encode($grafik_bulan);

// 3. Activity Logs (5 Terbaru)
$stmtLogs = $pdo->query("
    SELECT al.*, u.nama, u.foto 
    FROM activity_logs al 
    JOIN users u ON al.id_user = u.id 
    ORDER BY al.created_at DESC 
    LIMIT 5
");
$recent_activities = $stmtLogs->fetchAll();

// 4. Arsip Terbaru (5 Terbaru)
$stmtArsipTerbaru = $pdo->query("
    SELECT a.*, i.nama_instansi, b.nomor_bindex 
    FROM arsip_sp2d a 
    JOIN instansi i ON a.id_instansi = i.id 
    JOIN bindex b ON a.id_bindex = b.id 
    WHERE a.is_deleted = 0 
    ORDER BY a.created_at DESC 
    LIMIT 5
");
$arsip_terbaru = $stmtArsipTerbaru->fetchAll();

// Panggil View
$title = "Dashboard E-Arsip SP2D";
require 'views/dashboard/index.php';
