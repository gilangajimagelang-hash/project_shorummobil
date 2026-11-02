<?php
session_start();
include 'koneksi.php';


if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$total_pesan_baru = $koneksi->query("
    SELECT COUNT(*) AS total 
    FROM chat_booking
    WHERE pengirim = 'user' AND status = 'baru'
")->fetch_assoc()['total'] ?? 0;


$total_booking_baru = $koneksi->query("
    SELECT COUNT(*) AS total 
    FROM pemesanan 
    WHERE status_admin = 'menunggu'
")->fetch_assoc()['total'] ?? 0;


$total_notif = $total_pesan_baru + $total_booking_baru;


$total_tersedia = $koneksi->query("SELECT COUNT(*) AS total FROM mobil WHERE status='tersedia'")->fetch_assoc()['total'] ?? 0;
$total_terjual  = $koneksi->query("SELECT COUNT(*) AS total FROM mobil WHERE status='terjual'")->fetch_assoc()['total'] ?? 0;
$total_users    = $koneksi->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'] ?? 0;
$total_laporan  = $total_terjual;
$pendapatan_total = $koneksi->query("SELECT SUM(harga) AS total FROM mobil WHERE status='terjual'")->fetch_assoc()['total'] ?? 0;
$rata_rata_harga = $koneksi->query("SELECT AVG(harga) AS avg FROM mobil WHERE status='terjual'")->fetch_assoc()['avg'] ?? 0;


$labels = ["Tersedia", "Terjual"];
$values = [$total_tersedia, $total_terjual];


$data_merk = $koneksi->query("SELECT merk, COUNT(*) AS total FROM mobil WHERE status='terjual' GROUP BY merk");
$merk_label = [];
$merk_value = [];
foreach ($data_merk as $row) {
    $merk_label[] = $row['merk'];
    $merk_value[] = $row['total'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard | Showroom Mobil</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>

body{margin:0;font-family:'Segoe UI',sans-serif;background:#f4f6f8;color:#333; min-height: 100vh;}


        body::before {
            content: "";
            position: fixed;
            top: 50%;
            left: 55%; 
            transform: translate(-50%, -50%);
            width: 600px; 
            height: 600px;
            z-index: 0;
            background: url("logoshowrum.png") center/contain no-repeat;
            opacity: 0.08; 
            filter: grayscale(100%) brightness(0.8);
            pointer-events: none;
        }

        body > * { position: relative; z-index: 1; }

.sidebar{
    position:fixed;
    top:0; left:0;
    width:260px;
    height:100vh;
    background:#1f2a36;
    color:#cfd8dc;
    display:flex;
    flex-direction:column;
    z-index: 100;
}

.sidebar-header{
    padding:20px;
    text-align:center;
    border-bottom:1px solid rgba(255,255,255,.1);
}
.sidebar-header img{
    width:130px;
    border-radius:8px;
}

.sidebar-menu{
    flex:1;
    overflow-y:auto;
    padding:15px 0;
}

.sidebar-menu a{
    display:flex;
    align-items:center;
    gap:12px;
    padding:14px 22px;
    color:#cfd8dc;
    text-decoration:none;
    font-size:15px;
    transition:.3s;
}

.sidebar-menu a:hover,
.sidebar-menu a.active{
    background:#2d3b48;
    color:#fff;
    border-left:4px solid #4fc3f7;
}

.sidebar-footer{
    padding:15px;
    border-top:1px solid rgba(255,255,255,.1);
}

.sidebar-footer a{
    display:flex;
    justify-content:center;
    align-items:center;
    gap:8px;
    background:#c0392b;
    color:#fff;
    padding:12px;
    border-radius:10px;
    text-decoration:none;
}


.header{
    position:fixed;
    top:0; left:260px; right:0;
    height:70px;
    background:#fff;
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:0 25px;
    box-shadow:0 2px 10px rgba(0,0,0,.08);
    z-index:10;
}

.header h2{margin:0;font-size:20px;}

.header-right{
    display:flex;
    align-items:center;
    gap:14px;
}

.icon-pesan{
    position:relative;
    width:42px;height:42px;
    background:#0d6efd;
    color:#fff;
    border-radius:50%;
    display:flex;
    justify-content:center;
    align-items:center;
    text-decoration:none;
}

.icon-pesan .badge{
    position:absolute;
    top:-4px; right:-4px;
    background:#ffc107;
    color:#000;
    font-size:10px;
    padding:2px 6px;
    border-radius:50%;
    font-weight: bold;
}

.user-admin{
    display:flex;
    align-items:center;
    gap:10px;
    background:#33475b;
    padding:6px 12px;
    border-radius:25px;
    color:white;
    font-weight:500;
}
.user-admin .avatar{
    width:36px;height:36px;
    background:#cfd8dc;
    border-radius:50%;
    display:flex;
    justify-content:center;
    align-items:center;
    font-weight:bold;
    color:#1f2a36;
    font-size:16px;
}

.main{
    margin-left:260px;
    padding:100px 30px 30px;
}

.stats{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(230px,1fr));
    gap:20px;
}

.card{
    background:#fff;
    padding:22px;
    border-radius:14px;
    box-shadow:0 4px 14px rgba(0,0,0,.08);
    position:relative;
    transition: .3s;
}

.card:hover{ transform: translateY(-4px); box-shadow: 0 12px 20px rgba(0,0,0,0.12); }

.card i{
    position:absolute;
    right:18px; top:18px;
    font-size:40px;
    opacity:.12;
}

.card h2{margin:0;font-size:26px; font-weight: bold; color: #2c3e50;}
.card p{margin:6px 0;color:#7f8c8d; font-size: 14px;}
.card a{margin-top:12px;display:inline-block;text-decoration:none;font-weight:bold;color:#3498db;font-size:13px;}

.chart-container{
    margin-top:40px;
    background:#fff;
    padding:25px;
    border-radius:14px;
    box-shadow:0 4px 14px rgba(0,0,0,.08);
}
.chart-container h3{margin-bottom:20px;color:#34495e;}

</style>
</head>
<body>


<div class="sidebar">
    <div class="sidebar-header">
        <img src="logoshowrum.png" alt="Logo">
    </div>

    <div class="sidebar-menu">
        <a class="active" href="#"><i class="fa fa-home"></i> Dashboard</a>
        <a href="mobil_list.php"><i class="fa fa-car"></i> Data Mobil</a>
        <a href="tambah_mobil.php"><i class="fa fa-plus-circle"></i> Tambah Mobil</a>
        <a href="admin_login.php"><i class="fa fa-users"></i> Admin</a>
        <a href="banner_index.php"><i class="fa fa-users"></i> Edit Banner</a>
        <a href="jadwal_kunjungan.php"><i class="fa fa-calendar"></i> Jadwal</a>
        <a href="laporan.php"><i class="fa fa-file-alt"></i> Laporan</a>
        <a href="tambah_akun_admin.php"><i class="fa fa-user-shield"></i> Tambah Admin</a>
    </div>

    <div class="sidebar-footer">
        <a href="logout.php"><i class="fa fa-sign-out"></i> Logout</a>
    </div>
</div>


<div class="header">
    <h2>Dashboard Showroom Mobil</h2>
    <div class="header-right">

        <a href="admin_pesan_list.php" class="icon-pesan" title="Pesan Masuk">
            <i class="fa fa-envelope"></i>
            <?php if($total_pesan_baru > 0): ?>
                <span class="badge"><?= $total_pesan_baru ?></span>
            <?php endif; ?>
        </a>

        <a href="pemesanan_list.php" class="icon-pesan" title="Booking / DP">
            <i class="fa fa-calendar-check"></i>
            <?php if($total_booking_baru > 0): ?>
                <span class="badge"><?= $total_booking_baru ?></span>
            <?php endif; ?>
        </a>

        <div class="user-admin">
            <div class="avatar"><?= strtoupper(substr($_SESSION['username'],0,1)) ?></div>
            <div class="user-info">
                <span class="username"><?= $_SESSION['username']; ?></span>
                <small class="role">Administrator</small>
            </div>
        </div>
    </div>
</div>

<div class="main">
    <div class="stats">
        <div class="card">
            <h2><?= $total_tersedia ?></h2>
            <p>Mobil Tersedia</p>
            <i class="fa fa-car"></i>
            <a href="from_mobil_tersedia.php?status=tersedia">Lihat Detail</a>
        </div>
        <div class="card">
            <h2><?= $total_terjual ?></h2>
            <p>Mobil Terjual</p>
            <i class="fa fa-check-circle"></i>
            <a href="from_mobil_terjual.php?status=terjual">Lihat Detail</a>
        </div>
        <div class="card">
            <h2><?= $total_users ?></h2>
            <p>Total Admin</p>
            <i class="fa fa-users"></i>
            <a href="admin_login.php">Kelola</a>
        </div>
        <div class="card">
            <h2><?= $total_laporan ?></h2>
            <p>Total Laporan</p>
            <i class="fa fa-file-alt"></i>
            <a href="laporan.php">Lihat Laporan</a>
        </div>
        <div class="card">
            <h2>Rp <?= number_format($pendapatan_total, 0, ',', '.') ?></h2>
            <p>Total Pendapatan</p>
            <i class="fa fa-money-bill-wave"></i>
            <a href="pendapatan_admin.php">Detail</a>
        </div>
        <div class="card">
            <h2>Rp <?= number_format($rata_rata_harga, 0, ',', '.') ?></h2>
            <p>Rata-rata Harga Mobil Terjual</p>
            <i class="fa fa-chart-line"></i>
            <a href="laporan.php">Detail</a>
        </div>
    </div>

    <div class="chart-container">
        <h3><i class="fa fa-chart-bar"></i> Grafik Mobil Tersedia / Terjual</h3>
        <canvas id="chartMobil"></canvas>
    </div>

    <div class="chart-container">
        <h3><i class="fa fa-chart-pie"></i> Grafik Mobil Terjual Per Merk</h3>
        <canvas id="chartMerk"></canvas>
    </div>
</div>

<script>
new Chart(document.getElementById('chartMobil'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
            label: "Jumlah Mobil",
            data: <?= json_encode($values) ?>,
            backgroundColor: ["#3498db", "#f1c40f"]
        }]
    },
    options: { responsive: true }
});

new Chart(document.getElementById('chartMerk'), {
    type: 'pie',
    data: {
        labels: <?= json_encode($merk_label) ?>,
        datasets: [{
            data: <?= json_encode($merk_value) ?>,
            backgroundColor: ["#3498db","#9b59b6","#1abc9c","#f39c12","#e74c3c","#2ecc71","#e67e22"]
        }]
    },
    options: { responsive: true }
});
</script>
</body>
</html>