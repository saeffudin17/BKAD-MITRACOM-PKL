<?php include 'views/layout/header.php'; ?>
<?php include 'views/layout/sidebar.php'; ?>
<?php include 'views/layout/navbar.php'; ?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= base_url('index.php?page=dashboard') ?>" class="text-decoration-none">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Data Instansi</li>
  </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="font-weight-bold mb-0">Master Data Instansi</h4>
    <div>
        <button type="button" class="btn btn-outline-secondary me-2"><i class="fa-solid fa-file-excel me-1"></i> Export</button>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="fa-solid fa-plus me-1"></i> Tambah Instansi
        </button>
    </div>
</div>

<!-- Flash Message Handling (SweetAlert2 Trigger) -->
<?php if(isset($_SESSION['flash_msg'])): ?>
    <div id="flash-data" data-type="<?= $_SESSION['flash_type'] ?>" data-msg="<?= $_SESSION['flash_msg'] ?>"></div>
    <?php unset($_SESSION['flash_msg'], $_SESSION['flash_type']); ?>
<?php endif; ?>

<!-- Data Table -->
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="tableInstansi">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Instansi</th>
                        <th>Telepon</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach($instansiList as $row): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>
                            <strong><?= htmlspecialchars($row['nama_instansi']) ?></strong><br>
                            <small class="text-muted"><i class="fa-solid fa-location-dot me-1"></i> <?= htmlspecialchars($row['alamat']) ?></small>
                        </td>
                        <td><?= htmlspecialchars($row['no_telepon']) ?: '-' ?></td>
                        <td><?= htmlspecialchars($row['email']) ?: '-' ?></td>
                        <td>
                            <?php if($row['status_aktif'] == 'Aktif'): ?>
                                <span class="badge bg-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-warning text-white btn-edit" 
                                data-id="<?= $row['id'] ?>"
                                data-nama="<?= htmlspecialchars($row['nama_instansi']) ?>"
                                data-alamat="<?= htmlspecialchars($row['alamat']) ?>"
                                data-telp="<?= htmlspecialchars($row['no_telepon']) ?>"
                                data-email="<?= htmlspecialchars($row['email']) ?>"
                                data-status="<?= $row['status_aktif'] ?>"
                                data-bs-toggle="modal" data-bs-target="#modalEdit">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button class="btn btn-sm btn-danger btn-delete" data-id="<?= $row['id'] ?>">
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
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-primary text-white border-bottom-0">
        <h5 class="modal-title" id="modalTambahLabel">Tambah Instansi</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?= base_url('index.php?page=instansi_action&action=add') ?>" method="POST">
          <div class="modal-body">
              <div class="mb-3">
                  <label class="form-label">Nama Instansi <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="nama_instansi" required>
              </div>
              <div class="mb-3">
                  <label class="form-label">Alamat</label>
                  <textarea class="form-control" name="alamat" rows="2"></textarea>
              </div>
              <div class="row">
                  <div class="col-md-6 mb-3">
                      <label class="form-label">No. Telepon</label>
                      <input type="text" class="form-control" name="no_telepon">
                  </div>
                  <div class="col-md-6 mb-3">
                      <label class="form-label">Email</label>
                      <input type="email" class="form-control" name="email">
                  </div>
              </div>
              <div class="mb-3">
                  <label class="form-label">Status Aktif</label>
                  <select class="form-select" name="status_aktif">
                      <option value="Aktif">Aktif</option>
                      <option value="Nonaktif">Nonaktif</option>
                  </select>
              </div>
          </div>
          <div class="modal-footer bg-light border-top-0">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan Data</button>
          </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-warning border-bottom-0">
        <h5 class="modal-title" id="modalEditLabel">Edit Instansi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?= base_url('index.php?page=instansi_action&action=edit') ?>" method="POST">
          <div class="modal-body">
              <input type="hidden" name="id" id="edit_id">
              <div class="mb-3">
                  <label class="form-label">Nama Instansi <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="nama_instansi" id="edit_nama" required>
              </div>
              <div class="mb-3">
                  <label class="form-label">Alamat</label>
                  <textarea class="form-control" name="alamat" id="edit_alamat" rows="2"></textarea>
              </div>
              <div class="row">
                  <div class="col-md-6 mb-3">
                      <label class="form-label">No. Telepon</label>
                      <input type="text" class="form-control" name="no_telepon" id="edit_telp">
                  </div>
                  <div class="col-md-6 mb-3">
                      <label class="form-label">Email</label>
                      <input type="email" class="form-control" name="email" id="edit_email">
                  </div>
              </div>
              <div class="mb-3">
                  <label class="form-label">Status Aktif</label>
                  <select class="form-select" name="status_aktif" id="edit_status">
                      <option value="Aktif">Aktif</option>
                      <option value="Nonaktif">Nonaktif</option>
                  </select>
              </div>
          </div>
          <div class="modal-footer bg-light border-top-0">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-warning">Update Data</button>
          </div>
      </form>
    </div>
  </div>
</div>

<?php 
$custom_scripts = "
<script>
    $(document).ready(function() {
        $('#tableInstansi').DataTable();

        // Pass Data to Edit Modal
        $('.btn-edit').on('click', function() {
            $('#edit_id').val($(this).data('id'));
            $('#edit_nama').val($(this).data('nama'));
            $('#edit_alamat').val($(this).data('alamat'));
            $('#edit_telp').val($(this).data('telp'));
            $('#edit_email').val($(this).data('email'));
            $('#edit_status').val($(this).data('status'));
        });

        // SweetAlert Delete Confirmation
        $('.btn-delete').on('click', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Data instansi yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '" . base_url('index.php?page=instansi_action&action=delete&id=') . "' + id;
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
