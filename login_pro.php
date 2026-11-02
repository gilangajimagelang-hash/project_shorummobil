<?php
session_start();


if (
    isset($_SESSION['username'], $_SESSION['role']) &&
    !isset($_SESSION['flash']) &&
    !isset($_SESSION['redirect_after_login'])
) {
    $redirect = ($_SESSION['role'] === 'admin')
        ? 'dashboard.php'
        : 'shorum.php';
    header("Location: $redirect");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login | Admin-Pro</title>


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
body{
    background:linear-gradient(135deg,#f8fafc,#eef2ff);
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    font-family:"Source Sans Pro",sans-serif;
}
.login-box{width:380px;}
.login-logo a{
    color:#1f2937;
    font-weight:700;
}
.card{
    border-radius:14px;
    box-shadow:0 10px 30px rgba(0,0,0,.12);
}
.btn-login{
    background:#2563eb;
    border:none;
    color:#fff;
    font-weight:600;
    padding:10px;
}
.btn-login:hover{background:#1d4ed8;}
.input-group-text{background:#f1f5f9;}
</style>
</head>

<body>

<div class="login-box">
    <div class="login-logo mb-3">
        <a href="#"><b>LOGIN</b></a>
    </div>

    <div class="card">
        <div class="card-body login-card-body">

            <p class="login-box-msg text-muted">
                Masuk untuk mengakses sistem
            </p>

            <form action="login_proses_pro.php" method="post" autocomplete="off">

                <div class="input-group mb-3">
                    <input type="text" name="username" class="form-control"
                           placeholder="Username" required autofocus>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control"
                           placeholder="Password" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-login btn-block">
                    <i class="fas fa-sign-in-alt mr-1"></i> Masuk
                </button>
            </form>

            <p class="mt-3 mb-0 text-center small">
                Belum punya akun?
                <a href="register_pro.php" class="text-primary fw-bold">
                    Daftar User
                </a>
            </p>

        </div>
    </div>
</div>


<?php if (isset($_SESSION['flash']) && isset($_SESSION['redirect_after_login'])): ?>
<script>
Swal.fire({
    icon: '<?= $_SESSION['flash']['icon']; ?>',
    title: '<?= $_SESSION['flash']['title']; ?>',
    text: '<?= $_SESSION['flash']['text']; ?>',
    showConfirmButton: false,
    timer: 1500,
    timerProgressBar: true
});

setTimeout(function () {
    window.location.href = "<?= $_SESSION['redirect_after_login']; ?>";
}, 1500);
</script>
<?php
unset($_SESSION['flash'], $_SESSION['redirect_after_login']);
endif;
?>


<?php if (isset($_SESSION['flash'])): ?>
<script>
Swal.fire({
    icon: '<?= $_SESSION['flash']['icon']; ?>',
    title: '<?= $_SESSION['flash']['title']; ?>',
    text: '<?= $_SESSION['flash']['text']; ?>',
    confirmButtonColor: '#2563eb'
});
</script>
<?php unset($_SESSION['flash']); endif; ?>

</body>
</html>
