<?php
/**
 * =========================================================
 * FILE: login.php
 * Halaman Login - Portal BKAD (PT Mitracom Solusi Teknologi)
 * =========================================================
 * Tahap 1: form login dengan validasi sisi klien (JavaScript).
 * Proses otentikasi backend (query ke tabel `users`, verifikasi
 * password hash, dsb.) akan ditambahkan pada tahap berikutnya
 * melalui file terpisah, misalnya: auth/proses_login.php
 */
require_once __DIR__ . '/includes/config.php';

$pageTitle = 'Login';

// Jika sudah login, arahkan ke dashboard (placeholder untuk tahap berikutnya)
if (isLoggedIn()) {
    // header('Location: admin/dashboard.php'); exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $pageTitle . ' - ' . APP_NAME; ?></title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- HEADER RINGKAS -->
<header class="main-header">
  <div class="container header-inner">
    <a href="index.php" class="brand">
      <div class="brand-logo">MST</div>
      <div class="brand-text">
        <div class="brand-title">BKAD Kabupaten</div>
        <div class="brand-sub">Dikelola oleh PT Mitracom Solusi Teknologi</div>
      </div>
    </a>
  </div>
</header>

<!-- LOGIN SECTION -->
<div class="login-wrap">

  <!-- SISI VISUAL -->
  <div class="login-visual">
    <h2>Sistem Informasi Internal BKAD</h2>
    <p>Masuk untuk mengelola dokumen, laporan keuangan, dan data aset daerah sesuai hak akses Anda.</p>
    <ul>
      <li><span class="check"><i class="fa-solid fa-check"></i></span> Akses berdasarkan peran (Admin / User)</li>
      <li><span class="check"><i class="fa-solid fa-check"></i></span> Unggah &amp; kelola berkas PDF / Excel</li>
      <li><span class="check"><i class="fa-solid fa-check"></i></span> Pengelompokan berkas otomatis per entitas</li>
      <li><span class="check"><i class="fa-solid fa-check"></i></span> Riwayat aktivitas tersimpan aman</li>
    </ul>
  </div>

  <!-- SISI FORM -->
  <div class="login-form-side">
    <div class="login-box">
      <a href="index.php" class="back-home"><i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda</a>

      <div class="logo-row">
        <div class="brand-logo">MST</div>
        <div class="brand-text">
          <div class="brand-title">Portal BKAD</div>
          <div class="brand-sub">PT Mitracom Solusi Teknologi</div>
        </div>
      </div>

      <h1>Masuk ke Akun Anda</h1>
      <p class="sub">Silakan masukkan Username/Email dan Password Anda.</p>

      <div class="alert-box alert-error" id="alertError">
        <i class="fa-solid fa-circle-exclamation"></i> <span id="alertErrorText">Terjadi kesalahan.</span>
      </div>
      <div class="alert-box alert-success" id="alertSuccess">
        <i class="fa-solid fa-circle-check"></i> Login berhasil divalidasi. Mengarahkan...
      </div>

      <!--
        action="auth/proses_login.php" akan digunakan pada tahap backend berikutnya
        untuk memproses otentikasi (query database, verifikasi password_hash, session).
        method="post" dipertahankan agar form siap diintegrasikan langsung.
      -->
      <form id="formLogin" action="auth/proses_login.php" method="POST" novalidate>

        <div class="form-group" id="groupUsername">
          <label for="username">Username / Email</label>
          <div class="input-wrap">
            <i class="fa-solid fa-user icon"></i>
            <input type="text" id="username" name="username" placeholder="Masukkan username atau email" autocomplete="username">
          </div>
          <span class="field-error" id="errUsername">Username/Email wajib diisi.</span>
        </div>

        <div class="form-group" id="groupPassword">
          <label for="password">Password</label>
          <div class="input-wrap">
            <i class="fa-solid fa-lock icon"></i>
            <input type="password" id="password" name="password" placeholder="Masukkan password" autocomplete="current-password">
            <button type="button" class="toggle-pass" id="togglePass">Lihat</button>
          </div>
          <span class="field-error" id="errPassword">Password minimal 6 karakter.</span>
        </div>

        <div class="form-row-between">
          <label class="remember-me">
            <input type="checkbox" name="remember" id="remember">
            Ingat Saya
          </label>
          <a href="lupa-password.php" class="forgot-link">Lupa Password?</a>
        </div>

        <button type="submit" class="btn-submit" id="btnSubmit">
          <i class="fa-solid fa-right-to-bracket"></i> Masuk
        </button>
      </form>

      <p class="login-footer-note">
        Hak akses ditentukan oleh administrator sistem.<br>
        Kesulitan masuk? Hubungi <strong>info@bkad-mitracom.go.id</strong>
      </p>
    </div>
  </div>
</div>

<script src="js/login.js"></script>
</body>
</html>
