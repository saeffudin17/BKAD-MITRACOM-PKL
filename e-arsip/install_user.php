<?php
require_once 'config/database.php';

try {
    // Kosongkan tabel users agar tidak ada duplikat jika gagal sebelumnya
    $pdo->query("TRUNCATE TABLE users");
    
    // Hash password 'password'
    $password_hash = password_hash('password', PASSWORD_DEFAULT);
    
    // Insert Admin
    $stmt1 = $pdo->prepare("INSERT INTO users (nama, username, password, role, status) VALUES (?, ?, ?, ?, ?)");
    $stmt1->execute(['Administrator', 'admin', $password_hash, 'Admin', 'Aktif']);
    
    // Insert Petugas
    $stmt2 = $pdo->prepare("INSERT INTO users (nama, username, password, role, status) VALUES (?, ?, ?, ?, ?)");
    $stmt2->execute(['Petugas Satu', 'petugas1', $password_hash, 'Petugas Arsip', 'Aktif']);
    
    echo "<h2 style='color:green;'>Berhasil! Akun berhasil ditambahkan.</h2>";
    echo "<b>Admin:</b> Username: <code>admin</code> | Password: <code>password</code><br>";
    echo "<b>Petugas:</b> Username: <code>petugas1</code> | Password: <code>password</code><br>";
    echo "<br><a href='index.php?page=login'>Klik di sini untuk kembali ke halaman Login</a>";

} catch (PDOException $e) {
    echo "<h2 style='color:red;'>Gagal memasukkan data:</h2>";
    echo $e->getMessage();
}
