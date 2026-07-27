<?php include 'views/layout/header.php'; ?>
<?php include 'views/layout/sidebar.php'; ?>
<?php include 'views/layout/navbar.php'; ?>

<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= base_url('index.php?page=dashboard') ?>" class="text-decoration-none">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Cetak Laporan</li>
  </ol>
</nav>

<h4 class="font-weight-bold mb-4">Cetak Laporan Arsip SP2D</h4>

<div class="row">
    <div class="col-lg-8 col-md-10">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="<?= base_url('index.php') ?>" method="GET" target="_blank">
                    <input type="hidden" name="page" id="page_input" value="laporan_cetak">
                    
                    <div class="alert alert-info border-0 bg-info bg-opacity-10 d-flex align-items-center mb-4">
                        <i class="fa-solid fa-circle-info fa-2x text-info me-3"></i>
                        <div>
                            Silakan atur filter di bawah ini untuk menyesuaikan data arsip SP2D yang ingin Anda cetak. Halaman cetak akan terbuka di tab baru.
                        </div>
                    </div>
                    
                    <h6 class="text-primary mb-2"><i class="fa-solid fa-calendar-days me-2"></i>Periode SP2D (Wajib)</h6>
                    
                    <div class="mb-3">
                        <span class="text-muted small fw-semibold me-2">Pilih Cepat:</span>
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill me-1" onclick="setPeriode('mingguan')">Minggu Ini</button>
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill me-1" onclick="setPeriode('bulanan')">Bulan Ini</button>
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" onclick="setPeriode('tahunan')">Tahun Ini</button>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Dari Tanggal <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="tgl_awal" id="tgl_awal" required value="<?= date('Y-m-01') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Sampai Tanggal <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="tgl_akhir" id="tgl_akhir" required value="<?= date('Y-m-t') ?>">
                        </div>
                    </div>

                    <h6 class="text-primary mb-3"><i class="fa-solid fa-filter me-2"></i>Filter Tambahan (Opsional)</h6>
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Instansi / SKPD</label>
                            <select class="form-select" name="id_instansi">
                                <option value="">-- Semua Instansi --</option>
                                <?php foreach($instansiList as $i): ?>
                                    <option value="<?= $i['id'] ?>"><?= htmlspecialchars($i['nama_instansi']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Status Berkas</label>
                            <select class="form-select" name="status">
                                <option value="">-- Semua Status --</option>
                                <option value="Diproses">Diproses</option>
                                <option value="Dikembalikan">Dikembalikan</option>
                            </select>
                        </div>
                    </div>

                    <hr>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-success px-4 py-2" onclick="document.getElementById('page_input').value='laporan_excel'">
                            <i class="fa-solid fa-file-excel me-2"></i> Export Excel
                        </button>
                        <button type="submit" class="btn btn-primary px-4 py-2" onclick="document.getElementById('page_input').value='laporan_cetak'">
                            <i class="fa-solid fa-print me-2"></i> Generate & Cetak
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php 
$custom_scripts = "
<script>
function setPeriode(type) {
    const tglAwal = document.getElementById('tgl_awal');
    const tglAkhir = document.getElementById('tgl_akhir');
    const now = new Date();
    let start, end;

    if (type === 'mingguan') {
        const day = now.getDay() || 7; 
        if(day !== 1) now.setHours(-24 * (day - 1));
        start = new Date(now);
        end = new Date(start);
        end.setDate(start.getDate() + 6);
    } else if (type === 'bulanan') {
        start = new Date(now.getFullYear(), now.getMonth(), 1);
        end = new Date(now.getFullYear(), now.getMonth() + 1, 0);
    } else if (type === 'tahunan') {
        start = new Date(now.getFullYear(), 0, 1);
        end = new Date(now.getFullYear(), 11, 31);
    }

    const formatDate = (date) => {
        let d = new Date(date), month = '' + (d.getMonth() + 1), day = '' + d.getDate(), year = d.getFullYear();
        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;
        return [year, month, day].join('-');
    };

    tglAwal.value = formatDate(start);
    tglAkhir.value = formatDate(end);
}
</script>
";
?>

<?php include 'views/layout/footer.php'; ?>
