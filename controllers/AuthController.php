<?php
require_once 'models/AdminModel.php';

class AuthController {
    private $model;

    public function __construct($koneksi) {
        $this->model = new AdminModel($koneksi);
        if (session_status() === PHP_SESSION_NONE) session_start();
    }

    public function login() {
        // Kalau sudah login, langsung ke dashboard
        if (isset($_SESSION['id_admin'])) {
            header("Location: index.php?page=dashboard");
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);
            $user = $this->model->login($username, $password);
            if ($user) {
                $_SESSION['id_admin']      = $user['id_admin'];
                $_SESSION['nama_petugas']  = $user['nama_petugas'];
                header("Location: index.php?page=dashboard&pesan=welcome");
                exit;
            }
            header("Location: index.php?pesan=gagal");
            exit;
        }
        require_once 'views/login.php';
    }

    public function logout() {
        session_destroy();
        header("Location: index.php");
        exit;
    }
}
?>
