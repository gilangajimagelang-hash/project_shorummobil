<?php
session_start();
require "koneksi.php";


if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}


$koneksi->query("
    UPDATE pesan_user
    SET status = 'dibaca'
    WHERE status = 'baru'
");


$notif = '';
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['pesan_id'], $_POST['balasan'])) {

    $pesan_id = (int)$_POST['pesan_id'];
    $balasan  = trim($_POST['balasan']);
    $admin_id = $_SESSION['admin_id'] ?? 1;

    if ($balasan !== '') {

        
        $stmt = $koneksi->prepare("
            INSERT INTO balasan_admin (pesan_id, admin_id, balasan, waktu)
            VALUES (?, ?, ?, NOW())
        ");
        $stmt->bind_param("iis", $pesan_id, $admin_id, $balasan);
        $stmt->execute();
        $stmt->close();

        
        $stmt = $koneksi->prepare("
            UPDATE pesan_user
            SET status = 'dibalas'
            WHERE id = ?
        ");
        $stmt->bind_param("i", $pesan_id);
        $stmt->execute();
        $stmt->close();

        $notif = "Balasan berhasil dikirim";
    }
}


$sql = "
SELECT 
    pu.id AS pesan_id,
    pu.pesan,
    pu.status,
    pu.waktu AS waktu_user,
    u.username,
    m.nama_mobil,
    m.merk,
    m.foto,
    ba.balasan,
    ba.waktu AS waktu_balasan
FROM pesan_user pu
LEFT JOIN users u ON pu.user_id = u.id
LEFT JOIN mobil m ON pu.mobil_id = m.id
LEFT JOIN balasan_admin ba ON pu.id = ba.pesan_id
ORDER BY pu.waktu DESC, ba.waktu ASC
";
$result = $koneksi->query($sql);


$threads = [];
while ($row = $result->fetch_assoc()) {
    $threads[$row['pesan_id']][] = $row;
}


$total_pesan_baru = $koneksi->query("
    SELECT COUNT(*) total 
    FROM pesan_user 
    WHERE status = 'baru'
")->fetch_assoc()['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Admin Pesan</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{background:#f4f6f8;font-family:'Segoe UI',sans-serif}
.container{max-width:1000px;margin-top:30px}
.card{border-radius:14px;box-shadow:0 4px 12px rgba(0,0,0,.1)}
.bubble{padding:10px 15px;border-radius:14px;margin-bottom:8px;max-width:75%}
.user{background:#d1e7dd}
.admin{background:#f8d7da;margin-left:auto}
.timestamp{font-size:12px;color:#6c757d}
.card-body{max-height:350px;overflow-y:auto;background:#f1f3f5}
</style>
</head>
<body>

<div class="container">

<a href="dashboard.php" class="btn btn-secondary mb-3">← Dashboard</a>

<h4 class="text-center mb-4">
    Pesan User (<?= $total_pesan_baru ?> Pesan Baru)
</h4>

<?php if ($notif): ?>
<div class="alert alert-success"><?= $notif ?></div>
<?php endif; ?>

<?php if (empty($threads)): ?>
<div class="alert alert-info text-center">Belum ada pesan</div>
<?php endif; ?>

<?php foreach ($threads as $messages):
    $first = $messages[0];
    $foto  = $first['foto'] ? "uploads/".$first['foto'] : "https://via.placeholder.com/100";

    $badge = match ($first['status']) {
        'baru'    => 'bg-warning',
        'dibaca'  => 'bg-info',
        'dibalas' => 'bg-success',
        default   => 'bg-secondary'
    };
?>

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="d-flex gap-3 align-items-center">
            <img src="<?= $foto ?>" width="60" class="rounded">
            <div>
                <strong><?= $first['merk'] ?> - <?= $first['nama_mobil'] ?></strong><br>
                <small>User: <?= htmlspecialchars($first['username']) ?></small>
            </div>
        </div>
        <span class="badge <?= $badge ?>"><?= strtoupper($first['status']) ?></span>
    </div>

    <div class="card-body">
        <?php foreach ($messages as $msg): ?>
            <div class="bubble user">
                <?= htmlspecialchars($msg['pesan']) ?>
                <div class="timestamp"><?= $msg['waktu_user'] ?></div>
            </div>

            <?php if ($msg['balasan']): ?>
            <div class="bubble admin">
                <?= htmlspecialchars($msg['balasan']) ?>
                <div class="timestamp"><?= $msg['waktu_balasan'] ?></div>
            </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <div class="card-footer">
        <form method="POST">
            <input type="hidden" name="pesan_id" value="<?= $first['pesan_id'] ?>">
            <div class="input-group">
                <textarea name="balasan" class="form-control" required placeholder="Tulis balasan..."></textarea>
                <button class="btn btn-primary">Kirim</button>
            </div>
        </form>
    </div>
</div>

<?php endforeach; ?>

</div>
</body>
</html>
