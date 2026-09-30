<?php
// Konfigurasi koneksi database
date_default_timezone_set('Asia/Jakarta');
$koneksi = new mysqli("localhost", "root", "", "db_parkir");
if ($koneksi->connect_error) {
    die("Koneksi Error: " . $koneksi->connect_error);
}
$koneksi->set_charset("utf8mb4");
?>