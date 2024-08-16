<?php
session_start();

require_once('../config.php');

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $result = mysqli_query($connection, "SELECT * FROM users JOIN pegawai ON users.id_pegawai = pegawai.id WHERE username = '$username'");
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        if (password_verify($password, $row['password'])) {
            if ($row['status'] == 'Aktif') {
                $_SESSION['login'] = true;
                $_SESSION['id'] = $row['id'];
                $_SESSION['role'] = $row['role'];
                $_SESSION['nama'] = $row['nama'];
                $_SESSION['nip'] = $row['nip'];
                $_SESSION['jabatan'] = $row['jabatan'];
                $_SESSION['lokasi_presensi'] = $row['lokasi_presensi'];

                if ($row['role'] === 'admin') {
                    header('Location: ../admin/home/home.php');
                    exit();
                } else {
                    header('Location: ../pegawai/home/home.php');
                    exit();
                }
            } else {
                $_SESSION['gagal'] = 'Account anda belum aktif!';
            }
        } else {
            $_SESSION['gagal'] = 'Password salah, silahkan coba kebali!';
        }
    } else {
        $_SESSION['gagal'] = 'Username salah, silahkan coba kembali!';
    }
}



?>
<!doctype html>
<!--
* Tabler - Premium and Open Source dashboard template with responsive and high quality UI.
* @version 1.0.0-beta20
* @link https://tabler.io
* Copyright 2018-2023 The Tabler Authors
* Copyright 2018-2023 codecalm.net Paweł Kuna
* Licensed under MIT (https://github.com/tabler/tabler/blob/master/LICENSE)
-->
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Sign in with illustration - Tabler - Premium and Open Source dashboard template with responsive and high quality UI.</title>
    <!-- CSS files -->
    <link href="<?= base_url('./assets/css/tabler.min.css?1692870487'); ?>" rel="stylesheet" />
    <link href="<?= base_url('./assets/css/tabler-vendors.min.css?1692870487'); ?>" rel="stylesheet" />
    <link href="<?= base_url('./assets/css/demo.min.css?1692870487'); ?>" rel="stylesheet" />
    <!-- my style CSS -->
    <link rel="stylesheet" href="<?= base_url('style.css'); ?>">
    <!-- link font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        @import url('https://rsms.me/inter/inter.css');

        :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }

        body {
            font-feature-settings: "cv03", "cv04", "cv11";
        }
    </style>
</head>

<body class=" d-flex flex-column">
    <script src="./dist/js/demo-theme.min.js?1692870487"></script>
    <div class="page page-center">
        <div class="container container-normal py-4">
            <div class="row align-items-center g-4">
                <div class="col-lg">
                    <div class="container-tight">
                        <div class="text-center mb-4">
                            <a href="." class="navbar-brand navbar-brand-autodark"><img src="<?= base_url('./assets/img/logo-small.svg'); ?>" height="80" alt=""></a>
                        </div>
                        <?php if (isset($_GET['pesan'])) {
                            if ($_GET['pesan'] == 'belum_login') {
                                $_SESSION['gagal'] = 'Anda belum login!';
                            } else if ($_GET['pesan'] == 'tolak_akses') {
                                $_SESSION['gagal'] = 'Akses ke halaman ini ditolak!';
                            }
                        } ?>
                        <div class="card card-md">
                            <div class="card-body">
                                <h2 class="h2 text-center mb-4">Login to your account</h2>
                                <h3>user: wisnawa | password: 111</h3>
                                <h3>user: budi | password: 123</h3>
                                <form action="" method="post" autocomplete="off" novalidate>
                                    <div class="mb-3">
                                        <label class="form-label" for="username">Username</label>
                                        <input type="text" name="username" class="form-control" id="username" placeholder="Username" autocomplete="off" autofocus>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label" for="password">
                                            Password
                                        </label>
                                        <div class="input-group input-group-flat">
                                            <input type="password" name="password" class="form-control" id="password" placeholder="Password" autocomplete="off">
                                            <span class="input-group-text input-box">
                                                <!-- <i class="fa-regular fa-eye-slash" id="eyeicon"></i> -->
                                                <img src="image_icon/eye-close.png" alt="" id="eyeicon" class="eye" />
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-footer">
                                        <button type="submit" name="login" class="btn btn-primary w-100">Sign in</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg d-none d-lg-block">
                    <img src="<?= base_url('./assets/img/undraw_secure_login_pdn4.svg'); ?>" height="300" class="d-block mx-auto" alt="">
                </div>
            </div>
        </div>
    </div>
    <!-- Libs JS -->
    <script src="<?= base_url('assets/libs/apexcharts/dist/apexcharts.min.js?1692870487'); ?>" defer></script>
    <script src="<?= base_url('assets/libs/jsvectormap/dist/js/jsvectormap.min.js?1692870487'); ?>" defer></script>
    <script src="<?= base_url('assets/libs/jsvectormap/dist/maps/world.js?1692870487'); ?>" defer></script>
    <script src="<?= base_url('assets/libs/jsvectormap/dist/maps/world-merc.js?1692870487'); ?>" defer></script>
    <!-- my script JS -->
    <script src="<?= base_url('script.js'); ?>"></script>
    <!-- Tabler Core -->
    <script src="<?= base_url('assets/js/tabler.min.js?1692870487'); ?>" defer></script>
    <script src="<?= base_url('assets/js/demo.min.js?1692870487'); ?>" defer></script>
    <!-- sweetalert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php if (isset($_SESSION['gagal'])) { ?>
        <script>
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "<?= $_SESSION['gagal']; ?>",
            });
        </script>
        <?php unset($_SESSION['gagal']); ?>
    <?php } ?>
</body>

</html>