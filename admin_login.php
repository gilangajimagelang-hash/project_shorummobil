<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Query mengambil data pengguna tanpa aksi edit/hapus
$query = "SELECT id, username, email, created_at FROM users ORDER BY id DESC";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pengguna | V8 Auto Mobile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --primary: #1f2a36;    /* Navy Deep */
            --accent: #4fc3f7;     /* Sky Blue */
            --bg: #f4f6f8;
            --white: #ffffff;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--bg);
            margin: 0;
            padding: 40px;
            color: #333;
            min-height: 100vh;
        }

        /* === WATERMARK LOGO FULL ELEGAN === */
        body::before {
            content: "";
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 600px;
            height: 600px;
            z-index: 0;
            background: url("logoshowrum.png") center/contain no-repeat;
            opacity: 0.06;
            filter: grayscale(100%);
            pointer-events: none;
        }

        .container {
            position: relative;
            z-index: 1;
            max-width: 1100px;
            margin: auto;
        }

        /* TOMBOL KEMBALI */
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 25px;
            text-decoration: none;
            background: var(--primary);
            color: white;
            padding: 12px 25px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .btn-back:hover {
            background: var(--accent);
            color: var(--primary);
            transform: translateX(-5px);
        }

        /* KARTU TABEL */
        .card {
            background: var(--white);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.05);
            border: 1px solid rgba(0,0,0,0.05);
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 30px;
            border-bottom: 2px solid var(--bg);
            padding-bottom: 20px;
        }

        h2 {
            color: var(--primary);
            margin: 0;
            font-size: 26px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        h2 i { color: var(--accent); font-size: 30px; }

        /* TABEL DESIGN */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            background: #f8f9fa;
            color: var(--primary);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 13px;
            letter-spacing: 1.5px;
            padding: 18px 15px;
            text-align: left;
            border-bottom: 3px solid var(--bg);
        }

        td {
            padding: 18px 15px;
            border-bottom: 1px solid #f0f0f0;
            font-size: 15px;
            color: #444;
        }

        tr:hover td {
            background: rgba(79, 195, 247, 0.05);
            color: #000;
        }

        .user-name {
            font-weight: 600;
            color: var(--primary);
        }

        .email-text {
            color: #5d6d7e;
            font-style: italic;
        }

        .date-badge {
            display: inline-block;
            background: #eef2f7;
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 13px;
            color: var(--primary);
        }

        /* Status Aktif */
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #d4edda;
            color: #155724;
            padding: 6px 15px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .status-pill::before {
            content: "";
            width: 8px;
            height: 8px;
            background: #28a745;
            border-radius: 50%;
        }
    </style>
</head>
<body>

<div class="container">
    <a href="dashboard.php" class="btn-back">
        <i class="fa fa-arrow-left"></i> Kembali ke Dashboard Admin
    </a>

    <div class="card">
        <div class="card-header">
            <h2><i class="fa fa-users-cog"></i> Daftar Pengguna Sistem</h2>
            <span style="color: #95a5a6; font-size: 14px;">Total: <?= mysqli_num_rows($result) ?> User</span>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 80px;">ID</th>
                    <th>Nama Pengguna</th>
                    <th>Alamat Email</th>
                    <th>Tanggal Terdaftar</th>
                    <th>Status Akses</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if ($result && mysqli_num_rows($result) > 0) {
                $no = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    $originalDate = $row['created_at'];
                    $newDate = date("d M Y", strtotime($originalDate));
                    
                    echo "<tr>
                            <td><span style='color:#bdc3c7; font-weight:bold;'>#{$no}</span></td>
                            <td class='user-name'>".htmlspecialchars($row['username'])."</td>
                            <td class='email-text'>".htmlspecialchars($row['email'])."</td>
                            <td><span class='date-badge'>{$newDate}</span></td>
                            <td><span class='status-pill'>Aktif</span></td>
                          </tr>";
                    $no++;
                }
            } else {
                echo "<tr><td colspan='5' style='text-align:center; padding: 50px; color: #999;'>Belum ada data pengguna.</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>