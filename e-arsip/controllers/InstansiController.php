<?php
// Pastikan file ini dipanggil dari index.php
require_once 'config/database.php';

$action = $_GET['action'] ?? 'list';

if ($page === 'instansi_action') {
    // Proses Tambah
    if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $nama_instansi = trim($_POST['nama_instansi']);
        $alamat = trim($_POST['alamat']);
        $no_telepon = trim($_POST['no_telepon']);
        $email = trim($_POST['email']);
        $status_aktif = $_POST['status_aktif'] ?? 'Aktif';

        $stmt = $pdo->prepare("INSERT INTO instansi (nama_instansi, alamat, no_telepon, email, status_aktif) VALUES (?, ?, ?, ?, ?)");
        if($stmt->execute([$nama_instansi, $alamat, $no_telepon, $email, $status_aktif])){
            $_SESSION['flash_msg'] = "Data Instansi berhasil ditambahkan.";
            $_SESSION['flash_type'] = "success";
            
            // Log Activity
            $log = $pdo->prepare("INSERT INTO activity_logs (id_user, action, description) VALUES (?, 'Tambah', ?)");
            $log->execute([$_SESSION['user_id'], "Menambahkan instansi baru: $nama_instansi"]);
        } else {
            $_SESSION['flash_msg'] = "Gagal menambahkan data.";
            $_SESSION['flash_type'] = "error";
        }
        header("Location: " . base_url('index.php?page=instansi'));
        exit;
    }

    // Proses Edit
    if ($action === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'];
        $nama_instansi = trim($_POST['nama_instansi']);
        $alamat = trim($_POST['alamat']);
        $no_telepon = trim($_POST['no_telepon']);
        $email = trim($_POST['email']);
        $status_aktif = $_POST['status_aktif'] ?? 'Aktif';

        $stmt = $pdo->prepare("UPDATE instansi SET nama_instansi=?, alamat=?, no_telepon=?, email=?, status_aktif=? WHERE id=?");
        if($stmt->execute([$nama_instansi, $alamat, $no_telepon, $email, $status_aktif, $id])){
            $_SESSION['flash_msg'] = "Data Instansi berhasil diupdate.";
            $_SESSION['flash_type'] = "success";
            
            // Log Activity
            $log = $pdo->prepare("INSERT INTO activity_logs (id_user, action, description) VALUES (?, 'Edit', ?)");
            $log->execute([$_SESSION['user_id'], "Mengupdate data instansi: $nama_instansi"]);
        } else {
            $_SESSION['flash_msg'] = "Gagal mengupdate data.";
            $_SESSION['flash_type'] = "error";
        }
        header("Location: " . base_url('index.php?page=instansi'));
        exit;
    }

    // Proses Hapus (Hanya bisa menghapus jika tidak berelasi ke arsip? Kita asumsikan bisa dihapus jika aman, RESTRICT foreign key)
    if ($action === 'delete' && isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        
        // Ambil nama instansi untuk log
        $stmt_get = $pdo->prepare("SELECT nama_instansi FROM instansi WHERE id = ?");
        $stmt_get->execute([$id]);
        $data_instansi = $stmt_get->fetch();
        
        try {
            $stmt = $pdo->prepare("DELETE FROM instansi WHERE id=?");
            $stmt->execute([$id]);
            $_SESSION['flash_msg'] = "Data Instansi berhasil dihapus.";
            $_SESSION['flash_type'] = "success";
            
            // Log Activity
            if($data_instansi){
                $log = $pdo->prepare("INSERT INTO activity_logs (id_user, action, description) VALUES (?, 'Hapus', ?)");
                $log->execute([$_SESSION['user_id'], "Menghapus instansi: " . $data_instansi['nama_instansi']]);
            }
        } catch (PDOException $e) {
            // Error 1451: Cannot delete or update a parent row: a foreign key constraint fails
            if($e->getCode() == '23000') {
                // Fallback: update status jadi Nonaktif jika tidak bisa dihapus
                $pdo->prepare("UPDATE instansi SET status_aktif='Nonaktif' WHERE id=?")->execute([$id]);
                $_SESSION['flash_msg'] = "Data Instansi masih terhubung dengan Arsip (mungkin di dalam Recycle Bin). Status Instansi otomatis diubah menjadi Nonaktif.";
                $_SESSION['flash_type'] = "warning";
            } else {
                $_SESSION['flash_msg'] = "Gagal menghapus data: " . $e->getMessage();
                $_SESSION['flash_type'] = "error";
            }
        }
        
        header("Location: " . base_url('index.php?page=instansi'));
        exit;
    }
} else {
    // Tampilkan View List Data
    $stmt = $pdo->query("SELECT * FROM instansi ORDER BY id DESC");
    $instansiList = $stmt->fetchAll();
    
    $title = "Master Data Instansi - E-Arsip";
    require 'views/master/instansi.php';
}
