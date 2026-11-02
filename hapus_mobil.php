<?php
session_start();
require 'koneksi.php';


if(!isset($_SESSION['username'])){
    header('Location: login.php');
    exit;
}


$id = $_GET['id'] ?? 0;


$stmt = $koneksi->prepare("SELECT foto FROM mobil WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$mobil = $result->fetch_assoc();

if($mobil) {
    
    if($mobil['foto'] != 'default_car.png' && file_exists('uploads/'.$mobil['foto'])) {
        unlink('uploads/'.$mobil['foto']);
    }

    
    $stmtDel = $koneksi->prepare("DELETE FROM mobil WHERE id=?");
    $stmtDel->bind_param("i", $id);
    $stmtDel->execute();
}


header("Location: mobil_list.php");
exit;
