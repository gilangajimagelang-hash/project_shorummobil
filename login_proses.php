<?php
session_start();
include 'koneksi.php';
ini_set('display_errors',1);
error_reporting(E_ALL);

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if(empty($username) || empty($password)){
        echo "<script>
        Swal.fire({icon:'warning',title:'Oops...',text:'Username dan password wajib diisi!',confirmButtonColor:'#3b82f6'})
        .then(()=>window.location='login_pro.php');
        </script>";
        exit;
    }

    $username_safe = mysqli_real_escape_string($koneksi, $username);
    $cek = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username_safe' LIMIT 1");
    if(!$cek) die("Query error: ".mysqli_error($koneksi));

    if(mysqli_num_rows($cek) === 0){
        echo "<script>
        Swal.fire({icon:'error',title:'Login Gagal',text:'Username tidak ditemukan!',confirmButtonColor:'#3b82f6'})
        .then(()=>window.location='login_pro.php');
        </script>";
        exit;
    }

    $data = mysqli_fetch_assoc($cek);

 
    $role = isset($data['role']) && !empty($data['role']) ? strtolower($data['role']) : 'user';

    if(password_verify($password, $data['password'])){
        $_SESSION['username'] = $data['username'];
        $_SESSION['role'] = $role;
        $_SESSION['user_id'] = $data['id'];

      
        $redirect = ($role === 'admin') ? 'dashboard.php' : 'shorum.php';

        echo "<script>
        Swal.fire({icon:'success',title:'Login Berhasil!',text:'Selamat datang, {$data['username']} 👋',showConfirmButton:false,timer:1500});
        setTimeout(()=>{window.location='$redirect'},1500);
        </script>";
    } else {
        echo "<script>
        Swal.fire({icon:'error',title:'Login Gagal',text:'Password salah!',confirmButtonColor:'#3b82f6'})
        .then(()=>window.location='login_pro.php');
        </script>";
    }
} else {
    header("Location: login_pro.php");
    exit;
}
?>
