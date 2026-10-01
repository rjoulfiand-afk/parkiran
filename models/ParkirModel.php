<?php
class ParkirModel {
    private $db;
    public function __construct($koneksi) {
        $this->db = $koneksi;
    }

    public function getParkirAktif() {
        return $this->db->query("SELECT * FROM tabel_parkir WHERE status = 'Parkir' ORDER BY waktu_masuk DESC");
    }

    public function getLaporanHarian($tanggal) {
        $stmt = $this->db->prepare("SELECT * FROM tabel_parkir WHERE DATE(waktu_masuk) = ? ORDER BY waktu_masuk DESC");
        $stmt->bind_param("s", $tanggal);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getPendapatanHariIni($tanggal) {
        $stmt = $this->db->prepare("SELECT SUM(total_bayar) as total FROM tabel_parkir WHERE DATE(waktu_keluar) = ? AND status = 'Selesai'");
        $stmt->bind_param("s", $tanggal);
        $stmt->execute();
        $result = $stmt->get_result();
        $data   = $result->fetch_assoc();
        return $data['total'] ? $data['total'] : 0;
    }

    public function catatMasuk($plat, $jenis, $waktu_masuk) {
        $plat = strtoupper($plat);
        $stmt = $this->db->prepare("INSERT INTO tabel_parkir (nomor_plat, jenis_kendaraan, waktu_masuk, status) VALUES (?, ?, ?, 'Parkir')");
        $stmt->bind_param("sss", $plat, $jenis, $waktu_masuk);
        return $stmt->execute();
    }

    public function cariKendaraanKeluar($plat) {
        $plat = strtoupper(trim($plat));
        $cari = "%$plat%";
        $stmt = $this->db->prepare("SELECT * FROM tabel_parkir WHERE nomor_plat LIKE ? AND status = 'Parkir' LIMIT 1");
        $stmt->bind_param("s", $cari);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function prosesKeluar($id_parkir, $waktu_keluar, $total_bayar) {
        $id    = (int)$id_parkir;
        $bayar = (int)$total_bayar;
        $stmt  = $this->db->prepare("UPDATE tabel_parkir SET waktu_keluar = ?, status = 'Selesai', total_bayar = ? WHERE id_parkir = ?");
        $stmt->bind_param("sii", $waktu_keluar, $bayar, $id);
        return $stmt->execute();
    }

    public function getTotalKendaraanHariIni($tanggal) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM tabel_parkir WHERE DATE(waktu_masuk) = ? AND status = 'Selesai'");
        $stmt->bind_param("s", $tanggal);
        $stmt->execute();
        $result = $stmt->get_result();
        $data   = $result->fetch_assoc();
        return $data['total'];
    }

    public function hapusParkir($id_parkir) {
        $id   = (int)$id_parkir;
        $stmt = $this->db->prepare("DELETE FROM tabel_parkir WHERE id_parkir = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
