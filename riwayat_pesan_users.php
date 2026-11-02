<?php
session_start();
require 'koneksi.php';


if (!isset($_SESSION['user_id'])) {
    header('Location: login_user.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$notif = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pesan_id'], $_POST['balasan'])) {
    $pesan_id = intval($_POST['pesan_id']);
    $balasan = trim($_POST['balasan']);

    if ($balasan !== '') {
        $stmt = $koneksi->prepare("INSERT INTO pesan_user (user_id, mobil_id, pesan, status, waktu) 
                                   SELECT user_id, mobil_id, ?, 'baru', NOW() FROM pesan_user WHERE id=? LIMIT 1");
        $stmt->bind_param("si", $balasan, $pesan_id);
        $stmt->execute();
        $stmt->close();

        $notif = "Balasan berhasil dikirim!";
    }
}


$stmt = $koneksi->prepare("
    SELECT pu.id AS pesan_id, pu.pesan, pu.waktu AS waktu_user, pu.status, pu.mobil_id,
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
<html>
<head>
    <title>Riwayat Pesan Saya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; font-family: 'Segoe UI', sans-serif; }
        .container { margin-top: 30px; max-width: 900px; }
        .card { border-radius: 1rem; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        .bubble { padding: 10px 15px; border-radius: 15px; margin-bottom: 6px; max-width: 80%; }
        .bubble.user { background: #d1e7dd; }
        .bubble.admin { background: #f8d7da; text-align: right; }
        .timestamp { font-size: 0.75rem; color: #6c757d; display: block; margin-top: 2px; }
        .card-header img { width: 60px; height: 60px; object-fit: cover; border-radius: 12px; margin-right: 12px; }
        .chat-container { max-height: 400px; overflow-y: auto; padding: 15px; background: #f1f3f5; border-radius: 12px; }
        .badge-status { font-size: 0.85rem; }
        a.back { display: inline-block; margin-bottom: 16px; color: #374151; text-decoration: none; }
        textarea { resize: none; border-radius: 10px; }
        .card-footer { background: #fff; }
        .alert-success { border-radius: 12px; }
    </style>
</head>
<body>
<div class="container">

    <a href="shorum.php" class="back">&larr; Kembali ke Dashboard</a>

    <h3 class="text-center mb-4">Riwayat Pesan Saya</h3>

    <?php if(!empty($notif)): ?>
        <div class="alert alert-success"><?= $notif ?></div>
    <?php endif; ?>

    <?php if(empty($threads)): ?>
        <div class="alert alert-info text-center">Belum ada pesan yang dikirim.</div>
    <?php endif; ?>

    <?php foreach($threads as $thread_id => $messages): 
        $first = $messages[0];
        $foto = $first['foto'] && file_exists('uploads/'.$first['foto']) 
                ? 'uploads/'.$first['foto'] 
                : 'https://via.placeholder.com/150';
        $status_badge = $first['status']=='baru' 
                        ? '<span class="badge bg-warning badge-status">Baru</span>'
                        : '<span class="badge bg-success badge-status">Dibalas</span>';
    ?>
    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <img src="<?= $foto ?>" alt="Mobil">
                <div>
                    <h5 class="mb-0"><?= htmlspecialchars($first['merk'].' - '.$first['nama_mobil']) ?></h5>
                    <small>Harga: Rp <?= number_format($first['harga'],0,',','.') ?></small>
                </div>
            </div>
            <?= $status_badge ?>
        </div>
        <div class="card-body chat-container">
            <?php foreach($messages as $msg): ?>
                <div class="bubble user">
                    <?= htmlspecialchars($msg['pesan']) ?>
                    <span class="timestamp"><?= $msg['waktu_user'] ?></span>
                </div>
                <?php if($msg['balasan']): ?>
                    <div class="bubble admin">
                        <?= htmlspecialchars($msg['balasan']) ?>
                        <span class="timestamp"><?= $msg['waktu_balasan'] ?></span>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <div class="card-footer">
            <form method="POST">
                <input type="hidden" name="pesan_id" value="<?= $first['pesan_id'] ?>">
                <textarea name="balasan" class="form-control mb-2" rows="2" placeholder="Tulis balasan..."></textarea>
                <button class="btn btn-primary w-100">Kirim Balasan</button>
            </form>
        </div>
    </div>
    <?php endforeach; ?>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>

document.querySelectorAll('.chat-container').forEach(function(container){
    container.scrollTop = container.scrollHeight;
});
</script>
</body>
</html>
