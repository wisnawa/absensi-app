<?php
session_start();
ob_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../../auth/login.php?pesan=belum_login");
} else if ($_SESSION['role'] != 'admin') {
    header("Location: ../../auth/login.php?pesan=tolak_akses");
}

$judul = 'Tambah Lokasi Presensi';

include('../layout/header.php');
require_once('../../config.php');
if (isset($_POST['submit'])) {
    $id = null;
    $nama_lokasi = htmlspecialchars($_POST['nama_lokasi']);
    $alamat_lokasi = htmlspecialchars($_POST['alamat_lokasi']);
    $tipe_lokasi = htmlspecialchars($_POST['tipe_lokasi']);
    $latitude = htmlspecialchars($_POST['latitude']);
    $longtitude = htmlspecialchars($_POST['longtitude']);
    $radius = htmlspecialchars($_POST['radius']);
    $zona_waktu = htmlspecialchars($_POST['zona_waktu']);
    $jam_masuk = htmlspecialchars($_POST['jam_masuk']);
    $jam_pulang = htmlspecialchars($_POST['jam_pulang']);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (empty($nama_lokasi)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'>&nbsp;</i>Nama lokasi wajib diisi";
        }
        if (empty($alamat_lokasi)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>&nbsp;Alamat lokasi wajib diisi";
        }
        if (empty($tipe_lokasi)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>&nbsp;Tipe lokasi wajib diisi";
        }
        if (empty($latitude)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>&nbsp;Latitude wajib diisi";
        }
        if (empty($longtitude)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>&nbsp;Longitude wajib diisi";
        }
        if (empty($radius)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>&nbsp;Radius wajib diisi";
        }
        if (empty($zona_waktu)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>&nbsp;Zona waktu wajib diisi";
        }
        if (empty($jam_masuk)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>&nbsp;Jam masuk wajib diisi";
        }
        if (empty($jam_pulang)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>&nbsp;Jam pulang wajib diisi";
        }
        if (!empty($pesan_kesalahan)) {
            $_SESSION['validasi'] = implode('<br>', $pesan_kesalahan);
        } else {
            $result = mysqli_query($connection, "INSERT INTO `lokasi_presensi` (`id`, `nama_lokasi`, `alamat_lokasi`, `tipe_lokasi`, `latitude`, `longtitude`, `radius`, `zona_waktu`, `jam_masuk`, `jam_pulang`) VALUES ('$id' ,'$nama_lokasi', '$alamat_lokasi', '$tipe_lokasi',' $latitude', '$longtitude', '$radius', '$zona_waktu', '$jam_masuk', '$jam_pulang')");

            $_SESSION['berhasil'] = 'Data berhasil disimpan';
            header('Location: lokasi_presensi.php');
            exit;
        }
    }
}
?>
<div class="page-body">
    <div class="container-xl">
        <div class="card col-md-6">
            <div class="card-body">
                <form action="<?= base_url('admin/data_lokasi_presensi/tambah.php'); ?>" method="post">
                    <div class="mb-3">
                        <label for="nama_lokasi">Nama Lokasi</label>
                        <input type="text" name="nama_lokasi" id="nama_lokasi" class="form-control" value="<?php if (isset($_POST['nama_lokasi'])) echo $_POST['nama_lokasi'] ?>">
                    </div>
                    <div class="mb-3">
                        <label for="alamat_lokasi">Alamat Lokasi</label>
                        <input type="text" name="alamat_lokasi" id="alamat_lokasi" class="form-control" value="<?php if (isset($_POST['alamat_lokasi'])) echo $_POST['alamat_lokasi'] ?>">
                    </div>
                    <div class="mb-3">
                        <label for="tipe_lokasi">Alamat Lokasi</label>
                        <select name="tipe_lokasi" id="tipe_lokasi" class="form-control">
                            <option value="">Pilih Tipe Lokasi</option>
                            <option <?php if (isset($_POST['tipe_lokasi']) && $_POST['tipe_lokasi'] == 'Pusat') {
                                        echo 'selected';
                                    } ?> value="Pusat">Pusat</option>
                            <option <?php if (isset($_POST['tipe_lokasi']) && $_POST['tipe_lokasi'] == 'Cabang') {
                                        echo 'selected';
                                    } ?> value="Cabang">Cabang</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="latitude">Latitude</label>
                        <input type="text" name="latitude" id="latitude" class="form-control" value="<?php if (isset($_POST['latitude'])) echo $_POST['latitude'] ?>">
                    </div>
                    <div class="mb-3">
                        <label for="longtitude">Longtitude</label>
                        <input type="text" name="longtitude" id="longtitude" class="form-control" value="<?php if (isset($_POST['longtitude'])) echo $_POST['longtitude'] ?>">
                    </div>
                    <div class="mb-3">
                        <label for="radius">Radius</label>
                        <input type="number" name="radius" id="radius" class="form-control" value="<?php if (isset($_POST['radius'])) echo $_POST['radius'] ?>">
                    </div>
                    <div class="mb-3">
                        <label for="zona_waktu">Zona Waktu</label>
                        <select name="zona_waktu" id="zona_waktu" class="form-control">
                            <option value="">Pilih Zona Waktu</option>
                            <option <?php if (isset($_POST['zona_waktu']) && $_POST['zona_waktu'] == 'WIB') {
                                        echo 'selected';
                                    } ?> value="WIB">WIB</option>
                            <option <?php if (isset($_POST['zona_waktu']) && $_POST['zona_waktu'] == 'WITA') {
                                        echo 'selected';
                                    } ?> value="WITA">WITA</option>
                            <option <?php if (isset($_POST['zona_waktu']) && $_POST['zona_waktu'] == 'WIT') {
                                        echo 'selected';
                                    } ?> value="WIT">WIT</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="jam_masuk">Jam Masuk</label>
                        <input type="time" name="jam_masuk" id="jam_masuk" class="form-control" value="<?php if (isset($_POST['jam_masuk'])) echo $_POST['jam_masuk'] ?>">
                    </div>
                    <div class="mb-3">
                        <label for="jam_pulang">Jam Pulang</label>
                        <input type="time" name="jam_pulang" id="jam_pulang" class="form-control" value="<?php if (isset($_POST['jam_pulang'])) echo $_POST['jam_pulang'] ?>">
                    </div>
                    <button type="submit" class="btn btn-primary" name="submit">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include('.././layout/footer.php'); ?>