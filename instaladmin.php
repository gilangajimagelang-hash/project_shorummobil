<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = $_POST['password'];
    $role     = $_POST['role'];

    
    $hash = password_hash($password, PASSWORD_DEFAULT);

    
    $token = bin2hex(random_bytes(16));

    
    $foto_admin = 'default.png';
    if (!empty($_FILES['foto']['name'])) {
        $namaFile = time() . "_" . $_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], "uploads/" . $namaFile);
        $foto_admin = $namaFile;
    }

    
    $stmt = $koneksi->prepare("
        INSERT INTO users (username, email, password, nama_admin, foto_admin, role, status_aktif, token_verifikasi) 
        VALUES (?, ?, ?, ?, ?, ?, 0, ?)
    ");

    $nama_admin = $username; 
    $stmt->bind_param("sssssss", $username, $email, $hash, $nama_admin, $foto_admin, $role, $token);

    if ($stmt->execute()) {
        echo '<div class="alert alert-success">
                Akun berhasil dibuat! Silakan verifikasi email: <b>' . $email . '</b>
              </div>';
    } else {
        echo '<div class="alert alert-danger">Gagal membuat akun: ' . $stmt->error . '</div>';
    }
}
?>


<form method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label class="form-label">Username</label>
        <input name="username" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Foto Admin</label>
        <input type="file" name="foto" class="form-control" accept="image/*">
    </div>

    <div class="mb-3">
        <label class="form-label">Role</label>
        <select name="role" class="form-control">
            <option value="admin">Admin Biasa</option>
            <option value="superadmin">Super Admin</option>
        </select>
    </div>

    <button class="btn btn-success w-100">Daftar</button>

    <div class="mt-3 text-center">
        <a href="login.php">Sudah punya akun? Login</a>
    </div>
</form>
