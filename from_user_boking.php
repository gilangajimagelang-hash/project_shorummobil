<?php
session_start();
require "koneksi.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login_user_from_hubungi_sales.php");
    exit;
}

$mobil_id = $_GET['mobil_id'] ?? 0;


$stmt = $koneksi->prepare("SELECT merk, nama_mobil, foto FROM mobil WHERE id=? LIMIT 1");
$stmt->bind_param("i", $mobil_id);
$stmt->execute();
$result = $stmt->get_result();
$mobil = $result->fetch_assoc();
$stmt->close();

if (!$mobil) {
    die("Mobil tidak ditemukan.");
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $user_id = $_SESSION['user_id'];
    $tipe = $_POST['tipe'];             
    $jadwal_tanggal = $_POST['jadwal_tanggal'];
    $jadwal_jam = $_POST['jadwal_jam'];
    $pesan = $_POST['pesan'];
    $metode_bayar = $_POST['metode_bayar'] ?? null;

    $stmt2 = $koneksi->prepare("
        INSERT INTO pemesanan (user_id, mobil_id, tipe, jadwal_tanggal, jadwal_jam, pesan, metode_bayar, status_admin)
        VALUES (?, ?, ?, ?, ?, ?, ?, 'menunggu')
    ");
    $stmt2->bind_param("iisssss", $user_id, $mobil_id, $tipe, $jadwal_tanggal, $jadwal_jam, $pesan, $metode_bayar);

    if (!$stmt2->execute()) {
        die("Error: " . $stmt2->error);
    }

    $last_id = $stmt2->insert_id;
    $stmt2->close();

    if ($tipe === 'dp') {
        header("Location: cetak_dp.php?id={$last_id}");
        exit;
    } else {
        header("Location: sukses_pesan_boking.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Booking / DP - <?= htmlspecialchars($mobil['nama_mobil']) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{background:#f5f7fa;font-family:Inter}
.form-box{
    max-width:600px;margin:30px auto;background:#fff;padding:25px;
    border-radius:12px;box-shadow:0 4px 20px rgba(0,0,0,.08)
}
label{font-weight:600;margin-top:10px}
.btn-submit{
    margin-top:20px;width:100%;padding:12px;border-radius:10px;
    background:#0f172a;color:#fff;font-weight:600
}
.btn-submit:hover{background:#1c2938}
.foto-mobil {width:150px; height:100px; object-fit:cover; border-radius:12px; margin-bottom:15px;}
</style>
<script>
function toggleMetodeBayar() {
    var tipe = document.getElementById('tipe').value;
    var divBayar = document.getElementById('div_metode_bayar');
    divBayar.style.display = (tipe === 'dp') ? 'block' : 'none';
}
</script>
</head>

<body>

<div class="form-box">
    <h3>Form Booking / DP</h3>
    <img src="<?= (!empty($mobil['foto']) && file_exists("uploads/".$mobil['foto'])) ? "uploads/".$mobil['foto'] : "https://via.placeholder.com/150" ?>" class="foto-mobil">
    <p><strong>Mobil:</strong> <?= htmlspecialchars($mobil['nama_mobil']) ?> (<?= htmlspecialchars($mobil['merk']) ?>)</p>

    <form method="POST">
        <label>Pilih Tipe Pengajuan</label>
        <select name="tipe" id="tipe" class="form-control" onchange="toggleMetodeBayar()" required>
            <option value="">-- Pilih --</option>
            <option value="booking">Booking</option>
            <option value="dp">DP</option>
        </select>

        <div id="div_metode_bayar" style="display:none;">
            <label>Metode Pembayaran DP</label>
            <select name="metode_bayar" class="form-control">
                <option value="">-- Pilih Metode --</option>
                <option value="transfer">Transfer Bank</option>
                <option value="cash">Cash / Tunai</option>
                <option value="e-wallet">E-Wallet</option>
            </select>
        </div>

        <label>Kapan Anda Datang ke Showroom?</label>
        <input type="date" name="jadwal_tanggal" class="form-control" required>
        <input type="time" name="jadwal_jam" class="form-control mt-2" required>

        <label>Pesan Tambahan</label>
        <textarea name="pesan" class="form-control" rows="4" placeholder="Contoh: Saya ingin lihat unitnya dan test drive."></textarea>

        <button type="submit" class="btn-submit">Kirim ke Admin</button>
    </form>
</div>

</body>
</html>
