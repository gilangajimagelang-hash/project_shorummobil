<?php
require "koneksi.php";

if (!isset($_GET['id'])) {
    header("Location: banner_index.php");
    exit;
}

$id = intval($_GET['id']);

/
$q = $koneksi->query("SELECT foto FROM banner_iklan WHERE id='$id'");
$data = $q->fetch_assoc();

if (!$data) {
    header("Location: banner_index.php");
    exit;
}

$path = "uploads/banner/" . $data['foto'];
if (file_exists($path) && !empty($data['foto'])) {
    unlink($path);
}


$koneksi->query("DELETE FROM banner_iklan WHERE id='$id'");


header("Location: banner_index.php?msg=deleted");
exit;
?>
