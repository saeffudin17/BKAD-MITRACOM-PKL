<?php include 'views/layout/header.php'; ?>
<?php include 'views/layout/sidebar.php'; ?>
<?php include 'views/layout/navbar.php'; ?>

<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= base_url('index.php?page=dashboard') ?>" class="text-decoration-none">Home</a></li>
    <li class="breadcrumb-item"><a href="<?= base_url('index.php?page=arsip_list') ?>" class="text-decoration-none">Arsip SP2D</a></li>
    <li class="breadcrumb-item active" aria-current="page">Detail Arsip</li>
  </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="font-weight-bold mb-0">Detail Arsip SP2D</h4>
    <div>
        <a href="<?= base_url('index.php?page=arsip_list') ?>" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <!-- Kolom Kiri: Informasi Data -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent pt-4 pb-2 border-0">
                <h6 class="fw-bold text-primary"><i class="fa-solid fa-circle-info me-2"></i>Informasi SP2D</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless table-sm">
                    <tr>
                        <td width="35%" class="text-muted fw-semibold">Nomor SP2D</td>
                        <td width="5%">:</td>
                        <td><strong><?= htmlspecialchars($arsip['nomor_sp2d']) ?></strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Nomor SPM</td>
                        <td>:</td>
                        <td><strong><?= htmlspecialchars($arsip['nomor_spm'] ?: '-') ?></strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Tanggal SPM</td>
                        <td>:</td>
                        <td><?= $arsip['tanggal_spm'] ? date('d M Y', strtotime($arsip['tanggal_spm'])) : '-' ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">SKPD / Instansi Asal</td>
                        <td>:</td>
                        <td><?= htmlspecialchars($arsip['nama_instansi']) ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Jenis Kegiatan</td>
                        <td>:</td>
                        <td><?= htmlspecialchars($arsip['jenis_kegiatan']) ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Keperluan Untuk</td>
                        <td>:</td>
                        <td><?= htmlspecialchars($arsip['nama_kegiatan']) ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Tanggal Dokumen</td>
                        <td>:</td>
                        <td><?= date('d M Y', strtotime($arsip['tanggal_sp2d'])) ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Tanggal Arsip Diterima</td>
                        <td>:</td>
                        <td><?= date('d M Y', strtotime($arsip['tanggal_arsip'])) ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Jumlah SP2D</td>
                        <td>:</td>
                        <td><?= (int)$arsip['jumlah_sp2d'] ?> Dokumen</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Jumlah Halaman</td>
                        <td>:</td>
                        <td><?= (int)$arsip['jumlah_halaman'] ?> Lembar</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Status Berkas</td>
                        <td>:</td>
                        <td>
                            <?php
                            $badgeClass = 'bg-secondary';
                            if($arsip['status_arsip'] == 'Diproses') $badgeClass = 'bg-primary';
                            elseif($arsip['status_arsip'] == 'Dikembalikan') $badgeClass = 'bg-danger';
                            ?>
                            <span class="badge <?= $badgeClass ?> px-3 py-2"><?= $arsip['status_arsip'] ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Keterangan/Catatan</td>
                        <td>:</td>
                        <td><?= nl2br(htmlspecialchars($arsip['catatan'] ?: '-')) ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent pt-4 pb-2 border-0">
                <h6 class="fw-bold text-primary"><i class="fa-solid fa-box-archive me-2"></i>Penyimpanan Fisik</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless table-sm">
                    <tr>
                        <td width="35%" class="text-muted fw-semibold">Lokasi Rak</td>
                        <td width="5%">:</td>
                        <td><span class="badge bg-dark"><i class="fa-solid fa-server me-1"></i> <?= htmlspecialchars($arsip['nama_rak']) ?></span></td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Disimpan di Bindex</td>
                        <td>:</td>
                        <td><span class="badge bg-secondary"><?= htmlspecialchars($arsip['nomor_bindex']) ?></span></td>
                    </tr>

                </table>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Preview File & Meta -->
    <div class="col-lg-5">
        <!-- Area PDF -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent pt-3 pb-2 border-0 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold text-danger mb-0"><i class="fa-solid fa-file-pdf me-2"></i>Preview Dokumen</h6>
                <?php if(!empty($arsip['file_pdf'])): ?>
                <div>
                    <a href="<?= base_url('uploads/' . rawurlencode($arsip['file_pdf'])) ?>" target="_blank" class="btn btn-sm btn-outline-danger me-1">Buka Penuh <i class="fa-solid fa-external-link ms-1"></i></a>
                    <a href="<?= base_url('index.php?page=arsip_action&action=download_pdf&id='.$arsip['id']) ?>" class="btn btn-sm btn-success">Download <i class="fa-solid fa-download ms-1"></i></a>
                </div>
                <?php endif; ?>
            </div>
            <div class="card-body p-0" style="min-height: 400px; background: #e9ecef;">
                <?php if(!empty($arsip['file_pdf']) && file_exists('uploads/' . $arsip['file_pdf'])): ?>
                    <iframe src="<?= base_url('uploads/' . rawurlencode($arsip['file_pdf'])) ?>" width="100%" height="500px" style="border: none;"></iframe>
                <?php else: ?>
                    <div class="d-flex flex-column justify-content-center align-items-center text-center p-5 h-100" style="min-height: 400px;">
                        <i class="fa-solid fa-file-circle-xmark fa-4x text-muted mb-3 opacity-50"></i>
                        <h6 class="text-muted">File PDF Tidak Ditemukan</h6>
                        <p class="small text-muted mb-0">Dokumen fisik belum di-scan atau file telah dihapus dari server.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent pt-4 pb-2 border-0">
                <h6 class="fw-bold text-primary"><i class="fa-solid fa-user-clock me-2"></i>Sistem Meta</h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary bg-opacity-10 text-primary rounded p-3 me-3">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">Diinput Oleh</small>
                        <strong><?= htmlspecialchars($arsip['nama_petugas']) ?></strong>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 text-success rounded p-3 me-3">
                        <i class="fa-solid fa-calendar-plus"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">Waktu Input Sistem</small>
                        <strong><?= date('d M Y, H:i:s', strtotime($arsip['created_at'])) ?></strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>
