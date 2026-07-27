<?php include 'views/layout/header.php'; ?>
<?php include 'views/layout/sidebar.php'; ?>
<?php include 'views/layout/navbar.php'; ?>

<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= base_url('index.php?page=dashboard') ?>" class="text-decoration-none">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Recycle Bin</li>
  </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="font-weight-bold mb-0">Recycle Bin (Tong Sampah)</h4>
</div>

<?php if(isset($_SESSION['flash_msg'])): ?>
    <div id="flash-data" data-type="<?= $_SESSION['flash_type'] ?>" data-msg="<?= $_SESSION['flash_msg'] ?>"></div>
    <?php unset($_SESSION['flash_msg'], $_SESSION['flash_type']); ?>
<?php endif; ?>

<div class="alert alert-danger border-0 bg-danger bg-opacity-10 d-flex align-items-center mb-4">
    <i class="fa-solid fa-triangle-exclamation fa-2x text-danger me-3"></i>
    <div>
        <strong>Peringatan!</strong><br>
        Data yang dihapus secara permanen (Hard Delete) dari halaman ini tidak akan bisa dikembalikan lagi, dan file PDF yang terkait juga akan terhapus otomatis dari server untuk menghemat kapasitas penyimpanan.
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="tableRecycleBin">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>No. SP2D</th>
                        <th>Instansi</th>
                        <th>Jenis Kegiatan</th>
                        <th>Bindex / Rak</th>
                        <th width="20%" class="text-center">Aksi (Admin)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach($arsipDeleted as $row): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>
                            <strong><del><?= htmlspecialchars($row['nomor_sp2d']) ?></del></strong><br>
                            <small class="text-muted text-decoration-line-through"><?= date('d M Y', strtotime($row['tanggal_sp2d'])) ?></small>
                        </td>
                        <td class="text-muted"><?= htmlspecialchars($row['nama_instansi']) ?></td>
                        <td class="text-muted"><?= htmlspecialchars($row['jenis_kegiatan']) ?></td>
                        <td class="text-muted">
                            <span class="badge bg-secondary mb-1 opacity-50"><?= htmlspecialchars($row['nomor_bindex']) ?></span><br>
                            <small><i class="fa-solid fa-server me-1"></i> <?= htmlspecialchars($row['nama_rak']) ?></small>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-success btn-restore me-1" data-id="<?= $row['id'] ?>" data-bs-toggle="tooltip" title="Kembalikan Arsip">
                                <i class="fa-solid fa-rotate-left me-1"></i> Restore
                            </button>
                            <button class="btn btn-sm btn-danger btn-hard-delete" data-id="<?= $row['id'] ?>" data-bs-toggle="tooltip" title="Hapus Permanen">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php 
$custom_scripts = "
<script>
    $(document).ready(function() {
        $('#tableRecycleBin').DataTable();

        // Restore Action
        $('.btn-restore').on('click', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            Swal.fire({
                title: 'Kembalikan Arsip?',
                text: 'Data arsip ini akan dikembalikan ke daftar arsip aktif.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Kembalikan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '" . base_url('index.php?page=recycle_bin_action&action=restore&id=') . "' + id;
                }
            });
        });

        // Hard Delete Action
        $('.btn-hard-delete').on('click', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            Swal.fire({
                title: 'Hapus Permanen?',
                text: 'AWAS! Data dan file PDF yang terkait akan dihapus secara permanen dari server dan tidak bisa dikembalikan!',
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus Permanen!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '" . base_url('index.php?page=recycle_bin_action&action=hard_delete&id=') . "' + id;
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
