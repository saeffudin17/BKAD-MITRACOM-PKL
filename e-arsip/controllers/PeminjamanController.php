<?php
require_once 'config/database.php';

$action = $_GET['action'] ?? '';

if ($page === 'peminjaman') {
    // Ambil data arsip yang tidak dihapus (is_deleted = 0)
    $stmt = $pdo->query("
        SELECT a.id, a.nomor_sp2d, a.tanggal_sp2d, a.nama_kegiatan, a.status_pengembalian, 
               a.penerima_pengembalian, a.tanggal_pengembalian, 
               i.nama_instansi, b.nomor_bindex, r.nama_rak 
        FROM arsip_sp2d a 
        LEFT JOIN instansi i ON a.id_instansi = i.id 
        LEFT JOIN bindex b ON a.id_bindex = b.id 
        LEFT JOIN rak r ON a.id_rak = r.id 
        WHERE a.is_deleted = 0 
        ORDER BY 
            CASE WHEN a.status_pengembalian = 'Dipinjam' THEN 1 ELSE 2 END,
            a.tanggal_sp2d DESC
    ");
    $arsipList = $stmt->fetchAll();
    
    $title = "Sirkulasi Peminjaman Arsip - E-Arsip";
    require 'views/peminjaman/index.php';
    exit;
}

if ($page === 'peminjaman_action') {
    
    // Aksi Pinjam (Keluarkan Arsip Fisik)
    if ($action === 'pinjam' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = (int)$_POST['id'];
        $penerima = trim($_POST['penerima_pengembalian']);
        
        $stmt_get = $pdo->prepare("SELECT nomor_sp2d FROM arsip_sp2d WHERE id = ?");
        $stmt_get->execute([$id]);
        $data_arsip = $stmt_get->fetch();
        
        if($data_arsip){
            $stmt = $pdo->prepare("UPDATE arsip_sp2d SET status_pengembalian = 'Dipinjam', penerima_pengembalian = ?, tanggal_pengembalian = NULL WHERE id = ?");
            $stmt->execute([$penerima, $id]);
            
            $_SESSION['flash_msg'] = "Arsip fisik berhasil dipinjamkan ke: " . $penerima;
            $_SESSION['flash_type'] = "success";
            
            // Log Activity
            $log = $pdo->prepare("INSERT INTO activity_logs (id_user, action, description) VALUES (?, 'Edit', ?)");
            $log->execute([$_SESSION['user_id'], "Meminjamkan fisik arsip SP2D " . $data_arsip['nomor_sp2d'] . " kepada " . $penerima]);
        }
        
        header("Location: " . base_url('index.php?page=peminjaman'));
        exit;
    }

    // Aksi Kembali (Kembalikan Arsip Fisik ke Rak)
    if ($action === 'kembali' && isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        
        $stmt_get = $pdo->prepare("SELECT nomor_sp2d, penerima_pengembalian FROM arsip_sp2d WHERE id = ?");
        $stmt_get->execute([$id]);
        $data_arsip = $stmt_get->fetch();
        
        if($data_arsip){
            $tanggal_sekarang = date('Y-m-d');
            $stmt = $pdo->prepare("UPDATE arsip_sp2d SET status_pengembalian = 'Disimpan', tanggal_pengembalian = ? WHERE id = ?");
            $stmt->execute([$tanggal_sekarang, $id]);
            
            $_SESSION['flash_msg'] = "Arsip fisik berhasil dikembalikan ke dalam Rak.";
            $_SESSION['flash_type'] = "success";
            
            // Log Activity
            $log = $pdo->prepare("INSERT INTO activity_logs (id_user, action, description) VALUES (?, 'Edit', ?)");
            $log->execute([$_SESSION['user_id'], "Menerima pengembalian arsip SP2D " . $data_arsip['nomor_sp2d'] . " dari " . $data_arsip['penerima_pengembalian']]);
        }
        
        header("Location: " . base_url('index.php?page=peminjaman'));
        exit;
    }
}
