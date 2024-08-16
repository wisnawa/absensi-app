<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../../auth/login.php?pesan=belum_login");
} else if ($_SESSION['role'] != 'admin') {
    header("Location: ../../auth/login.php?pesan=tolak_akses");
}

$judul = 'Detail Pegawai';

include('../layout/header.php');
require_once('../../config.php');

$id = $_GET['id'];
$result = mysqli_query($connection, "SELECT users.id_pegawai, users.username, users.password, users.status, users.role, pegawai.* FROM users JOIN pegawai ON users.id_pegawai = pegawai.id WHERE pegawai.id = $id");
?>
<?php while ($pegawai = mysqli_fetch_array($result)) : ?>
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <table class="table">
                                <tr>
                                    <th>Nama</th>
                                    <td>:<?= $pegawai['nama']; ?></td>
                                </tr>
                                <tr>
                                    <th>Jenis Kelamin</th>
                                    <td>:<?= $pegawai['jenis_kelamin']; ?></td>
                                </tr>
                                <tr>
                                    <th>Alamat</th>
                                    <td>:<?= $pegawai['alamat']; ?></td>
                                </tr>
                                <tr>
                                    <th>Nomor Handphone</th>
                                    <td>:<?= $pegawai['no_handphone']; ?></td>
                                </tr>
                                <tr>
                                    <th>Jabatan</th>
                                    <td>:<?= $pegawai['jabatan']; ?></td>
                                </tr>
                                <tr>
                                    <th>User Name</th>
                                    <td>:<?= $pegawai['username']; ?></td>
                                </tr>
                                <tr>
                                    <th>Role</th>
                                    <td>:<?= $pegawai['role']; ?></td>
                                </tr>
                                <tr>
                                    <th>Lokasi Presensi</th>
                                    <td>:<?= $pegawai['lokasi_presensi']; ?></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>:<?= $pegawai['status']; ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <img style="width: 350px; border-radius: 10px;" src="<?= base_url('assets/img/foto_pegawai/' . $pegawai['foto']); ?>" alt="">
                </div>
            </div>
        </div>
    </div>
<?php endwhile; ?>
<?php include('.././layout/footer.php'); ?>