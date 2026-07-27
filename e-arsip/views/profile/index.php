<?php include 'views/layout/header.php'; ?>
<?php include 'views/layout/sidebar.php'; ?>
<?php include 'views/layout/navbar.php'; ?>

<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= base_url('index.php?page=dashboard') ?>" class="text-decoration-none">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">My Profile</li>
  </ol>
</nav>

<h4 class="font-weight-bold mb-4">Profil Pengguna</h4>

<?php if(isset($_SESSION['flash_msg'])): ?>
    <div id="flash-data" data-type="<?= $_SESSION['flash_type'] ?>" data-msg="<?= $_SESSION['flash_msg'] ?>"></div>
    <?php unset($_SESSION['flash_msg'], $_SESSION['flash_type']); ?>
<?php endif; ?>

<div class="row">
    <!-- Card Info Profil -->
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body py-5">
                <?php 
                    $foto = ($user['foto'] == 'default.png' || empty($user['foto'])) 
                            ? 'https://ui-avatars.com/api/?name='.urlencode($user['nama']).'&background=0D6EFD&color=fff&size=128' 
                            : base_url('assets/images/users/' . $user['foto']);
                ?>
                <img src="<?= $foto ?>" class="rounded-circle mb-3 shadow-sm" alt="User Photo" style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #fff;">
                <h5 class="fw-bold mb-1"><?= htmlspecialchars($user['nama']) ?></h5>
                <p class="text-muted mb-3">@<?= htmlspecialchars($user['username']) ?></p>
                
                <?php if($user['role'] == 'Admin'): ?>
                    <span class="badge bg-danger px-3 py-2"><i class="fa-solid fa-shield-halved me-1"></i> Administrator</span>
                <?php else: ?>
                    <span class="badge bg-info text-dark px-3 py-2"><i class="fa-solid fa-user-tie me-1"></i> Petugas Arsip</span>
                <?php endif; ?>
                
                <hr class="my-4">
                <p class="small text-muted mb-0">Bergabung sejak: <?= date('d M Y', strtotime($user['created_at'])) ?></p>
            </div>
        </div>
    </div>
    
    <!-- Form Edit Profil & Password -->
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-0 pt-4 pb-0">
                <h6 class="fw-bold text-primary"><i class="fa-solid fa-user-pen me-2"></i>Update Informasi Profil</h6>
            </div>
            <div class="card-body p-4">
                <form action="<?= base_url('index.php?page=profile_action&action=update_profile') ?>" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap</label>
                        <input type="text" class="form-control" name="nama" value="<?= htmlspecialchars($user['nama']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Username (Login ID)</label>
                        <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($user['username']) ?>" readonly disabled>
                        <small class="text-muted">Username tidak dapat diubah.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Ganti Foto Profil (Opsional)</label>
                        <input type="file" class="form-control" name="foto" accept=".jpg,.jpeg,.png">
                        <small class="text-muted">Format: JPG/PNG, Maks: 2MB.</small>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i> Simpan Perubahan</button>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 pt-4 pb-0">
                <h6 class="fw-bold text-warning"><i class="fa-solid fa-key me-2"></i>Keamanan & Ganti Password</h6>
            </div>
            <div class="card-body p-4">
                <form action="<?= base_url('index.php?page=profile_action&action=update_password') ?>" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password Lama <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="password_lama" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Password Baru <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" name="password_baru" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" name="konfirmasi_password" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-warning text-dark"><i class="fa-solid fa-lock me-1"></i> Update Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php 
$custom_scripts = "
<script>
    $(document).ready(function() {
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
