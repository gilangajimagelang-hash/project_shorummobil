<?php
session_start();
require "koneksi.php";


$q = $koneksi->query("
    SELECT p.*, m.nama_mobil, m.merk, m.foto, u.username
    FROM pemesanan p
    LEFT JOIN mobil m ON p.mobil_id = m.id
    LEFT JOIN users u ON p.user_id = u.id
    ORDER BY p.id DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data Booking Masuk</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<style>
body { background: #f4f5f7; font-family: 'Inter', sans-serif; }
.container { max-width: 1100px; margin-top: 40px; }
h2 { font-weight: 600; margin-bottom: 20px; color: #111827; }
.card-booking { background: #fff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); padding: 20px; margin-bottom: 15px; transition: transform .2s; }
.card-booking:hover { transform: translateY(-3px); }
.card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
.card-header h5 { margin: 0; font-size: 1.1rem; font-weight: 600; }
.badge-status { padding: 5px 10px; border-radius: 10px; font-size: 0.85rem; font-weight: 500; }
.badge-menunggu { background: #facc15; color: #111827; }
.badge-diproses { background: #0ea5e9; color: #fff; }
.badge-disetujui { background: #22c55e; color: #fff; }
.badge-selesai { background: #64748b; color: #fff; }
.badge-dibatalkan { background: #ef4444; color: #fff; }
.foto-mobil { width: 90px; height: 60px; object-fit: cover; border-radius: 8px; }
.btn-detail { font-size: 0.85rem; }
</style>
</head>
<body>

<div class="container">
    <h2><i class="bi bi-bell"></i> Data Booking Masuk</h2>

    <?php if ($q->num_rows > 0): ?>
        <?php while($d = $q->fetch_assoc()): ?>
        <div class="card-booking d-flex align-items-center">
            <div class="d-flex align-items-center">
                <img src="<?= (!empty($d['foto']) && file_exists("uploads/".$d['foto'])) ? "uploads/".$d['foto'] : "https://via.placeholder.com/90" ?>" class="foto-mobil me-3">
                <div>
                    <div class="card-header">
                        <h5><?= htmlspecialchars($d['username']) ?> - <?= htmlspecialchars($d['merk'].' '.$d['nama_mobil']) ?></h5>
                        <?php
                            $status = strtolower($d['status_admin']);
                            $badgeClass = "badge-status badge-menunggu";
                            if($status=="diproses") $badgeClass="badge-status badge-diproses";
                            else if($status=="disetujui") $badgeClass="badge-status badge-disetujui";
                            else if($status=="selesai") $badgeClass="badge-status badge-selesai";
                            else if($status=="dibatalkan") $badgeClass="badge-status badge-dibatalkan";
                        ?>
                        <span class="<?= $badgeClass ?>"><?= strtoupper($d['status_admin']) ?></span>
                    </div>
                    <p class="mb-1"><strong>Tipe:</strong> <?= htmlspecialchars($d['tipe']) ?> | <strong>Tanggal:</strong> <?= $d['jadwal_tanggal'] ?> | <strong>Jam:</strong> <?= $d['jadwal_jam'] ?></p>
                    <a href="pemesanan_detail.php?id=<?= $d['id'] ?>" class="btn btn-sm btn-primary btn-detail">Lihat / Proses</a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="text-center text-muted mt-4">
            <i class="bi bi-inbox" style="font-size: 2rem;"></i>
            <p class="mt-2">Tidak ada data booking.</p>
        </div>
    <?php endif; ?>

     <div class="mt-3">
        <a href="dashboard.php" class="btn btn-secondary">Kembali</a>
    </div>


</div>

</body>
</html>
