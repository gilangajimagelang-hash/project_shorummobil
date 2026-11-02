<?php
session_start();
require 'koneksi.php';

$user_id = $_SESSION['user_id'] ?? 0;
$notif = '';


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['mobil_id']) && isset($_POST['pesan'])) {
    $mobil_id = $_POST['mobil_id'];
    $pesan = $_POST['pesan'];

    $stmt = $koneksi->prepare("INSERT INTO pesan_user (user_id, mobil_id, pesan, status, waktu) VALUES (?, ?, ?, 'baru', NOW())");
    $stmt->bind_param("iis", $user_id, $mobil_id, $pesan);
    $stmt->execute();
    $stmt->close();

    $notif = "Pesan berhasil dikirim ke sales!";
}


$stmt = $koneksi->prepare("
    SELECT pu.id AS pesan_id, pu.pesan, pu.waktu AS waktu_user, pu.mobil_id,
           m.nama_mobil, m.merk, m.harga, m.foto,
           ba.balasan, ba.waktu AS waktu_balasan
    FROM pesan_user pu
    LEFT JOIN balasan_admin ba ON pu.id = ba.pesan_id
    LEFT JOIN mobil m ON pu.mobil_id = m.id
    WHERE pu.user_id = ?
    ORDER BY pu.waktu DESC, ba.waktu ASC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$pesan_result = $stmt->get_result();
$stmt->close();


$threads = [];
while ($row = $pesan_result->fetch_assoc()) {
    $threads[$row['pesan_id']][] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Riwayat Pesan - User</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
body{background:#f4f6f8;font-family:'Segoe UI',sans-serif;}
.container{margin-top:30px;}
.bubble{padding:10px 15px;border-radius:15px;margin-bottom:6px;max-width:80%;}
.bubble.user{background:#d1e7dd;}
.bubble.admin{background:#f8d7da;text-align:right;}
.timestamp{font-size:0.75rem;color:#6c757d;display:block;margin-top:2px;}
.card-header img{width:60px;height:60px;object-fit:cover;border-radius:10px;margin-right:12px;}
.notif{position:fixed;top:20px;right:20px;background:#28a745;color:#fff;padding:12px 18px;border-radius:10px;box-shadow:0 4px 12px rgba(0,0,0,0.2);opacity:0;transition:0.5s;}
.notif.show{opacity:1;}
.back{display:inline-block;margin-bottom:16px;color:#374151;text-decoration:none;}
</style>
</head>
<body>
<div class="container">

    <a href="index.php" class="back"><i class="fa fa-arrow-left"></i> Kembali ke Dashboard</a>

    <h3 class="text-center mb-4">Riwayat Pesan Saya</h3>

    <?php if(!empty($notif)): ?>
        <div class="notif show"><?= $notif ?></div>
        <script>
            setTimeout(()=>{document.querySelector('.notif').classList.remove('show');},3000);
        </script>
    <?php endif; ?>

    <?php foreach($threads as $thread_id => $messages): 
        $first = $messages[0];
        $foto = $first['foto'] ? "uploads/".$first['foto'] : "https://via.placeholder.com/150";
    ?>
    <div class="card mb-4 shadow-sm">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <img src="<?= $foto ?>" alt="Mobil">
                <div>
                    <h5 class="card-title mb-0"><?= $first['merk'] ?> - <?= $first['nama_mobil'] ?></h5>
                    <small>Harga: Rp <?= number_format($first['harga'],0,',','.') ?></small>
                </div>
            </div>
            <span class="badge bg-primary">ID Pesan: <?= $first['pesan_id'] ?></span>
        </div>
        <div class="card-body" style="max-height:350px;overflow-y:auto;">
            <?php foreach($messages as $msg): ?>
                <div class="bubble user"><?= htmlspecialchars($msg['pesan']) ?><br><span class="timestamp"><?= $msg['waktu_user'] ?></span></div>
                <?php if($msg['balasan']): ?>
                    <div class="bubble admin"><?= htmlspecialchars($msg['balasan']) ?><br><span class="timestamp"><?= $msg['waktu_balasan'] ?></span></div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <div class="card-footer">
            <form method="POST">
                <input type="hidden" name="mobil_id" value="<?= $first['mobil_id'] ?>">
                <div class="input-group">
                    <textarea name="pesan" class="form-control" placeholder="Tulis balasan..." required></textarea>
                    <button class="btn btn-primary"><i class="fa fa-paper-plane"></i> Kirim</button>
                </div>
            </form>
        </div>
    </div>
    <?php endforeach; ?>

    <?php if(empty($threads)): ?>
        <div class="alert alert-info text-center">Belum ada pesan yang dikirim.</div>
    <?php endif; ?>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
