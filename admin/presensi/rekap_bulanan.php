<?php
ob_start();
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../../auth/login.php?pesan=belum_login");
} else if ($_SESSION['role'] != 'admin') {
    header("Location: ../../auth/login.php?pesan=tolak_akses");
}

$judul = 'Rekap Presensi Bulanan';

include('../layout/header.php');
include_once('../../config.php');

if (empty($_GET['filter_bulan'])) {
    $bulan_sekarang = date('Y-m');
    $result = mysqli_query($connection, "SELECT presensi.*,  pegawai.nama,  pegawai.lokasi_presensi FROM presensi JOIN pegawai ON presensi.id_pegawai = pegawai.id WHERE DATE_FORMAT(tanggal_masuk, '%Y-%m') = '$bulan_sekarang' ORDER BY tanggal_masuk DESC");
} else {
    $filter_tahun_bulan = $_GET['filter_tahun'] . '-' . $_GET['filter_bulan'];
    $result = mysqli_query($connection, "SELECT presensi.*, pegawai.nama, pegawai.lokasi_presensi FROM presensi JOIN pegawai ON presensi.id_pegawai = pegawai.id WHERE DATE_FORMAT(tanggal_masuk, '%Y-%m') = '$filter_tahun_bulan' ORDER BY tanggal_masuk DESC");
}

if (empty($_GET['filter_bulan'])) {
    $bulan = date('Y-m');
} else {
    $bulan = $_GET['filter_tahun'] . '-' . $_GET['filter_bulan'];
}

?>

<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-md-2">
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Export Excel
                </button>
            </div>
            <div class="col-md-10">
                <form method="get">
                    <div class="input-group">
                        <select name="filter_bulan" class="form-control">
                            <option value="">Pilih Bulan</option>
                            <option value="01">Januari</option>
                            <option value="02">Februari</option>
                            <option value="03">Maret</option>
                            <option value="04">April</option>
                            <option value="05">Mei</option>
                            <option value="06">Juni</option>
                            <option value="07">Juli</option>
                            <option value="08">Agustus</option>
                            <option value="09">September</option>
                            <option value="10">Oktober</option>
                            <option value="11">Nopember</option>
                            <option value="12">Desember</option>
                        </select>
                        <select name="filter_tahun" class="form-control">
                            <option value="">Pilih Tahun</option>
                            <?php
                            for ($i = 2023; $i <= date('Y'); $i++) { ?>
                                <option value="<?= $i; ?>"><?= $i; ?></option>
                            <?php } ?>
                        </select>
                        <button type="submit" class="btn btn-primary">Tampilkan</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="mb-2 text-black fs-3 fw-bold">Rekap Presensi Bulan:&nbsp;<?= date('F Y', strtotime($bulan)); ?></div>
        <table class="table table-bordered">
            <tr class="text-center">
                <th>No.</th>
                <th>Nama</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
                <th>Total Jam</th>
                <th>Total Terlabat</th>
            </tr>
            <?php if (mysqli_num_rows($result) === 0) { ?>
                <tr class="text-red fw-bold text-center fs-3">
                    <td colspan="6">Data rekap presensi masih kosong!</td>
                </tr>
            <?php } else { ?>
                <?php
                $no = 1;
                while ($rekap = mysqli_fetch_array($result)) :

                    // menghitung total jam kerja
                    $jam_tanggal_masuk = date('Y-m-d H:i:s', strtotime($rekap['tanggal_masuk'] . ' ' . $rekap['jam_masuk']));
                    $jam_tanggal_keluar = date('Y-m-d H:i:s', strtotime($rekap['tanggal_keluar'] . ' ' . $rekap['jam_keluar']));

                    $timestamp_masuk = strtotime($jam_tanggal_masuk);
                    $timestamp_keluar = strtotime($jam_tanggal_keluar);

                    $selisih = $timestamp_keluar - $timestamp_masuk;

                    $total_jam_kerja = floor($selisih / 3600);
                    $selisih -= $total_jam_kerja * 3600;
                    $selisih_menit_kerja = floor($selisih / 60);

                    // menghitung total jam terlambat
                    $lokasi_presensi = $rekap['lokasi_presensi'];
                    $lokasi = mysqli_query($connection, "SELECT * FROM `lokasi_presensi` WHERE nama_lokasi = '$lokasi_presensi'");

                    while ($lokasi_result = mysqli_fetch_array($lokasi)) :
                        $jam_masuk_kantor = date('H:i:s', strtotime($lokasi_result['jam_masuk']));
                    endwhile;

                    $jam_masuk = date('H:i:s', strtotime($rekap['jam_masuk']));
                    $timestamp_jam_masuk_real = strtotime($jam_masuk);
                    $timestamp_jam_masuk_kantor = strtotime($jam_masuk_kantor);

                    $terlambat = $timestamp_jam_masuk_real - $timestamp_jam_masuk_kantor;

                    $total_jam_terlambat = floor($terlambat / 3600);
                    $terlambat -= $total_jam_terlambat * 3600;
                    $selisih_menit_terlambat = floor($terlambat / 60);
                ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $rekap['nama']; ?></td>
                        <td><?= date('d F Y', strtotime($rekap['tanggal_masuk'])); ?></td>
                        <td class="text-center"><?= $rekap['jam_masuk']; ?></td>
                        <td class="text-center"><?= $rekap['jam_keluar']; ?></td>
                        <td class="text-center">
                            <?php if ($rekap['tanggal_keluar'] == '0000-00-00') : ?>
                                <span class="badge bg-warning text-white">Jam pulang belum ada</span>
                            <?php else : ?>
                                <?= $total_jam_kerja . ' Jam ' . $selisih_menit_kerja . ' Menit'; ?>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php if ($total_jam_terlambat < 0) : ?>
                                <span class="badge bg-success text-white">On Time</span>
                            <?php else : ?>
                                <?= $total_jam_terlambat . ' Jam ' . $selisih_menit_terlambat . ' Menit'; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php } ?>
        </table>
    </div>
</div>
<div class="modal" id="exampleModal" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Excel Rekap Presensi Bulanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('admin/presensi/rekap_bulanan_excel.php'); ?>" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="filter_bulan">Bulan</label>
                        <select name="filter_bulan" id="filter_bulan" class="form-control">
                            <option value="">Pilih Bulan</option>
                            <option value="01">Januari</option>
                            <option value="02">Februari</option>
                            <option value="03">Maret</option>
                            <option value="04">April</option>
                            <option value="05">Mei</option>
                            <option value="06">Juni</option>
                            <option value="07">Juli</option>
                            <option value="08">Agustus</option>
                            <option value="09">September</option>
                            <option value="10">Oktober</option>
                            <option value="11">Nopember</option>
                            <option value="12">Desember</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="filter_tahun">Tahun</label>
                        <select name="filter_tahun" id="filter_tahun" class="form-control">
                            <option value="">Pilih Tahun</option>
                            <?php
                            for ($i = 2023; $i <= date('Y'); $i++) { ?>
                                <option value="<?= $i; ?>"><?= $i; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Export</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include('../layout/footer.php') ?>