<?php
require_once 'config/database.php';

$action = $_GET['action'] ?? 'list';

if ($page === 'rak_action') {
    
    // Proses Tambah
    if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $kode_rak = trim($_POST['kode_rak']);
        $nama_rak = trim($_POST['nama_rak']);
        $lokasi = trim($_POST['lokasi']);
        $kapasitas = (int)$_POST['kapasitas'];
        $status = $_POST['status'] ?? 'Tersedia';

        try {
            $stmt = $pdo->prepare("INSERT INTO rak (kode_rak, nama_rak, lokasi, kapasitas, status) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$kode_rak, $nama_rak, $lokasi, $kapasitas, $status]);
            
            $_SESSION['flash_msg'] = "Data Rak berhasil ditambahkan.";
            $_SESSION['flash_type'] = "success";
            
            // Log Activity
            $log = $pdo->prepare("INSERT INTO activity_logs (id_user, action, description) VALUES (?, 'Tambah', ?)");
            $log->execute([$_SESSION['user_id'], "Menambahkan rak baru: $kode_rak - $nama_rak"]);
            
        } catch (PDOException $e) {
            if($e->getCode() == '23000') {
                $_SESSION['flash_msg'] = "Kode Rak sudah ada. Gunakan kode lain.";
            } else {
                $_SESSION['flash_msg'] = "Gagal menambahkan data: " . $e->getMessage();
            }
            $_SESSION['flash_type'] = "error";
        }
        header("Location: " . base_url('index.php?page=rak'));
        exit;
    }

    // Proses Edit
    if ($action === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = (int)$_POST['id'];
        $kode_rak = trim($_POST['kode_rak']);
        $nama_rak = trim($_POST['nama_rak']);
        $lokasi = trim($_POST['lokasi']);
        $kapasitas = (int)$_POST['kapasitas'];
        $status = $_POST['status'] ?? 'Tersedia';

        try {
            $stmt = $pdo->prepare("UPDATE rak SET kode_rak=?, nama_rak=?, lokasi=?, kapasitas=?, status=? WHERE id=?");
            $stmt->execute([$kode_rak, $nama_rak, $lokasi, $kapasitas, $status, $id]);
            
            $_SESSION['flash_msg'] = "Data Rak berhasil diupdate.";
            $_SESSION['flash_type'] = "success";
            
            // Log Activity
            $log = $pdo->prepare("INSERT INTO activity_logs (id_user, action, description) VALUES (?, 'Edit', ?)");
            $log->execute([$_SESSION['user_id'], "Mengupdate data rak: $kode_rak - $nama_rak"]);
            
        } catch (PDOException $e) {
            if($e->getCode() == '23000') {
                $_SESSION['flash_msg'] = "Kode Rak sudah digunakan di tempat lain.";
            } else {
                $_SESSION['flash_msg'] = "Gagal mengupdate data: " . $e->getMessage();
            }
            $_SESSION['flash_type'] = "error";
        }
        header("Location: " . base_url('index.php?page=rak'));
        exit;
    }

    // Proses Hapus
    if ($action === 'delete' && isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        
        $stmt_get = $pdo->prepare("SELECT kode_rak, nama_rak FROM rak WHERE id = ?");
        $stmt_get->execute([$id]);
        $data_rak = $stmt_get->fetch();
        
        try {
            $stmt = $pdo->prepare("DELETE FROM rak WHERE id=?");
            $stmt->execute([$id]);
            
            $_SESSION['flash_msg'] = "Data Rak berhasil dihapus.";
            $_SESSION['flash_type'] = "success";
            
            if($data_rak){
                $log = $pdo->prepare("INSERT INTO activity_logs (id_user, action, description) VALUES (?, 'Hapus', ?)");
                $log->execute([$_SESSION['user_id'], "Menghapus rak: " . $data_rak['kode_rak'] . " - " . $data_rak['nama_rak']]);
            }
        } catch (PDOException $e) {
            if($e->getCode() == '23000') {
                $_SESSION['flash_msg'] = "Tidak bisa menghapus Rak ini karena sedang terisi Bindex/Arsip (termasuk yang ada di Recycle Bin)!";
            } else {
                $_SESSION['flash_msg'] = "Gagal menghapus data: " . $e->getMessage();
            }
            $_SESSION['flash_type'] = "error";
        }
        
        header("Location: " . base_url('index.php?page=rak'));
        exit;
    }
} else {
    // Tampilkan View List Data Rak
    // Menghitung ulang jumlah_arsip pada rak bisa dilakukan otomatis melalui trigger, atau kita load secara manual
    // Pada contoh ini kita asumsikan 'jumlah_arsip' sudah otomatis terupdate via trigger atau query dinamis.
    $stmt = $pdo->query("SELECT * FROM rak ORDER BY id DESC");
    $rakList = $stmt->fetchAll();
    
    $title = "Master Data Rak - E-Arsip";
    require 'views/master/rak.php';
}
