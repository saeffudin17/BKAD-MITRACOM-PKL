<?php
require_once 'config/database.php';

// Auto-migrate role ENUM if needed
try {
    $pdo->exec("ALTER TABLE users MODIFY COLUMN role ENUM('Admin', 'Operator', 'Reviewer', 'Management', 'Direktur') NOT NULL DEFAULT 'Operator'");
    
    // Migrasi data lama ke role baru (contoh pemetaan)
    $pdo->exec("UPDATE users SET role='Admin' WHERE role='Superadmin' OR role='Admin'");
    $pdo->exec("UPDATE users SET role='Operator' WHERE role='Pegawai' OR role='User' OR role='Petugas Arsip'");

    // Pastikan jika ada role yang masih salah, fallback ke Operator
    $pdo->exec("UPDATE users SET role='Operator' WHERE role NOT IN ('Admin', 'Operator', 'Reviewer', 'Management', 'Direktur')");
} catch (Exception $e) {}

// Hak Akses Khusus Admin
if ($_SESSION['user_role'] !== 'Admin') {
    $_SESSION['flash_msg'] = "Hanya Administrator yang dapat mengelola pengguna.";
    $_SESSION['flash_type'] = "error";
    header("Location: " . base_url('index.php?page=dashboard'));
    exit;
}

$action = $_GET['action'] ?? 'list';

if ($page === 'users_action') {
    
    // Tambah User
    if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $nama = trim($_POST['nama']);
        $username = trim($_POST['username']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role = $_POST['role'];
        $status = $_POST['status'];

        try {
            $stmt = $pdo->prepare("INSERT INTO users (nama, username, password, role, status) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$nama, $username, $password, $role, $status]);
            
            $_SESSION['flash_msg'] = "Pengguna $nama berhasil ditambahkan.";
            $_SESSION['flash_type'] = "success";
            
            // Activity Log
            $log = $pdo->prepare("INSERT INTO activity_logs (id_user, action, description) VALUES (?, 'Tambah', ?)");
            $log->execute([$_SESSION['user_id'], "Membuat akun pengguna baru: $username"]);
            
        } catch (PDOException $e) {
            if($e->getCode() == '23000') {
                $_SESSION['flash_msg'] = "Username '$username' sudah terdaftar. Gunakan yang lain.";
            } else {
                $_SESSION['flash_msg'] = "Gagal menambah pengguna: " . $e->getMessage();
            }
            $_SESSION['flash_type'] = "error";
        }
        header("Location: " . base_url('index.php?page=users'));
        exit;
    }

    // Edit User
    if ($action === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = (int)$_POST['id'];
        $nama = trim($_POST['nama']);
        $username = trim($_POST['username']);
        $role = $_POST['role'];
        $status = $_POST['status'];
        
        // Proteksi agar role Admin yang sedang login tidak diubah menjadi Petugas oleh dirinya sendiri secara tidak sengaja
        if ($id == $_SESSION['user_id'] && $role !== 'Admin') {
            $_SESSION['flash_msg'] = "Anda tidak bisa mengubah role Anda sendiri menjadi Petugas!";
            $_SESSION['flash_type'] = "error";
            header("Location: " . base_url('index.php?page=users'));
            exit;
        }

        try {
            if (!empty($_POST['password'])) {
                // Jika password diisi, maka update password juga
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET nama=?, username=?, password=?, role=?, status=? WHERE id=?");
                $stmt->execute([$nama, $username, $password, $role, $status, $id]);
            } else {
                // Jika password kosong, biarkan password lama
                $stmt = $pdo->prepare("UPDATE users SET nama=?, username=?, role=?, status=? WHERE id=?");
                $stmt->execute([$nama, $username, $role, $status, $id]);
            }
            
            $_SESSION['flash_msg'] = "Data pengguna berhasil diubah.";
            $_SESSION['flash_type'] = "success";
            
            // Activity Log
            $log = $pdo->prepare("INSERT INTO activity_logs (id_user, action, description) VALUES (?, 'Edit', ?)");
            $log->execute([$_SESSION['user_id'], "Mengupdate data pengguna: $username"]);
            
        } catch (PDOException $e) {
            if($e->getCode() == '23000') {
                $_SESSION['flash_msg'] = "Username sudah dipakai oleh pengguna lain.";
            } else {
                $_SESSION['flash_msg'] = "Gagal mengupdate: " . $e->getMessage();
            }
            $_SESSION['flash_type'] = "error";
        }
        header("Location: " . base_url('index.php?page=users'));
        exit;
    }

    // Hapus User
    if ($action === 'delete' && isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        
        // Proteksi: Tidak bisa hapus akun sendiri
        if ($id == $_SESSION['user_id']) {
            $_SESSION['flash_msg'] = "Anda tidak bisa menghapus akun Anda sendiri!";
            $_SESSION['flash_type'] = "error";
            header("Location: " . base_url('index.php?page=users'));
            exit;
        }
        
        $stmt_get = $pdo->prepare("SELECT username FROM users WHERE id = ?");
        $stmt_get->execute([$id]);
        $user_data = $stmt_get->fetch();
        
        try {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$id]);
            
            $_SESSION['flash_msg'] = "Pengguna berhasil dihapus dari sistem.";
            $_SESSION['flash_type'] = "success";
            
            if($user_data){
                $log = $pdo->prepare("INSERT INTO activity_logs (id_user, action, description) VALUES (?, 'Hapus', ?)");
                $log->execute([$_SESSION['user_id'], "Menghapus akun pengguna: " . $user_data['username']]);
            }
        } catch (PDOException $e) {
            if($e->getCode() == '23000') {
                // Fallback: Nonaktifkan pengguna karena berelasi
                $stmt_upd = $pdo->prepare("UPDATE users SET status = 'Nonaktif' WHERE id = ?");
                $stmt_upd->execute([$id]);
                
                $_SESSION['flash_msg'] = "Pengguna tidak dapat dihapus permanen karena masih terkait dengan data arsip/aktivitas sistem. Akun berhasil di-Nonaktifkan sebagai gantinya!";
                $_SESSION['flash_type'] = "warning";
                
                if($user_data){
                    $log = $pdo->prepare("INSERT INTO activity_logs (id_user, action, description) VALUES (?, 'Edit', ?)");
                    $log->execute([$_SESSION['user_id'], "Sistem otomatis menonaktifkan pengguna (terikat relasi data): " . $user_data['username']]);
                }
            } else {
                $_SESSION['flash_msg'] = "Gagal menghapus data: " . $e->getMessage();
                $_SESSION['flash_type'] = "error";
            }
        }
        header("Location: " . base_url('index.php?page=users'));
        exit;
    }
} else {
    // List View
    $stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");
    $usersList = $stmt->fetchAll();
    
    $title = "Manajemen User - E-Arsip";
    require 'views/master/users.php';
}
