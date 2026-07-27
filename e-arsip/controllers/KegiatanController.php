<?php
require_once 'config/database.php';

// Auto-migrate: create table if not exists
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS `kegiatan` (
      `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
      `jenis_kegiatan` VARCHAR(100) NOT NULL,
      `nama_kegiatan` VARCHAR(255) NOT NULL,
      `status_aktif` ENUM('Aktif', 'Nonaktif') DEFAULT 'Aktif',
      `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
} catch (Exception $e) {}

if ($page === 'kegiatan') {
    $stmt = $pdo->query("SELECT * FROM kegiatan ORDER BY nama_kegiatan ASC");
    $kegiatanList = $stmt->fetchAll();
    
    $title = "Manajemen Kegiatan - E-Arsip";
    require 'views/master/kegiatan.php';
    exit;
}

if ($page === 'kegiatan_action') {
    $action = $_GET['action'] ?? '';
    
    if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $jenis = trim($_POST['jenis_kegiatan']);
        $nama = trim($_POST['nama_kegiatan']);
        $status = $_POST['status_aktif'];
        
        try {
            $stmt = $pdo->prepare("INSERT INTO kegiatan (jenis_kegiatan, nama_kegiatan, status_aktif) VALUES (?, ?, ?)");
            $stmt->execute([$jenis, $nama, $status]);
            
            $_SESSION['flash_msg'] = "Data kegiatan berhasil ditambahkan.";
            $_SESSION['flash_type'] = "success";
            
            $log = $pdo->prepare("INSERT INTO activity_logs (id_user, action, description) VALUES (?, 'Tambah', ?)");
            $log->execute([$_SESSION['user_id'], "Menambah master kegiatan: $nama"]);
        } catch (PDOException $e) {
            $_SESSION['flash_msg'] = "Gagal menambah data: " . $e->getMessage();
            $_SESSION['flash_type'] = "error";
        }
        
        header("Location: " . base_url('index.php?page=kegiatan'));
        exit;
    }
    
    if ($action === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = (int)$_POST['id'];
        $jenis = trim($_POST['jenis_kegiatan']);
        $nama = trim($_POST['nama_kegiatan']);
        $status = $_POST['status_aktif'];
        
        try {
            $stmt = $pdo->prepare("UPDATE kegiatan SET jenis_kegiatan=?, nama_kegiatan=?, status_aktif=? WHERE id=?");
            $stmt->execute([$jenis, $nama, $status, $id]);
            
            $_SESSION['flash_msg'] = "Data kegiatan berhasil diupdate.";
            $_SESSION['flash_type'] = "success";
            
            $log = $pdo->prepare("INSERT INTO activity_logs (id_user, action, description) VALUES (?, 'Edit', ?)");
            $log->execute([$_SESSION['user_id'], "Mengupdate master kegiatan: $nama"]);
        } catch (PDOException $e) {
            $_SESSION['flash_msg'] = "Gagal mengupdate data: " . $e->getMessage();
            $_SESSION['flash_type'] = "error";
        }
        
        header("Location: " . base_url('index.php?page=kegiatan'));
        exit;
    }
    
    if ($action === 'delete' && isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        
        try {
            // Kita coba delete. Jika dipakai, akan gagal karena foreign key (meskipun kita belum set FK, untuk jaga-jaga).
            $stmt_get = $pdo->prepare("SELECT nama_kegiatan FROM kegiatan WHERE id = ?");
            $stmt_get->execute([$id]);
            $nama = $stmt_get->fetchColumn();
            
            $stmt = $pdo->prepare("DELETE FROM kegiatan WHERE id = ?");
            $stmt->execute([$id]);
            
            $_SESSION['flash_msg'] = "Data kegiatan berhasil dihapus.";
            $_SESSION['flash_type'] = "success";
            
            if ($nama) {
                $log = $pdo->prepare("INSERT INTO activity_logs (id_user, action, description) VALUES (?, 'Hapus', ?)");
                $log->execute([$_SESSION['user_id'], "Menghapus master kegiatan: $nama"]);
            }
        } catch (PDOException $e) {
            // Fallback: update status jadi Nonaktif jika tidak bisa dihapus
            $pdo->prepare("UPDATE kegiatan SET status_aktif='Nonaktif' WHERE id=?")->execute([$id]);
            $_SESSION['flash_msg'] = "Data kegiatan sedang digunakan. Status diubah menjadi Nonaktif.";
            $_SESSION['flash_type'] = "warning";
        }
        
        header("Location: " . base_url('index.php?page=kegiatan'));
        exit;
    }
}
