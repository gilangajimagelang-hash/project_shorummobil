<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: register_pro.php");
    exit;
}

$username = trim($_POST['username'] ?? '');
$email    = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');
$role     = 'user';


if ($username === '' || $email === '' || $password === '') {
    $_SESSION['flash'] = [
        'icon'  => 'warning',
        'title' => 'Oops...',
        'text'  => 'Semua field wajib diisi!'
    ];
    header("Location: register_pro.php");
    exit;
}


$cek = $koneksi->prepare(
    "SELECT id FROM users WHERE username=? OR email=? LIMIT 1"
);
$cek->bind_param("ss", $username, $email);
$cek->execute();
$cek->store_result();

if ($cek->num_rows > 0) {
    $_SESSION['flash'] = [
        'icon'  => 'error',
        'title' => 'Gagal',
        'text'  => 'Username atau email sudah digunakan!'
    ];
    header("Location: register_pro.php");
    exit;
}


$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $koneksi->prepare(
    "INSERT INTO users (username,email,password,role)
     VALUES (?,?,?,?)"
);
$stmt->bind_param("ssss", $username, $email, $hash, $role);

if ($stmt->execute()) {

    $_SESSION['flash'] = [
        'icon'  => 'success',
        'title' => 'Registrasi Berhasil!',
        'text'  => 'Akun berhasil dibuat, silakan login 👋'
    ];

    header("Location: login_pro.php");
    exit;

} else {
    $_SESSION['flash'] = [
        'icon'  => 'error',
        'title' => 'Error',
        'text'  => 'Registrasi gagal, coba lagi!'
    ];
    header("Location: register_pro.php");
    exit;
}
