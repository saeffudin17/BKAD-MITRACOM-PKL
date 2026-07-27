<?php
// Header untuk force download sebagai file Excel (.xls)
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Arsip_SP2D_" . date('Ymd_His') . ".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Export Excel</title>
</head>
<body>
    <table border="1">
        <thead>
            <tr>
                <th colspan="10" style="font-size: 16px; font-weight: bold; text-align: center;">LAPORAN ARSIP SP2D</th>
            </tr>
            <tr>
                <th colspan="10" style="text-align: center;">Periode: <?= date('d-m-Y', strtotime($tgl_awal)) ?> s/d <?= date('d-m-Y', strtotime($tgl_akhir)) ?></th>
            </tr>
            <tr>
                <th colspan="10"></th>
            </tr>
            <tr>
                <th style="background-color: #0d6efd; color: #ffffff; text-align: center;">No</th>
                <th style="background-color: #0d6efd; color: #ffffff; text-align: center;">Nomor SP2D</th>
                <th style="background-color: #0d6efd; color: #ffffff; text-align: center;">Tanggal SP2D</th>
                <th style="background-color: #0d6efd; color: #ffffff; text-align: center;">Tanggal Arsip</th>
                <th style="background-color: #0d6efd; color: #ffffff; text-align: center;">Instansi</th>
                <th style="background-color: #0d6efd; color: #ffffff; text-align: center;">Kegiatan</th>
                <th style="background-color: #0d6efd; color: #ffffff; text-align: center;">Lokasi Penyimpanan</th>
                <th style="background-color: #0d6efd; color: #ffffff; text-align: center;">Status</th>
                <th style="background-color: #0d6efd; color: #ffffff; text-align: center;">Petugas</th>
                <th style="background-color: #0d6efd; color: #ffffff; text-align: center;">Catatan</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            foreach($dataLaporan as $row): 
            ?>
            <tr>
                <td style="text-align: center;"><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['nomor_sp2d']) ?></td>
                <td style="text-align: center;"><?= date('d-m-Y', strtotime($row['tanggal_sp2d'])) ?></td>
                <td style="text-align: center;"><?= date('d-m-Y', strtotime($row['tanggal_arsip'])) ?></td>
                <td><?= htmlspecialchars($row['nama_instansi']) ?></td>
                <td>
                    <?= htmlspecialchars($row['jenis_kegiatan']) ?><br>
                    <small><?= htmlspecialchars($row['nama_kegiatan']) ?></small>
                </td>
                <td>
                    Rak: <?= htmlspecialchars($row['nama_rak']) ?><br>
                    Bindex: <?= htmlspecialchars($row['nomor_bindex']) ?>
                </td>
                <td style="text-align: center;"><?= htmlspecialchars($row['status_arsip']) ?></td>
                <td><?= htmlspecialchars($row['nama_petugas']) ?></td>
                <td><?= htmlspecialchars($row['catatan']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
