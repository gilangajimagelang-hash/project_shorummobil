<?php
session_start();
require "koneksi.php";


if (!isset($_SESSION['user_id'])) {
    header("Location: login_user_from_hubungi_sales.php");
    exit;
}

$user_id = $_SESSION['user_id'];


$notif_admin = $koneksi->query("
    SELECT COUNT(*) total
    FROM chat_booking cb
    JOIN pemesanan p ON cb.booking_id = p.id
    WHERE p.user_id = $user_id
    AND cb.pengirim = 'admin'
    AND cb.status = 'baru'
")->fetch_assoc()['total'];


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kirim_chat'])) {
    $booking_id = intval($_POST['booking_id']);
    $pesan = trim($_POST['pesan']);

    if ($pesan != "") {
        $stmt = $koneksi->prepare("
            INSERT INTO chat_booking (booking_id, pengirim, pesan, status) 
            VALUES (?, 'user', ?, 'baru')
        ");
        $stmt->bind_param("is", $booking_id, $pesan);
        $stmt->execute();
        $stmt->close();

       
        header("Location: ".$_SERVER['PHP_SELF']."#booking".$booking_id);
        exit;
    }
}


$stmt = $koneksi->prepare("
    SELECT b.*, m.nama_mobil, m.merk, m.foto
    FROM pemesanan b
    JOIN mobil m ON b.mobil_id = m.id
    WHERE b.user_id = ?
    ORDER BY b.id DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$bookings = $stmt->get_result();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Riwayat Booking & Pesan</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{background:#f4f4f9;font-family:Inter;}
.container{max-width:900px;}
.card{border-radius:15px;box-shadow:0 4px 20px rgba(0,0,0,0.1);margin-bottom:25px;}
.foto-mobil{width:100px;height:70px;object-fit:cover;border-radius:10px;}
.chat-box{background:#fff;padding:15px;border-radius:12px;max-height:250px;overflow-y:auto;}
.bubble-user{background:#d1e7dd;padding:10px 14px;border-radius:15px;display:inline-block;margin-bottom:6px;}
.bubble-admin{background:#ffdede;padding:10px 14px;border-radius:15px;display:block;margin-bottom:6px;margin-left:auto;}
textarea.form-control{resize:none;}
</style>
</head>
<body>

<div class="container mt-4">
<h3 class="mb-4 text-center">Riwayat Booking & Pesan Anda</h3>

<?php if($notif_admin > 0): ?>
<div class="alert alert-info text-center">
🔔 Admin telah membalas <?= $notif_admin ?> pesan Anda
</div>
<?php endif; ?>

<?php while ($b = $bookings->fetch_assoc()): ?>
<?php
    $foto = (!empty($b['foto']) && file_exists("uploads/".$b['foto'])) 
            ? "uploads/".$b['foto'] 
            : "https://via.placeholder.com/100";

   
    $koneksi->query("
        UPDATE chat_booking 
        SET status='dibaca' 
        WHERE booking_id={$b['id']} AND pengirim='admin'
    ");

    $chats = $koneksi->query("
        SELECT * FROM chat_booking 
        WHERE booking_id={$b['id']} 
        ORDER BY waktu ASC
    ");
?>
<div class="card p-3" id="booking<?= $b['id'] ?>">
    <div class="d-flex">
        <img src="<?= $foto ?>" class="foto-mobil">
        <div class="ms-3 flex-grow-1">
            <h5><?= htmlspecialchars($b['merk'].' '.$b['nama_mobil']) ?></h5>
            <small>Tanggal Booking: <?= htmlspecialchars($b['jadwal_tanggal']) ?> — Jam: <?= htmlspecialchars($b['jadwal_jam']) ?></small><br>
            <small>Status Admin: <b><?= strtoupper(htmlspecialchars($b['status_admin'])) ?></b></small>
        </div>
    </div>

    
    <div class="chat-box mt-3">
        <?php while($c = $chats->fetch_assoc()): ?>
            <?php if($c['pengirim'] == 'user'): ?>
                <div class="bubble-user">
                    <?= htmlspecialchars($c['pesan']) ?><br>
                    <small><?= $c['waktu'] ?></small>
                </div>
            <?php else: ?>
                <div class="bubble-admin">
                    <?= htmlspecialchars($c['pesan']) ?><br>
                    <small><?= $c['waktu'] ?></small>
                </div>
            <?php endif; ?>
        <?php endwhile; ?>
    </div>

 
    <form method="POST" class="mt-3">
        <input type="hidden" name="booking_id" value="<?= $b['id'] ?>">
        <textarea name="pesan" class="form-control mb-2" rows="2" placeholder="Tulis pesan..."></textarea>
        <button type="submit" name="kirim_chat" class="btn btn-primary w-100">
            Kirim Pesan
        </button>
    </form>
</div>
<?php endwhile; ?>

<a href="shorum.php" class="btn btn-secondary mt-3">Kembali</a>
</div>

<script>
document.querySelectorAll(".chat-box").forEach(box => {
    box.scrollTop = box.scrollHeight;
});
</script>

</body>
</html>
