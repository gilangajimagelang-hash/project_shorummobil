<?php
require "koneksi.php";

$judul = $_POST['judul'];
$deskripsi = $_POST['deskripsi'];
$urutan = $_POST['urutan'];
$status = $_POST['status'];

$foto = $_FILES['foto']['name'];
$tmp = $_FILES['foto']['tmp_name'];


move_uploaded_file($tmp, "uploads/banner/" . $foto);

$koneksi->query("
    INSERT INTO banner_iklan (foto, judul, deskripsi, status, urutan)
    VALUES ('$foto', '$judul', '$deskripsi', '$status', '$urutan')
");

header("Location: banner_index.php");
exit;
