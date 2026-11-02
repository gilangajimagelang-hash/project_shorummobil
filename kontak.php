<?php
$nama_showroom = "Showrum v8 Auto Mobile";
$alamat = "Jl. Susukan, Grabag, Kec. Grabag, Kabupaten Magelang, Jawa Tengah 56196";
$telepon = "+62 87 2909 5422";
$email = "info@Showrumv8AutoMobile.com";


$maps_embed = "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3956.8595697239493!2d110.30641787357304!3d-7.369632972523588!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a80bfbba6f405%3A0x29f16b4adcb7753a!2sShowroom%20V8%20Automobile!5e0!3m2!1sen!2sid!4v1766108554006";
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contact Us | <?= $nama_showroom ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-gold: #c5a059;
            --dark-bg: #0f172a;
            --card-bg: #1e293b;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--dark-bg);
            color: #f8fafc;
            min-height: 100vh;
            margin: 0;
            overflow-x: hidden;
        }

      
        body::before {
            content: "";
            position: fixed;
            inset: 0;
            z-index: 0;
            background: url("logoshowrum.png") center/450px no-repeat;
            opacity: 0.05;
            pointer-events: none;
        }

        body > * { position: relative; z-index: 1; }

        
        .navbar {
            background: rgba(15, 23, 42, 0.9) !important;
            backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(255,255,255,0.1);
            padding: 15px 0;
        }
        .navbar-brand img { height: 45px; border-radius: 8px; }
        .nav-link.active { color: var(--primary-gold) !important; font-weight: 700; }

        
        .page-header {
            padding: 60px 0 40px;
        }
        .page-title {
            font-size: 40px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 10px;
        }
        .page-title i { color: var(--primary-gold); margin-right: 15px; }
        .page-subtitle { color: #94a3b8; font-size: 18px; }

       
        .contact-card {
            background: var(--card-bg);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            height: 100%;
        }

        .icon-box {
            display: flex;
            align-items: flex-start;
            gap: 20px;
            margin-bottom: 30px;
            transition: 0.3s;
        }
        .icon-box:hover { transform: translateX(10px); }
        
        .icon-circle {
            width: 54px;
            height: 54px;
            background: rgba(197, 160, 89, 0.1);
            color: var(--primary-gold);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
            border: 1px solid rgba(197, 160, 89, 0.2);
        }

        .icon-box h6 {
            margin: 0 0 5px;
            font-weight: 700;
            color: #fff;
            text-transform: uppercase;
            font-size: 14px;
            letter-spacing: 1px;
        }
        .icon-box p {
            margin: 0;
            color: #94a3b8;
            line-height: 1.6;
        }

        
        .map-wrapper {
            border-radius: 24px;
            overflow: hidden;
            height: 100%;
            min-height: 450px;
            border: 1px solid rgba(255,255,255,0.08);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            position: relative;
        }
        .map-wrapper iframe { filter: grayscale(0.3) invert(0.9) contrast(1.2); }

        .business-hours {
            margin-top: 30px;
            padding-top: 30px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }
        .badge-status {
            background: rgba(34, 197, 94, 0.1);
            color: #4ade80;
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
  <div class="container">
    <a class="navbar-brand" href="shorum.php">
      <img src="logoshowrum.png"> <strong>V8 AUTOMOBILE</strong>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="shorum.php">Showroom</a></li>
        <li class="nav-item"><a class="nav-link active" href="kontak.php">Contact Us</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container">
    <div class="page-header text-center">
        <h2 class="page-title"><i class="fa fa-envelope-open"></i> Get In Touch</h2>
        <p class="page-subtitle">Hubungi tim sales ahli kami untuk konsultasi unit terbaik</p>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-lg-5">
            <div class="contact-card">
                <div class="icon-box">
                    <div class="icon-circle">
                        <i class="fa fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <h6>Showroom Location</h6>
                        <p><?= $alamat ?></p>
                    </div>
                </div>

                <div class="icon-box">
                    <div class="icon-circle">
                        <i class="fa fa-phone-alt"></i>
                    </div>
                    <div>
                        <h6>Customer Care</h6>
                        <p><?= $telepon ?></p>
                    </div>
                </div>

                <div class="icon-box">
                    <div class="icon-circle">
                        <i class="fa fa-envelope"></i>
                    </div>
                    <div>
                        <h6>Official Email</h6>
                        <p><?= $email ?></p>
                    </div>
                </div>

                <div class="business-hours">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0 text-white">Opening Hours</h6>
                        <span class="badge-status">OPEN NOW</span>
                    </div>
                    <p class="small text-white-50">Senin – Minggu : 08.00 – 18.00 WIB</p>
                    <a href="https://wa.me/<?= str_replace(['+', ' '], '', $telepon) ?>" class="btn btn-outline-warning w-100 mt-3 border-secondary text-white">
                       
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="map-wrapper">
                <iframe 
                    src="<?= $maps_embed ?>"
                    width="100%" 
                    height="100%" 
                    style="border:0;" 
                    allowfullscreen 
                    loading="lazy">
                </iframe>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>