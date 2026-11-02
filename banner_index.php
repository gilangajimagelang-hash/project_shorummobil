<?php
require "koneksi.php";

$query = $koneksi->query("SELECT * FROM banner_iklan ORDER BY urutan ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Kelola Banner Iklan</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    .banner-card {
        border-radius: 10px;
        overflow: hidden;
        background: white;
        border: 1px solid #ddd;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        transition: 0.25s;
    }
    .banner-card:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    }
    .banner-img {
        width: 100%;
        height: 150px;
        object-fit: cover;
        border-bottom: 1px solid #eee;
    }
</style>
</head>

<body class="hold-transition bg-light">

<div class="container mt-4">

    <h3 class="mb-3">
        <i class="fa-solid fa-images"></i> Kelola Banner Iklan
    </h3>

    <a href="banner_add.php" class="btn btn-primary mb-4">
        <i class="fa fa-plus"></i> Tambah Banner
    </a>

    <div class="row g-4">

        <?php while ($b = $query->fetch_assoc()) { ?>
        <div class="col-md-4">
            <div class="banner-card">

                <img src="uploads/banner/<?= $b['foto'] ?>" class="banner-img">

                <div class="p-3">

                    <h5 class="text-primary fw-bold mb-2">
                        <?= htmlspecialchars($b['judul']) ?>
                    </h5>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="badge bg-success"><?= $b['status'] ?></span>
                        <span class="badge bg-dark">Urutan: <?= $b['urutan'] ?></span>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="banner_edit.php?id=<?= $b['id'] ?>" 
                           class="btn btn-warning btn-sm w-50">
                           <i class="fa fa-edit"></i> Edit
                        </a>

                        <a href="banner_delete.php?id=<?= $b['id'] ?>" 
                           onclick="return confirm('Hapus banner ini?')"
                           class="btn btn-danger btn-sm w-50">
                           <i class="fa fa-trash"></i> Hapus
                        </a>
                    </div>

                </div>

            </div>
        </div>
        <?php } ?>

    </div>

    <div class="mt-4">
        <a href="dashboard.php" class="btn btn-secondary">
            <i class="fa fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

</div>

</body>
</html>
