<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$search = $_GET['search'] ?? '';
$filterMerk = $_GET['merk'] ?? '';
$filterBahan = $_GET['bahan'] ?? '';

$mobils = [];

$query = "SELECT * FROM mobil WHERE 1=1";

if ($search) { $query .= " AND nama_mobil LIKE ?"; }
if ($filterMerk) { $query .= " AND merk = ?"; }
if ($filterBahan) { $query .= " AND bahan_bakar = ?"; }

$query .= " ORDER BY created_at DESC";

$stmt = $koneksi->prepare($query);

$types = '';
$params = [];
if ($search) { $types .= 's'; $params[] = "%$search%"; }
if ($filterMerk) { $types .= 's'; $params[] = $filterMerk; }
if ($filterBahan) { $types .= 's'; $params[] = $filterBahan; }

if ($types) { $stmt->bind_param($types, ...$params); }

$stmt->execute();
$result = $stmt->get_result();
if ($result) {
    $mobils = $result->fetch_all(MYSQLI_ASSOC);
}

$merkList = $koneksi->query("SELECT DISTINCT merk FROM mobil")->fetch_all(MYSQLI_ASSOC);
$bahanList = $koneksi->query("SELECT DISTINCT bahan_bakar FROM mobil")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Daftar Mobil | Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.card img { width: 100%; height: 180px; object-fit: cover; border-radius: 6px; }
.price { color: #e67e22; font-weight: bold; }
.badge-status {
    position: absolute;
    top: 10px;
    left: 10px;
    padding: 6px 12px;
    border-radius: 6px;
    font-weight: bold;
    color: white;
    z-index: 10;
    font-size: 14px;
}
.card-body { position: relative; }
</style>
</head>
<body class="bg-light">

<div class="container my-4">
    <h2 class="mb-3">Daftar Mobil</h2>

    <a href="dashboard.php" class="btn btn-secondary mb-3">Kembali ke Dashboard</a>
    <a href="tambah_mobil.php" class="btn btn-success mb-3">Tambah Mobil</a>

   
    <form class="row g-3 mb-4" method="get">
        <div class="col-md-4">
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" class="form-control" placeholder="Cari nama mobil...">
        </div>
        <div class="col-md-3">
            <select name="merk" class="form-select">
                <option value="">Semua Merk</option>
                <?php foreach ($merkList as $m): ?>
                    <option value="<?= $m['merk'] ?>" <?= ($filterMerk==$m['merk'])?'selected':''; ?>><?= $m['merk'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <select name="bahan" class="form-select">
                <option value="">Semua Bahan Bakar</option>
                <?php foreach ($bahanList as $b): ?>
                    <option value="<?= $b['bahan_bakar'] ?>" <?= ($filterBahan==$b['bahan_bakar'])?'selected':''; ?>><?= $b['bahan_bakar'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    <div class="row row-cols-1 row-cols-md-3 g-4">

        <?php if (empty($mobils)): ?>
            <p class="text-center">Tidak ada mobil ditemukan.</p>
        <?php endif; ?>

        <?php foreach ($mobils as $m): ?>
        <div class="col position-relative">
            <div class="card h-100 shadow-sm">

            
                <?php if($m['status'] === 'terjual'): ?>
                    <div class="badge-status bg-danger">Terjual</div>
                <?php elseif($m['status'] === 'tersedia'): ?>
                    <div class="badge-status bg-success">Tersedia</div>
                <?php elseif($m['status'] === 'terboking'): ?>
                    <div class="badge-status bg-warning text-dark">Terboking</div>
                <?php elseif($m['status'] === 'ter dp'): ?>
                    <div class="badge-status bg-primary">Ter DP</div>
                <?php endif; ?>

                <img src="uploads/<?= $m['foto'] ?>" alt="<?= $m['nama_mobil'] ?>">

                <div class="card-body">
                    <h5 class="card-title"><?= $m['nama_mobil'] ?></h5>
                    <p class="card-text">
                        <strong>Merk:</strong> <?= $m['merk'] ?><br>
                        <strong>Tahun:</strong> <?= $m['tahun'] ?><br>
                        <strong>Bahan Bakar:</strong> <?= $m['bahan_bakar'] ?>
                    </p>
                    <p class="price">Rp <?= number_format($m['harga'],0,',','.') ?></p>

                    <a href="detailmobil.php?id=<?= $m['id'] ?>&from=admin" class="btn btn-primary w-100 mb-1">Detail</a>
                    <a href="mobil_edit.php?id=<?= $m['id'] ?>" class="btn btn-warning w-100 mb-1">Edit</a>
                    <a href="hapus_mobil.php?id=<?= $m['id'] ?>" class="btn btn-danger w-100" onclick="return confirm('Yakin ingin hapus mobil ini?')">Hapus</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
