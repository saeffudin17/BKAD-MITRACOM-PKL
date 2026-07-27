<?php include 'views/layout/header.php'; ?>
<?php include 'views/layout/sidebar.php'; ?>
<?php include 'views/layout/navbar.php'; ?>

<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= base_url('index.php?page=dashboard') ?>" class="text-decoration-none">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">List Data Arsip</li>
  </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="font-weight-bold mb-0">Daftar Arsip SP2D</h4>
    <div>
        <a href="<?= base_url('index.php?page=arsip_input') ?>" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i> Input Arsip Baru
        </a>
    </div>
</div>

<?php if(isset($_SESSION['flash_msg'])): ?>
    <div id="flash-data" data-type="<?= $_SESSION['flash_type'] ?>" data-msg="<?= $_SESSION['flash_msg'] ?>"></div>
    <?php unset($_SESSION['flash_msg'], $_SESSION['flash_type']); ?>
<?php endif; ?>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="tableArsipList">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>No. SP2D</th>
                        <th>Instansi</th>
                        <th>Kegiatan</th>
                        <th>Bindex / Rak</th>
                        <th>Status</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach($arsipList as $row): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>
                            <strong><?= htmlspecialchars($row['nomor_sp2d']) ?></strong><br>
                            <small class="text-muted"><i class="fa-regular fa-calendar-check me-1"></i> <?= date('d M Y', strtotime($row['tanggal_sp2d'])) ?></small>
                        </td>
                        <td><?= htmlspecialchars($row['nama_instansi']) ?></td>
                        <td>
                            <?= htmlspecialchars($row['jenis_kegiatan']) ?><br>
                            <small class="text-muted"><?= htmlspecialchars($row['nama_kegiatan']) ?></small>
                        </td>
                        <td>
                            <span class="badge bg-secondary mb-1"><?= htmlspecialchars($row['nomor_bindex']) ?></span><br>
                            <small class="text-muted"><i class="fa-solid fa-server me-1"></i> <?= htmlspecialchars($row['nama_rak']) ?></small>
                        </td>
                        <td>
                            <?php
                            $badgeClass = 'bg-secondary';
                            if($row['status_arsip'] == 'Diproses') $badgeClass = 'bg-primary';
                            elseif($row['status_arsip'] == 'Dikembalikan') $badgeClass = 'bg-danger';
                            ?>
                            <span class="badge <?= $badgeClass ?>"><?= $row['status_arsip'] ?></span>
                        </td>
                        <td class="text-center">
                            <a href="<?= base_url('index.php?page=arsip_detail&id='.$row['id']) ?>" class="btn btn-sm btn-info text-white mb-1" data-bs-toggle="tooltip" title="Detail Arsip">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="<?= base_url('index.php?page=arsip_input&id='.$row['id']) ?>" class="btn btn-sm btn-warning text-white mb-1" data-bs-toggle="tooltip" title="Edit Arsip">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <?php if(!empty($row['file_pdf'])): ?>
                            <a href="<?= base_url('index.php?page=arsip_action&action=download_pdf&id='.$row['id']) ?>" class="btn btn-sm btn-success text-white mb-1" data-bs-toggle="tooltip" title="Download PDF">
                                <i class="fa-solid fa-download"></i>
                            </a>
                            <?php endif; ?>
                            <button class="btn btn-sm btn-danger btn-delete mb-1" data-id="<?= $row['id'] ?>" data-bs-toggle="tooltip" title="Hapus Arsip">
                                <i class="fa-solid fa-trash"></i>
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
$custom_scripts = <<<HTML
<script>
    $(document).ready(function() {
        // Ambil parameter search dari URL jika ada
        const urlParams = new URLSearchParams(window.location.search);
        const searchQuery = urlParams.get('search') || '';

        $('#tableArsipList').DataTable({
            dom: '<"row"<"col-md-6"B><"col-md-6"f>>rt<"row"<"col-md-6"i><"col-md-6"p>>',
            buttons: [
                { extend: 'copy', className: 'btn btn-sm btn-light border' },
                { extend: 'excel', className: 'btn btn-sm btn-success', text: '<i class="fa-solid fa-file-excel"></i> Excel' },
                { extend: 'pdf', className: 'btn btn-sm btn-danger', text: '<i class="fa-solid fa-file-pdf"></i> PDF' },
                { extend: 'print', className: 'btn btn-sm btn-info text-white', text: '<i class="fa-solid fa-print"></i> Print' }
            ],
            search: {
                search: searchQuery
            }
        });

        $('.btn-delete').on('click', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            Swal.fire({
                title: 'Pindahkan ke Trash?',
                text: 'Arsip ini akan dipindahkan ke Recycle Bin.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'index.php?page=arsip_action&action=delete&id=' + id;
                }
            });
        });

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
HTML;
include 'views/layout/footer.php'; 
?>
