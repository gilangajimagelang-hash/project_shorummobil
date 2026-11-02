<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrasi - Data Usaha</title>
  <script src="https://kit.fontawesome.com/a2e0ad4b4b.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

  <style>
    body {
      background: #f9fafb; 
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: "Source Sans Pro", sans-serif;
    }

    .register-card {
      width: 360px;
      border-radius: 8px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      background: #ffffff;
    }

    .register-logo a {
      color: #007bff;
      font-weight: 700;
      font-size: 28px;
      text-align: center;
      display: block;
      margin-bottom: 15px;
    }

    .input-group-text {
      background-color: #f1f5f9;
      border-right: 0;
      color: #007bff;
    }

    .form-control {
      border-left: 0;
    }

    .btn-register {
      width: 100%;
      background-color: #007bff;
      border: none;
      color: #fff;
      font-weight: 600;
      padding: 10px;
      margin-top: 10px;
    }

    .btn-register:hover {
      background-color: #0056b3;
    }

    .auth-footer {
      margin-top: 15px;
      text-align: center;
      font-size: 14px;
      color: #6c757d;
    }

    .auth-footer a {
      text-decoration: none;
      font-weight: 600;
      color: #007bff;
    }

    .auth-footer a:hover {
      color: #0056b3;
    }
  </style>
</head>
<body>
  <div class="register-box">
    <div class="register-logo">
      <a href="#"><b>REGISTER</b>admin</a>
    </div>

    <div class="card register-card">
      <div class="card-body register-card-body">
        <p class="login-box-msg">Buat akun baru untuk mengelola sistem</p>

        <form action="registrasi_proses.php" method="post">
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-envelope"></i></span>
            </div>
            <input type="email" name="email" class="form-control" placeholder="Email" required>
          </div>
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-user"></i></span>
            </div>
            <input type="text" name="username" class="form-control" placeholder="Username" required>
          </div>
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-lock"></i></span>
            </div>
            <input type="password" name="password" class="form-control" placeholder="Password" required>
          </div>
          <button type="submit" class="btn-register">Daftar</button>
        </form>

        <div class="auth-footer">
          Sudah punya akun? <a href="login.php">Login di sini</a>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
