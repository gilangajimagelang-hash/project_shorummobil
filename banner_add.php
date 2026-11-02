<!DOCTYPE html>
<html>
<head>
    <title>Tambah Banner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-4">
    <h3>Tambah Banner</h3>

    <form action="banner_add_proses.php" method="POST" enctype="multipart/form-data">

        <label>texs banner</label>
        <input type="text" name="judul" class="form-control" required>

        <label>Foto Banner</label>
        <input type="file" name="foto" class="form-control" required>

        <label>Urutan Slide</label>
        <input type="number" name="urutan" class="form-control" value="0">

        <label>Status</label>
        <select name="status" class="form-control">
            <option value="aktif">Aktif</option>
            <option value="nonaktif">Nonaktif</option>
        </select>

        <button class="btn btn-success mt-3">Simpan</button>
    </form>

</div>

</body>
</html>
