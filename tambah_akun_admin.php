<?php
session_start();


if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login_pro.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tambah Admin</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="hold-transition login-page">

<div class="login-box">
  <div class="login-logo">
    <b>Tambah</b> Admin
  </div>

  <div class="card">
    <div class="card-body login-card-body">

      <form method="post" action="tambah_admin_proses.php">

        <div class="input-group mb-3">
          <input type="text" name="username" class="form-control" placeholder="Username Admin" required>
        </div>

        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email" required>
        </div>

        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>

        <button type="submit" class="btn btn-primary btn-block">
          Simpan Admin
        </button>

        <a href="dashboard.php" class="btn btn-secondary btn-block mt-2">
          Kembali
        </a>

      </form>

    </div>
  </div>
</div>

</body>
</html>
