<?php
require "koneksi.php";

$id        = $_POST['id'];
$judul     = $_POST['judul'];
$urutan    = $_POST['urutan'];
$status    = $_POST['status'];

$updateOK = false; 


if (!empty($_FILES['foto']['name'])) {

    $foto = $_FILES['foto']['name'];
    $tmp  = $_FILES['foto']['tmp_name'];


    if (move_uploaded_file($tmp, "uploads/banner/" . $foto)) {

        $updateOK = $koneksi->query("
            UPDATE banner_iklan SET 
                foto='$foto',
                judul='$judul',
                status='$status',
                urutan='$urutan'
            WHERE id='$id'
        ");
    }

} else {

   
    $updateOK = $koneksi->query("
        UPDATE banner_iklan SET 
            judul='$judul',
            status='$status',
            urutan='$urutan'
        WHERE id='$id'
    ");
}

?>
<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php if ($updateOK) : ?>

<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil!',
    text: 'Banner berhasil diperbarui',
    showConfirmButton: false,
    timer: 1700
});
setTimeout(() => {
    window.location = 'banner_index.php';
}, 1700);
</script>

<?php else : ?>

<script>
Swal.fire({
    icon: 'error',
    title: 'Gagal!',
    text: 'Proses edit gagal, coba lagi!',
    confirmButtonColor: '#3b82f6'
}).then(() => {
    window.location = 'banner_edit.php?id=<?= $id ?>';
});
</script>

<?php endif; ?>
</body>
</html>
