<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../../auth/login.php?pesan=belum_login");
} else if ($_SESSION['role'] != 'pegawai') {
    header("Location: ../../auth/login.php?pesan=tolak_akses");
}

$judul = 'Home';

include('../layout/header.php');
include_once('../../config.php');
$lokasi_presensi = $_SESSION['lokasi_presensi'];
$result = mysqli_query($connection, "SELECT * FROM lokasi_presensi WHERE nama_lokasi = '$lokasi_presensi'");
while ($lokasi = mysqli_fetch_array($result)) {
    $latitude_kantor = $lokasi['latitude'];
    $longtitude_kantor = $lokasi['longtitude'];
    $radius = $lokasi['radius'];
    $zona_waktu = $lokasi['zona_waktu'];
    $jam_pulang = $lokasi['jam_pulang'];
}
if ($zona_waktu == 'WIB') {
    date_default_timezone_set('Asia/Jakarta');
} elseif ($zona_waktu == 'WITA') {
    date_default_timezone_set('Asia/Makassar');
} elseif ($zona_waktu  == 'WIT') {
    date_default_timezone_set('Asia/Jayapura');
}

?>
<style>
    .parent_date {
        display: grid;
        grid-template-columns: auto auto auto auto auto;
        font-size: 20px;
        text-align: center;
        justify-content: center;
    }

    .parent_clock {
        display: grid;
        grid-template-columns: auto auto auto auto auto;
        font-size: 30px;
        text-align: center;
        font-weight: bold;
        justify-content: center;
    }
