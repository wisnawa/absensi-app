<?php
session_start();
require_once('../../config.php');

$id = $_GET['id'];
$result = mysqli_query($connection, "DELETE FROM `pegawai` WHERE id = $id");
$_SESSION['berhasil'] = 'Data berhasil di hapus!';
header('Location: pegawai.php');
exit;
include('../layout/footer.php');
