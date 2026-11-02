<?php
session_start();
require "koneksi.php";


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$mobil_id = intval($_POST['mobil_id']);
$pesan = $_POST['pesan'];


$stmt = $koneksi->prepare("
    INSERT INTO pesan_sales (user_id, mobil_id, pesan, status)
    VALUES (?, ?, ?, 'baru')
");
$stmt->bind_param("iis", $user_id, $mobil_id, $pesan);
$stmt->execute();
$stmt->close();


header("Location: sukses.php");
exit;