</style>
<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-4">
                <div class="card text-center h-100">
                    <div class="card-header">Pesensi Masuk</div>
                    <div class="card-body">
                        <?php
                        $id_pegawai = $_SESSION['id'];
                        $tanggal_hari_ini = date('Y-m-d');

                        $cek_presensi_masuk = mysqli_query($connection, "SELECT * FROM `presensi` WHERE id_pegawai = '$id_pegawai' AND tanggal_masuk = '$tanggal_hari_ini'");
                        ?>
                        <?php if (mysqli_num_rows($cek_presensi_masuk) === 0) { ?>
                            <div class="parent_date">
                                <div id="tanggal_masuk"></div>
                                <div class="ms-2"></div>
                                <div id="bulan_masuk"></div>
                                <div class="ms-2"></div>
                                <div id="tahun_masuk"></div>
                            </div>
                            <div class="parent_clock">
                                <div id="jam_masuk"></div>
                                <div>:</div>
                                <div id="menit_masuk"></div>
                                <div>:</div>
                                <div id="detik_masuk"></div>
                            </div>
                            <form action="<?= base_url('pegawai/presensi/presensi_masuk.php'); ?>" method="post">
                                <input type="hidden" name="latitude_pegawai" id="latitude_pegawai">
                                <input type="hidden" name="longtitude_pegawai" id="longtitude_pegawai">
                                <input type="hidden" name="latitude_kantor" id="" value="<?= $latitude_kantor; ?>">
                                <input type="hidden" name="longtitude_kantor" id="" value="<?= $longtitude_kantor; ?>">
                                <input type="hidden" name="radius" id="" value="<?= $radius; ?>">
                                <input type="hidden" name="zona_waktu" id="" value="<?= $zona_waktu; ?>">
                                <input type="hidden" name="tanggal_masuk" id="" value="<?= date('Y-m-d'); ?>">
                                <input type="hidden" name="jam_masuk" id="" value="<?= date('H:i:s'); ?>">
                                <button type="submit" name="tombol_masuk" class="btn btn-primary mt-3">Masuk</button>
                            </form>
                        <?php } else { ?>
                            <i class="fa-regular fa-circle-check fa-4x text-success"></i>
                            <h4 class="my-3">Anda telah melakukan <br> presensi masuk!</h4>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center h-100">
                    <div class="card-header">Pesensi Keluar</div>
                    <div class="card-body">
                        <?php $ambil_data_presensi = mysqli_query($connection, "SELECT * FROM `presensi` WHERE id_pegawai = '$id_pegawai' AND tanggal_masuk = '$tanggal_hari_ini'"); ?>
                        <?php
                        $waktu_sekarang = date('H:i:s');

                        if (strtotime($waktu_sekarang) <= strtotime($jam_pulang)) { ?>
                            <i class="fa-regular fa-circle-xmark fa-4x text-danger"></i>
                            <h4 class="my-3">Belum waktunya pulang!</h4>
                        <?php } elseif (strtotime($waktu_sekarang) >= strtotime($jam_pulang) && mysqli_num_rows($ambil_data_presensi) == 0) { ?>
                            <i class="fa-regular fa-circle-xmark fa-4x text-danger"></i>
                            <h4 class="my-3">Silahakan melakukan presensi masuk <br> terlebih dahulu!</h4>
                        <?php } else { ?>

                            <?php while ($cek_presensi_keluar = mysqli_fetch_array($ambil_data_presensi)) { ?>
                                <?php if ($cek_presensi_keluar['tanggal_masuk'] && $cek_presensi_keluar['tanggal_keluar'] == '0000-00-00') { ?>
                                    <div class="parent_date">
                                        <div id="tanggal_keluar"></div>
                                        <div class="ms-2"></div>
                                        <div id="bulan_keluar"></div>
                                        <div class="ms-2"></div>
                                        <div id="tahun_keluar"></div>
                                    </div>
                                    <div class="parent_clock">
                                        <div id="jam_keluar"></div>
                                        <div>:</div>
                                        <div id="menit_keluar"></div>
                                        <div>:</div>
                                        <div id="detik_keluar"></div>
                                    </div>
                                    <form action="<?= base_url('pegawai/presensi/presensi_keluar.php'); ?>" method="post">
                                        <input type="hidden" name="id" id="" value="<?= $cek_presensi_keluar['id']; ?>">
                                        <input type="hidden" name="latitude_pegawai" id="latitude_pegawai">
                                        <input type="hidden" name="longtitude_pegawai" id="longtitude_pegawai">
                                        <input type="hidden" name="latitude_kantor" id="" value="<?= $latitude_kantor; ?>">
                                        <input type="hidden" name="longtitude_kantor" id="" value="<?= $longtitude_kantor; ?>">
                                        <input type="hidden" name="radius" id="" value="<?= $radius; ?>">
                                        <input type="hidden" name="zona_waktu" id="" value="<?= $zona_waktu; ?>">
                                        <input type="hidden" name="tanggal_keluar" id="" value="<?= date('Y-m-d'); ?>">
                                        <input type="hidden" name="jam_keluar" id="" value="<?= date('H:i:s'); ?>">
                                        <button type="submit" name="tombol_keluar" class="btn btn-danger mt-3">Keluar</button>
                                    </form>
                                <?php } else { ?>
                                    <i class="fa-regular fa-circle-check fa-4x text-success"></i>
                                    <h4 class="my-3">Anda telah melakukan <br> presensi keluar!</h4>
                                <?php } ?>
                            <?php }; ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="col-md-2"></div>
        </div>
    </div>
</div>
<script>
    // set waktu di card presensi masuk
    window.setTimeout('waktuMasuk()', 1000);
    namaBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'Nopember', 'Desember'];

    function waktuMasuk() {
        const waktu = new Date();
        setTimeout('waktuMasuk()', 1000);
        document.getElementById('tanggal_masuk').innerHTML = waktu.getDate();
        document.getElementById('bulan_masuk').innerHTML = namaBulan[waktu.getMonth()];
        document.getElementById('tahun_masuk').innerHTML = waktu.getFullYear();
        document.getElementById('jam_masuk').innerHTML = waktu.getHours();
        document.getElementById('menit_masuk').innerHTML = waktu.getMinutes();
        document.getElementById('detik_masuk').innerHTML = waktu.getSeconds();
    }
    // set waktu di card presensi keluar
    window.setTimeout('waktuKeluar()', 1000);
    namaBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'Nopember', 'Desember'];

    function waktuKeluar() {
        const waktu = new Date();
        setTimeout('waktuKeluar()', 1000);
        document.getElementById('tanggal_keluar').innerHTML = waktu.getDate();
        document.getElementById('bulan_keluar').innerHTML = namaBulan[waktu.getMonth()];
        document.getElementById('tahun_keluar').innerHTML = waktu.getFullYear();
        document.getElementById('jam_keluar').innerHTML = waktu.getHours();
        document.getElementById('menit_keluar').innerHTML = waktu.getMinutes();
        document.getElementById('detik_keluar').innerHTML = waktu.getSeconds();
    }
    getLocation();

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {
            alert('Browser anda tidak mendukung!');
        }
    }

    function showPosition(position) {
        $('#latitude_pegawai').val(position.coords.latitude);
        $('#longtitude_pegawai').val(position.coords.longitude);
    }
</script>
<?php include('../layout/footer.php') ?>