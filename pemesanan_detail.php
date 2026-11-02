<?php
session_start();
require "koneksi.php";


$id = isset($_GET['id']) ? intval($_GET['id']) : 0;


$stmt = $koneksi->prepare("
    SELECT 
        p.*,
        m.nama_mobil,
        m.merk,
        m.foto,
        m.status AS status_mobil,
        u.username
    FROM pemesanan p
    LEFT JOIN mobil m ON p.mobil_id = m.id
    LEFT JOIN users u ON u.id = p.user_id
    WHERE p.id = ?
    LIMIT 1
");

  
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
$stmt->close();

if(!$data){
    die("Data booking tidak ditemukan.");
}


$foto = (!empty($data['foto']) && file_exists("uploads/".$data['foto'])) ? "uploads/".$data['foto'] : "https://via.placeholder.com/900x400?text=Tidak+Ada+Foto";


$success_msg = '';
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_send'])){
    $status_admin = $_POST['status_admin'] ?? $data['status_admin'];
    $status_mobil = $_POST['status_mobil'] ?? $data['status_mobil'];
    $balasan = trim($_POST['balasan']) ?? '';


    $stmt2 = $koneksi->prepare("UPDATE pemesanan SET status_admin=? WHERE id=?");
    $stmt2->bind_param("si", $status_admin, $id);
    $stmt2->execute();
    $stmt2->close();

    $stmt3 = $koneksi->prepare("UPDATE mobil SET status=? WHERE id=?");
    $stmt3->bind_param("si", $status_mobil, $data['mobil_id']);
    $stmt3->execute();
    $stmt3->close();

   
    if($balasan !== ''){
        $stmt4 = $koneksi->prepare("INSERT INTO chat_booking (booking_id, pengirim, pesan) VALUES (?, 'admin', ?)");
        $stmt4->bind_param("is", $id, $balasan);
        $stmt4->execute();
        $stmt4->close();
    }

    $success_msg = "✅ Status dan keterangan mobil diperbarui, balasan berhasil dikirim.";
}


$chats = $koneksi->query("SELECT * FROM chat_booking WHERE booking_id={$data['id']} ORDER BY waktu ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Detail Booking Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background:#f3f4f6; font-family:Inter; }
.card { border-radius:12px; padding:20px; margin-bottom:20px; background:#fff; }
.foto-mobil { width:100%; max-height:350px; object-fit:cover; border-radius:10px; }
.label { font-weight:bold; width:150px; }
.row-item{ margin-bottom:10px; display:flex; }
.chat-box { max-height:250px; overflow-y:auto; padding:10px; background:#f8f9fa; border-radius:10px; margin-bottom:15px;}
.bubble-user { background:#d1e7dd; padding:10px; border-radius:10px; margin-bottom:6px; display:block; }
.bubble-admin { background:#ffdede; padding:10px; border-radius:10px; margin-bottom:6px; display:block; text-align:right; }
</style>
</head>
<body>
<div class="container mt-4">

    <a href="pemesanan_list.php" class="btn btn-dark mb-3">← Kembali</a>

    <?php if($success_msg): ?>
        <div class="alert alert-success"><?= $success_msg ?></div>
    <?php endif; ?>

    <div class="card">
        <img src="<?= $foto ?>" class="foto-mobil mb-3">

        <div class="row-item">
    <div class="label">User</div>
    <div>: <?= htmlspecialchars($data['username'] ?: 'User Tidak Diketahui') ?></div>
</div>

        <div class="row-item"><div class="label">Mobil</div><div>: <?= htmlspecialchars($data['merk'] . " " . $data['nama_mobil']) ?></div></div>
        <div class="row-item"><div class="label">Status Mobil</div>
            <div>
                <select name="status_mobil" form="form-update" class="form-select">
                    <option value="tersedia" <?= ($data['status_mobil']=='tersedia'?'selected':'') ?>>Tersedia</option>
                    <option value="terboking" <?= ($data['status_mobil']=='terboking'?'selected':'') ?>>Terboking</option>
                    <option value="ter dp" <?= ($data['status_mobil']=='ter dp'?'selected':'') ?>>Ter DP</option>
                    <option value="terjual" <?= ($data['status_mobil']=='terjual'?'selected':'') ?>>Terjual</option>
                </select>
            </div>
        </div>
        <div class="row-item"><div class="label">Tipe</div><div>: <?= strtoupper($data['tipe']) ?></div></div>
        <div class="row-item"><div class="label">Tanggal</div><div>: <?= $data['jadwal_tanggal'] ?></div></div>
        <div class="row-item"><div class="label">Jam</div><div>: <?= $data['jadwal_jam'] ?></div></div>
        <div class="row-item"><div class="label">Pesan User</div><div>: <?= nl2br(htmlspecialchars($data['pesan'])) ?></div></div>
        <div class="row-item"><div class="label">Status Admin</div>
            <div>
                <select name="status_admin" form="form-update" class="form-select">
                    <option value="menunggu" <?= $data['status_admin']=='menunggu'?'selected':'' ?>>Menunggu</option>
                    <option value="diproses" <?= $data['status_admin']=='diproses'?'selected':'' ?>>Diproses</option>
                    <option value="disetujui" <?= $data['status_admin']=='disetujui'?'selected':'' ?>>Disetujui</option>
                    <option value="selesai" <?= $data['status_admin']=='selesai'?'selected':'' ?>>Selesai</option>
                    <option value="dibatalkan" <?= $data['status_admin']=='dibatalkan'?'selected':'' ?>>Dibatalkan</option>
                </select>
            </div>
        </div>

        <hr>
        <h5>💬 Chat dengan User</h5>
        <div class="chat-box">
            <?php while($c = $chats->fetch_assoc()): ?>
                <?php if($c['pengirim']=='user'): ?>
                    <div class="bubble-user"><?= htmlspecialchars($c['pesan']) ?><br><small><?= $c['waktu'] ?></small></div>
                <?php else: ?>
                    <div class="bubble-admin"><?= htmlspecialchars($c['pesan']) ?><br><small><?= $c['waktu'] ?></small></div>
                <?php endif; ?>
            <?php endwhile; ?>
        </div>

        <form method="POST" id="form-update">
            <label>Balasan Admin</label>
            <textarea name="balasan" class="form-control mb-2" rows="3" placeholder="Tulis balasan ke user"></textarea>

            <button name="update_send" class="btn btn-success w-100">Update Status & Kirim Balasan</button>
        </form>
    </div>

</div>

<script>

document.querySelectorAll(".chat-box").forEach(box=>{ box.scrollTop = box.scrollHeight; });
</script>
</body>
</html>
