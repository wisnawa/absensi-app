<?php
ob_start();
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../../auth/login.php?pesan=belum_login");
} else if ($_SESSION['role'] != 'pegawai') {
    header("Location: ../../auth/login.php?pesan=tolak_akses");
}

$judul = 'Ketidakhadiran';

include('../layout/header.php');
include_once('../../config.php');

$id = $_SESSION['id'];
$result = mysqli_query($connection, "SELECT * FROM `ketidakhadiran` WHERE `id_pegawai` = '$id' ORDER BY `id` DESC");
?>
<div class="page-body">
    <div class="container-xl">
        <a href="<?= base_url('pegawai/ketidakhadiran/pengajuan_ketidakhadiran.php'); ?>" class="btn btn-primary mb-3">Tambah Data</a>
        <table class="table table-bordered">
            <tr class="text-center">
                <th>No.</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Deskripsi</th>
                <th>File</th>
                <th>Status Pengajuan</th>
                <th>Aksi</th>
            </tr>
            <?php if (mysqli_num_rows($result) === 0) { ?>
                <tr class="text-red fw-bold text-center fs-3">
                    <td colspan="7">Data ketidakhadiran masih kosong!</td>
                </tr>
            <?php } else { ?>
                <?php $no = 1;
                while ($data = mysqli_fetch_array($result)) : ?>
                    <tr class="align-middle">
                        <td><?= $no++; ?></td>
                        <td><?= date('d F Y', strtotime($data['tanggal'])); ?></td>
                        <td><?= $data['keterangan']; ?></td>
                        <td><?= $data['deskripsi']; ?></td>
                        <td class="text-center">
                            <a target="_blank" href="<?= base_url('assets/file_ketidakhadiran/' . $data['file']); ?>" class="badge badge-pill bg-primary">Download</a>
                        </td>
                        <td>
                            <?php if ($data['status_pengajuan'] == 'PENDING') : ?>
                                <div class="fw-bold text-warning">PENDING</div>
                            <?php elseif ($data['status_pengajuan'] == 'REJECTED') : ?>
                                <div class="fw-bold text-danger">REJECTED</div>
                            <?php else : ?>
                                <div class="fw-bold text-success">APPROVED</div>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <a href="edit.php?id=<?= $data['id']; ?>" class="btn btn-success">Update</a>
                            <a href="hapus.php?id=<?= $data['id']; ?>" class="btn btn-danger tombol-hapus">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php } ?>
        </table>
    </div>
</div>
<?php include('../layout/footer.php') ?>