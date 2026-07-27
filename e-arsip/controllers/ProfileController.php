<?php
require_once 'config/database.php';

$id_user = $_SESSION['user_id'];
$action = $_GET['action'] ?? '';

// Ambil data user saat ini
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id_user]);
$user = $stmt->fetch();

if ($page === 'profile') {
    $title = "Profil Pengguna - E-Arsip";
    require 'views/profile/index.php';
    exit;
}

if ($page === 'profile_action') {
    
    // Update Profil (Nama & Foto)
    if ($action === 'update_profile' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $nama = trim($_POST['nama']);
        $foto_lama = $user['foto'];
        $foto_baru = $foto_lama;
        
        // Proses Upload Foto Baru
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
            $allowed_ext = ['jpg', 'jpeg', 'png'];
            $file_name = $_FILES['foto']['name'];
            $file_size = $_FILES['foto']['size'];
            $file_tmp  = $_FILES['foto']['tmp_name'];
            $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            
            if (in_array($file_ext, $allowed_ext) && $file_size <= 2097152) { // Max 2MB
                $new_filename = 'user_' . $id_user . '_' . time() . '.' . $file_ext;
                $upload_dir = 'assets/images/users/';
                
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                if (move_uploaded_file($file_tmp, $upload_dir . $new_filename)) {
                    $foto_baru = $new_filename;
                    // Hapus foto lama jika bukan default
                    if ($foto_lama != 'default.png' && file_exists($upload_dir . $foto_lama)) {
                        unlink($upload_dir . $foto_lama);
                    }
                }
            } else {
                $_SESSION['flash_msg'] = "Gagal upload foto! Format harus JPG/PNG & maksimal 2MB.";
                $_SESSION['flash_type'] = "error";
                header("Location: " . base_url('index.php?page=profile'));
                exit;
            }
        }
        
        $stmt = $pdo->prepare("UPDATE users SET nama = ?, foto = ? WHERE id = ?");
        $stmt->execute([$nama, $foto_baru, $id_user]);
        
        // Update Session Nama
        $_SESSION['user_nama'] = $nama;
        
        $_SESSION['flash_msg'] = "Profil berhasil diperbarui.";
        $_SESSION['flash_type'] = "success";
        
        $log = $pdo->prepare("INSERT INTO activity_logs (id_user, action, description) VALUES (?, 'Edit', ?)");
        $log->execute([$id_user, "Memperbarui profil (Nama / Foto)"]);
        
        header("Location: " . base_url('index.php?page=profile'));
        exit;
    }

    // Update Password
    if ($action === 'update_password' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $password_lama = $_POST['password_lama'];
        $password_baru = $_POST['password_baru'];
        $konfirmasi = $_POST['konfirmasi_password'];
        
        // Cek password lama
        if (password_verify($password_lama, $user['password'])) {
            if ($password_baru === $konfirmasi) {
                $hash_baru = password_hash($password_baru, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                $stmt->execute([$hash_baru, $id_user]);
                
                $_SESSION['flash_msg'] = "Password berhasil diubah!";
                $_SESSION['flash_type'] = "success";
                
                $log = $pdo->prepare("INSERT INTO activity_logs (id_user, action, description) VALUES (?, 'Edit', ?)");
                $log->execute([$id_user, "Mengubah password akun"]);
            } else {
                $_SESSION['flash_msg'] = "Konfirmasi password tidak cocok!";
                $_SESSION['flash_type'] = "error";
            }
        } else {
            $_SESSION['flash_msg'] = "Password lama yang Anda masukkan salah!";
            $_SESSION['flash_type'] = "error";
        }
        
        header("Location: " . base_url('index.php?page=profile'));
        exit;
    }
}
