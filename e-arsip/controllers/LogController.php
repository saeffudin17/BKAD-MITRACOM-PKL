<?php
require_once 'config/database.php';

// Pastikan hanya Admin yang bisa melihat Activity Logs (Opsional, tapi praktik yang baik)
if (!in_array($_SESSION['user_role'], ['Admin', 'Direktur'])) {
    $_SESSION['flash_msg'] = "Anda tidak memiliki akses ke Log Sistem.";
    $_SESSION['flash_type'] = "error";
    header("Location: " . base_url('index.php?page=dashboard'));
    exit;
}

if ($page === 'logs') {
    // Ambil log dari database (maksimal 500 terbaru agar tidak berat)
    $stmt = $pdo->query("
        SELECT l.*, u.nama, u.username, u.role 
        FROM activity_logs l 
        LEFT JOIN users u ON l.id_user = u.id 
        ORDER BY l.created_at DESC 
        LIMIT 500
    ");
    $logList = $stmt->fetchAll();
    
    $title = "Sistem Activity Log - E-Arsip";
    require 'views/logs/index.php';
    exit;
}

if ($page === 'logs_action') {
    $action = $_GET['action'] ?? '';
    
    // Hanya Admin yang boleh menghapus
    if ($_SESSION['user_role'] !== 'Admin') {
        $_SESSION['flash_msg'] = "Hanya Administrator yang dapat mengosongkan log.";
        $_SESSION['flash_type'] = "error";
        header("Location: " . base_url('index.php?page=logs'));
        exit;
    }

    if ($action === 'clear_all') {
        try {
            // Kosongkan seluruh log
            $pdo->exec("TRUNCATE TABLE activity_logs");
            
            // Opsional: Catat bahwa admin baru saja mengosongkan log 
            $log = $pdo->prepare("INSERT INTO activity_logs (id_user, action, description) VALUES (?, 'Hapus', ?)");
            $log->execute([$_SESSION['user_id'], "Admin mengosongkan seluruh riwayat activity logs."]);
            
            $_SESSION['flash_msg'] = "Seluruh riwayat log berhasil dihapus.";
            $_SESSION['flash_type'] = "success";
        } catch (PDOException $e) {
            $_SESSION['flash_msg'] = "Gagal menghapus log: " . $e->getMessage();
            $_SESSION['flash_type'] = "error";
        }
        
        header("Location: " . base_url('index.php?page=logs'));
        exit;
    }
}
