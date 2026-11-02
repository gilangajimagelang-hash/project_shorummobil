<?php
session_start();
require "koneksi.php";

$mobil_id = $_GET['mobil_id'] ?? 0;


if (
    isset($_SESSION['user_id']) &&
    isset($_SESSION['role']) &&
    $_SESSION['role'] === 'user'
) {
    header("Location: shorum.php?mobil_id=".$mobil_id);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $mobil_id = $_POST['mobil_id'];

    $stmt = $koneksi->prepare("
        SELECT id, username, password, role
        FROM users
        WHERE username = ?
        LIMIT 1
    ");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $data = $res->fetch_assoc();

        if ($data['role'] !== 'user') {
            $_SESSION['swal'] = [
                'icon'=>'error',
                'title'=>'Akses Ditolak',
                'text'=>'Akun ini bukan akun user!'
            ];
        }
        elseif (password_verify($password, $data['password'])) {
            $_SESSION['user_id']  = $data['id'];
            $_SESSION['username'] = $data['username'];
            $_SESSION['role']     = $data['role'];

            $_SESSION['swal'] = [
                'icon'=>'success',
                'title'=>'Login Berhasil!',
                'text'=>"Selamat datang, {$data['username']} 👋",
                'redirect'=>"shorum.php?mobil_id=".$mobil_id
            ];
        } else {
            $_SESSION['swal'] = [
                'icon'=>'error',
                'title'=>'Login Gagal',
                'text'=>'Password salah!'
            ];
        }
    } else {
        $_SESSION['swal'] = [
            'icon'=>'error',
            'title'=>'Login Gagal',
            'text'=>'Username tidak ditemukan!'
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login User | V8 Automobile</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background: linear-gradient(135deg,#f8fafc,#eef2ff);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: "Source Sans Pro",sans-serif;
        }
        .login-box { width: 400px; }
        .login-logo a { color: #1f2937; font-weight: 700; }
        .card {
            border-radius: 14px;
            box-shadow: 0 10px 30px rgba(0,0,0,.12);
            border: none;
        }
        .btn-login {
            background: #2563eb;
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 10px;
        }
        .btn-login:hover { background: #1d4ed8; color: #fff; }
        .input-group-text { background: #f1f5f9; border-left: none; }
        .form-control { border-right: none; }
        .alert-warning {
            border-radius: 10px;
            border: none;
            background-color: #fff3cd;
            color: #856404;
        }
    </style>
</head>

<body>

<div class="login-box">
    <div class="login-logo mb-3">
        <a href="#"><b>LOGIN</b> USER</a>
    </div>

    <div class="card">
        <div class="card-body login-card-body">
            
            <div class="alert alert-warning small mb-3">
                <i class="fas fa-info-circle mr-1"></i>
                Untuk menghubungi sales dan menanyakan informasi mobil, silakan login terlebih dahulu.
            </div>

            <p class="login-box-msg text-muted small">Masuk untuk melanjutkan interaksi</p>

            <form method="POST" autocomplete="off">
                <input type="hidden" name="mobil_id" value="<?= htmlspecialchars($mobil_id) ?>">

                <div class="input-group mb-3">
                    <input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
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

            <p class="mt-4 mb-0 text-center small">
                Belum punya akun? 
                <a href="register_pro.php?mobil_id=<?= $mobil_id ?>" class="text-primary fw-bold">
                    Daftar di sini
                </a>
            </p>
        </div>
    </div>
</div>

<?php if(isset($_SESSION['swal'])): ?>
<script>
Swal.fire({
    icon: '<?= $_SESSION['swal']['icon'] ?>',
    title: '<?= $_SESSION['swal']['title'] ?>',
    text: '<?= $_SESSION['swal']['text'] ?>',
    showConfirmButton: <?= isset($_SESSION['swal']['redirect']) ? 'false' : 'true' ?>,
    timer: <?= isset($_SESSION['swal']['redirect']) ? 1500 : 'null' ?>,
    timerProgressBar: true
}).then(() => {
    <?php if(isset($_SESSION['swal']['redirect'])): ?>
        window.location = '<?= $_SESSION['swal']['redirect'] ?>';
    <?php endif; ?>
});
</script>
<?php unset($_SESSION['swal']); endif; ?>

</body>
</html>