<?php
session_start();
require "koneksi.php";

// Pastikan user login
$user_id = $_SESSION['user_id'] ?? 0;
if (!$user_id) {
    header("Location: login_user.php");
    exit;
}

// Ambil ID mobil dari query string (mobil yang dipilih)
$mobil_id = $_GET['mobil_id'] ?? 0;
if (!$mobil_id) {
    echo "Mobil tidak ditemukan.";
    exit;
}

// Ambil data mobil
$stmt = $koneksi->prepare("SELECT * FROM mobil WHERE id = ?");
$stmt->bind_param("i", $mobil_id);
$stmt->execute();
$mobil = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$mobil) {
    echo "Mobil tidak ditemukan.";
    exit;
}


$default_msg = "Halo Sales, saya tertarik dengan {$mobil['merk']} - {$mobil['nama_mobil']}. Mohon informasi lebih lanjut mengenai ketersediaan unit, fitur tambahan, dan prosedur pembelian. Terima kasih.";

$notif = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pesan'])) {
    $pesan = $_POST['pesan'];

    $stmt = $koneksi->prepare("
        INSERT INTO pesan_user (user_id, mobil_id, pesan, status, waktu)
        VALUES (?, ?, ?, 'baru', NOW())
    ");
    $stmt->bind_param("iis", $user_id, $mobil_id, $pesan);
    $stmt->execute();
    $stmt->close();

    $notif = "Pesan berhasil dikirim ke Sales!";
}


$stmt = $koneksi->prepare("
    SELECT pu.id AS pesan_id, pu.pesan, pu.status, pu.waktu AS waktu_user,
           ba.balasan, ba.waktu AS waktu_balasan
    FROM pesan_user pu
    LEFT JOIN balasan_admin ba ON pu.id = ba.pesan_id
    WHERE pu.user_id = ? AND pu.mobil_id = ?
    ORDER BY pu.waktu ASC
");
$stmt->bind_param("ii", $user_id, $mobil_id);
$stmt->execute();
$result = $stmt->get_result();
$threads = [];
while ($row = $result->fetch_assoc()) {
    $threads[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Hubungi Sales - <?= htmlspecialchars($mobil['merk'] . ' ' . $mobil['nama_mobil']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background:#f4f6f8; font-family:'Segoe UI',sans-serif; }
        .container { margin-top:30px; max-width: 900px; }
        .card-header img { width:80px; height:80px; object-fit:cover; border-radius:10px; margin-right:12px; }
        .bubble { padding:10px 15px; border-radius:15px; margin-bottom:6px; max-width:80%; }
        .bubble.user { background:#d1e7dd; }
        .bubble.admin { background:#f8d7da; text-align:right; }
        .timestamp { font-size:0.75rem; color:#6c757d; display:block; margin-top:2px; }
        .chat-container { max-height:400px; overflow-y:auto; padding:10px; background:#f1f3f5; border-radius:10px; margin-bottom:10px; }
        .notif { position:fixed; top:20px; right:20px; background:#28a745; color:#fff; padding:12px 18px; border-radius:10px; opacity:0; transition:0.5s; }
        .notif.show { opacity:1; }
        a.back { display:inline-block; margin-bottom:16px; text-decoration:none; color:#374151; }
    </style>
</head>
<body>
<div class="container">

    <a href="shorum.php" class="back">&larr; Kembali ke Dashboard</a>

    <h3 class="text-center mb-4">Hubungi Sales</h3>

    <div class="card mb-4 shadow-sm">
        <div class="card-header d-flex align-items-center">
            <img src="<?= $mobil['foto'] ? 'uploads/'.$mobil['foto'] : 'https://via.placeholder.com/150' ?>" alt="Mobil">
            <div>
                <h5 class="mb-0"><?= htmlspecialchars($mobil['merk'] . ' ' . $mobil['nama_mobil']) ?></h5>
                <small>Harga: Rp <?= number_format($mobil['harga'],0,',','.') ?></small>
            </div>
        </div>
        <div class="card-body chat-container">
            <?php if(empty($threads)): ?>
                <div class="text-center text-muted">Belum ada percakapan sebelumnya.</div>
            <?php else: ?>
                <?php foreach($threads as $msg): ?>
                    <div class="bubble user"><?= htmlspecialchars($msg['pesan']) ?><span class="timestamp"><?= $msg['waktu_user'] ?></span></div>
                    <?php if($msg['balasan']): ?>
                        <div class="bubble admin"><?= htmlspecialchars($msg['balasan']) ?><span class="timestamp"><?= $msg['waktu_balasan'] ?></span></div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="card-footer">
            <form method="POST">
                <textarea name="pesan" class="form-control" rows="4" required><?= htmlspecialchars($default_msg) ?></textarea>
                <button class="btn btn-primary mt-2">Kirim Pesan</button>
            </form>
        </div>
    </div>

    <?php if(!empty($notif)): ?>
        <div class="notif show" id="notif"><?= $notif ?></div>
        <script>
            setTimeout(()=>{document.getElementById('notif').classList.remove('show');},3000);
        </script>
    <?php endif; ?>

</div>
</body>
</html>
