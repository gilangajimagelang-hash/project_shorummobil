<?php
session_start();
require 'koneksi.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) { header('Location: shorum.php'); exit; }

$stmt = $koneksi->prepare("
    SELECT id, nama_mobil, merk, tahun, harga, deskripsi, foto, status, bahan_bakar
    FROM mobil WHERE id = ? LIMIT 1
");
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$mobil = $res->fetch_assoc();
$stmt->close();

if (!$mobil) { header('Location: shorum.php'); exit; }

$fotos = [];
$fotoUtamaPath = __DIR__ . '/uploads/' . $mobil['foto'];
if (!empty($mobil['foto']) && file_exists($fotoUtamaPath)) {
    $fotos[] = 'uploads/' . $mobil['foto'];
}

$stmt2 = $koneksi->prepare("SELECT filename FROM foto_mobil WHERE mobil_id = ? ORDER BY id ASC");
$stmt2->bind_param("i", $id);
$stmt2->execute();
$res2 = $stmt2->get_result();
while ($row = $res2->fetch_assoc()) {
    $file = __DIR__ . '/uploads/' . $row['filename'];
    if (file_exists($file)) {
        $fotos[] = 'uploads/' . $row['filename'];
    }
}
$stmt2->close();

if (empty($fotos)) {
    $fotos[] = 'https://via.placeholder.com/900x400?text=No+Image';
}

$from = $_GET['from'] ?? 'user';
$backUrl = ($from === 'admin') ? "mobil_list.php" : "shorum.php";

$hubungiUrl = isset($_SESSION['user_id'])
    ? "hubungi_sales_pesan_kirim.php?mobil_id=" . $mobil['id']
    : "login_user_from_hubungi_sales.php?mobil_id=" . $mobil['id'];

$bookingUrl = isset($_SESSION['user_id'])
    ? "from_user_boking.php?mobil_id=" . $mobil['id']
    : "login_user_from_hubungi_sales.php?mobil_id=" . $mobil['id'];
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= htmlspecialchars($mobil['nama_mobil']) ?> - Detail Mobil | V8</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">

<style>
:root {
    --primary-gold: #c5a059;
    --dark-bg: #0f172a;
    --card-bg: #1e293b;
    --accent-blue: #3b82f6;
}

body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background-color: var(--dark-bg);
    color: #f8fafc;
    margin: 0;
}

.container {
    max-width: 1000px;
    margin: 40px auto;
    padding: 0 20px;
}

.back-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #94a3b8;
    text-decoration: none;
    font-weight: 600;
    margin-bottom: 25px;
    transition: 0.3s;
}

.back-link:hover { color: var(--primary-gold); }

.detail-card {
    background: var(--card-bg);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 24px;
    padding: 30px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
}


.carousel-inner {
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}
.carousel-inner img {
    width: 100%;
    height: 500px;
    object-fit: cover;
}

