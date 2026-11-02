<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require 'koneksi.php';


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login_pro.php");
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');


if ($username === '' || $password === '') {
    $_SESSION['flash'] = [
        'icon'  => 'warning',
        'title' => 'Oops...',
        'text'  => 'Username dan password wajib diisi!'
    ];
    header("Location: login_pro.php");
    exit;
}


$stmt = $koneksi->prepare(
    "SELECT id, username, password, role FROM users WHERE username=? LIMIT 1"
);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($id, $uname, $hash, $role);

if (!$stmt->fetch()) {
    $_SESSION['flash'] = [
        'icon'  => 'error',
        'title' => 'Login Gagal',
        'text'  => 'Username tidak ditemukan!'
    ];
    header("Location: login_pro.php");
    exit;
}


if (!password_verify($password, $hash)) {
    $_SESSION['flash'] = [
        'icon'  => 'error',
        'title' => 'Login Gagal',
        'text'  => 'Password salah!'
    ];
    header("Location: login_pro.php");
    exit;
}


$_SESSION['user_id']  = $id;
$_SESSION['username'] = $uname;
$_SESSION['role']     = $role;


$_SESSION['flash'] = [
    'icon'  => 'success',
    'title' => 'Login Berhasil!',
    'text'  => 'Selamat datang, '.$uname.' 👋'
];

$_SESSION['redirect_after_login'] =
    ($role === 'admin') ? 'dashboard.php' : 'shorum.php';


header("Location: login_pro.php");
exit;
