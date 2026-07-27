<?php include 'views/layout/header.php'; ?>
<?php include 'views/layout/sidebar.php'; ?>
<?php include 'views/layout/navbar.php'; ?>

<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= base_url('index.php?page=dashboard') ?>" class="text-decoration-none">Home</a></li>
    <li class="breadcrumb-item"><a href="<?= base_url('index.php?page=arsip_list') ?>" class="text-decoration-none">Arsip SP2D</a></li>
    <li class="breadcrumb-item active" aria-current="page">Input Arsip</li>
  </ol>
</nav>

<h4 class="font-weight-bold mb-4">Input Data Arsip SP2D</h4>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="<?= base_url('index.php?page=arsip_action&action=' . ($is_edit ? 'edit' : 'add')) ?>" method="POST" enctype="multipart/form-data">
            
            <?php if($is_edit): ?>
                <input type="hidden" name="id" value="<?= $arsip_edit['id'] ?>">
            <?php endif; ?>

            <h6 class="text-primary mb-3"><i class="fa-solid fa-file-signature me-2"></i>Informasi Dokumen</h6>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">SKPD / Instansi Asal <span class="text-danger">*</span></label>
                    <select class="form-select" name="id_instansi" required>
                        <option value="">-- Pilih Instansi --</option>
                        <?php foreach($instansi as $i): ?>
                            <option value="<?= $i['id'] ?>" <?= ($is_edit && $arsip_edit['id_instansi'] == $i['id']) ? 'selected' : '' ?>><?= htmlspecialchars($i['nama_instansi']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Masukkan ke Bindex <span class="text-danger">*</span></label>
                    <select class="form-select" name="id_bindex" required>
                        <option value="">-- Pilih Bindex & Rak --</option>
                        <?php foreach($bindex as $b): ?>
                            <option value="<?= $b['id'] ?>" <?= ($is_edit && $arsip_edit['id_bindex'] == $b['id']) ? 'selected' : '' ?>><?= htmlspecialchars($b['nomor_bindex'] . ' (Di ' . $b['nama_rak'] . ')') ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if(empty($bindex)): ?>
                        <small class="text-danger mt-1 d-block"><i class="fa-solid fa-triangle-exclamation"></i> Tidak ada Bindex tersedia. Silakan buat Bindex terlebih dahulu.</small>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Nomor SPM <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="nomor_spm" placeholder="Masukkan Nomor SPM" value="<?= $is_edit ? htmlspecialchars($arsip_edit['nomor_spm']) : '' ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Tanggal SPM <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="tanggal_spm" value="<?= $is_edit ? $arsip_edit['tanggal_spm'] : '' ?>" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Nomor SP2D <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="nomor_sp2d" placeholder="Masukkan Nomor SP2D" value="<?= $is_edit ? htmlspecialchars($arsip_edit['nomor_sp2d']) : '' ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Tanggal Dokumen SP2D <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="tanggal_sp2d" value="<?= $is_edit ? $arsip_edit['tanggal_sp2d'] : '' ?>" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label fw-semibold">Keperluan Untuk (Pilih Kegiatan) <span class="text-danger">*</span></label>
                    <select class="form-select" id="kegiatan_pilih" required onchange="updateKegiatan()">
                        <option value="">-- Pilih Kegiatan dari Master Data --</option>
                        <?php foreach($kegiatan_list as $k): 
                            $val = $k['jenis_kegiatan'] . '|' . $k['nama_kegiatan'];
                            $selected = ($is_edit && $arsip_edit['jenis_kegiatan'] == $k['jenis_kegiatan'] && $arsip_edit['nama_kegiatan'] == $k['nama_kegiatan']) ? 'selected' : '';
                        ?>
                            <option value="<?= htmlspecialchars($val) ?>" <?= $selected ?>>
                                <?= htmlspecialchars($k['nama_kegiatan']) ?> — (<?= htmlspecialchars($k['jenis_kegiatan']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="hidden" name="jenis_kegiatan" id="jenis_kegiatan" value="<?= $is_edit ? htmlspecialchars($arsip_edit['jenis_kegiatan']) : '' ?>">
                    <input type="hidden" name="nama_kegiatan" id="nama_kegiatan" value="<?= $is_edit ? htmlspecialchars($arsip_edit['nama_kegiatan']) : '' ?>">
                    <div class="form-text text-muted"><small><i class="fa-solid fa-info-circle"></i> Jika kegiatan tidak ada, tambahkan dulu di menu Master Kegiatan.</small></div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Jumlah Halaman <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="jumlah_halaman" min="1" value="<?= $is_edit ? $arsip_edit['jumlah_halaman'] : '1' ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Jumlah SP2D <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="jumlah_sp2d" min="1" value="<?= $is_edit ? $arsip_edit['jumlah_sp2d'] : '1' ?>" required>
                </div>
            </div>

            <hr class="mt-4 mb-4">
            <h6 class="text-primary mb-3"><i class="fa-solid fa-clipboard-check me-2"></i>Penerimaan & Status</h6>
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Tanggal Diterima Arsip <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="tanggal_arsip" value="<?= $is_edit ? $arsip_edit['tanggal_arsip'] : date('Y-m-d') ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Status Awal Workflow <span class="text-danger">*</span></label>
                    <select class="form-select" name="status_arsip" required>
                        <?php
                        $statuses = ['Diproses', 'Dikembalikan'];
                        foreach($statuses as $s): ?>
                            <option value="<?= $s ?>" <?= ($is_edit && $arsip_edit['status_arsip'] == $s) ? 'selected' : '' ?>><?= $s ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold text-primary">Upload File Scan SP2D (PDF) <?= $is_edit ? '' : '<span class="text-danger">*</span>' ?></label>
                <input type="file" class="form-control" name="file_pdf" accept=".pdf" <?= $is_edit ? '' : 'required' ?>>
                <?php if($is_edit && $arsip_edit['file_pdf']): ?>
                    <small class="text-success mt-1 d-block"><i class="fa-solid fa-check-circle"></i> File saat ini: <a href="uploads/<?= $arsip_edit['file_pdf'] ?>" target="_blank">Lihat PDF</a> (Abaikan jika tidak ingin mengubah file)</small>
                <?php else: ?>
                    <div class="form-text text-muted"><i class="fa-solid fa-circle-info"></i> Format PDF | Maksimal ukuran file: 10 MB. <?= $is_edit ? '(Kosongkan jika tidak ada update PDF)' : '' ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Catatan / Keterangan</label>
                <textarea class="form-control" name="catatan" rows="3" placeholder="Tambahkan catatan khusus jika ada..."><?= $is_edit ? htmlspecialchars($arsip_edit['catatan']) : '' ?></textarea>
            </div>

            <script>
                function updateKegiatan() {
                    var val = document.getElementById('kegiatan_pilih').value;
                    if (val) {
                        var parts = val.split('|');
                        document.getElementById('jenis_kegiatan').value = parts[0];
                        document.getElementById('nama_kegiatan').value = parts[1];
                    } else {
                        document.getElementById('jenis_kegiatan').value = '';
                        document.getElementById('nama_kegiatan').value = '';
                    }
                }
            </script>

            <div class="d-flex justify-content-end gap-2">
                <a href="<?= base_url('index.php?page=arsip_list') ?>" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary" <?= empty($bindex) ? 'disabled' : '' ?>><i class="fa-solid fa-save me-1"></i> <?= $is_edit ? 'Update Arsip' : 'Simpan Arsip' ?></button>
            </div>
        </form>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>
