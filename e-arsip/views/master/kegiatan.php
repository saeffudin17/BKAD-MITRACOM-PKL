<?php include 'views/layout/header.php'; ?>
<?php include 'views/layout/sidebar.php'; ?>
<?php include 'views/layout/navbar.php'; ?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= base_url('index.php?page=dashboard') ?>" class="text-decoration-none">Home</a></li>
    <li class="breadcrumb-item">Master Data</li>
    <li class="breadcrumb-item active" aria-current="page">Data Kegiatan</li>
  </ol>
</nav>

<!-- Flash Message Handling (SweetAlert2 Trigger) -->
<?php if(isset($_SESSION['flash_msg'])): ?>
    <div id="flash-data" data-type="<?= $_SESSION['flash_type'] ?>" data-msg="<?= $_SESSION['flash_msg'] ?>"></div>
    <?php unset($_SESSION['flash_msg'], $_SESSION['flash_type']); ?>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="font-weight-bold mb-0">Manajemen Data Kegiatan</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddKegiatan">
        <i class="fa-solid fa-plus me-1"></i> Tambah Kegiatan
    </button>
</div>

<!-- DataTables -->
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="tableKegiatan">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Jenis Kegiatan</th>
                        <th>Nama Kegiatan</th>
                        <th width="10%">Status</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach($kegiatanList as $row): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['jenis_kegiatan']) ?></td>
                        <td><?= htmlspecialchars($row['nama_kegiatan']) ?></td>
                        <td>
                            <?php if($row['status_aktif'] == 'Aktif'): ?>
                                <span class="badge bg-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-warning text-white" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalEditKegiatan"
                                    data-id="<?= $row['id'] ?>"
                                    data-jenis="<?= htmlspecialchars($row['jenis_kegiatan']) ?>"
                                    data-nama="<?= htmlspecialchars($row['nama_kegiatan']) ?>"
                                    data-status="<?= $row['status_aktif'] ?>"
                                    title="Edit">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" 
                                    onclick="confirmDelete('<?= base_url('index.php?page=kegiatan_action&action=delete&id='.$row['id']) ?>')"
                                    title="Hapus">
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

<!-- Modal Add -->
<div class="modal fade" id="modalAddKegiatan" tabindex="-1" aria-labelledby="modalAddKegiatanLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="<?= base_url('index.php?page=kegiatan_action&action=add') ?>" method="POST">
      <div class="modal-content border-0 shadow">
        <div class="modal-header bg-primary text-white border-0">
          <h5 class="modal-title" id="modalAddKegiatanLabel"><i class="fa-solid fa-briefcase me-2"></i>Tambah Kegiatan Baru</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4">
          <div class="mb-3">
              <label class="form-label fw-semibold">Jenis Kegiatan <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="jenis_kegiatan" required placeholder="Contoh: Belanja Modal">
          </div>
          <div class="mb-3">
              <label class="form-label fw-semibold">Nama Kegiatan <span class="text-danger">*</span></label>
              <textarea class="form-control" name="nama_kegiatan" rows="2" required placeholder="Contoh: Pengadaan Komputer"></textarea>
          </div>
          <div class="mb-3">
              <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
              <select class="form-select" name="status_aktif" required>
                  <option value="Aktif">Aktif</option>
                  <option value="Nonaktif">Nonaktif</option>
              </select>
          </div>
        </div>
        <div class="modal-footer border-0 bg-light">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i> Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEditKegiatan" tabindex="-1" aria-labelledby="modalEditKegiatanLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="<?= base_url('index.php?page=kegiatan_action&action=edit') ?>" method="POST">
      <div class="modal-content border-0 shadow">
        <div class="modal-header bg-warning border-0">
          <h5 class="modal-title" id="modalEditKegiatanLabel"><i class="fa-solid fa-pen me-2"></i>Edit Data Kegiatan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4">
          <input type="hidden" name="id" id="edit_id">
          <div class="mb-3">
              <label class="form-label fw-semibold">Jenis Kegiatan <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="jenis_kegiatan" id="edit_jenis_kegiatan" required>
          </div>
          <div class="mb-3">
              <label class="form-label fw-semibold">Nama Kegiatan <span class="text-danger">*</span></label>
              <textarea class="form-control" name="nama_kegiatan" id="edit_nama_kegiatan" rows="2" required></textarea>
          </div>
          <div class="mb-3">
              <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
              <select class="form-select" name="status_aktif" id="edit_status_aktif" required>
                  <option value="Aktif">Aktif</option>
                  <option value="Nonaktif">Nonaktif</option>
              </select>
          </div>
        </div>
        <div class="modal-footer border-0 bg-light">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-warning"><i class="fa-solid fa-save me-1"></i> Update Data</button>
        </div>
      </div>
    </form>
  </div>
</div>

<?php 
$custom_scripts = "
<script>
    $(document).ready(function() {
        $('#tableKegiatan').DataTable();

        // SweetAlert Flash Message
        const flashData = $('#flash-data').data('msg');
        const flashType = $('#flash-data').data('type');
        if (flashData) {
            Swal.fire({
                icon: flashType,
                title: flashType === 'success' ? 'Berhasil!' : 'Gagal!',
                text: flashData,
                showConfirmButton: false,
                timer: 2000
            });
        }

        // Script untuk passing data ke Modal Edit
        $('#modalEditKegiatan').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var jenis = button.data('jenis');
            var nama = button.data('nama');
            var status = button.data('status');

            var modal = $(this);
            modal.find('#edit_id').val(id);
            modal.find('#edit_jenis_kegiatan').val(jenis);
            modal.find('#edit_nama_kegiatan').val(nama);
            modal.find('#edit_status_aktif').val(status);
        });
    });

    function confirmDelete(url) {
        Swal.fire({
            title: 'Hapus Kegiatan?',
            text: 'Data yang dihapus mungkin tidak dapat dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    }
</script>
";
include 'views/layout/footer.php'; 
?>
