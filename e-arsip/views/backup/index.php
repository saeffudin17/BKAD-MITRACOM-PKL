<?php include 'views/layout/header.php'; ?>
<?php include 'views/layout/sidebar.php'; ?>
<?php include 'views/layout/navbar.php'; ?>

<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= base_url('index.php?page=dashboard') ?>" class="text-decoration-none">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Backup & Restore</li>
  </ol>
</nav>

<h4 class="font-weight-bold mb-4">Backup & Restore Database</h4>

<?php if(isset($_SESSION['flash_msg'])): ?>
    <div id="flash-data" data-type="<?= $_SESSION['flash_type'] ?>" data-msg="<?= $_SESSION['flash_msg'] ?>"></div>
    <?php unset($_SESSION['flash_msg'], $_SESSION['flash_type']); ?>
<?php endif; ?>

<div class="row">
    <!-- Panel Backup -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-0 pt-4 pb-0">
                <h5 class="fw-bold text-success"><i class="fa-solid fa-cloud-arrow-down me-2"></i>Backup Sistem</h5>
            </div>
            <div class="card-body p-4 d-flex flex-column">
                <p class="text-muted">
                    Lakukan <i>backup</i> (cadangkan data) secara berkala untuk menghindari kehilangan data akibat kerusakan server atau *human error*. 
                    Proses ini akan mengunduh seluruh data (Tabel, User, Riwayat, dan Arsip) dalam format file <b>.sql</b>.
                </p>
                <div class="mt-auto pt-3 text-center">
                    <i class="fa-solid fa-database fa-4x text-success opacity-50 mb-4 d-block"></i>
                    <a href="<?= base_url('index.php?page=backup_action&action=backup') ?>" class="btn btn-success btn-lg w-100">
                        <i class="fa-solid fa-download me-2"></i> Download Backup (.sql)
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Panel Restore -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-0 pt-4 pb-0">
                <h5 class="fw-bold text-danger"><i class="fa-solid fa-cloud-arrow-up me-2"></i>Restore Sistem</h5>
            </div>
            <div class="card-body p-4 d-flex flex-column">
                <p class="text-muted">
                    Gunakan fitur ini untuk memulihkan database dari file backup <b>.sql</b> yang pernah Anda unduh sebelumnya. 
                    <br><strong class="text-danger">Peringatan:</strong> Proses ini akan menghapus/menimpa data yang ada di server saat ini dengan data dari file backup!
                </p>
                
                <form action="<?= base_url('index.php?page=backup_action&action=restore') ?>" method="POST" enctype="multipart/form-data" class="mt-auto" id="formRestore">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih File SQL Backup <span class="text-danger">*</span></label>
                        <input type="file" class="form-control border-danger" name="file_sql" accept=".sql" required>
                    </div>
                    <button type="button" class="btn btn-danger btn-lg w-100 btn-restore-confirm">
                        <i class="fa-solid fa-upload me-2"></i> Upload & Restore Data
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php 
$custom_scripts = "
<script>
    $(document).ready(function() {
        // Konfirmasi Restore
        $('.btn-restore-confirm').on('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'PERINGATAN KRITIKAL!',
                html: 'Anda akan melakukan Restore Database.<br><b>Semua data sistem saat ini akan ditimpa (hilang)</b> dan digantikan dengan data dari file SQL ini.<br><br>Apakah Anda sangat yakin?',
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Timpa Data Sekarang!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#formRestore').submit();
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
                timer: 4000,
                showConfirmButton: false
            });
        }
    });
</script>
";
include 'views/layout/footer.php'; 
?>
