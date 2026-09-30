<?php
session_start();
require_once 'config/database.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/ParkirController.php';

$auth   = new AuthController($koneksi);
$parkir = new ParkirController($koneksi);

$page = isset($_GET['page']) ? $_GET['page'] : '';

switch ($page) {
    case 'login':
        $auth->login();
        break;

    case 'logout':
        $auth->logout();
        break;

    case 'dashboard':
        if (!isset($_SESSION['id_admin'])) { header("Location: index.php"); exit; }
        $parkir->dashboard();
        break;

    case 'parkir_masuk':
        if (!isset($_SESSION['id_admin'])) { header("Location: index.php"); exit; }
        $parkir->parkir_masuk();
        break;

    case 'parkir_keluar':
        if (!isset($_SESSION['id_admin'])) { header("Location: index.php"); exit; }
        $parkir->parkir_keluar();
        break;

    case 'laporan':
        if (!isset($_SESSION['id_admin'])) { header("Location: index.php"); exit; }
        $parkir->laporan();
        break;

    case 'cetak_pdf':
        if (!isset($_SESSION['id_admin'])) { header("Location: index.php"); exit; }
        $parkir->cetak_pdf();
        break;

    case 'export_csv':
        if (!isset($_SESSION['id_admin'])) { header("Location: index.php"); exit; }
        $parkir->export_csv();
        break;

    case 'hapus':
        if (!isset($_SESSION['id_admin'])) { header("Location: index.php"); exit; }
        $parkir->hapus();
        break;

    default:
        if (isset($_SESSION['id_admin'])) {
            header("Location: index.php?page=dashboard");
            exit;
        }
        $auth->login();
        break;
}
?>