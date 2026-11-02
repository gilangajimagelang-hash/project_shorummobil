<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}


$q_laporan = $koneksi->query("SELECT COUNT(*) AS total FROM mobil WHERE status='terjual'");
$total_laporan = ($q_laporan) ? $q_laporan->fetch_assoc()['total'] : 0;


$q_pendapatan = $koneksi->query("SELECT SUM(harga) AS pendapatan FROM mobil WHERE status='terjual'");
$total_pendapatan = ($q_pendapatan) ? ($q_pendapatan->fetch_assoc()['pendapatan'] ?? 0) : 0;


$q_riwayat = $koneksi->query("
    SELECT nama_mobil, merk, harga, created_at 
    FROM mobil 
    WHERE status='terjual'
    ORDER BY created_at DESC
");

$riwayat = [];
if ($q_riwayat) {
    $riwayat = $q_riwayat->fetch_all(MYSQLI_ASSOC);
}


$tahun_ini = date('Y');
$bulan_nama = [
    1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
    7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
];

$stmt_bulan = $koneksi->prepare("
    SELECT MONTH(created_at) AS bulan, COUNT(*) AS jumlah
    FROM mobil
    WHERE status='terjual' AND YEAR(created_at)=?
    GROUP BY MONTH(created_at)
    ORDER BY bulan
");
$stmt_bulan->bind_param("i", $tahun_ini);
$stmt_bulan->execute();
$result_bulan = $stmt_bulan->get_result();

$rekap_bulan = array_fill(1, 12, 0);
while($row = $result_bulan->fetch_assoc()){
    $rekap_bulan[(int)$row['bulan']] = (int)$row['jumlah'];
}


$result_tahun = $koneksi->query("
    SELECT YEAR(created_at) AS tahun, COUNT(*) AS jumlah
    FROM mobil
    WHERE status='terjual'
    GROUP BY YEAR(created_at)
    ORDER BY tahun
");

$rekap_tahun = [];
while($row = $result_tahun->fetch_assoc()){
    $rekap_tahun[$row['tahun']] = $row['jumlah'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Laporan Penjualan Mobil</title>
<style>
body { font-family: Arial, sans-serif; margin: 20px; color: #000; }
h2, h3, h4 { margin: 5px 0; }
table { width: 100%; border-collapse: collapse; margin-top: 10px; }
table, th, td { border: 1px solid #000; }
th, td { padding: 8px; text-align: left; }
thead { background-color: #f0f0f0; }
button, a.btn { margin: 5px 5px 15px 0; padding: 8px 12px; font-size: 14px; text-decoration: none; border: 1px solid #000; background-color: #eee; color: #000; cursor: pointer; }
a.btn:hover, button:hover { background-color: #ccc; }
.stat-box { display: inline-block; width: 30%; padding: 10px; margin-bottom: 10px; border: 1px solid #000; text-align: center; }
.stat-box h3 { margin: 5px 0; }
@media print {
    body { margin: 0; }
    button, a.btn { display: none; }
}
</style>
</head>
<body>

<h2>Laporan Penjualan Mobil</h2>
<p>Tahun: <?= $tahun_ini ?></p>


<button onclick="window.print()">Cetak Laporan</button>
<a href="dashboard.php" class="btn">Kembali ke Dashboard</a>

<div>
    <div class="stat-box">
        <h3><?= $total_laporan ?></h3>
        <div>Total Mobil Terjual</div>
    </div>
    <div class="stat-box">
        <h3>Rp <?= number_format($total_pendapatan,0,',','.') ?></h3>
        <div>Total Pendapatan</div>
    </div>
</div>


<h4>Rekap Bulanan</h4>
<table>
    <thead>
        <tr>
            <th>Bulan</th>
            <th>Jumlah Terjual</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($bulan_nama as $b => $nama): ?>
        <tr>
            <td><?= $nama ?></td>
            <td><?= $rekap_bulan[$b] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>


<h4>Rekap Tahunan</h4>
<table>
    <thead>
        <tr>
            <th>Tahun</th>
            <th>Jumlah Terjual</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($rekap_tahun as $t => $jml): ?>
        <tr>
            <td><?= $t ?></td>
            <td><?= $jml ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>


<h4>Riwayat Mobil Terjual</h4>
<table>
    <thead>
        <tr>
            <th>Nama Mobil</th>
            <th>Merk</th>
            <th>Harga</th>
            <th>Tanggal Input</th>
        </tr>
    </thead>
    <tbody>
        <?php if(empty($riwayat)): ?>
        <tr><td colspan="4" style="text-align:center">Belum ada mobil terjual</td></tr>
        <?php else: ?>
        <?php foreach($riwayat as $r): ?>
        <tr>
            <td><?= $r['nama_mobil'] ?></td>
            <td><?= $r['merk'] ?></td>
            <td>Rp <?= number_format($r['harga'],0,',','.') ?></td>
            <td><?= $r['created_at'] ?></td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
