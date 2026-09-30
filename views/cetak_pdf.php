<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['id_admin'])) { header("Location: index.php"); exit; }

if (!isset($tanggal)) $tanggal = date('Y-m-d');

if (!function_exists('tgl_indo')) {
    function tgl_indo($t) {
        $bulan = [1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        $ts = strtotime($t);
        return date('j', $ts) . ' ' . $bulan[(int)date('n', $ts)] . ' ' . date('Y', $ts);
    }
}

$petugas = isset($_SESSION['nama_petugas']) ? $_SESSION['nama_petugas'] : 'Petugas Parkir';

$baris = [];
while ($r = $data_laporan->fetch_assoc()) { 
    $baris[] = $r; 
}
$jml_total   = count($baris);
$jml_selesai = 0;
foreach ($baris as $b) { 
    if ($b['status'] == 'Selesai') $jml_selesai++; 
}
$jml_parkir  = $jml_total - $jml_selesai;
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Harian Parkir - <?php echo tgl_indo($tanggal); ?></title>
<style>
/* CSS Standar Dokumen A4 - Kompatibel Browser & Word */
* { margin: 0; padding: 0; box-sizing: border-box; }

body {
    background-color: #f1f5f9;
    font-family: Arial, Helvetica, sans-serif;
    color: #000000;
    font-size: 10pt;
    line-height: 1.3;
}

/* Toolbar Navigasi (Hanya di Layar) */
.no-print {
    background: #ffffff;
    border-bottom: 1px solid #cbd5e1;
    padding: 10px 20px;
    display: flex;
    align-items: center;
    gap: 12px;
}
.no-print button {
    padding: 6px 16px;
    font-size: 9.5pt;
    font-weight: bold;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}
.btn-cetak { background: #0f172a; color: #ffffff; }
.btn-tutup  { background: #e2e8f0; color: #334155; }
.tip-text   { font-size: 8.5pt; color: #64748b; }

/* Kertas Cetak A4 */
.sheet {
    width: 210mm;
    margin: 15px auto;
    padding: 15mm 15mm 15mm 15mm;
    background: #ffffff;
    border: 1px solid #cbd5e1;
}

table {
    width: 100%;
    border-collapse: collapse;
}

/* Kop Laporan */
.kop-title {
    font-size: 14pt;
    font-weight: bold;
    text-transform: uppercase;
    text-align: center;
    padding-bottom: 2px;
}
.kop-sub {
    font-size: 9pt;
    text-align: center;
    padding-bottom: 10px;
    border-bottom: 2px solid #000000;
}

/* Metadata */
.meta-table {
    margin-top: 12px;
    margin-bottom: 12px;
    font-size: 9pt;
}
.meta-table td {
    padding: 2px 0;
    vertical-align: top;
    border: none;
}

/* Tabel Data Laporan */
.data-table {
    margin-top: 5px;
    margin-bottom: 10px;
    font-size: 9pt;
}
.data-table th, .data-table td {
    border: 1px solid #000000;
    padding: 6px 8px;
    vertical-align: middle;
}
.data-table th {
    background-color: #f2f2f2 !important;
    font-weight: bold;
    text-align: center;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
}
.text-center { text-align: center; }
.text-right  { text-align: right; }
.fw-bold     { font-weight: bold; }

/* Aturan Print Otomatis Tanpa Header Jam Bawaan Browser */
@page {
    size: A4 portrait;
    margin: 0 !important;
}

@media print {
    body { 
        background: #ffffff !important; 
        margin: 0 !important;
        padding: 0 !important;
    }
    .no-print { 
        display: none !important; 
    }
    .sheet {
        width: 100% !important;
        margin: 0 !important;
        padding: 15mm 15mm 15mm 15mm !important;
        border: none !important;
    }
}
</style>
</head>
<body>

<div class="no-print">
    <button type="button" class="btn-cetak" onclick="window.print()">Cetak / Simpan PDF</button>
    <button type="button" class="btn-tutup" onclick="window.close()">Tutup Jendela</button>
    <span class="tip-text">Pilih <strong>Destination: Save as PDF</strong> untuk menyimpan dokumen.</span>
</div>

<div class="sheet">
    <!-- Kop Laporan -->
    <table>
        <tr>
            <td class="kop-title">Laporan Rekapitulasi Parkir Harian</td>
        </tr>
        <tr>
            <td class="kop-sub">Sistem Informasi Pengelolaan Parkir Kendaraan Bermotor</td>
        </tr>
    </table>

    <!-- Metadata Informasi -->
    <table class="meta-table">
        <tr>
            <td style="width: 18%;">Tanggal Laporan</td>
            <td style="width: 2%;">:</td>
            <td style="width: 45%;"><strong><?php echo tgl_indo($tanggal); ?></strong></td>
            <td style="width: 15%;">Waktu Cetak</td>
            <td style="width: 2%;">:</td>
            <td style="width: 18%;"><?php echo date('d/m/Y H:i'); ?> WIB</td>
        </tr>
        <tr>
            <td>Total Kendaraan</td>
            <td>:</td>
            <td><?php echo $jml_total; ?> Unit (<?php echo $jml_selesai; ?> Selesai, <?php echo $jml_parkir; ?> Masih Parkir)</td>
            <td>Petugas Kasir</td>
            <td>:</td>
            <td><?php echo htmlspecialchars($petugas); ?></td>
        </tr>
    </table>

    <!-- Tabel Rekap Data Transaksi -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 6%;">No</th>
                <th style="width: 18%;">Nomor Plat</th>
                <th style="width: 16%;">Jenis Kendaraan</th>
                <th style="width: 14%;">Waktu Masuk</th>
                <th style="width: 14%;">Waktu Keluar</th>
                <th style="width: 12%;">Status</th>
                <th style="width: 20%;">Total Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($jml_total == 0): ?>
            <tr>
                <td colspan="7" class="text-center" style="padding: 20px;">Tidak ada transaksi kendaraan pada tanggal ini.</td>
            </tr>
            <?php else: ?>
            <?php foreach ($baris as $i => $row): ?>
            <tr>
                <td class="text-center"><?php echo $i + 1; ?></td>
                <td class="text-center fw-bold"><?php echo htmlspecialchars($row['nomor_plat']); ?></td>
                <td class="text-center"><?php echo htmlspecialchars($row['jenis_kendaraan']); ?></td>
                <td class="text-center"><?php echo date('H:i', strtotime($row['waktu_masuk'])); ?></td>
                <td class="text-center"><?php echo $row['waktu_keluar'] ? date('H:i', strtotime($row['waktu_keluar'])) : '-'; ?></td>
                <td class="text-center"><?php echo htmlspecialchars($row['status']); ?></td>
                <td class="text-right">
                    <?php echo $row['total_bayar'] > 0 ? 'Rp ' . number_format($row['total_bayar'], 0, ',', '.') : '-'; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <tr style="background-color: #f2f2f2; font-weight: bold;">
                <td colspan="6" class="text-right">TOTAL PENDAPATAN :</td>
                <td class="text-right">Rp <?php echo number_format($total_pendapatan, 0, ',', '.'); ?></td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
window.addEventListener('load', function () {
    setTimeout(function () { window.print(); }, 500);
});
</script>

</body>
</html>