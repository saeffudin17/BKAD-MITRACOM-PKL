<?php
// Pastikan file ini di-include dari index.php sehingga $pdo sudah tersedia

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $_SESSION['error_login'] = 'Username dan Password wajib diisi.';
        header("Location: " . base_url('index.php?page=login'));
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username AND status = 'Aktif' LIMIT 1");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Setup Session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nama'] = $user['nama'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_foto'] = $user['foto'];

            // Log Activity
            $logStmt = $pdo->prepare("INSERT INTO activity_logs (id_user, action, description) VALUES (:id_user, 'Login', 'User berhasil login ke sistem')");
            $logStmt->execute([':id_user' => $user['id']]);

            // Redirect ke Dashboard
            header("Location: " . base_url('index.php?page=dashboard'));
            exit;
        } else {
            $_SESSION['error_login'] = 'Username atau Password salah, atau akun nonaktif.';
            header("Location: " . base_url('index.php?page=login'));
            exit;
        }
    } catch (PDOException $e) {
        $_SESSION['error_login'] = 'Terjadi kesalahan sistem.';
        header("Location: " . base_url('index.php?page=login'));
        exit;
    }
} else {
    // Jika bukan POST, kembalikan ke form login
    header("Location: " . base_url('index.php?page=login'));
    exit;
}
