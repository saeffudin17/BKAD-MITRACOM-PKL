<?php
require_once 'config/database.php';

if ($page === 'laporan') {
    // Ambil data instansi untuk filter
    $stmtInstansi = $pdo->query("SELECT id, nama_instansi FROM instansi WHERE status_aktif = 'Aktif' ORDER BY nama_instansi ASC");
    $instansiList = $stmtInstansi->fetchAll();
    
    $title = "Cetak Laporan Arsip - E-Arsip";
    require 'views/laporan/index.php';
    exit;
}

if ($page === 'laporan_cetak') {
    // Tangkap parameter filter
    $tgl_awal = $_GET['tgl_awal'] ?? '';
    $tgl_akhir = $_GET['tgl_akhir'] ?? '';
    $id_instansi = $_GET['id_instansi'] ?? '';
    $status = $_GET['status'] ?? '';
    
    // Validasi
    if(empty($tgl_awal) || empty($tgl_akhir)){
        die("Rentang tanggal harus diisi!");
    }
    
    // Bangun Query secara dinamis
    $sql = "SELECT a.*, i.nama_instansi, b.nomor_bindex, r.nama_rak, u.nama as nama_petugas 
            FROM arsip_sp2d a 
            LEFT JOIN instansi i ON a.id_instansi = i.id 
            LEFT JOIN bindex b ON a.id_bindex = b.id 
            LEFT JOIN rak r ON a.id_rak = r.id 
            LEFT JOIN users u ON a.id_user = u.id 
            WHERE a.is_deleted = 0 
            AND a.tanggal_sp2d >= ? AND a.tanggal_sp2d <= ?";
            
    $params = [$tgl_awal, $tgl_akhir];
    
    if(!empty($id_instansi)){
        $sql .= " AND a.id_instansi = ?";
        $params[] = $id_instansi;
    }
    
    if(!empty($status)){
        $sql .= " AND a.status_arsip = ?";
        $params[] = $status;
    }
    
    $sql .= " ORDER BY a.tanggal_sp2d ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $dataLaporan = $stmt->fetchAll();
    
    // Log Activity (Hanya dicatat ketika tombol cetak dieksekusi / halaman ini terbuka)
    $log = $pdo->prepare("INSERT INTO activity_logs (id_user, action, description) VALUES (?, 'Cetak', ?)");
    $log->execute([$_SESSION['user_id'], "Mencetak laporan arsip SP2D periode $tgl_awal s/d $tgl_akhir"]);
    
    $title = "Laporan Arsip SP2D - Cetak";
    require 'views/laporan/cetak.php';
    exit;
}

if ($page === 'laporan_excel') {
    // Tangkap parameter filter
    $tgl_awal = $_GET['tgl_awal'] ?? '';
    $tgl_akhir = $_GET['tgl_akhir'] ?? '';
    $id_instansi = $_GET['id_instansi'] ?? '';
    $status = $_GET['status'] ?? '';
    
    // Validasi
    if(empty($tgl_awal) || empty($tgl_akhir)){
        die("Rentang tanggal harus diisi!");
    }
    
    // Bangun Query secara dinamis
    $sql = "SELECT a.*, i.nama_instansi, b.nomor_bindex, r.nama_rak, u.nama as nama_petugas 
            FROM arsip_sp2d a 
            LEFT JOIN instansi i ON a.id_instansi = i.id 
            LEFT JOIN bindex b ON a.id_bindex = b.id 
            LEFT JOIN rak r ON a.id_rak = r.id 
            LEFT JOIN users u ON a.id_user = u.id 
            WHERE a.is_deleted = 0 
            AND a.tanggal_sp2d >= ? AND a.tanggal_sp2d <= ?";
            
    $params = [$tgl_awal, $tgl_akhir];
    
    if(!empty($id_instansi)){
        $sql .= " AND a.id_instansi = ?";
        $params[] = $id_instansi;
    }
    
    if(!empty($status)){
        $sql .= " AND a.status_arsip = ?";
        $params[] = $status;
    }
    
    $sql .= " ORDER BY a.tanggal_sp2d ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $dataLaporan = $stmt->fetchAll();
    
    // Log Activity
    $log = $pdo->prepare("INSERT INTO activity_logs (id_user, action, description) VALUES (?, 'Export', ?)");
    $log->execute([$_SESSION['user_id'], "Export Excel laporan arsip SP2D periode $tgl_awal s/d $tgl_akhir"]);
    
    require 'views/laporan/excel.php';
    exit;
}

