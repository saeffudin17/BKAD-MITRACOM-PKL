<?php include 'views/layout/header.php'; ?>
<?php include 'views/layout/sidebar.php'; ?>
<?php include 'views/layout/navbar.php'; ?>

<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= base_url('index.php?page=dashboard') ?>" class="text-decoration-none">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Sirkulasi Arsip Fisik</li>
  </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="font-weight-bold mb-0">Manajemen Peminjaman Fisik</h4>
</div>

<?php if(isset($_SESSION['flash_msg'])): ?>
    <div id="flash-data" data-type="<?= $_SESSION['flash_type'] ?>" data-msg="<?= $_SESSION['flash_msg'] ?>"></div>
    <?php unset($_SESSION['flash_msg'], $_SESSION['flash_type']); ?>
<?php endif; ?>

<div class="alert alert-primary border-0 bg-primary bg-opacity-10 d-flex align-items-center mb-4">
    <i class="fa-solid fa-handshake fa-2x text-primary me-3"></i>
    <div>
        <strong>Pencatatan Peminjaman</strong><br>
        Gunakan menu ini untuk mencatat arsip fisik/kertas SP2D yang dikeluarkan sementara dari Rak/Bindex untuk keperluan dinas. Pastikan untuk selalu menekan tombol "Terima Kembali" jika berkas sudah kembali.
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="tableSirkulasi">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="20%">No. SP2D & Instansi</th>
                        <th width="15%">Lokasi Fisik</th>
                        <th width="25%">Status Sirkulasi</th>
                        <th width="20%">Peminjam</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach($arsipList as $row): ?>
                    <tr class="<?= $row['status_pengembalian'] == 'Dipinjam' ? 'table-warning' : '' ?>">
                        <td><?= $no++ ?></td>
                        <td>
                            <strong><?= htmlspecialchars($row['nomor_sp2d']) ?></strong><br>
                            <small class="text-muted"><?= htmlspecialchars($row['nama_instansi']) ?></small>
                        </td>
                        <td>
                            <span class="badge bg-secondary mb-1"><?= htmlspecialchars($row['nomor_bindex']) ?></span><br>
                            <small><i class="fa-solid fa-server me-1"></i> <?= htmlspecialchars($row['nama_rak']) ?></small>
                        </td>
                        <td>
                            <?php if($row['status_pengembalian'] == 'Dipinjam'): ?>
                                <span class="badge bg-warning text-dark px-2 py-1 mb-1">Sedang Dipinjam</span><br>
                                <small class="text-danger"><i class="fa-solid fa-clock me-1"></i> Menunggu pengembalian</small>
                            <?php else: ?>
                                <span class="badge bg-success px-2 py-1 mb-1">Disimpan (Tersedia)</span><br>
                                <?php if($row['tanggal_pengembalian']): ?>
                                    <small class="text-success"><i class="fa-solid fa-check me-1"></i> Dikembalikan tgl <?= date('d/m/Y', strtotime($row['tanggal_pengembalian'])) ?></small>
                                <?php else: ?>
                                    <small class="text-muted">Aman di dalam Rak</small>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?= htmlspecialchars($row['penerima_pengembalian'] ?: '-') ?>
                        </td>
                        <td class="text-center">
                            <?php if($row['status_pengembalian'] == 'Disimpan'): ?>
                                <button class="btn btn-sm btn-primary btn-pinjam w-100" 
                                    data-id="<?= $row['id'] ?>" 
                                    data-nosp2d="<?= htmlspecialchars($row['nomor_sp2d']) ?>"
                                    data-bs-toggle="modal" data-bs-target="#modalPinjam">
                                    <i class="fa-solid fa-hand-holding-hand me-1"></i> Pinjamkan
                                </button>
                            <?php else: ?>
                                <button class="btn btn-sm btn-success btn-kembali w-100" data-id="<?= $row['id'] ?>" data-nosp2d="<?= htmlspecialchars($row['nomor_sp2d']) ?>">
                                    <i class="fa-solid fa-arrow-right-to-bracket me-1"></i> Terima Kembali
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Pinjam -->
<div class="modal fade" id="modalPinjam" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-primary text-white border-bottom-0">
        <h5 class="modal-title">Keluarkan Arsip Fisik</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?= base_url('index.php?page=peminjaman_action&action=pinjam') ?>" method="POST">
          <div class="modal-body">
              <div class="alert alert-warning py-2 mb-3">
                  <small>Anda akan meminjamkan Dokumen SP2D: <strong id="lbl_sp2d"></strong></small>
              </div>
              <input type="hidden" name="id" id="pinjam_id">
              <div class="mb-3">
                  <label class="form-label fw-semibold">Nama Peminjam / Penerima Berkas <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="penerima_pengembalian" placeholder="Contoh: Bpk. Ahmad (Dinas Pendidikan)" required>
              </div>
          </div>
          <div class="modal-footer bg-light border-top-0">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan Catatan Peminjaman</button>
          </div>
      </form>
    </div>
  </div>
</div>

<?php 
$custom_scripts = "
<script>
    $(document).ready(function() {
        $('#tableSirkulasi').DataTable({
            'order': [] // Biarkan urutan dari SQL (Dipinjam di atas)
        });

        // Pass Data to Modal Pinjam
        $('.btn-pinjam').on('click', function() {
            $('#pinjam_id').val($(this).data('id'));
            $('#lbl_sp2d').text($(this).data('nosp2d'));
        });

        // Terima Kembali Confirmation
        $('.btn-kembali').on('click', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            const nosp2d = $(this).data('nosp2d');
            
            Swal.fire({
                title: 'Konfirmasi Pengembalian',
                text: 'Apakah arsip fisik ' + nosp2d + ' sudah Anda terima dan letakkan kembali ke Bindex/Rak?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Sudah Diterima!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '" . base_url('index.php?page=peminjaman_action&action=kembali&id=') . "' + id;
                }
            });
        });

        // SweetAlert Flash Message
        const flashData = $('#flash-data').data('msg');
        const flashType = $('#flash-data').data('type');
        if (flashData) {
            Swal.fire({
                icon: flashType,
                title: flashType === 'success' ? 'Berhasil!' : 'Gagal!',
                text: flashData,
                timer: 3000,
                showConfirmButton: false
            });
        }
    });
</script>
";
include 'views/layout/footer.php'; 
?>
