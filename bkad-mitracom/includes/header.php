<?php
/**
 * =========================================================
 * FILE: includes/header.php
 * Komponen Header & Navigasi (dipakai di semua halaman)
 * =========================================================
 */
require_once __DIR__ . '/config.php';

$current = basename($_SERVER['PHP_SELF']);
function navActive($page, $current) {
    return $page === $current ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo isset($pageTitle) ? $pageTitle . ' - ' . APP_NAME : APP_NAME; ?></title>
<meta name="description" content="Portal resmi BKAD (Badan Keuangan dan Aset Daerah) - dikelola oleh PT Mitracom Solusi Teknologi. Informasi berita, layanan, dan dokumen/laporan keuangan daerah.">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- TOPBAR -->
<div class="topbar">
  <div class="container">
    <div class="topbar-info">
      <span><i class="fa-solid fa-phone"></i> (0231) 123-4567</span>
      <span><i class="fa-solid fa-envelope"></i> info@bkad-mitracom.go.id</span>
      <span><i class="fa-solid fa-clock"></i> Senin - Jumat, 08.00 - 16.00 WIB</span>
    </div>
    <div class="topbar-links">
      <a href="#"><i class="fa-solid fa-circle-question"></i> FAQ</a>
      <a href="#"><i class="fa-solid fa-sitemap"></i> Peta Situs</a>
    </div>
  </div>
</div>

<!-- HEADER / NAVBAR -->
<header class="main-header">
  <div class="container header-inner">
    <a href="index.php" class="brand">
      <div class="brand-logo">MST</div>
      <div class="brand-text">
        <div class="brand-title">BKAD MITRACOM</div>
        <div class="brand-sub">PT Mitracom Solusi Teknologi</div>
      </div>
    </a>

    <nav class="navbar">
      <ul class="nav-menu" id="navMenu">
        <li><a href="index.php" class="<?php echo navActive('index.php', $current); ?>">Beranda</a></li>
        <li><a href="berita.php" class="<?php echo navActive('berita.php', $current); ?>">Berita</a></li>
        <li><a href="layanan.php" class="<?php echo navActive('layanan.php', $current); ?>">Layanan</a></li>
        <li><a href="dokumen.php" class="<?php echo navActive('dokumen.php', $current); ?>">Dokumen</a></li>
        <li><a href="kontak.php" class="<?php echo navActive('kontak.php', $current); ?>">Kontak</a></li>
        <li><a href="login.php" class="btn-login-nav <?php echo navActive('login.php', $current); ?>"><i class="fa-solid fa-right-to-bracket"></i> Login</a></li>
      </ul>
      <button class="hamburger" id="hamburgerBtn" aria-label="Buka menu">
        <span></span><span></span><span></span>
      </button>
    </nav>
  </div>
</header>
