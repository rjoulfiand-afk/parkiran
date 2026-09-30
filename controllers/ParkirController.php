<?php
require_once 'models/ParkirModel.php';

class ParkirController {
    private $model;

    public function __construct($koneksi) {
        $this->model = new ParkirModel($koneksi);
    }

    public function dashboard() {
        $tgl_hari_ini    = date('Y-m-d');
        $kendaraan_aktif = $this->model->getParkirAktif();
        $pendapatan      = $this->model->getPendapatanHariIni($tgl_hari_ini);
        $total_selesai   = $this->model->getTotalKendaraanHariIni($tgl_hari_ini);
        require_once 'views/dashboard.php';
    }

    public function parkir_masuk() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $plat        = trim($_POST['nomor_plat']);
            $jenis       = $_POST['jenis_kendaraan'];
            $waktu_masuk = date('Y-m-d H:i:s');
            $this->model->catatMasuk($plat, $jenis, $waktu_masuk);
            header("Location: index.php?page=parkir_masuk&pesan=sukses_masuk");
            exit;
        }
        require_once 'views/parkir_masuk.php';
    }

    public function parkir_keluar() {
        $kendaraan    = null;
        $tagihan      = 0;
        $durasi_jam   = 0;
        $waktu_keluar = date('Y-m-d H:i:s');

        if (isset($_GET['cari_plat']) && $_GET['cari_plat'] !== '') {
            $kendaraan = $this->model->cariKendaraanKeluar($_GET['cari_plat']);
            if ($kendaraan) {
                $masuk         = strtotime($kendaraan['waktu_masuk']);
                $keluar        = strtotime($waktu_keluar);
                $selisih_detik = $keluar - $masuk;
                $durasi_jam    = ceil($selisih_detik / 3600);
                if ($durasi_jam < 1) $durasi_jam = 1;

                // Tarif sesuai soal:
                // Roda 2: Rp 2.000 awal + Rp 1.000/jam setelah 2 jam
                // Roda 4: Rp 5.000 awal + Rp 1.000/jam setelah 2 jam
                $tarif_dasar = ($kendaraan['jenis_kendaraan'] == 'Roda 2') ? 2000 : 5000;
                $tambahan    = ($durasi_jam > 2) ? ($durasi_jam - 2) * 1000 : 0;
                $tagihan     = $tarif_dasar + $tambahan;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->model->prosesKeluar(
                $_POST['id_parkir'],
                $_POST['waktu_keluar'],
                $_POST['total_bayar']
            );
            // Kirim bayar ke URL untuk ditampilkan di pop-up (sesuai soal)
            header("Location: index.php?page=parkir_keluar&pesan=sukses_keluar&bayar=" . (int)$_POST['total_bayar']);
            exit;
        }

        require_once 'views/parkir_keluar.php';
    }

    public function laporan() {
        $tanggal          = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');
        $data_laporan     = $this->model->getLaporanHarian($tanggal);
        $total_pendapatan = $this->model->getPendapatanHariIni($tanggal);
        require_once 'views/laporan.php';
    }

    public function cetak_pdf() {
        $tanggal          = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');
        $data_laporan     = $this->model->getLaporanHarian($tanggal);
        $total_pendapatan = $this->model->getPendapatanHariIni($tanggal);
        require_once 'views/cetak_pdf.php';
    }

    public function export_csv() {
        $tanggal      = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');
        $data_laporan = $this->model->getLaporanHarian($tanggal);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="laporan_parkir_' . $tanggal . '.csv"');

        $output = fopen('php://output', 'w');
        // BOM UTF-8 agar Excel baca benar
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        fputcsv($output, ['No', 'No. Plat', 'Jenis Kendaraan', 'Waktu Masuk', 'Waktu Keluar', 'Status', 'Total Bayar (Rp)']);
        $no = 1;
        while ($row = $data_laporan->fetch_assoc()) {
            fputcsv($output, [
                $no++,
                $row['nomor_plat'],
                $row['jenis_kendaraan'],
                $row['waktu_masuk'],
                $row['waktu_keluar'] ?? '-',
                $row['status'],
                $row['total_bayar']
            ]);
        }
        fclose($output);
        exit;
    }

    // ===== HAPUS data parkir (CRUD: Delete) =====
    public function hapus() {
        if (isset($_GET['id'])) {
            $id     = (int)$_GET['id'];
            $from   = isset($_GET['from']) ? $_GET['from'] : 'laporan';
            $tanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');
            $this->model->hapusParkir($id);
            if ($from === 'dashboard') {
                header("Location: index.php?page=dashboard&pesan=hapus_sukses");
            } else {
                header("Location: index.php?page=laporan&tanggal=$tanggal&pesan=hapus_sukses");
            }
            exit;
        }
        header("Location: index.php?page=laporan");
        exit;
    }
}
?>