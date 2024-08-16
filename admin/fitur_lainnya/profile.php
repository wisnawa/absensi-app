<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../../auth/login.php?pesan=belum_login");
} else if ($_SESSION['role'] != 'admin') {
    header("Location: ../../auth/login.php?pesan=tolak_akses");
}

$judul = '';

include('../layout/header.php');
require_once('../../config.php');

$id = $_SESSION['id'];
$result = mysqli_query($connection, "SELECT users.id_pegawai, users.username, users.status, users.role, pegawai.* FROM users JOIN pegawai ON users.id_pegawai = pegawai.id WHERE pegawai.id = $id");

?>

<?php while ($pegawai = mysqli_fetch_array($result)) : ?>
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-center">
                                <img style="width: 50%; border-radius: 50%;" src="<?= base_url('assets/img/foto_pegawai/' . $pegawai['foto']); ?>" alt="" class="card-img-top img-thumbnail">
                            </div>
                            <table class="table text-start">
                                <tr>
                                    <th>Nama</th>
                                    <td>:&nbsp;<?= $pegawai['nama']; ?></td>
                                </tr>
                                <tr>
                                    <th>Jenis Kelamin</th>
                                    <td>:&nbsp;<?= $pegawai['jenis_kelamin']; ?></td>
                                </tr>
                                <tr>
                                    <th>Alamat</th>
                                    <td>:&nbsp;<?= $pegawai['alamat']; ?></td>
                                </tr>
                                <tr>
                                    <th>No Handphone</th>
                                    <td>:&nbsp;<?= $pegawai['no_handphone']; ?></td>
                                </tr>
                                <tr>
                                    <th>Jabatan</th>
                                    <td>:&nbsp;<?= $pegawai['jabatan']; ?></td>
                                </tr>
                                <tr>
                                    <th>username</th>
                                    <td>:&nbsp;<?= $pegawai['username']; ?></td>
                                </tr>
                                <tr>
                                    <th>Role</th>
                                    <td>:&nbsp;<?= $pegawai['role']; ?></td>
                                </tr>
                                <tr>
                                    <th>Lokasi Presensi</th>
                                    <td>:&nbsp;<?= $pegawai['lokasi_presensi']; ?></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>:&nbsp;<?= $pegawai['status']; ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-4"></div>
            </div>
        </div>
    </div>
<?php endwhile; ?>
<?php include('.././layout/footer.php'); ?>