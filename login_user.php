<?php
session_start();
require "koneksi.php";

$mobil_id = $_GET['mobil_id'] ?? 0;
$error = "";


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = $_POST['username'];
    $password = $_POST['password'];
    $mobil_id = $_POST['mobil_id'];

    $stmt = $koneksi->prepare("SELECT * FROM pengguna WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $row = $res->fetch_assoc();

        if (password_verify($password, $row['password'])) {

            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username_user'] = $row['username'];

           
            header("Location: shorum.php?mobil_id=" . $mobil_id);
            exit;

        } else {
            $error = "Password salah!";
        }

    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5" style="max-width: 450px;">
    <div class="card shadow">
        <div class="card-body">
            <h3 class="text-center mb-3">Login User</h3>

            <div class="alert alert-warning">
                Silahkan Login.
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST">
                <input type="hidden" name="mobil_id" value="<?= htmlspecialchars($mobil_id) ?>">

                <div class="mb-3">
                    <label>Username</label>
                    <input name="username" class="form-control" required autofocus>
                </div>

                <div class="mb-3">
                    <label>Password</label>
                    <input name="password" type="password" class="form-control" required>
                </div>

                <button class="btn btn-primary w-100">Login</button>

                <p class="text-center mt-3">
                    Belum punya akun?
                    <a href="register_user.php?mobil_id=<?= htmlspecialchars($mobil_id) ?>">Daftar di sini</a>
                </p>
            </form>

        </div>
    </div>
</div>

</body>
</html>
