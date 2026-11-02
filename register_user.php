<?php
session_start();
require "koneksi.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $mobil_id = $_POST['mobil_id'];

  
    $cek = $koneksi->prepare("SELECT * FROM pengguna WHERE username = ?");
    $cek->bind_param("s", $username);
    $cek->execute();
    $result = $cek->get_result();

    if ($result->num_rows > 0) {
        $error = "Username sudah terdaftar!";
    } else {
        
        $stmt = $koneksi->prepare("INSERT INTO pengguna (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();

        $success = "Registrasi berhasil! Silakan login.";

        header("refresh:2; url=login_user_from_hubungi_sales.php?mobil_id=$mobil_id");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Registrasi User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5" style="max-width:450px;">
    <div class="card shadow">
        <div class="card-body">

            <h3 class="text-center mb-3">Registrasi User</h3>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>

            <form method="POST">
                <input type="hidden" name="mobil_id" value="<?= $_GET['mobil_id'] ?? 0 ?>">

                <div class="mb-3">
                    <label>Username</label>
                    <input name="username" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Password</label>
                    <input name="password" type="password" class="form-control" required>
                </div>

                <button class="btn btn-success w-100">Daftar</button>

            </form>

            <p class="text-center mt-3">
                Sudah punya akun?  
                <a href="login_user_from_hubungi_sales.php?mobil_id=<?= $_GET['mobil_id'] ?? 0 ?>">Login</a>
            </p>

        </div>
    </div>
</div>

</body>
</html>
