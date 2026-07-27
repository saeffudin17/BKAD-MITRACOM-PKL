<?php include 'views/layout/header.php'; ?>
<?php include 'views/layout/sidebar.php'; ?>
<?php include 'views/layout/navbar.php'; ?>

<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= base_url('index.php?page=dashboard') ?>" class="text-decoration-none">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Activity Log</li>
  </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="font-weight-bold mb-0">Sistem Activity Log</h4>
    <div>
        <?php if ($_SESSION['user_role'] === 'Superadmin'): ?>
            <a href="#" onclick="if(confirm('Peringatan: Aksi ini akan menghapus permanen SEMUA riwayat log aktivitas. Anda yakin?')) { window.location.href='<?= base_url('index.php?page=logs_action&action=clear_all') ?>'; }" class="btn btn-danger btn-sm me-2">
                <i class="fa-solid fa-trash me-1"></i> Kosongkan Log
            </a>
        <?php endif; ?>
        <button class="btn btn-outline-secondary btn-sm" onclick="location.reload();">
            <i class="fa-solid fa-arrows-rotate me-1"></i> Refresh
        </button>
    </div>
</div>

<div class="alert alert-warning border-0 bg-warning bg-opacity-10 d-flex align-items-center mb-4">
    <i class="fa-solid fa-shield-halved fa-2x text-warning me-3"></i>
    <div>
        <strong>Keamanan Sistem</strong><br>
        Seluruh rekaman aktivitas pengguna dicatat secara otomatis (Real-Time). Hanya <b>Administrator / Superadmin</b> yang dapat melihat riwayat ini. Untuk alasan audit, hanya level <b>Superadmin</b> yang diizinkan untuk mengosongkan log ini. (Menampilkan 500 riwayat terbaru).
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="tableLogs">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Waktu Aktivitas</th>
                        <th width="20%">Pengguna (User)</th>
                        <th width="15%">Tipe Aksi</th>
                        <th width="45%">Deskripsi Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach($logList as $row): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>
                            <span class="d-none"><?= strtotime($row['created_at']) ?></span> <!-- For sorting -->
                            <strong><?= date('d M Y', strtotime($row['created_at'])) ?></strong><br>
                            <small class="text-muted"><i class="fa-regular fa-clock me-1"></i> <?= date('H:i:s', strtotime($row['created_at'])) ?></small>
                        </td>
                        <td>
                            <strong><?= htmlspecialchars($row['nama'] ?? 'System / Deleted User') ?></strong><br>
                            <?php if(!empty($row['role'])): ?>
                                <span class="badge bg-secondary" style="font-size: 0.65rem;"><?= htmlspecialchars($row['role']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php 
                                $action = htmlspecialchars($row['action']);
                                $badge = 'bg-secondary';
                                if($action == 'Login') $badge = 'bg-info text-dark';
                                elseif($action == 'Logout') $badge = 'bg-dark';
                                elseif($action == 'Tambah') $badge = 'bg-primary';
                                elseif($action == 'Edit') $badge = 'bg-warning text-dark';
                                elseif($action == 'Hapus') $badge = 'bg-danger';
                                elseif($action == 'Download') $badge = 'bg-success';
                                elseif($action == 'Cetak') $badge = 'bg-primary bg-opacity-75';
                            ?>
                            <span class="badge <?= $badge ?> px-2 py-1"><?= $action ?></span>
                        </td>
                        <td><?= htmlspecialchars($row['description']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if(isset($_SESSION['flash_msg'])): ?>
    <div id="flash-data" data-msg="<?= htmlspecialchars($_SESSION['flash_msg']) ?>" data-type="<?= htmlspecialchars($_SESSION['flash_type']) ?>"></div>
    <?php unset($_SESSION['flash_msg'], $_SESSION['flash_type']); ?>
<?php endif; ?>

<?php 
$custom_scripts = "
<script>
    $(document).ready(function() {
        $('#tableLogs').DataTable({
            'order': [[0, 'asc']] // Tetap ascending berdasar nomor karena sudah diurutkan dari DB
        });
        
        var flashMsg = $('#flash-data').data('msg');
        var flashType = $('#flash-data').data('type');
        if(flashMsg) {
            Swal.fire({
                title: flashType === 'success' ? 'Berhasil!' : (flashType === 'error' ? 'Gagal!' : 'Info'),
                text: flashMsg,
                icon: flashType,
                confirmButtonColor: '#3085d6'
            });
        }
    });
</script>
";
include 'views/layout/footer.php'; 
?>
