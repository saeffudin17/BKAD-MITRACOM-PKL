<?php include 'views/layout/header.php'; ?>
<?php include 'views/layout/sidebar.php'; ?>
<?php include 'views/layout/navbar.php'; ?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= base_url('index.php?page=dashboard') ?>" class="text-decoration-none">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Data Rak</li>
  </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="font-weight-bold mb-0">Master Data Rak</h4>
    <div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="fa-solid fa-plus me-1"></i> Tambah Rak
        </button>
    </div>
</div>

<!-- Flash Message Handling -->
<?php if(isset($_SESSION['flash_msg'])): ?>
    <div id="flash-data" data-type="<?= $_SESSION['flash_type'] ?>" data-msg="<?= $_SESSION['flash_msg'] ?>"></div>
    <?php unset($_SESSION['flash_msg'], $_SESSION['flash_type']); ?>
<?php endif; ?>

<!-- Data Table -->
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="tableRak">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Kode Rak</th>
                        <th>Nama Rak</th>
                        <th>Lokasi</th>
                        <th>Kapasitas</th>
                        <th>Status</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach($rakList as $row): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><span class="badge bg-secondary"><?= htmlspecialchars($row['kode_rak']) ?></span></td>
                        <td><strong><?= htmlspecialchars($row['nama_rak']) ?></strong></td>
                        <td><i class="fa-solid fa-map-pin text-muted me-1"></i> <?= htmlspecialchars($row['lokasi']) ?></td>
                        <td>
                            <?php 
                            // Hitung persentase kapasitas
                            $kapasitas = $row['kapasitas'] > 0 ? $row['kapasitas'] : 1; 
                            $persen = round(($row['jumlah_arsip'] / $kapasitas) * 100);
                            $progressClass = 'bg-success';
                            if ($persen > 70) $progressClass = 'bg-warning';
                            if ($persen >= 100) $progressClass = 'bg-danger';
                            ?>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <small class="text-muted"><?= $row['jumlah_arsip'] ?> / <?= $row['kapasitas'] ?></small>
                                <small class="text-muted fw-bold"><?= $persen ?>%</small>
                            </div>
                            <div class="progress" style="height: 5px;">
                                <div class="progress-bar <?= $progressClass ?>" role="progressbar" style="width: <?= $persen ?>%"></div>
                            </div>
                        </td>
                        <td>
                            <?php if($row['status'] == 'Tersedia'): ?>
                                <span class="badge bg-success">Tersedia</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Penuh</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-warning text-white btn-edit" 
                                data-id="<?= $row['id'] ?>"
                                data-kode="<?= htmlspecialchars($row['kode_rak']) ?>"
                                data-nama="<?= htmlspecialchars($row['nama_rak']) ?>"
                                data-lokasi="<?= htmlspecialchars($row['lokasi']) ?>"
                                data-kapasitas="<?= $row['kapasitas'] ?>"
                                data-status="<?= $row['status'] ?>"
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
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-primary text-white border-bottom-0">
        <h5 class="modal-title">Tambah Rak Arsip</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?= base_url('index.php?page=rak_action&action=add') ?>" method="POST">
          <div class="modal-body">
              <div class="row">
                  <div class="col-md-5 mb-3">
                      <label class="form-label">Kode Rak <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" name="kode_rak" placeholder="Contoh: RK-A1" required>
                  </div>
                  <div class="col-md-7 mb-3">
                      <label class="form-label">Nama Rak <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" name="nama_rak" placeholder="Rak Arsip A1" required>
                  </div>
              </div>
              <div class="mb-3">
                  <label class="form-label">Lokasi <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="lokasi" placeholder="Lantai 1 - Ruangan Arsip" required>
              </div>
              <div class="row">
                  <div class="col-md-6 mb-3">
                      <label class="form-label">Kapasitas Maksimal <span class="text-danger">*</span></label>
                      <input type="number" class="form-control" name="kapasitas" min="1" required>
                  </div>
                  <div class="col-md-6 mb-3">
                      <label class="form-label">Status</label>
                      <select class="form-select" name="status">
                          <option value="Tersedia">Tersedia</option>
                          <option value="Penuh">Penuh</option>
                      </select>
                  </div>
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
<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-warning border-bottom-0">
        <h5 class="modal-title">Edit Rak Arsip</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?= base_url('index.php?page=rak_action&action=edit') ?>" method="POST">
          <div class="modal-body">
              <input type="hidden" name="id" id="edit_id">
              <div class="row">
                  <div class="col-md-5 mb-3">
                      <label class="form-label">Kode Rak <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" name="kode_rak" id="edit_kode" required>
                  </div>
                  <div class="col-md-7 mb-3">
                      <label class="form-label">Nama Rak <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" name="nama_rak" id="edit_nama" required>
                  </div>
              </div>
              <div class="mb-3">
                  <label class="form-label">Lokasi <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="lokasi" id="edit_lokasi" required>
              </div>
              <div class="row">
                  <div class="col-md-6 mb-3">
                      <label class="form-label">Kapasitas Maksimal <span class="text-danger">*</span></label>
                      <input type="number" class="form-control" name="kapasitas" id="edit_kapasitas" min="1" required>
                  </div>
                  <div class="col-md-6 mb-3">
                      <label class="form-label">Status</label>
                      <select class="form-select" name="status" id="edit_status">
                          <option value="Tersedia">Tersedia</option>
                          <option value="Penuh">Penuh</option>
                      </select>
                  </div>
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
        $('#tableRak').DataTable();

        // Pass Data to Edit Modal
        $('.btn-edit').on('click', function() {
            $('#edit_id').val($(this).data('id'));
            $('#edit_kode').val($(this).data('kode'));
            $('#edit_nama').val($(this).data('nama'));
            $('#edit_lokasi').val($(this).data('lokasi'));
            $('#edit_kapasitas').val($(this).data('kapasitas'));
            $('#edit_status').val($(this).data('status'));
        });

        // SweetAlert Delete Confirmation
        $('.btn-delete').on('click', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            Swal.fire({
                title: 'Hapus Data Rak?',
                text: 'Data rak tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '" . base_url('index.php?page=rak_action&action=delete&id=') . "' + id;
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