.header-section {
    margin: 30px 0;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

h1 {
    font-size: 36px;
    font-weight: 800;
    color: #fff;
    margin: 0;
}

.price-tag {
    font-size: 32px;
    font-weight: 800;
    color: var(--primary-gold);
}


.badge-status {
    display: inline-block;
    padding: 6px 16px;
    border-radius: 10px;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 1px;
    margin-bottom: 15px;
}
.status-tersedia { background: rgba(34, 197, 94, 0.2); color: #4ade80; border: 1px solid #4ade8040; }
.status-terjual { background: rgba(239, 68, 68, 0.2); color: #f87171; border: 1px solid #f8717140; }
.status-terboking { background: rgba(234, 179, 8, 0.2); color: #facc15; border: 1px solid #facc1540; }


.spec-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
    margin-bottom: 35px;
}
.spec-item {
    background: rgba(15, 23, 42, 0.5);
    padding: 20px;
    border-radius: 16px;
    border: 1px solid rgba(255,255,255,0.05);
}
.spec-label {
    color: #64748b;
    font-size: 13px;
    text-transform: uppercase;
    display: block;
    margin-bottom: 5px;
}
.spec-value {
    color: #fff;
    font-weight: 700;
    font-size: 16px;
}


.btn-action-group {
    display: flex;
    gap: 15px;
    margin-bottom: 40px;
}
.btn-premium {
    flex: 1;
    padding: 18px;
    border-radius: 16px;
    font-weight: 700;
    text-transform: uppercase;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}
.btn-sales {
    background: linear-gradient(135deg, #c5a059 0%, #947a44 100%);
    color: #000;
}
.btn-book {
    background: transparent;
    border: 2px solid var(--accent-blue);
    color: var(--accent-blue);
}
.btn-premium:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.3);
}
.btn-book:hover {
    background: var(--accent-blue);
    color: #fff;
}

.description-box {
    background: rgba(255,255,255,0.02);
    padding: 30px;
    border-radius: 20px;
    line-height: 1.8;
}
.description-box h3 {
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 15px;
    color: var(--primary-gold);
}

@media (max-width: 768px) {
    .header-section { flex-direction: column; }
    .spec-grid { grid-template-columns: 1fr; }
    .btn-action-group { flex-direction: column; }
    .carousel-inner img { height: 300px; }
}
</style>
</head>

<body>

<div class="container">

    <a class="back-link" href="<?= $backUrl ?>">
        <i class="fa fa-arrow-left"></i> KEMBALI KE SHOWROOM
    </a>

    <div class="detail-card">
        <div id="carouselMobil" class="carousel slide mb-4" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php foreach($fotos as $i => $foto): ?>
                <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
                    <img src="<?= $foto ?>" alt="Unit Gallery">
                </div>
                <?php endforeach; ?>
            </div>

            <?php if(count($fotos) > 1): ?>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselMobil" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselMobil" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
            <?php endif; ?>
        </div>

        <div class="header-section">
            <div>
                <?php $statusKey = strtolower(str_replace(" ", "", $mobil['status'])); ?>
                <span class="badge-status status-<?= $statusKey ?>">
                    <i class="fa fa-circle me-1" style="font-size: 8px;"></i> <?= strtoupper($mobil['status']) ?>
                </span>
                <h1><?= htmlspecialchars($mobil['nama_mobil']) ?></h1>
            </div>
            <div class="text-md-end">
                <div class="price-tag">Rp <?= number_format($mobil['harga'],0,',','.') ?></div>
                <small class="text-white-50">Harga On The Road (OTR)</small>
            </div>
        </div>

        <div class="spec-grid">
            <div class="spec-item">
                <span class="spec-label">Brand / Merk</span>
                <span class="spec-value"><i class="fa fa-tag text-primary me-2"></i><?= htmlspecialchars($mobil['merk']) ?></span>
            </div>
            <div class="spec-item">
                <span class="spec-label">Tahun Rilis</span>
                <span class="spec-value"><i class="fa fa-calendar text-primary me-2"></i><?= htmlspecialchars($mobil['tahun']) ?></span>
            </div>
            <div class="spec-item">
                <span class="spec-label">Fuel Type</span>
                <span class="spec-value"><i class="fa fa-gas-pump text-primary me-2"></i><?= htmlspecialchars($mobil['bahan_bakar']) ?></span>
            </div>
        </div>

        <?php if ($from === 'user'): ?>
        <div class="btn-action-group">
            <a href="<?= $hubungiUrl ?>" class="btn-premium btn-sales">
                <i class="fa fa-comments"></i> Hubungi Sales Kami
            </a>
            <a href="<?= $bookingUrl ?>" class="btn-premium btn-book">
                <i class="fa fa-calendar-check"></i> Booking Unit Sekarang
            </a>
        </div>
        <?php endif; ?>

        <div class="description-box">
            <h3>Spesifikasi & Deskripsi Unit</h3>
            <p><?= nl2br(htmlspecialchars($mobil['deskripsi'])) ?></p>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>