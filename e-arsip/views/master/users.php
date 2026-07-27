<?php include 'views/layout/header.php'; ?>
<?php include 'views/layout/sidebar.php'; ?>
<?php include 'views/layout/navbar.php'; ?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= base_url('index.php?page=dashboard') ?>" class="text-decoration-none">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Manajemen User</li>
  </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="font-weight-bold mb-0">Manajemen Pengguna (User)</h4>
    <div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="fa-solid fa-user-plus me-1"></i> Tambah User Baru
        </button>
    </div>
</div>

<?php if(isset($_SESSION['flash_msg'])): ?>
    <div id="flash-data" data-type="<?= $_SESSION['flash_type'] ?>" data-msg="<?= $_SESSION['flash_msg'] ?>"></div>
    <?php unset($_SESSION['flash_msg'], $_SESSION['flash_type']); ?>
<?php endif; ?>

<!-- Data Table -->
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="tableUsers">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Info Pengguna</th>
                        <th>Role Akses</th>
                        <th>Status</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach($usersList as $row): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center me-3" style="width: 40px; height: 40px;">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <div>
                                    <strong><?= htmlspecialchars($row['nama']) ?></strong>
                                    <?php if($row['id'] == $_SESSION['user_id']): ?>
                                        <span class="badge bg-success ms-1" style="font-size:0.6rem;">You</span>
                                    <?php endif; ?>
                                    <br>
                                    <small class="text-muted">@<?= htmlspecialchars($row['username']) ?></small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <?php if($row['role'] == 'Admin'): ?>
                                <span class="badge bg-dark"><i class="fa-solid fa-crown me-1"></i> Admin</span>
                            <?php elseif($row['role'] == 'Operator'): ?>
                                <span class="badge bg-info text-dark"><i class="fa-solid fa-keyboard me-1"></i> Operator</span>
                            <?php elseif($row['role'] == 'Reviewer'): ?>
                                <span class="badge bg-primary"><i class="fa-solid fa-check-double me-1"></i> Reviewer</span>
                            <?php elseif($row['role'] == 'Management'): ?>
                                <span class="badge bg-warning text-dark"><i class="fa-solid fa-chart-pie me-1"></i> Management</span>
                            <?php elseif($row['role'] == 'Direktur'): ?>
                                <span class="badge bg-success"><i class="fa-solid fa-eye me-1"></i> Direktur</span>
                            <?php else: ?>
                                <span class="badge bg-secondary"><i class="fa-solid fa-user me-1"></i> User</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($row['status'] == 'Aktif'): ?>
                                <span class="badge bg-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-warning text-white btn-edit" 
                                data-id="<?= $row['id'] ?>"
                                data-nama="<?= htmlspecialchars($row['nama']) ?>"
                                data-username="<?= htmlspecialchars($row['username']) ?>"
                                data-role="<?= $row['role'] ?>"
                                data-status="<?= $row['status'] ?>"
                                data-bs-toggle="modal" data-bs-target="#modalEdit">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button class="btn btn-sm btn-danger btn-delete <?= ($row['id'] == $_SESSION['user_id']) ? 'disabled' : '' ?>" data-id="<?= $row['id'] ?>">
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

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-primary text-white border-bottom-0">
        <h5 class="modal-title">Tambah Pengguna Baru</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?= base_url('index.php?page=users_action&action=add') ?>" method="POST">
          <div class="modal-body">
              <div class="mb-3">
                  <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="nama" required>
              </div>
              <div class="mb-3">
                  <label class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="username" required>
              </div>
              <div class="mb-3">
                  <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                  <input type="password" class="form-control" name="password" required>
              </div>
              <div class="row">
                  <div class="col-md-6 mb-3">
                      <label class="form-label fw-semibold">Role Akses <span class="text-danger">*</span></label>
                      <select class="form-select" name="role" required>
                          <option value="Operator">Operator</option>
                          <option value="Reviewer">Reviewer</option>
                          <option value="Management">Management</option>
                          <option value="Direktur">Direktur</option>
                          <option value="Admin">Admin</option>
                      </select>
                  </div>
                  <div class="col-md-6 mb-3">
                      <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                      <select class="form-select" name="status" required>
                          <option value="Aktif">Aktif</option>
                          <option value="Nonaktif">Nonaktif</option>
                      </select>
                  </div>
              </div>
          </div>
          <div class="modal-footer bg-light border-top-0">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan User</button>
          </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-warning border-bottom-0">
        <h5 class="modal-title">Edit Pengguna</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?= base_url('index.php?page=users_action&action=edit') ?>" method="POST">
          <div class="modal-body">
              <input type="hidden" name="id" id="edit_id">
              <div class="mb-3">
                  <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="nama" id="edit_nama" required>
              </div>
              <div class="mb-3">
                  <label class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="username" id="edit_username" required>
              </div>
              <div class="mb-3">
                  <label class="form-label fw-semibold">Reset Password</label>
                  <input type="password" class="form-control" name="password" placeholder="Kosongkan jika tidak ingin merubah password">
              </div>
              <div class="row">
                  <div class="col-md-6 mb-3">
                      <label class="form-label fw-semibold">Role Akses <span class="text-danger">*</span></label>
                      <select class="form-select" name="role" id="edit_role" required>
                          <option value="Operator">Operator</option>
                          <option value="Reviewer">Reviewer</option>
                          <option value="Management">Management</option>
                          <option value="Direktur">Direktur</option>
                          <option value="Admin">Admin</option>
                      </select>
                  </div>
                  <div class="col-md-6 mb-3">
                      <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                      <select class="form-select" name="status" id="edit_status" required>
                          <option value="Aktif">Aktif</option>
                          <option value="Nonaktif">Nonaktif</option>
                      </select>
                  </div>
              </div>
          </div>
          <div class="modal-footer bg-light border-top-0">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-warning">Update User</button>
          </div>
      </form>
    </div>
  </div>
</div>

<?php 
$custom_scripts = "
<script>
    $(document).ready(function() {
        $('#tableUsers').DataTable();

        // Pass Data to Edit Modal
        $('.btn-edit').on('click', function() {
            $('#edit_id').val($(this).data('id'));
            $('#edit_nama').val($(this).data('nama'));
            $('#edit_username').val($(this).data('username'));
            $('#edit_role').val($(this).data('role'));
            $('#edit_status').val($(this).data('status'));
        });

        // Delete Action
        $('.btn-delete').on('click', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            Swal.fire({
                title: 'Hapus Pengguna?',
                text: 'Data pengguna ini akan dihapus dari sistem.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '" . base_url('index.php?page=users_action&action=delete&id=') . "' + id;
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
