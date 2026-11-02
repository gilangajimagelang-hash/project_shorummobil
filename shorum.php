<?php
session_start();
require 'koneksi.php';

$search = $_GET['search'] ?? '';
$merk_filter = $_GET['merk'] ?? '';
$bahan_bakar_filter = $_GET['bahan_bakar'] ?? '';
$currentPage = basename($_SERVER['PHP_SELF']);

$where = "WHERE 1=1";
if ($search) {
    $searchEsc = $koneksi->real_escape_string($search);
    $where .= " AND nama_mobil LIKE '%$searchEsc%'";
}
if ($merk_filter) {
    $merkEsc = $koneksi->real_escape_string($merk_filter);
    $where .= " AND merk = '$merkEsc'";
}
if ($bahan_bakar_filter) {
    $bahanEsc = $koneksi->real_escape_string($bahan_bakar_filter);
    $where .= " AND bahan_bakar = '$bahanEsc'";
}

$q = $koneksi->query("SELECT * FROM mobil $where ORDER BY created_at DESC LIMIT 30");
$mobils = [];
if ($q) {
    while ($r = $q->fetch_assoc()) { $mobils[] = $r; }
}

$getBanner = $koneksi->query("SELECT * FROM banner_iklan WHERE status='aktif' ORDER BY urutan ASC LIMIT 5");
$banner_data = [];
if ($getBanner) {
    while ($b = $getBanner->fetch_assoc()) { $banner_data[] = $b; }
}

$loggedIn = isset($_SESSION['user_id']);
$username = $loggedIn ? ($_SESSION['username_user'] ?? $_SESSION['username'] ?? '') : '';

