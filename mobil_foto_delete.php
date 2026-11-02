<?php
require 'koneksi.php';

$id = $_GET['id'];
$mobil_id = $_GET['mobil_id'];

$q = $koneksi->query("SELECT filename FROM foto_mobil WHERE id=$id");
$f = $q->fetch_assoc();

if ($f && file_exists("uploads/" . $f['filename'])) {
    unlink("uploads/" . $f['filename']);
}

$koneksi->query("DELETE FROM foto_mobil WHERE id=$id");

header("Location: mobil_edit.php?id=$mobil_id");
exit;
