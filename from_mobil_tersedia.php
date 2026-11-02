<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Ambil semua mobil yang masih tersedia
$stmt = $koneksi->prepare("SELECT * FROM mobil WHERE status = 'tersedia' ORDER BY created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
$mobils = $result->fetch_all(MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Mobil Tersedia | Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
.card img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 6px;
}
.price {
    color: #e67e22;
    font-weight: bold;
}
.card-body { position: relative; }
</style>

</head>
<body class="bg-light">

<div class="container my-4">

    <h2 class="mb-3">📌 Daftar Mobil Tersedia</h2>

    <a href="dashboard.php" class="btn btn-secondary mb-3">Kembali ke Dashboard</a>
    <a href="mobil_list.php" class="btn btn-primary mb-3">Lihat Semua Mobil</a>
    <a href="from_mobil_terjual.php" class="btn btn-danger mb-3">Lihat Mobil Terjual</a>


    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php if (empty($mobils)): ?>
            <p class="text-center">Tidak ada mobil yang tersedia.</p>
        <?php endif; ?>

        <?php foreach ($mobils as $m): ?>
        <div class="col">
            <div class="card h-100 shadow-sm">

                <img src="uploads/<?= $m['foto'] ?>" alt="<?= $m['nama_mobil'] ?>">

                <div class="card-body">
                    <h5 class="card-title"><?= $m['nama_mobil'] ?></h5>
                    <p class="card-text">
                        <strong>Merk:</strong> <?= $m['merk'] ?><br>
                        <strong>Tahun:</strong> <?= $m['tahun'] ?><br>
                        <strong>Bahan Bakar:</strong> <?= $m['bahan_bakar'] ?><br>
                        <strong>Status:</strong> <span class="text-success fw-bold">Tersedia</span>
                    </p>

                    <p class="price">Rp <?= number_format($m['harga'], 0, ',', '.') ?></p>

                    <a href="detailmobil.php?id=<?= $m['id'] ?>&from=admin" class="btn btn-primary w-100 mb-1">Detail</a>
                    <a href="mobil_edit.php?id=<?= $m['id'] ?>" class="btn btn-warning w-100 mb-1">Edit</a>
                    <a href="hapus_mobil.php?id=<?= $m['id'] ?>" class="btn btn-danger w-100"
                       onclick="return confirm('Yakin ingin hapus mobil ini?')">Hapus</a>
                </div>

            </div>
        </div>
        <?php endforeach; ?>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
