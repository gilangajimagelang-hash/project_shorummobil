<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}


$q_pendapatan = $koneksi->query("SELECT SUM(harga) AS total FROM mobil WHERE status='terjual'");
$total_pendapatan = ($q_pendapatan) ? ($q_pendapatan->fetch_assoc()['total'] ?? 0) : [];


$q_history = $koneksi->query("
    SELECT nama_mobil, merk, harga, created_at
    FROM mobil
    WHERE status='terjual'
    ORDER BY created_at DESC
");
$history = $q_history ? $q_history->fetch_all(MYSQLI_ASSOC) : [];


$tahun_ini = date('Y');


$bulan_nama = [
    1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
    7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
];


$stmt_bulan = $koneksi->prepare("
    SELECT MONTH(created_at) AS bulan, SUM(harga) AS total
    FROM mobil
    WHERE status='terjual' AND YEAR(created_at)=?
    GROUP BY MONTH(created_at)
    ORDER BY bulan
");
$stmt_bulan->bind_param("i", $tahun_ini);
$stmt_bulan->execute();
$res_bulan = $stmt_bulan->get_result();

$pendapatan_bulan = array_fill(1, 12, 0);
while($row = $res_bulan->fetch_assoc()){
    $pendapatan_bulan[(int)$row['bulan']] = (int)$row['total'];
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Pendapatan Admin</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
.box-card{
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 4px 25px rgba(0,0,0,0.12);
}
.title{
    font-weight: 600;
    font-size: 22px;
}
.money{
    font-size: 32px;
    font-weight: bold;
    color: #27ae60;
}
</style>

</head>
<body class="bg-light">

<div class="container my-4">

    <h2 class="mb-3">💰 Pendapatan Penjualan Mobil (Admin)</h2>
    <a href="dashboard.php" class="btn btn-secondary mb-4">← Kembali ke Dashboard</a>

  
    <div class="box-card mb-4">
        <div class="title mb-2">Total Pendapatan Keseluruhan</div>
        <div class="money">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></div>
    </div>

   
    <div class="box-card mb-4">
        <h4 class="mb-3">Grafik Pendapatan Bulanan - <?= $tahun_ini ?></h4>
        <canvas id="chartPendapatan" height="100"></canvas>
    </div>

  
    <div class="box-card mb-4">
        <h4>Riwayat Pendapatan / Mobil Terjual</h4>

        <table class="table table-bordered mt-3">
            <thead class="table-dark">
                <tr>
                    <th>Nama Mobil</th>
                    <th>Merk</th>
                    <th>Harga Terjual</th>
                    <th>Tanggal Input</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($history)): ?>
                    <tr>
                        <td colspan="4" class="text-center">Belum ada mobil terjual.</td>
                    </tr>
                <?php endif; ?>

                <?php foreach($history as $h): ?>
                <tr>
                    <td><?= $h['nama_mobil'] ?></td>
                    <td><?= $h['merk'] ?></td>
                    <td>Rp <?= number_format($h['harga'], 0, ',', '.') ?></td>
                    <td><?= $h['created_at'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>


<script>
const bulanLabels = <?= json_encode(array_values($bulan_nama)); ?>;
const bulanData = <?= json_encode(array_values($pendapatan_bulan)); ?>;

new Chart(document.getElementById('chartPendapatan'), {
    type: 'line',
    data: {
        labels: bulanLabels,
        datasets: [{
            label: "Pendapatan Bulanan",
            data: bulanData,
            borderColor: "#27ae60",
            backgroundColor: "rgba(46, 204, 113, .3)",
            borderWidth: 3,
            tension: .25,
            fill: true
        }]
    }
});
</script>

</body>
</html>
