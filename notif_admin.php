<?php
session_start();
require "koneksi.php";


if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}


if (isset($_GET['read']) && isset($_GET['booking'])) {
    $chat_id    = (int) $_GET['read'];
    $booking_id = (int) $_GET['booking'];

    
    $stmt = $koneksi->prepare("
        UPDATE chat_booking 
        SET status = 'dibaca' 
        WHERE id = ?
    ");
    $stmt->bind_param("i", $chat_id);
    $stmt->execute();
    $stmt->close();

    
    header("Location: pemesanan_detail_admin.php?id=".$booking_id);
    exit;
}


$pesan = $koneksi->query("
    SELECT 
        cb.id AS chat_id,
        cb.pesan,
        cb.status,
        cb.waktu,
        u.username,
        m.nama_mobil,
        p.id AS booking_id
    FROM chat_booking cb
    JOIN pemesanan p ON cb.booking_id = p.id
    JOIN users u ON p.user_id = u.id
    JOIN mobil m ON p.mobil_id = m.id
    WHERE cb.pengirim = 'user'
    ORDER BY cb.waktu DESC
");
?>
<!doctype html>
<html lang="id">
<head>
<title>Notif Pesan User</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background:#0f172a; color:#e5e7eb; }
.sidebar { width:220px; background:#020617; min-height:100vh; }
.sidebar a { color:#cbd5f5; display:block; padding:12px 16px; text-decoration:none; }
.sidebar a.active, .sidebar a:hover { background:#1e293b; color:#38bdf8; }
.inbox { padding:20px; }
.msg {
    background:#020617;
    border-radius:12px;
    padding:14px;
    margin-bottom:12px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}
.msg.unread {
    border-left:5px solid #38bdf8;
}
.msg small { color:#94a3b8; }
.btn-open {
    background:#38bdf8;
    border:none;
    padding:6px 16px;
    border-radius:20px;
    color:#020617;
    text-decoration:none;
}
</style>
</head>
<body>

<div class="d-flex">
 
    <div class="sidebar">
        <h5 class="text-center py-3">📨 PESAN</h5>
        <a class="active">Kotak Masuk</a>
        <a>Pesan Sistem</a>
        <a>Laporan</a>
        <a>Info Penting</a>
    </div>

   
    <div class="flex-fill inbox">
        <h4 class="mb-4">Pesan Masuk User</h4>

        <?php if ($pesan->num_rows == 0): ?>
            <div class="alert alert-secondary">
                Tidak ada pesan user
            </div>
        <?php endif; ?>

        <?php while($p = $pesan->fetch_assoc()): ?>
            <div class="msg <?= $p['status']=='baru' ? 'unread' : '' ?>">
                <div>
                    <b><?= htmlspecialchars($p['username']) ?></b> —
                    <?= htmlspecialchars($p['nama_mobil']) ?><br>
                    <small><?= htmlspecialchars($p['pesan']) ?></small><br>
                    <small><?= $p['waktu'] ?></small>
                </div>

                <?php if ($p['status']=='baru'): ?>
                    <a class="btn-open"
                       href="?read=<?= $p['chat_id'] ?>&booking=<?= $p['booking_id'] ?>">
                        Buka
                    </a>
                <?php else: ?>
                    <small>✔ Dibaca</small>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>
