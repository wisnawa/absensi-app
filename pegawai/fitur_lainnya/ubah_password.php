<?php
ob_start();
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../../auth/login.php?pesan=belum_login");
} else if ($_SESSION['role'] != 'pegawai') {
    header("Location: ../../auth/login.php?pesan=tolak_akses");
}

$judul = 'Ubah Password';

include('../layout/header.php');
require_once('../../config.php');

$id = $_SESSION['id'];
if (isset($_POST['update'])) {
    $password_baru = password_hash($_POST['password_baru'], PASSWORD_DEFAULT);
    $ulangi_password_baru = password_hash($_POST['ulangi_password_baru'], PASSWORD_DEFAULT);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (empty($_POST['password_baru'])) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'>&nbsp;</i>Password wajib diisi";
        }
        if (empty($_POST['ulangi_password_baru'])) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>&nbsp;Ulangi Password Baru wajib diisi";
        }
        if ($_POST['password_baru'] != $_POST['ulangi_password_baru']) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>&nbsp;Password tidak cocok!";
        }
        if (!empty($pesan_kesalahan)) {
            $_SESSION['validasi'] = implode('<br>', $pesan_kesalahan);
        } else {
            $pegawai = mysqli_query($connection, "UPDATE `users` SET `password` = '$password_baru' WHERE `id_pegawai` = $id");

            $_SESSION['berhasil'] = 'Password berhasil diubah!';
            header('Location: ../home/home.php');
            exit;
        }
    }
}
?>

<div class="page-body">
    <div class="container-xl">
        <form action="" method="post">
            <div class="card col-md-6">
                <div class="card-body">
                    <div class="mb-3">
                        <label for="password_baru">Password Baru</label>
                        <input type="password" name="password_baru" id="password_baru" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="ulangi_password_baru">Ulangi Password Baru</label>
                        <input type="password" name="ulangi_password_baru" id="ulangi_password_baru" class="form-control">
                    </div>
                    <input type="hidden" name="id" value="<?= $id; ?>">
                    <button type="submit" class="btn btn-primary" name="update">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php include('.././layout/footer.php'); ?>