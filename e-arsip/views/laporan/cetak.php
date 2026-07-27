<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Laporan Arsip SP2D' ?></title>
    <!-- Gunakan Bootstrap untuk mempermudah layout tabel -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            color: #000;
            background: #fff;
        }
        .kop-surat {
            border-bottom: 3px solid #000;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }
        .tabel-laporan th, .tabel-laporan td {
            border: 1px solid #000;
            padding: 5px;
            vertical-align: top;
        }
        .tabel-laporan th {
            background-color: #f2f2f2 !important;
            -webkit-print-color-adjust: exact;
            text-align: center;
        }
        .ttd-box {
            width: 300px;
            float: right;
            text-align: center;
            margin-top: 50px;
        }
        /* Mode Cetak (Menghilangkan tombol print saat dicetak) */
        @media print {
            @page { margin: 1.5cm; }
            .no-print { display: none !important; }
            body { -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body>

<div class="container-fluid py-4">
    
    <!-- Tombol Aksi (Hanya tampil di layar) -->
    <div class="no-print text-center mb-4">
        <button onclick="window.print()" class="btn btn-primary me-2"><i class="fa-solid fa-print"></i> Cetak Sekarang</button>
        <button onclick="window.close()" class="btn btn-secondary">Tutup Tab</button>
    </div>

    <!-- Kop Laporan -->
    <div class="kop-surat text-center">
        <h4 class="mb-1 fw-bold">PEMERINTAH KABUPATEN / KOTA ...</h4>
        <h5 class="mb-1 fw-bold">BADAN PENGELOLAAN KEUANGAN DAN ASET DAERAH</h5>
        <p class="mb-0">Jl. Contoh Alamat No. 123, Telp. (021) 1234567, Fax (021) 7654321</p>
        <p class="mb-0">Email: bpkad@contoh.go.id, Website: www.contoh.go.id</p>
    </div>

    <div class="text-center mb-4">
        <h5 class="fw-bold text-decoration-underline mb-2">LAPORAN ARSIP SP2D</h5>
        <p class="mb-0">Periode: <?= date('d/m/Y', strtotime($tgl_awal)) ?> s.d <?= date('d/m/Y', strtotime($tgl_akhir)) ?></p>
        <?php if(!empty($id_instansi)): ?>
            <?php 
                $stmtInst = $pdo->prepare("SELECT nama_instansi FROM instansi WHERE id=?");
                $stmtInst->execute([$id_instansi]);
                $ni = $stmtInst->fetchColumn();
            ?>
            <p class="mb-0">Instansi: <?= htmlspecialchars($ni) ?></p>
        <?php endif; ?>
        <?php if(!empty($status)): ?>
            <p class="mb-0">Status: <?= htmlspecialchars($status) ?></p>
        <?php endif; ?>
    </div>

    <!-- Tabel Data -->
    <table class="table tabel-laporan w-100">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">No. SP2D</th>
                <th width="10%">Tanggal</th>
                <th width="20%">Instansi</th>
                <th width="20%">Jenis & Nama Kegiatan</th>
                <th width="15%">Lokasi Fisik</th>
                <th width="15%">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if(count($dataLaporan) > 0): ?>
                <?php $no = 1; foreach($dataLaporan as $row): ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['nomor_sp2d']) ?></td>
                    <td class="text-center"><?= date('d/m/Y', strtotime($row['tanggal_sp2d'])) ?></td>
                    <td><?= htmlspecialchars($row['nama_instansi']) ?></td>
                    <td>
                        <strong><?= htmlspecialchars($row['jenis_kegiatan']) ?></strong><br>
                        <?= htmlspecialchars($row['nama_kegiatan']) ?>
                    </td>
                    <td>
                        Bindex: <?= htmlspecialchars($row['nomor_bindex']) ?><br>
                        Rak: <?= htmlspecialchars($row['nama_rak']) ?>
                    </td>
                    <td class="text-center"><?= htmlspecialchars($row['status_arsip']) ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center py-4">Tidak ada data arsip SP2D pada kriteria/periode ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Tanda Tangan -->
    <div class="ttd-box">
        <p class="mb-5">Tempat, <?= date('d F Y') ?><br>Kepala Sub Bagian Kearsipan</p>
        <p class="fw-bold text-decoration-underline mb-0">NAMA PEJABAT</p>
        <p>NIP. 19800101 200501 1 001</p>
    </div>

</div>

<!-- Auto Print Script -->
<script>
    window.onload = function() {
        // Otomatis membuka dialog print saat tab selesai dimuat
        window.print();
    }
</script>

</body>
</html>
