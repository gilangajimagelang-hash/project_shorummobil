<?php
include 'koneksi.php';

if (!$koneksi) {
    echo "<script>
    Swal.fire({
      icon: 'error',
      title: 'Koneksi Database Gagal',
      text: 'Periksa konfigurasi koneksi Anda!',
      confirmButtonColor: '#3b82f6'
    }).then(() => window.location = 'register_admin.php');
    </script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Proses Registrasi</title>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.all.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.css">
</head>
<body style="background:#0d1117;color:white;font-family:Poppins, sans-serif;display:flex;justify-content:center;align-items:center;height:100vh;">

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($username) || empty($password)) {
        echo "<script>
        Swal.fire({
          icon: 'warning',
          title: 'Oops...',
          text: 'Semua kolom wajib diisi!',
          confirmButtonColor: '#3b82f6'
        }).then(() => window.location = 'register_admin.php');
        </script>";
        exit;
    }

    $cek = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username' OR email='$email'");

 
    if (!$cek) {
        echo "<script>
        Swal.fire({
          icon: 'error',
          title: 'Query Gagal',
          text: 'Terjadi kesalahan: " . mysqli_error($koneksi) . "',
          confirmButtonColor: '#3b82f6'
        }).then(() => window.location = 'register_admin.php');
        </script>";
        exit;
    }

    if (mysqli_num_rows($cek) > 0) {
        echo "<script>
        Swal.fire({
          icon: 'error',
          title: 'Gagal Registrasi',
          text: 'Username atau Email sudah terdaftar!',
          confirmButtonColor: '#3b82f6'
        }).then(() => window.location = 'register_admin.php');
        </script>";
        exit;
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $query = "INSERT INTO users (email, username, password) VALUES ('$email', '$username', '$password_hash')";
    if (mysqli_query($koneksi, $query)) {
        echo "<script>
        Swal.fire({
          icon: 'success',
          title: 'Registrasi Berhasil!',
          text: 'Silakan login untuk melanjutkan.',
          showConfirmButton: false,
          timer: 2000
        });
        setTimeout(() => { window.location = 'login.php'; }, 2000);
        </script>";
    } else {
        echo "<script>
        Swal.fire({
          icon: 'error',
          title: 'Terjadi Kesalahan',
          text: 'Data gagal disimpan, coba lagi. (" . mysqli_error($koneksi) . ")',
          confirmButtonColor: '#3b82f6'
        }).then(() => window.location = 'register_admin.php');
        </script>";
    }
} else {
    echo "<script>window.location='register_admin.php';</script>";
}
?>
</body>
</html>
