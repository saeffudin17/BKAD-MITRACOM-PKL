<?php
require_once 'config/database.php';

// Fitur Recycle Bin hanya untuk Admin
if ($_SESSION['user_role'] !== 'Admin') {
    $_SESSION['flash_msg'] = "Hanya Administrator yang dapat mengakses Recycle Bin.";
    $_SESSION['flash_type'] = "error";
    header("Location: " . base_url('index.php?page=dashboard'));
    exit;
}

$action = $_GET['action'] ?? '';

if ($page === 'recycle_bin') {
    // Tampilkan List Data Arsip yang Dihapus (is_deleted = 1)
    $stmt = $pdo->query("
        SELECT a.*, i.nama_instansi, b.nomor_bindex, r.nama_rak 
        FROM arsip_sp2d a 
        LEFT JOIN instansi i ON a.id_instansi = i.id 
        LEFT JOIN bindex b ON a.id_bindex = b.id 
        LEFT JOIN rak r ON a.id_rak = r.id 
        WHERE a.is_deleted = 1 
        ORDER BY a.id DESC
    ");
    $arsipDeleted = $stmt->fetchAll();
    
    $title = "Recycle Bin Arsip - E-Arsip";
    require 'views/recycle_bin/index.php';
    exit;
}

if ($page === 'recycle_bin_action') {
    
    // Aksi Restore
    if ($action === 'restore' && isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        
        $stmt_get = $pdo->prepare("SELECT nomor_sp2d FROM arsip_sp2d WHERE id = ? AND is_deleted = 1");
        $stmt_get->execute([$id]);
        $data_arsip = $stmt_get->fetch();
        
        if($data_arsip){
            $stmt = $pdo->prepare("UPDATE arsip_sp2d SET is_deleted = 0 WHERE id = ?");
            $stmt->execute([$id]);
            
            $_SESSION['flash_msg'] = "Arsip berhasil dikembalikan dari Recycle Bin.";
            $_SESSION['flash_type'] = "success";
            
            $log = $pdo->prepare("INSERT INTO activity_logs (id_user, action, description) VALUES (?, 'Edit', ?)");
            $log->execute([$_SESSION['user_id'], "Restore arsip SP2D: " . $data_arsip['nomor_sp2d']]);
        }
        
        header("Location: " . base_url('index.php?page=recycle_bin'));
        exit;
    }

    // Aksi Hard Delete (Hapus Permanen)
    if ($action === 'hard_delete' && isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        
        $stmt_get = $pdo->prepare("SELECT nomor_sp2d, file_pdf FROM arsip_sp2d WHERE id = ? AND is_deleted = 1");
        $stmt_get->execute([$id]);
        $data_arsip = $stmt_get->fetch();
        
        if($data_arsip){
            // Hapus file fisik PDF jika ada
            if(!empty($data_arsip['file_pdf'])) {
                $filepath = 'uploads/' . $data_arsip['file_pdf'];
                if(file_exists($filepath)) {
                    unlink($filepath);
                }
            }

            // Hapus dari Database
            $stmt = $pdo->prepare("DELETE FROM arsip_sp2d WHERE id = ?");
            $stmt->execute([$id]);
            
            $_SESSION['flash_msg'] = "Arsip telah dihapus secara permanen beserta file fisiknya.";
            $_SESSION['flash_type'] = "success";
            
            $log = $pdo->prepare("INSERT INTO activity_logs (id_user, action, description) VALUES (?, 'Hapus', ?)");
            $log->execute([$_SESSION['user_id'], "Hard Delete (Permanen) arsip SP2D: " . $data_arsip['nomor_sp2d']]);
        }
        
        header("Location: " . base_url('index.php?page=recycle_bin'));
        exit;
    }
}
