<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = trim($_POST['username']);
$email    = trim($_POST['email']);
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$role     = 'admin';


$cek = $koneksi->prepare("SELECT id FROM users WHERE username=?");
$cek->bind_param("s", $username);
$cek->execute();
$cek->store_result();

if ($cek->num_rows > 0) {
    echo "<script>
        alert('Username sudah digunakan!');
        window.location='tambah_akun_admin.php';
    </script>";
    exit;
}

$stmt = $koneksi->prepare("
    INSERT INTO users (username, email, password, role)
    VALUES (?,?,?,?)
");
$stmt->bind_param("ssss", $username, $email, $password, $role);

if ($stmt->execute()) {
    echo "<script>
        alert('Admin baru berhasil ditambahkan!');
        window.location='dashboard.php';
    </script>";
} else {
    echo "<script>
        alert('Gagal menambahkan admin');
        window.location='tambah_akun_admin.php';
    </script>";
}
