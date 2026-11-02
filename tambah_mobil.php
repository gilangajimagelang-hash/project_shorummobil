<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$pesan = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_mobil   = $_POST['nama_mobil'];
    $merk         = $_POST['merk'];
    $tahun        = $_POST['tahun'];
    $harga        = $_POST['harga'];
    $bahan_bakar  = $_POST['bahan_bakar'];
    $status       = $_POST['status'];
    $deskripsi    = $_POST['deskripsi'];

   
    $foto = 'default_car.png';
    if (!empty($_FILES['foto']['name'])) {
        $folder = 'uploads/';
        if (!is_dir($folder)) mkdir($folder, 0777, true);

        $foto = time() . "_" . rand(100,999) . "_" . $_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], $folder.$foto);
    }

    
    $stmt = $koneksi->prepare("
        INSERT INTO mobil (nama_mobil, merk, tahun, harga, bahan_bakar, status, deskripsi, foto, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
    ");
    $stmt->bind_param("ssiissss", $nama_mobil, $merk, $tahun, $harga, $bahan_bakar, $status, $deskripsi, $foto);

    if ($stmt->execute()) {
        $mobil_id = $stmt->insert_id;

        
        if (!empty($_FILES["foto_tambahan"]["name"][0])) {
            foreach ($_FILES["foto_tambahan"]["tmp_name"] as $i => $tmp) {
                if ($_FILES["foto_tambahan"]["name"][$i] === "") continue;

                $nama_foto = time() . "_" . rand(100,999) . "_" . $_FILES["foto_tambahan"]["name"][$i];
                move_uploaded_file($tmp, "uploads/" . $nama_foto);

                $stmt2 = $koneksi->prepare("INSERT INTO foto_mobil (mobil_id, filename) VALUES (?, ?)");
                $stmt2->bind_param("is", $mobil_id, $nama_foto);
                $stmt2->execute();
            }
        }

        $pesan = "Mobil berhasil ditambahkan!";
    } else {
        $pesan = "Gagal: " . $stmt->error;
    }
}
?>


<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Tambah Mobil | Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h3 class="mb-4">Tambah Mobil</h3>
    <?php if($pesan): ?>
        <div class="alert alert-info"><?= $pesan ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Nama Mobil</label>
            <input type="text" name="nama_mobil" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Merk</label>
            <input type="text" name="merk" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Tahun</label>
            <input type="number" name="tahun" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Harga</label>
            <input type="number" name="harga" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Bahan Bakar</label>
            <select name="bahan_bakar" class="form-select" required>
                <option value="">-- Pilih Bahan Bakar --</option>
                <option value="Bensin">Bensin</option>
                <option value="Diesel">Diesel</option>
                <option value="Listrik">Listrik</option>
                <option value="Hybrid">Hybrid</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
                <option value="">-- Pilih Status --</option>
                <option value="tersedia">Tersedia</option>
                <option value="terjual">Terjual</option>
                <option value="terboking">Terboking</option>
                <option value="ter dp">Ter DP</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="4"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Foto Utama</label>
            <input type="file" name="foto" class="form-control" accept="image/*">
        </div>
        <div class="mb-3">
            <label class="form-label">Foto Tambahan</label>
            <input type="file" name="foto_tambahan[]" class="form-control" accept="image/*" multiple>
        </div>
        <button class="btn btn-primary w-100">Tambah Mobil</button>
    </form>
    <div class="mt-3">
        <a href="mobil_list.php" class="btn btn-secondary">Kembali ke Daftar Mobil</a>
    </div>
</div>
</body>
</html>
