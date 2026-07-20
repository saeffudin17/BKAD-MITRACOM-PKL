<?php
/**
 * =========================================================
 * FILE: index.php
 * Landing Page - Portal BKAD (PT Mitracom Solusi Teknologi)
 * =========================================================
 */
$pageTitle = 'Beranda';
require_once __DIR__ . '/includes/header.php';

// ---------------------------------------------------------
// Data dummy Berita/Pengumuman (Tahap 1 - statis).
// Pada tahap berikutnya, data ini diambil dari database
// melalui getConnection() pada includes/config.php
// ---------------------------------------------------------
$daftarBerita = [
    [
        'tag' => 'Pengumuman', 'kelas' => 'pengumuman',
        'tanggal' => '18 Juli 2026',
        'judul' => 'Jadwal Rekonsiliasi Laporan Keuangan Triwulan II Tahun 2026',
        'ringkas' => 'Seluruh OPD diminta menyelesaikan rekonsiliasi laporan keuangan paling lambat 31 Juli 2026.',
        'icon' => 'fa-bullhorn',
    ],
    [
        'tag' => 'Berita', 'kelas' => '',
        'tanggal' => '15 Juli 2026',
        'judul' => 'Peluncuran Sistem Informasi Pengelolaan Aset Daerah Versi Terbaru',
        'ringkas' => 'BKAD bersama PT Mitracom Solusi Teknologi resmi meluncurkan pembaruan sistem pengelolaan aset daerah.',
        'icon' => 'fa-newspaper',
    ],
    [
        'tag' => 'Berita', 'kelas' => '',
        'tanggal' => '10 Juli 2026',
        'judul' => 'Sosialisasi Standar Akuntansi Pemerintahan bagi Bendahara OPD',
        'ringkas' => 'Kegiatan sosialisasi diselenggarakan guna meningkatkan kualitas pelaporan keuangan di lingkungan OPD.',
        'icon' => 'fa-users',
    ],
];
?>

<!-- HERO -->
<section class="hero">
  <div class="container hero-inner">
    <div class="hero-content">
      <span class="hero-eyebrow"><i class="fa-solid fa-landmark"></i> Portal Resmi Pemerintahan</span>
      <h1>Badan Keuangan dan Aset Daerah</h1>
      <p>
        Portal informasi resmi seputar pengelolaan keuangan dan aset daerah, dikembangkan
        dan dikelola oleh <strong>PT Mitracom Solusi Teknologi</strong> untuk mendukung
        transparansi dan akuntabilitas publik.
      </p>
      <div class="hero-actions">
        <a href="dokumen.php" class="btn btn-accent"><i class="fa-solid fa-file-arrow-down"></i> Akses Dokumen &amp; Laporan</a>
        <a href="layanan.php" class="btn btn-outline-light"><i class="fa-solid fa-circle-info"></i> Lihat Layanan</a>
      </div>
    </div>

    <div class="hero-slider">
      <div class="hero-slide-track" id="heroSlideTrack">
        <div class="hero-slide active">
          <div class="slide-icon"><i class="fa-solid fa-chart-line"></i></div>
          <h3>Transparansi Anggaran</h3>
          <p>Pantau realisasi APBD dan laporan keuangan daerah secara terbuka dan mudah diakses.</p>
        </div>
        <div class="hero-slide">
          <div class="slide-icon"><i class="fa-solid fa-building-columns"></i></div>
          <h3>Pengelolaan Aset Daerah</h3>
          <p>Data aset daerah terkelola secara digital, akurat, dan terintegrasi antar-OPD.</p>
        </div>
        <div class="hero-slide">
          <div class="slide-icon"><i class="fa-solid fa-file-shield"></i></div>
          <h3>Layanan Dokumen Resmi</h3>
          <p>Unduh dokumen dan laporan resmi yang telah terverifikasi melalui satu portal.</p>
        </div>
      </div>
      <div class="slide-dots" id="slideDots">
        <button class="active" data-slide="0" aria-label="Slide 1"></button>
        <button data-slide="1" aria-label="Slide 2"></button>
        <button data-slide="2" aria-label="Slide 3"></button>
      </div>
    </div>
  </div>
</section>

<!-- STAT STRIP -->
<div class="stat-strip">
  <div class="container">
    <div class="stat-grid">
      <div class="stat-item"><div class="num">120+</div><div class="label">Dokumen Laporan</div></div>
      <div class="stat-item"><div class="num">34</div><div class="label">OPD Terhubung</div></div>
      <div class="stat-item"><div class="num">98%</div><div class="label">Realisasi Anggaran</div></div>
      <div class="stat-item"><div class="num">24/7</div><div class="label">Akses Layanan Online</div></div>
    </div>
  </div>
</div>

<!-- BERITA / PENGUMUMAN -->
<section class="section" id="berita">
  <div class="container">
    <div class="section-head">
      <div>
        <span class="eyebrow">Informasi Terkini</span>
        <h2>Berita &amp; Pengumuman</h2>
      </div>
      <a href="berita.php" class="link-more">Lihat Semua Berita <i class="fa-solid fa-arrow-right"></i></a>
    </div>

    <div class="news-grid">
      <?php foreach ($daftarBerita as $b): ?>
      <article class="news-card">
        <div class="news-thumb"><i class="fa-solid <?php echo $b['icon']; ?>"></i></div>
        <div class="news-body">
          <span class="news-tag <?php echo $b['kelas']; ?>"><?php echo htmlspecialchars($b['tag']); ?></span>
          <div class="news-date"><i class="fa-regular fa-calendar"></i> <?php echo htmlspecialchars($b['tanggal']); ?></div>
          <h3><?php echo htmlspecialchars($b['judul']); ?></h3>
          <p><?php echo htmlspecialchars($b['ringkas']); ?></p>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- AKSES CEPAT -->
<section class="section section-alt" id="layanan">
  <div class="container">
    <div class="section-head">
      <div>
        <span class="eyebrow">Layanan Portal</span>
        <h2>Akses Cepat Dokumen &amp; Laporan</h2>
      </div>
    </div>

    <div class="quick-grid">
      <a href="dokumen.php" class="quick-card">
        <div class="quick-icon"><i class="fa-solid fa-file-pdf"></i></div>
        <h4>Laporan Keuangan</h4>
        <p>Unduh laporan keuangan daerah format PDF</p>
      </a>
      <a href="dokumen.php" class="quick-card">
        <div class="quick-icon"><i class="fa-solid fa-file-excel"></i></div>
        <h4>Data Aset (Excel)</h4>
        <p>Rekapitulasi data aset daerah terkini</p>
      </a>
      <a href="dokumen.php" class="quick-card">
        <div class="quick-icon"><i class="fa-solid fa-scale-balanced"></i></div>
        <h4>Regulasi &amp; Perda</h4>
        <p>Kumpulan peraturan terkait keuangan daerah</p>
      </a>
      <a href="kontak.php" class="quick-card">
        <div class="quick-icon"><i class="fa-solid fa-headset"></i></div>
        <h4>Pengaduan &amp; Kontak</h4>
        <p>Sampaikan pertanyaan atau pengaduan Anda</p>
      </a>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="section" style="padding-top:0;">
  <div class="container">
    <div class="cta-strip">
      <div>
        <h3>Butuh akses ke Sistem Informasi Internal?</h3>
        <p>Login untuk mengelola dan mengakses berkas sesuai hak akses Anda.</p>
      </div>
      <a href="login.php" class="btn btn-accent"><i class="fa-solid fa-right-to-bracket"></i> Login Sekarang</a>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
