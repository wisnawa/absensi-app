<?php
ob_start();
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../../auth/login.php?pesan=belum_login");
} else if ($_SESSION['role'] != 'pegawai') {
    header("Location: ../../auth/login.php?pesan=tolak_akses");
}

$judul = 'Pengajuan Ketidakhadiran';

include('../layout/header.php');
include_once('../../config.php');

if (isset($_POST['submit'])) {
    $id = $_POST['id_pegawai'];
    $keterangan = $_POST['keterangan'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal = $_POST['tanggal'];
    $status_pengajuan = 'PENDING';

    if (isset($_FILES['file'])) {
        $file = $_FILES['file'];
        $nama_file = $file['name'];
        $file_tmp = $file['tmp_name'];
        $ukuran_file = $file['size'];
        $file_direktori = "../../assets/file_ketidakhadiran/" . $nama_file;

        $ambil_ekstensi = pathinfo($nama_file, PATHINFO_EXTENSION);
        $ekstensi_diizinkan = ['jpg', 'png', 'jpeg', 'pdf'];
        $max_ukuran_file = 10 * 1024 * 1024;

        move_uploaded_file($file_tmp, $file_direktori);
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (empty($keterangan)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'>&nbsp;</i>Keterangan wajib diisi";
        }
        if (empty($deskripsi)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>&nbsp;deskripsi wajib diisi";
        }
        if (empty($tanggal)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>&nbsp;tanggal wajib diisi";
        }
        if (!in_array(strtolower($ambil_ekstensi), $ekstensi_diizinkan)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>&nbsp;Hanya file JPG, JPEG, dan PNG yang diperbolehkan!";
        }
        if ($ukuran_file > $max_ukuran_file) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>&nbsp;Ukuran file melebihi 10MB";
        }
        if (!empty($pesan_kesalahan)) {
            $_SESSION['validasi'] = implode('<br>', $pesan_kesalahan);
        } else {
            $result = mysqli_query($connection, "INSERT INTO `ketidakhadiran` (`id_pegawai`, `keterangan`, `tanggal`, `deskripsi`, `file`, `status_pengajuan`) VALUES ('$id' ,'$keterangan', '$tanggal', '$deskripsi','$nama_file', '$status_pengajuan')");

            $_SESSION['berhasil'] = 'Data berhasil disimpan';
            header('Location: ketidakhadiran.php');
            exit;
        }
    }
}

$id = $_SESSION['id'];
$result = mysqli_query($connection, "SELECT * FROM `ketidakhadiran` WHERE `id_pegawai` = '$id' ORDER BY id DESC");
?>
<div class="page-body">
    <div class="container-xl">
        <div class="card col-md-6">
            <div class="card-body">
                <form action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id_pegawai" value="<?= $_SESSION['id']; ?>">
                    <div class="mb-3">
                        <label for="keterangan">Keterangan</label>
                        <select name="keterangan" id="keterangan" class="form-control">
                            <option value="">Pilih Jenis Keterangan</option>
                            <option <?php if (isset($_POST['keterangan']) && $_POST['keterangan'] == 'Cuti') {
                                        echo 'selected';
                                    } ?> value="Cuti">Cuti</option>
                            <option <?php if (isset($_POST['keterangan']) && $_POST['keterangan'] == 'Izin') {
                                        echo 'selected';
                                    } ?> value="Izin">Izin</option>
                            <option <?php if (isset($_POST['keterangan']) && $_POST['keterangan'] == 'Sakit') {
                                        echo 'selected';
                                    } ?> value="Sakit">Sakit</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" cols="30" rows="5" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="file">Surat Keterangan</label>
                        <input type="file" name="file" id="file" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary" name="submit">Ajukan</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include('../layout/footer.php') ?>