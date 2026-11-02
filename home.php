<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>V8 Auto Mobile </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #1f2a36;    
            --primary-gold: #c5a059;
            --accent: ;     
            --bg-light: #f4f6f8;   
            --white: #ffffff;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg-light);
            color: #333;
            overflow-x: hidden;
        }

       
        body::before {
            content: "";
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 600px;
            height: 600px;
            z-index: -1;
            background: url("logoshowrum.png") center/contain no-repeat;
            opacity: 0.05;
            filter: grayscale(100%);
            pointer-events: none;
        }

        
        .navbar-glass {
            position: fixed;
            top: 25px;
            left: 50%;
            transform: translateX(-50%);
            width: 85%;
            z-index: 999;
            background: rgba(31, 42, 54, 0.9); 
            backdrop-filter: blur(10px);
            border-radius: 50px;
            padding: 12px 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .nav-links a {
            color: #cfd8dc;
            font-weight: 500;
            text-decoration: none;
            margin: 0 15px;
            transition: 0.3s;
            font-size: 14px;
        }
        
        .nav-links a:hover {
            color: var(--accent);
        }

        .nav-brand img {
            height: 35px;
        }

      
        .hero-slider {
            height: 100vh;
            position: relative;
            background: var(--primary);
        }
        
        .slide {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            opacity: 0;
            transition: opacity 1.5s ease-in-out;
        }
        
        .slide.active { opacity: 1; }
        
        .slide::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(31,42,54,0.7), rgba(31,42,54,0.9));
        }

        .hero-content {
            position: relative;
            z-index: 2;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: #fff;
        }

        .hero-title {
            font-size: 4rem;
            font-weight: 800;
            letter-spacing: -1px;
            margin-bottom: 10px;
        }
        
        .hero-title span {
            color: var(--accent);
        }

        .hero-subtitle {
            font-size: 1.2rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            opacity: 0.8;
            margin-bottom: 30px;
        }

      
        .features-section { padding: 100px 0; position: relative; }

        .feature-card {
            background: var(--white);
            border-radius: 20px;
            padding: 50px 30px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            transition: 0.4s;
            border: 1px solid rgba(0,0,0,0.02);
        }

        .feature-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 20px 40px rgba(79, 195, 247, 0.15);
        }

        .feature-icon {
            font-size: 3rem;
            color: var(--primary);
            margin-bottom: 25px;
            transition: 0.3s;
        }
        
        .feature-card:hover .feature-icon {
            color: var(--accent);
        }

       
        .stats-section {
            padding: 80px 0;
            background: var(--primary);
            color: var(--white);
        }

        .stat-box {
            padding: 20px;
        }

        .stat-number {
            font-size: 3.5rem;
            font-weight: 700;
            color: var(--accent);
            display: block;
        }

       
        .cta-section { padding: 100px 0; text-align: center; }

        .btn-cta {
            padding: 15px 50px;
            border-radius: 50px;
            background: var(--primary);
            color: var(--white);
            font-weight: 600;
            border: none;
            transition: 0.3s;
            box-shadow: 0 10px 20px rgba(31, 42, 54, 0.2);
        }

        .btn-cta:hover {
            background: var(--accent);
            color: var(--primary);
            transform: scale(1.05);
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .hero-box { animation: fadeUp 1s ease-out; }

        @media (max-width: 768px) {
            .hero-title { font-size: 2.5rem; }
            .navbar-glass { width: 95%; padding: 12px 20px; }
            .nav-links a { margin: 0 8px; font-size: 12px; }
        }
    </style>
</head>

<body>

<div class="navbar-glass">
    <div class="nav-brand">
        <img src="logoshowrum.png" alt="Logo">
    </div>
    <div class="nav-links">
        <a href="#">Home</a>
        <a href="shorum.php">Showroom</a>
        <a href="login_pro.php">Login </a>
    </div>
</div>

<section class="hero-slider">
    <div class="slide active" style="background-image:url('beckgroundhome.jpg')"></div>
    <div class="slide" style="background-image:url('fotohome.jpg')"></div>
    
    <div class="hero-content">
        <div class="hero-box">
            <p class="hero-subtitle">Premium Glass</p>
            <h1 class="hero-title">V8 AUTO <span>MOBILE</span></h1>
            <p class="opacity-75 mb-4">Solusi Terpercaya untuk Mobil Impian Berkelas & Berkualitas</p>
            <a href="shorum.php" class="btn-cta text-decoration-none">Kunjungi Showrum</a>
        </div>
    </div>
</section>

<section class="features-section">
    <div class="container">
        <div class="text-center mb-5">
            <h6 class="text-uppercase fw-bold text-info">Why Us</h6>
            <h2 class="fw-bold text-dark">Layanan Profesional Kami</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card">
                    <i class="fas fa-shield-check feature-icon"></i>
                    <h5 class="fw-bold">Garansi & Legalitas</h5>
                    <p class="text-muted small">Setiap unit melewati 150+ titik inspeksi dan kelengkapan surat terjamin 100%.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <i class="fas fa-tags feature-icon"></i>
                    <h5 class="fw-bold">Harga Transparan</h5>
                    <p class="text-muted small">Harga kompetitif tanpa biaya tersembunyi. Kondisi unit jujur apa adanya.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <i class="fas fa-user-tie feature-icon"></i>
                    <h5 class="fw-bold">Konsultan Ahli</h5>
                    <p class="text-muted small">Tim sales kami siap membantu memberikan rekomendasi mobil sesuai kebutuhan Anda.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="stats-section">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-md-3">
                <div class="stat-box">
                    <span class="stat-number">500+</span>
                    <small class="opacity-50 text-uppercase">Mobil Terjual</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-box">
                    <span class="stat-number">10+</span>
                    <small class="opacity-50 text-uppercase">Tahun Berdiri</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-box">
                    <span class="stat-number">1k+</span>
                    <small class="opacity-50 text-uppercase">Happy Clients</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-box">
                    <span class="stat-number">50+</span>
                    <small class="opacity-50 text-uppercase">Koleksi Merk</small>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container">
        <h2 class="fw-bold mb-3">Siap Membawa Pulang Mobil Impian?</h2>
        <p class="text-muted mb-5">Konsultasikan kebutuhan otomotif Anda bersama V8 Auto Mobile sekarang juga.</p>
        <a href="https://wa.me/yournumber" class="btn-cta text-decoration-none">
            <i class="fab fa-whatsapp me-2"></i>Hubungi Sales Kami
        </a>
    </div>
</section>

<script>
   
    let slides = document.querySelectorAll('.slide');
    let index = 0;
    setInterval(() => {
        slides[index].classList.remove('active');
        index = (index + 1) % slides.length;
        slides[index].classList.add('active');
    }, 5000);
</script>

</body>
</html>