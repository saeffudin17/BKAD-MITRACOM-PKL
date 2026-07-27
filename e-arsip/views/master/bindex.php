<?php include 'views/layout/header.php'; ?>
<?php include 'views/layout/sidebar.php'; ?>
<?php include 'views/layout/navbar.php'; ?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= base_url('index.php?page=dashboard') ?>" class="text-decoration-none">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Data Bindex</li>
  </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="font-weight-bold mb-0">Master Data Bindex</h4>
    <div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="fa-solid fa-plus me-1"></i> Tambah Bindex
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
            <table class="table table-hover align-middle w-100" id="tableBindex">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Nomor Bindex</th>
                        <th>Nama Bindex</th>
                        <th>Lokasi Rak</th>
                        <th>Keterangan</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach($bindexList as $row): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><span class="badge bg-primary text-white"><?= htmlspecialchars($row['nomor_bindex']) ?></span></td>
                        <td><strong><?= htmlspecialchars($row['nama_bindex']) ?></strong></td>
                        <td>
                            <i class="fa-solid fa-server text-muted me-1"></i> 
                            <?= htmlspecialchars($row['nama_rak']) ?> 
                            <small class="text-muted">(<?= htmlspecialchars($row['kode_rak']) ?>)</small>
                        </td>
                        <td><small><?= htmlspecialchars($row['keterangan']) ?: '-' ?></small></td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-warning text-white btn-edit" 
                                data-id="<?= $row['id'] ?>"
                                data-nomor="<?= htmlspecialchars($row['nomor_bindex']) ?>"
                                data-nama="<?= htmlspecialchars($row['nama_bindex']) ?>"
                                data-rak="<?= $row['id_rak'] ?>"
                                data-ket="<?= htmlspecialchars($row['keterangan']) ?>"
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
        <h5 class="modal-title">Tambah Bindex Arsip</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?= base_url('index.php?page=bindex_action&action=add') ?>" method="POST">
          <div class="modal-body">
              <div class="mb-3">
                  <label class="form-label">Nomor Bindex <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="nomor_bindex" placeholder="Contoh: BDX-2026-01" required>
              </div>
              <div class="mb-3">
                  <label class="form-label">Nama Bindex <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="nama_bindex" placeholder="Bindex Januari 2026" required>
              </div>
              <div class="mb-3">
                  <label class="form-label">Lokasi Penempatan Rak <span class="text-danger">*</span></label>
                  <select class="form-select" name="id_rak" required>
                      <option value="">-- Pilih Rak Tersedia --</option>
                      <?php foreach($rakListOptions as $rak): ?>
                          <option value="<?= $rak['id'] ?>"><?= htmlspecialchars($rak['kode_rak'] . ' - ' . $rak['nama_rak']) ?></option>
                      <?php endforeach; ?>
                  </select>
                  <?php if(empty($rakListOptions)): ?>
                      <small class="text-danger mt-1 d-block"><i class="fa-solid fa-triangle-exclamation"></i> Tidak ada rak dengan status 'Tersedia'. Tambahkan atau ubah status rak terlebih dahulu.</small>
                  <?php endif; ?>
              </div>
              <div class="mb-3">
                  <label class="form-label">Keterangan</label>
                  <textarea class="form-control" name="keterangan" rows="2" placeholder="Informasi tambahan..."></textarea>
              </div>
          </div>
          <div class="modal-footer bg-light border-top-0">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary" <?= empty($rakListOptions) ? 'disabled' : '' ?>>Simpan Data</button>
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
        <h5 class="modal-title">Edit Bindex Arsip</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?= base_url('index.php?page=bindex_action&action=edit') ?>" method="POST">
          <div class="modal-body">
              <input type="hidden" name="id" id="edit_id">
              <div class="mb-3">
                  <label class="form-label">Nomor Bindex <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="nomor_bindex" id="edit_nomor" required>
              </div>
              <div class="mb-3">
                  <label class="form-label">Nama Bindex <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="nama_bindex" id="edit_nama" required>
              </div>
              <div class="mb-3">
                  <label class="form-label">Pindah Lokasi Rak <span class="text-danger">*</span></label>
                  <select class="form-select" name="id_rak" id="edit_rak" required>
                      <?php foreach($allRakOptions as $rak): ?>
                          <option value="<?= $rak['id'] ?>"><?= htmlspecialchars($rak['kode_rak'] . ' - ' . $rak['nama_rak']) ?></option>
                      <?php endforeach; ?>
                  </select>
              </div>
              <div class="mb-3">
                  <label class="form-label">Keterangan</label>
                  <textarea class="form-control" name="keterangan" id="edit_ket" rows="2"></textarea>
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
        $('#tableBindex').DataTable();

        // Pass Data to Edit Modal
        $('.btn-edit').on('click', function() {
            $('#edit_id').val($(this).data('id'));
            $('#edit_nomor').val($(this).data('nomor'));
            $('#edit_nama').val($(this).data('nama'));
            $('#edit_rak').val($(this).data('rak'));
            $('#edit_ket').val($(this).data('ket'));
        });

        // SweetAlert Delete Confirmation
        $('.btn-delete').on('click', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            Swal.fire({
                title: 'Hapus Data Bindex?',
                text: 'Bindex yang dihapus tidak bisa dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '" . base_url('index.php?page=bindex_action&action=delete&id=') . "' + id;
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
