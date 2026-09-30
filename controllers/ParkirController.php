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
        $tanggal          = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');
        $data_laporan     = $this->model->getLaporanHarian($tanggal);
        $total_pendapatan = $this->model->getPendapatanHariIni($tanggal);
        $nama_petugas     = isset($_SESSION['nama_petugas']) ? $_SESSION['nama_petugas'] : 'Admin';

        header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
        header("Content-Disposition: attachment; filename=\"Laporan_Parkir_" . $tanggal . ".xls\"");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
        echo '<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
        echo '<!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet>';
        echo '<x:Name>Laporan Harian</x:Name>';
        echo '<x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions>';
        echo '</x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]-->';
        echo '<style>
                table { border-collapse: collapse; font-family: Arial, sans-serif; font-size: 10pt; }
                th { background-color: #1e293b; color: #ffffff; border: 1px solid #000; padding: 8px 10px; font-weight: bold; text-align: center; }
                td { border: 1px solid #000; padding: 6px 10px; vertical-align: middle; }
                .text-center { text-align: center; }
                .text-right { text-align: right; }
                .total { background-color: #f1f5f9; font-weight: bold; }
              </style>';
        echo '</head><body>';

        echo '<table>';
        // Atur lebar kolom paten di Excel (Anti #######)
        echo '<colgroup>';
        echo '<col width="50">';
        echo '<col width="130">';
        echo '<col width="130">';
        echo '<col width="120">';
        echo '<col width="120">';
        echo '<col width="110">';
        echo '<col width="150">';
        echo '</colgroup>';

        // Header Laporan
        echo '<tr><td colspan="7" style="font-size: 13pt; font-weight: bold; text-align: center; border: none;">LAPORAN TRANSAKSI PARKIR HARIAN</td></tr>';
        echo '<tr><td colspan="7" style="text-align: center; border: none;">Tanggal Periode: ' . $tanggal . '</td></tr>';
        echo '<tr><td colspan="7" style="text-align: center; border: none; font-size: 9pt; color: #555;">Petugas: ' . htmlspecialchars($nama_petugas) . ' | Waktu Unduh: ' . date('d/m/Y H:i') . ' WIB</td></tr>';
        echo '<tr><td colspan="7" style="border: none;">&nbsp;</td></tr>';

        // Header Tabel
        echo '<tr>';
        echo '<th>No</th>';
        echo '<th>Nomor Plat</th>';
        echo '<th>Jenis Kendaraan</th>';
        echo '<th>Waktu Masuk</th>';
        echo '<th>Waktu Keluar</th>';
        echo '<th>Status</th>';
        echo '<th>Total Bayar (Rp)</th>';
        echo '</tr>';

        $no = 1;
        while ($row = $data_laporan->fetch_assoc()) {
            // Gunakan format jam H:i agar pas di sel Excel dan tidak overflow
            $jam_masuk  = $row['waktu_masuk'] ? date('H:i', strtotime($row['waktu_masuk'])) : '-';
            $jam_keluar = $row['waktu_keluar'] ? date('H:i', strtotime($row['waktu_keluar'])) : '-';

            echo '<tr>';
            echo '<td class="text-center">' . $no++ . '</td>';
            echo '<td class="text-center" style="font-weight: bold;">' . htmlspecialchars($row['nomor_plat']) . '</td>';
            echo '<td class="text-center">' . htmlspecialchars($row['jenis_kendaraan']) . '</td>';
            echo '<td class="text-center">' . $jam_masuk . '</td>';
            echo '<td class="text-center">' . $jam_keluar . '</td>';
            echo '<td class="text-center">' . htmlspecialchars($row['status']) . '</td>';
            echo '<td class="text-right">' . number_format($row['total_bayar'], 0, ',', '.') . '</td>';
            echo '</tr>';
        }

        // Baris Total
        echo '<tr class="total">';
        echo '<td colspan="6" class="text-right">TOTAL PENDAPATAN :</td>';
        echo '<td class="text-right">Rp ' . number_format($total_pendapatan, 0, ',', '.') . '</td>';
        echo '</tr>';

        echo '</table>';
        echo '</body></html>';
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