$notif_balas = 0;
if($loggedIn){
    $stmtNotif = $koneksi->prepare("SELECT COUNT(*) as jml FROM pesan_user WHERE user_id=? AND status=1");
    $stmtNotif->bind_param("i", $_SESSION['user_id']);
    $stmtNotif->execute();
    $stmtNotif->bind_result($notif_balas);
    $stmtNotif->fetch();
    $stmtNotif->close();
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Premium Showroom | V8 Automobile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-gold: #c5a059;
            --dark-bg: #0f172a;
            --card-bg: #1e293b;
            --accent-blue: #38bdf8;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--dark-bg);
            color: #f8fafc;
            overflow-x: hidden;
        }

        .navbar {
            background: rgba(15, 23, 42, 0.9) !important;
            backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(255,255,255,0.1);
            padding: 15px 0;
            z-index: 1000;
        }
        .navbar-brand img { height: 50px; border-radius: 8px; }

        .banner-container { position: relative; margin-top: 20px; }
        .banner-wrapper {
            height: 500px;
            border-radius: 30px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7);
        }
        .banner-slide { position: absolute; inset: 0; opacity: 0; transition: opacity 1.2s ease-in-out; z-index: 1; }
        .banner-slide.active { opacity: 1; z-index: 2; }
        .banner-slide img { width: 100%; height: 100%; object-fit: cover; transform: scale(1.1); transition: transform 6s linear; }
        .banner-slide.active img { transform: scale(1); }
        .banner-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to right, rgba(15, 23, 42, 0.9) 10%, rgba(15, 23, 42, 0.2) 60%, rgba(15, 23, 42, 0.1) 100%);
            z-index: 3;
        }
        .banner-content { position: absolute; top: 50%; left: 8%; transform: translateY(-50%); z-index: 4; max-width: 600px; }
        .banner-text-small { color: var(--primary-gold); text-transform: uppercase; letter-spacing: 4px; font-weight: 700; font-size: 14px; margin-bottom: 10px; display: block; }
        .banner-title { color: #fff; font-size: 52px; font-weight: 800; line-height: 1.1; margin-bottom: 25px; }
        .banner-dots { position: absolute; bottom: 30px; left: 50%; transform: translateX(-50%); z-index: 5; display: flex; gap: 10px; }
        .dot { width: 12px; height: 12px; border-radius: 50%; background: rgba(255,255,255,0.3); cursor: pointer; transition: 0.3s; }
        .dot.active { background: var(--primary-gold); width: 30px; border-radius: 10px; }

        .filter-card {
            background: var(--card-bg);
            padding: 30px;
            border-radius: 24px;
            margin-top: -60px;
            z-index: 10;
            position: relative;
            border: 1px solid rgba(255,255,255,0.08);
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
        }

        .card {
            background: var(--card-bg);
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 24px;
            transition: all 0.4s ease;
        }
        .card:hover { transform: translateY(-10px); border-color: var(--primary-gold); }
        .card-img-top { height: 230px; border-radius: 24px 24px 0 0; object-fit: cover; }
        .price-tag { color: var(--primary-gold); font-weight: 700; font-size: 1.2rem; }

        .form-control, .form-select {
            background: #0f172a !important;
            border: 1px solid #334155 !important;
            color: #fff !important;
            border-radius: 14px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #c5a059 0%, #947a44 100%);
            border: none; border-radius: 14px; font-weight: 700; color: #000;
            padding: 12px 25px;
        }
        
        .status-badge {
            font-size: 11px; 
            text-transform: uppercase; 
            padding: 6px 14px;
            border-radius: 10px; 
            font-weight: 800;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
            border: 1px solid rgba(255,255,255,0.1);
            letter-spacing: 0.5px;
        }
        .status-tersedia { background: #10b981; color: #fff; } 
        .status-terjual { background: #ef4444; color: #fff; }  
        .status-boking { background: #f59e0b; color: #fff; }   
        .status-dp { background: #3b82f6; color: #fff; }       
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="home.php">
            <img src="logoshowrum.png" alt="Logo" class="me-2">
            <strong class="d-none d-sm-inline">V8 AUTOMOBILE</strong>
        </a>
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div id="navbarNav" class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a href="home.php" class="nav-link px-3">Home</a></li>
                <li class="nav-item"><a href="shorum.php" class="nav-link px-3 active">Showroom</a></li>
                <li class="nav-item"><a href="kontak.php" class="nav-link px-3">Contact</a></li>
                <?php if($loggedIn): ?>
                <li class="nav-item dropdown ms-lg-3">
                    <a class="nav-link dropdown-toggle btn btn-outline-light px-3" href="#" data-bs-toggle="dropdown">
                        <i class="fa fa-user-circle me-1"></i> <?= htmlspecialchars($username) ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark">
                        <li><a class="dropdown-item" href="riwayat_pesan_users.php">Pesan</a></li>
                        <li><a class="dropdown-item" href="riwayat_bokingan_users.php">Booking Saya</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
                    </ul>
                </li>
                <?php else: ?>
                <li class="nav-item"><a href="login_pro.php" class="nav-link px-4 btn btn-primary ms-lg-3 text-dark">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <div class="banner-container">
        <div class="banner-wrapper">
            <?php if (!empty($banner_data)): ?>
                <?php foreach ($banner_data as $i => $b): ?>
                <div class="banner-slide <?= $i == 0 ? 'active' : '' ?>" data-index="<?= $i ?>">
                    <img src="<?= 'uploads/banner/' . htmlspecialchars($b['foto']) ?>" alt="Banner">
                    <div class="banner-overlay"></div>
                    <div class="banner-content">
                        <span class="banner-text-small">Experience Luxury</span>
                        <h1 class="banner-title"><?= htmlspecialchars($b['judul']) ?></h1>
                        <a href="#shorum-list" class="btn btn-primary px-5">Explore Collection</a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="banner-slide active">
                    <img src="default-banner.jpg" alt="Default">
                    <div class="banner-overlay"></div>
                    <div class="banner-content">
                        <span class="banner-text-small">Welcome to V8</span>
                        <h1 class="banner-title">Premium Automobile Selection</h1>
                    </div>
                </div>
            <?php endif; ?>

            <div class="banner-dots">
                <?php foreach ($banner_data as $i => $b): ?>
                    <div class="dot <?= $i == 0 ? 'active' : '' ?>" onclick="goToSlide(<?= $i ?>)"></div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="filter-card">
        <form class="row g-3" method="get" action="">
            <div class="col-md-4">
                <label class="small text-white-50 mb-2">Pencarian</label>
                <input type="text" name="search" class="form-control py-2" placeholder="Cari unit..." value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="col-md-2">
                <label class="small text-white-50 mb-2">Merek</label>
                <select name="merk" class="form-select py-2">
                    <option value="">Semua</option>
                    <?php
                    $merkListFixed = ["Toyota","Daihatsu","Mitsubishi","BMW","Wuling","Mercedes-Benz","Honda","Suzuki"];
                    foreach ($merkListFixed as $merk) {
                        $sel = ($merk_filter == $merk) ? "selected" : "";
                        echo "<option value=\"".htmlspecialchars($merk)."\" $sel>$merk</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="small text-white-50 mb-2">Bahan Bakar</label>
                <select name="bahan_bakar" class="form-select py-2">
                    <option value="">Semua</option>
                    <option value="Bensin" <?= $bahan_bakar_filter=='Bensin'?'selected':'' ?>>Bensin</option>
                    <option value="Diesel" <?= $bahan_bakar_filter=='Diesel'?'selected':'' ?>>Diesel</option>
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end gap-2">
                <button class="btn btn-primary flex-grow-1 py-2">Cari Unit</button>
                <a href="shorum.php" class="btn btn-outline-secondary px-3 py-2"><i class="fa fa-sync"></i></a>
            </div>
        </form>
    </div>

    <div id="shorum-list" class="row row-cols-1 row-cols-md-3 g-4 mt-5 mb-5">
        <?php foreach ($mobils as $m): 
            $foto = ($m['foto']) ? "uploads/".htmlspecialchars($m['foto']) : "https://via.placeholder.com/400x300";
            $statusRaw = strtolower(trim($m['status'] ?? ''));
            
            $classStatus = 'status-tersedia';
            if (strpos($statusRaw, 'jual') !== false) {
                $classStatus = 'status-terjual';
            } elseif (strpos($statusRaw, 'boking') !== false || strpos($statusRaw, 'booking') !== false) {
                $classStatus = 'status-boking';
            } elseif (strpos($statusRaw, 'dp') !== false) {
                $classStatus = 'status-dp';
            }
        ?>
        <div class="col">
            <div class="card h-100">
                <div class="position-relative">
                    <img src="<?= $foto ?>" class="card-img-top" alt="Car">
                    <div class="position-absolute top-0 end-0 p-3">
                        <span class="status-badge <?= $classStatus ?>">
                            <?= htmlspecialchars($m['status']) ?>
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="card-title text-uppercase mb-1" style="color: #fff;">
                        <?= htmlspecialchars($m['nama_mobil']) ?>
                    </h5>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="price-tag">Rp <?= number_format($m['harga'] ?? 0, 0, ',', '.') ?></span>
                        <small class="text-white-50"><?= htmlspecialchars($m['tahun']) ?></small>
                    </div>
                    
                    <div class="row g-0 mb-4 text-white-50 small border-top border-secondary pt-3">
                        <div class="col-6"><i class="fa fa-tag me-2 text-primary"></i><?= htmlspecialchars($m['merk']) ?></div>
                        <div class="col-6 text-end"><i class="fa fa-gas-pump me-2 text-primary"></i><?= htmlspecialchars($m['bahan_bakar']) ?></div>
                    </div>

                    <a href="detailmobil.php?id=<?= (int)$m['id'] ?>&from=user" class="btn btn-primary w-100 text-uppercase">
                        Lihat Detail
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let current = 0;
    const slides = document.querySelectorAll(".banner-slide");
    const dots = document.querySelectorAll(".dot");

    function showSlide(index) {
        slides.forEach(s => s.classList.remove("active"));
        dots.forEach(d => d.classList.remove("active"));
        if(slides[index]) slides[index].classList.add("active");
        if(dots[index]) dots[index].classList.add("active");
        current = index;
    }

    function goToSlide(index) { showSlide(index); }
    function autoSlide() { current = (current + 1) % slides.length; showSlide(current); }
    if (slides.length > 1) { setInterval(autoSlide, 6000); }
</script>
</body>
</html>