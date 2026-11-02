<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Data Usaha</title>
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

    .login-card {
      width: 360px;
      border-radius: 8px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      background: #ffffff;
    }

    .login-logo a {
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

    .btn-login {
      width: 100%;
      background-color: #007bff;
      border: none;
      color: #fff;
      font-weight: 600;
      padding: 10px;
      margin-top: 10px;
    }

    .btn-login:hover {
      background-color: #0056b3;
    }

    .btn-register {
      width: 100%;
      margin-top: 10px;
      background-color: #6c757d;
      color: #fff;
      font-weight: 600;
      padding: 10px;
      border: none;
    }

    .btn-register:hover {
      background-color: #495057;
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
    .btn-outline-primary {
  color: #007bff;           
  background-color: #ffffff; 
  border: 1px solid #007bff; 
  padding: 8px 20px;
  font-size: 14px;
  font-weight: 600;
  border-radius: 5px;
  text-decoration: none;
  transition: 0.3s;
}

.btn-outline-primary:hover {
  background-color: #007bff; 
  color: #ffffff;            
}

  </style>
</head>
<body>
  <div class="login-box">
    <div class="login-logo">
      <a href="#"><b>LOGIN</b>admin</a>
    </div>

    <div class="card login-card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">Masuk untuk mengakses sistem</p>

        <form action="login_proses.php" method="post">
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
          <button type="submit" class="btn btn-login">Masuk</button>
        </form>

        <div class="auth-footer mt-3 text-center">
  Belum punya akun?
 <a href="register_admin.php" class="btn btn-outline-primary btn-sm ml-2">Daftar Sekarang</a>

</div>

      </div>
    </div>
  </div>
</body>
</html>
