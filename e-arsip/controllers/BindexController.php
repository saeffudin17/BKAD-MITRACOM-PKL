<?php
require_once 'config/database.php';

$action = $_GET['action'] ?? 'list';

if ($page === 'bindex_action') {
    
    // Proses Tambah
    if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $nomor_bindex = trim($_POST['nomor_bindex']);
        $nama_bindex = trim($_POST['nama_bindex']);
        $id_rak = (int)$_POST['id_rak'];
        $keterangan = trim($_POST['keterangan']);

        try {
            $stmt = $pdo->prepare("INSERT INTO bindex (nomor_bindex, nama_bindex, id_rak, keterangan) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nomor_bindex, $nama_bindex, $id_rak, $keterangan]);
            
            $_SESSION['flash_msg'] = "Data Bindex berhasil ditambahkan.";
            $_SESSION['flash_type'] = "success";
            
            // Log Activity
            $log = $pdo->prepare("INSERT INTO activity_logs (id_user, action, description) VALUES (?, 'Tambah', ?)");
            $log->execute([$_SESSION['user_id'], "Menambahkan Bindex baru: $nomor_bindex"]);
            
        } catch (PDOException $e) {
            if($e->getCode() == '23000') {
                $_SESSION['flash_msg'] = "Nomor Bindex sudah ada. Gunakan nomor lain.";
            } else {
                $_SESSION['flash_msg'] = "Gagal menambahkan data: " . $e->getMessage();
            }
            $_SESSION['flash_type'] = "error";
        }
        header("Location: " . base_url('index.php?page=bindex'));
        exit;
    }

    // Proses Edit
    if ($action === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = (int)$_POST['id'];
        $nomor_bindex = trim($_POST['nomor_bindex']);
        $nama_bindex = trim($_POST['nama_bindex']);
        $id_rak = (int)$_POST['id_rak'];
        $keterangan = trim($_POST['keterangan']);

        try {
            $stmt = $pdo->prepare("UPDATE bindex SET nomor_bindex=?, nama_bindex=?, id_rak=?, keterangan=? WHERE id=?");
            $stmt->execute([$nomor_bindex, $nama_bindex, $id_rak, $keterangan, $id]);
            
            $_SESSION['flash_msg'] = "Data Bindex berhasil diupdate.";
            $_SESSION['flash_type'] = "success";
            
            // Log Activity
            $log = $pdo->prepare("INSERT INTO activity_logs (id_user, action, description) VALUES (?, 'Edit', ?)");
            $log->execute([$_SESSION['user_id'], "Mengupdate data Bindex: $nomor_bindex"]);
            
        } catch (PDOException $e) {
            if($e->getCode() == '23000') {
                $_SESSION['flash_msg'] = "Nomor Bindex sudah digunakan di tempat lain.";
            } else {
                $_SESSION['flash_msg'] = "Gagal mengupdate data: " . $e->getMessage();
            }
            $_SESSION['flash_type'] = "error";
        }
        header("Location: " . base_url('index.php?page=bindex'));
        exit;
    }

    // Proses Hapus
    if ($action === 'delete' && isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        
        $stmt_get = $pdo->prepare("SELECT nomor_bindex FROM bindex WHERE id = ?");
        $stmt_get->execute([$id]);
        $data_bindex = $stmt_get->fetch();
        
        try {
            $stmt = $pdo->prepare("DELETE FROM bindex WHERE id=?");
            $stmt->execute([$id]);
            
            $_SESSION['flash_msg'] = "Data Bindex berhasil dihapus.";
            $_SESSION['flash_type'] = "success";
            
            if($data_bindex){
                $log = $pdo->prepare("INSERT INTO activity_logs (id_user, action, description) VALUES (?, 'Hapus', ?)");
                $log->execute([$_SESSION['user_id'], "Menghapus Bindex: " . $data_bindex['nomor_bindex']]);
            }
        } catch (PDOException $e) {
            if($e->getCode() == '23000') {
                $_SESSION['flash_msg'] = "Tidak bisa menghapus Bindex ini karena berisi data Arsip (termasuk yang ada di Recycle Bin)!";
            } else {
                $_SESSION['flash_msg'] = "Gagal menghapus data: " . $e->getMessage();
            }
            $_SESSION['flash_type'] = "error";
        }
        
        header("Location: " . base_url('index.php?page=bindex'));
        exit;
    }
} else {
    // Tampilkan View List Data Bindex dengan JOIN ke tabel rak
    $stmt = $pdo->query("
        SELECT b.*, r.nama_rak, r.kode_rak 
        FROM bindex b 
        JOIN rak r ON b.id_rak = r.id 
        ORDER BY b.id DESC
    ");
    $bindexList = $stmt->fetchAll();

    // Ambil data rak untuk dropdown di modal form
    $stmtRak = $pdo->query("SELECT id, kode_rak, nama_rak FROM rak WHERE status = 'Tersedia' ORDER BY nama_rak ASC");
    $rakListOptions = $stmtRak->fetchAll();
    
    // Ambil semua rak (termasuk yang penuh) untuk kebutuhan form edit
    $stmtAllRak = $pdo->query("SELECT id, kode_rak, nama_rak FROM rak ORDER BY nama_rak ASC");
    $allRakOptions = $stmtAllRak->fetchAll();
    
    $title = "Master Data Bindex - E-Arsip";
    require 'views/master/bindex.php';
}
