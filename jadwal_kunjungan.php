<?php 
session_start();
require "koneksi.php";


$tanggal_mulai = $_GET['start'] ?? date('Y-m-01'); 
$tanggal_selesai = $_GET['end'] ?? date('Y-m-t');  

$stmt = $koneksi->prepare("
    SELECT p.*, m.nama_mobil, m.merk, u.username
    FROM pemesanan p
    LEFT JOIN mobil m ON p.mobil_id = m.id
    LEFT JOIN users u ON p.user_id = u.id
    WHERE p.jadwal_tanggal BETWEEN ? AND ?
    ORDER BY p.jadwal_tanggal ASC, p.jadwal_jam ASC
");
$stmt->bind_param("ss", $tanggal_mulai, $tanggal_selesai);
$stmt->execute();
$bookings = $stmt->get_result();
$stmt->close();


function badgeStatus($status){
    switch(strtolower($status)){
        case 'menunggu': return '<span class="badge bg-warning text-dark">Menunggu</span>';
        case 'diproses': return '<span class="badge bg-primary">Diproses</span>';
        case 'disetujui':
        case 'selesai': return '<span class="badge bg-success">Selesai</span>';
        case 'dibatalkan': return '<span class="badge bg-danger">Dibatalkan</span>';
        default: return '<span class="badge bg-secondary">'.htmlspecialchars($status).'</span>';
    }
}


function namaHari($tanggal){
    $hari = date('N', strtotime($tanggal));
    $arr = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
    return $arr[$hari-1];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Cetak Rekap Jadwal Booking</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background: #f3f4f6; font-family: 'Inter', sans-serif; }
.container { max-width: 1100px; margin: 40px auto; }
.table thead { background: #111827; color: #fff; }
.table tbody tr:nth-child(even) { background: #f9fafb; }
h2 { margin-bottom: 20px; }
.filter-box { margin-bottom: 20px; }
.btn-print { margin-top: 10px; }
.badge { font-size: 0.9em; }
</style>
<script>
function printPage() { window.print(); }
</script>
</head>
<body>
<div class="container">
    <h2>📋 Rekap Jadwal Booking</h2>

    <form method="GET" class="filter-box row g-2">
        <div class="col-md-3">
            <label>Dari Tanggal</label>
            <input type="date" name="start" value="<?= htmlspecialchars($tanggal_mulai) ?>" class="form-control">
        </div>
        <div class="col-md-3">
            <label>Sampai Tanggal</label>
            <input type="date" name="end" value="<?= htmlspecialchars($tanggal_selesai) ?>" class="form-control">
        </div>
        <div class="col-md-2 align-self-end">
            <button class="btn btn-primary w-100">Filter</button>
        </div>
        <div class="col-md-2 align-self-end">
            <button type="button" onclick="printPage()" class="btn btn-success w-100">Cetak</button>
        </div>
    </form>

    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Hari</th>
                <th>Tanggal</th>
                <th>User</th>
                <th>Mobil</th>
                <th>Tipe</th>
                <th>Jam</th>
                <th>Status</th>
                <th>Pesan</th>
            </tr>
        </thead>
        <tbody>
        <?php if($bookings->num_rows > 0):
            $no=1;
            while($b = $bookings->fetch_assoc()):
                $badge = badgeStatus($b['status_admin']);
        ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= namaHari($b['jadwal_tanggal']) ?></td>
                <td><?= $b['jadwal_tanggal'] ?></td>
                <td><?= htmlspecialchars($b['username']) ?></td>
                <td><?= htmlspecialchars($b['merk'] . " " . $b['nama_mobil']) ?></td>
                <td><?= strtoupper($b['tipe']) ?></td>
                <td><?= $b['jadwal_jam'] ?></td>
                <td><?= $badge ?></td>
                <td><?= nl2br(htmlspecialchars($b['pesan'])) ?></td>
            </tr>
        <?php endwhile; else: ?>
            <tr><td colspan="9" class="text-center text-muted">Tidak ada booking pada periode ini.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
     <div class="mt-3">
        <a href="dashboard.php" class="btn btn-secondary">Kembali</a>
    </div>

</div>
</body>
</html>
