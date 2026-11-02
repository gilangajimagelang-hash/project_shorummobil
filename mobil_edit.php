<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: mobil_list.php");
    exit;
}

$id = intval($_GET['id']);
$pesan = "";


$stmt = $koneksi->prepare("SELECT * FROM mobil WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$hasil = $stmt->get_result();
$mobil = $hasil->fetch_assoc();

if (!$mobil) {
    die("Data mobil tidak ditemukan!");
}


$qFoto = $koneksi->query("SELECT * FROM foto_mobil WHERE mobil_id = $id");


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama_mobil   = $_POST['nama_mobil'];
    $merk         = $_POST['merk'];
    $tahun        = $_POST['tahun'];
    $harga        = $_POST['harga'];
    $bahan_bakar  = $_POST['bahan_bakar'];
    $status       = $_POST['status'];
    $deskripsi    = $_POST['deskripsi'];

   
    $foto = $mobil['foto'];


    if (!empty($_FILES['foto']['name'])) {

        $folder = 'uploads/';
        if (!is_dir($folder)) mkdir($folder, 0777, true);

        
        if ($mobil['foto'] != "default_car.png" && file_exists("uploads/" . $mobil['foto'])) {
            unlink("uploads/" . $mobil['foto']);
        }

        $foto = time() . "_" . rand(100,999) . "_" . $_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], "uploads/" . $foto);
    }

   
    $stmt2 = $koneksi->prepare("
        UPDATE mobil SET 
            nama_mobil=?, merk=?, tahun=?, harga=?, bahan_bakar=?, status=?, deskripsi=?, foto=?
        WHERE id=?
    ");
    $stmt2->bind_param("ssiissssi", 
        $nama_mobil, $merk, $tahun, $harga, 
        $bahan_bakar, $status, $deskripsi, $foto, $id
    );

    if ($stmt2->execute()) {

        if (!empty($_FILES["foto_tambahan"]["name"][0])) {
            foreach ($_FILES["foto_tambahan"]["tmp_name"] as $i => $tmp) {

                if ($_FILES["foto_tambahan"]["name"][$i] == "") continue;

                $nama_foto = time() . "_" . rand(100,999) . "_" . $_FILES["foto_tambahan"]["name"][$i];
                move_uploaded_file($tmp, "uploads/" . $nama_foto);

                $koneksi->query("INSERT INTO foto_mobil (mobil_id, filename) 
                                 VALUES ($id, '$nama_foto')");
            }
        }

        $pesan = "Mobil berhasil diperbarui!";
    } else {
        $pesan = "Gagal update: " . $stmt2->error;
    }
}
?>


<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Edit Mobil | Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
<div class="container py-5">

    <h3 class="mb-4">Edit Mobil</h3>

    <?php if($pesan): ?>
        <div class="alert alert-info"><?= $pesan ?></div>
    <?php endif; ?>


    <form method="POST" enctype="multipart/form-data">

        <div class="mb-3">
            <label class="form-label">Nama Mobil</label>
            <input type="text" name="nama_mobil" value="<?= $mobil['nama_mobil'] ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Merk</label>
            <input type="text" name="merk" value="<?= $mobil['merk'] ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Tahun</label>
            <input type="number" name="tahun" value="<?= $mobil['tahun'] ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Harga</label>
            <input type="number" name="harga" value="<?= $mobil['harga'] ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Bahan Bakar</label>
            <select name="bahan_bakar" class="form-select" required>
                <option <?= ($mobil['bahan_bakar']=="Bensin"?"selected":"") ?>>Bensin</option>
                <option <?= ($mobil['bahan_bakar']=="Diesel"?"selected":"") ?>>Diesel</option>
                <option <?= ($mobil['bahan_bakar']=="Listrik"?"selected":"") ?>>Listrik</option>
                <option <?= ($mobil['bahan_bakar']=="Hybrid"?"selected":"") ?>>Hybrid</option>
            </select>
        </div>

       <select name="status" class="form-select" required>
    <option value="tersedia" <?= ($mobil['status']=="tersedia" ? "selected" : "") ?>>Tersedia</option>
    <option value="terjual" <?= ($mobil['status']=="terjual" ? "selected" : "") ?>>Terjual</option>
    <option value="terboking" <?= ($mobil['status']=="terboking" ? "selected" : "") ?>>Terboking</option>
    <option value="ter dp" <?= ($mobil['status']=="ter dp" ? "selected" : "") ?>>Ter DP</option>
</select>


        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="4"><?= $mobil['deskripsi'] ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Foto Utama Sekarang</label><br>
            <img src="uploads/<?= $mobil['foto'] ?>" width="200" class="mb-2 rounded shadow">
            <input type="file" name="foto" class="form-control mt-2">
        </div>

        <div class="mb-3">
            <label class="form-label">Foto Tambahan (Tambah Baru)</label>
            <input type="file" name="foto_tambahan[]" multiple class="form-control">
        </div>

        <h5 class="mt-4">Foto Tambahan Saat Ini</h5>
        <div class="row">
            <?php while($f = $qFoto->fetch_assoc()): ?>
                <div class="col-md-3 text-center mb-3">
                    <img src="uploads/<?= $f['filename'] ?>" class="img-fluid rounded shadow">
                    <br>
                    <a href="mobil_foto_delete.php?id=<?= $f['id'] ?>&mobil_id=<?= $id ?>" 
                       class="btn btn-danger btn-sm mt-2">Hapus</a>
                </div>
            <?php endwhile; ?>
        </div>

        <button class="btn btn-primary w-100 mt-4">Update Mobil</button>
    </form>

    <div class="mt-3">
        <a href="mobil_list.php" class="btn btn-secondary">Kembali</a>
    </div>

</div>
</body>
</html>
