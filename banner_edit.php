<?php
require "koneksi.php";

$id = $_GET['id'];
$q = $koneksi->query("SELECT * FROM banner_iklan WHERE id='$id'");
$data = $q->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Banner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-4">
    <h2>Edit Banner</h2>

    <form action="banner_edit_process.php" method="POST" enctype="multipart/form-data">

        <input type="hidden" name="id" value="<?= $data['id'] ?>">

        <label>Teks Banner</label>
        <input type="text" name="judul" class="form-control" value="<?= $data['judul'] ?>">

        
        <label>Foto Saat Ini:</label><br>
        <img src="uploads/banner/<?= $data['foto'] ?>" width="200" class="mb-2">

        <label>Ganti Foto (opsional)</label>
        <input type="file" name="foto" class="form-control">

        <label>Urutan Slide</label>
        <input type="number" name="urutan" class="form-control" value="<?= $data['urutan'] ?>">

        <label>Status</label>
        <select name="status" class="form-control">
            <option value="aktif" <?= $data['status']=="aktif"?"selected":"" ?>>Aktif</option>
            <option value="nonaktif" <?= $data['status']=="nonaktif"?"selected":"" ?>>Nonaktif</option>
        </select>

        <button class="btn btn-success mt-3">Simpan Perubahan</button>
        <a href="banner_index.php" class="btn btn-secondary mt-3">Kembali</a>
    </form>
</div>

</body>
</html>
