<?php
ob_start();
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../../auth/login.php?pesan=belum_login");
} else if ($_SESSION['role'] != 'admin') {
    header("Location: ../../auth/login.php?pesan=tolak_akses");
}

$judul = 'Detail Ketidakhadiran';

include('../layout/header.php');
require_once('../../config.php');

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $status_pengajuan = $_POST['status_pengajuan'];

    $result = mysqli_query($connection, "UPDATE `ketidakhadiran` SET `status_pengajuan` = '$status_pengajuan' WHERE `id` = $id");

    $_SESSION['berhasil'] = 'Status pengajuan berhasil di Update';
    header('Location: ketidakhadiran.php');
    exit;
}

$id = $_GET['id'];
$result = mysqli_query($connection, "SELECT * FROM `ketidakhadiran` WHERE `id` = $id");

// $id = $_GET['id'];
// $result = mysqli_query($connection, "SELECT * FROM `ketidakhadiran` WHERE `id` = $id");

// untuk mendapatkan query data di table pegawai
$pegawai = mysqli_query($connection, "SELECT ketidakhadiran.id_pegawai, pegawai.id, pegawai.nama, pegawai.jabatan FROM ketidakhadiran JOIN pegawai ON ketidakhadiran.id_pegawai = pegawai.id");

while ($data = mysqli_fetch_array($result)) {
    $keterangan = $data['keterangan'];
    $tanggal = $data['tanggal'];
    $status_pengajuan = $data['status_pengajuan'];
}
while ($data_pegawai = mysqli_fetch_array($pegawai)) {
    $nama = $data_pegawai['nama'];
    $jabatan = $data_pegawai['jabatan'];
}
// echo $nama;
// var_dump(mysqli_fetch_array($pegawai));
// die();
?>
<div class="page-body">
    <div class="container-xl">
        <div class="card col-md-6">
            <div class="card-body">
                <form action="" method="post">
                    <div class="mb-3">
                        <label>Nama</label>
                        <input type="text" name="nama" class="form-control" value="<?= $nama; ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label>Jabatan</label>
                        <input type="text" name="jabatan" class="form-control" value="<?= $jabatan; ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label>Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="<?= $tanggal; ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="">Keterangan</label>
                        <input type="text" name="keterangan" id="" class="form-control" value="<?= $keterangan; ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="status_pengajuan">Status Pengajuan</label>
                        <select name="status_pengajuan" id="status_pengajuan" class="form-control">
                            <option <?php if ($status_pengajuan == 'PENDING') {
                                        echo 'selected';
                                    } ?> value="PENDING">PENDING</option>
                            <option <?php if ($status_pengajuan == 'REJECTED') {
                                        echo 'selected';
                                    } ?> value="REJECTED">REJECTED</option>
                            <option <?php if ($status_pengajuan == 'APPROVED') {
                                        echo 'selected';
                                    } ?> value="APPROVED">APPROVED</option>
                        </select>
                    </div>
                    <input type="hidden" name="id" value="<?= $id; ?>">
                    <button type="submit" class="btn btn-primary" name="update">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include('.././layout/footer.php'); ?>