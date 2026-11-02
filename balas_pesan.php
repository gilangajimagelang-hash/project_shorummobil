<?php
session_start();
require 'koneksi.php';

$id = $_GET['id'];

$p = $koneksi->query("
    SELECT p.*, u.username, m.nama_mobil
    FROM pesan p
    JOIN users u ON p.user_id = u.id
    JOIN mobil m ON p.id_mobil = m.id
    WHERE p.id = $id
")->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
<title>Balas Pesan</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h3>Balas Pesan dari <?= $p['username'] ?></h3>
    <p><b>Mobil:</b> <?= $p['nama_mobil'] ?></p>
    <p><b>Pesan:</b><br><?= nl2br($p['pesan']) ?></p>

    <form action="balas_pesan_proses.php" method="POST">
        <input type="hidden" name="id" value="<?= $p['id'] ?>">

        <div class="mb-3">
            <label>Balasan:</label>
            <textarea name="balasan" class="form-control" rows="3" required><?= $p['balasan'] ?></textarea>
        </div>

        <button class="btn btn-primary">Kirim Balasan</button>
    </form>
</div>

</body>
</html>
