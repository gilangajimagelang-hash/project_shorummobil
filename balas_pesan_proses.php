<?php
require 'koneksi.php';

$id = $_POST['id'];
$balasan = $_POST['balasan'];

$stmt = $koneksi->prepare("UPDATE pesan SET balasan = ? WHERE id = ?");
$stmt->bind_param("si", $balasan, $id);
$stmt->execute();
$stmt->close();

header("Location: pesan_masuk.php");
exit;
