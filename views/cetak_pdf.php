<?php
if(session_status()===PHP_SESSION_NONE) session_start();
if(!isset($_SESSION['id_admin'])) { header("Location: index.php"); exit; }

// Pastikan $tanggal tersedia
if(!isset($tanggal)) $tanggal = date('Y-m-d');
?><!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Parkir - <?php echo date('d F Y', strtotime($tanggal)); ?></title>
<style>
/* =========================================
   RESET
   ========================================= */
*, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }

/* =========================================
   HALAMAN LAYAR: area tombol
   ========================================= */
.area-tombol {
    background:#f4f4f4;
    border-bottom:1px solid #ccc;
    padding:10px 16px;
    display:flex;
    align-items:center;
    gap:10px;
}
.area-tombol button {
    padding:7px 18px;
    font-size:11pt;
    font-weight:bold;
    border:none;
    border-radius:4px;
    cursor:pointer;
}
.btn-cetak { background:#1a1a1a; color:#fff; }
.btn-tutup  { background:#777; color:#fff; }
.area-tombol .tip {
    font-size:9pt;
    color:#555;
    border-left:3px solid #aaa;
    padding-left:10px;
    margin-left:6px;
}

/* =========================================
   DOKUMEN LAPORAN
   ========================================= */
.dokumen {
    width:210mm;
    margin:0 auto;
    background:#fff;
    padding:20mm 18mm 18mm 18mm;
    font-family:Arial, Helvetica, sans-serif;
    font-size:10.5pt;
    color:#000;
}

/* Kop surat */
.kop {
    text-align:center;
    border-bottom:3px solid #000;
    padding-bottom:10px;
    margin-bottom:16px;
}
.kop h2 {
    font-size:14pt;
    font-weight:bold;
    text-transform:uppercase;
    letter-spacing:1px;
    color:#000;
}
.kop .sub {
    font-size:10pt;
    color:#222;
    margin-top:5px;
}

/* Tabel */
table {
    width:100%;
    border-collapse:collapse;
    margin-top:4px;
}
thead th {
    background:#222 !important;
    color:#fff !important;
    font-size:9.5pt;
    font-weight:bold;
    text-align:center;
    padding:7px 5px;
    border:1px solid #222;
}
tbody td {
    font-size:9.5pt;
    color:#000;
    padding:6px 5px;
    border:1px solid #bbb;
    text-align:center;
}
tbody tr:nth-child(even) td { background:#f6f6f6; }

/* Baris total */
.baris-total td {
    background:#e0e0e0 !important;
    font-weight:bold;
    font-size:10pt;
    border-top:2px solid #000;
}

/* Status */
.selesai { color:#006400; font-weight:bold; }
.parkir  { color:#8B4500; font-weight:bold; }

/* Nomor plat */
.plat { font-weight:bold; color:#00008B; }

/* Footer */
.footer-lap {
    margin-top:24px;
    font-size:9pt;
    color:#444;
    display:flex;
    justify-content:space-between;
    border-top:1px solid #ccc;
    padding-top:7px;
}

/* =========================================
   CSS CETAK — sembunyikan tombol, atur halaman
   ========================================= */
@page {
    size: A4 portrait;
    margin: 15mm 12mm 15mm 12mm;
}

@media print {
    /* Sembunyikan TOTAL area tombol saat cetak */
    .area-tombol {
        display: none !important;
        visibility: hidden !important;
        height: 0 !important;
        overflow: hidden !important;
    }

    /* Atur dokumen agar penuh satu halaman */
    .dokumen {
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    body {
        background: #fff !important;
        /* Paksa browser cetak warna background */
        print-color-adjust: exact !important;
        -webkit-print-color-adjust: exact !important;
    }

    /* Paksa warna thead tetap hitam saat cetak */
    thead th {
        background: #222 !important;
        color: #fff !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    /* Paksa baris genap tetap berwarna */
    tbody tr:nth-child(even) td {
        background: #f6f6f6 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    .baris-total td {
        background: #e0e0e0 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
}
</style>
</head>
<body>

<!-- ======================================
     AREA TOMBOL — tidak ikut tercetak
     ====================================== -->
<div class="area-tombol">
    <button class="btn-cetak" onclick="window.print()">&#128438; Cetak / Simpan PDF</button>
    <button class="btn-tutup" onclick="window.close()">&#10006; Tutup</button>
    <span class="tip">
        Saat dialog print muncul &rarr; hilangkan centang <strong>"Headers and footers"</strong>
        agar halaman bersih (tanpa tanggal/judul dari browser).
    </span>
</div>

<!-- ======================================
     DOKUMEN LAPORAN (yang dicetak)
     ====================================== -->
<div class="dokumen">

    <div class="kop">
        <h2>Laporan Harian Parkir</h2>
        <div class="sub">
            Tanggal &nbsp;: <strong><?php echo date('d F Y', strtotime($tanggal)); ?></strong><br>
            Dicetak oleh : <?php echo htmlspecialchars($_SESSION['nama_petugas']); ?>
            &mdash; <?php echo date('d/m/Y H:i'); ?> WIB
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:5%">No</th>
                <th style="width:15%">No. Plat</th>
                <th style="width:15%">Jenis Kendaraan</th>
                <th style="width:13%">Waktu Masuk</th>
                <th style="width:13%">Waktu Keluar</th>
                <th style="width:12%">Status</th>
                <th style="width:17%">Total Bayar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $ada_data = false;
            while($row = $data_laporan->fetch_assoc()):
                $ada_data = true;
            ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td class="plat"><?php echo htmlspecialchars($row['nomor_plat']); ?></td>
                <td><?php echo htmlspecialchars($row['jenis_kendaraan']); ?></td>
                <td><?php echo date('H:i', strtotime($row['waktu_masuk'])); ?></td>
                <td><?php echo $row['waktu_keluar'] ? date('H:i', strtotime($row['waktu_keluar'])) : '-'; ?></td>
                <td>
                    <?php if($row['status'] == 'Selesai'): ?>
                        <span class="selesai">Selesai</span>
                    <?php else: ?>
                        <span class="parkir">Parkir</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php echo $row['total_bayar'] > 0
                        ? 'Rp '.number_format($row['total_bayar'], 0, ',', '.')
                        : '-'; ?>
                </td>
            </tr>
            <?php endwhile; ?>

            <?php if(!$ada_data): ?>
            <tr>
                <td colspan="7" style="text-align:center;padding:20px;color:#666;font-style:italic;">
                    Tidak ada data parkir pada tanggal ini.
                </td>
            </tr>
            <?php else: ?>
            <tr class="baris-total">
                <td colspan="6" style="text-align:right;padding-right:12px;">
                    TOTAL PENDAPATAN :
                </td>
                <td>Rp <?php echo number_format($total_pendapatan, 0, ',', '.'); ?></td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer-lap">
        <span>Sistem Informasi Parkir</span>
        <span>Halaman 1</span>
    </div>

</div><!-- end .dokumen -->

<script>
// Auto buka dialog print setelah halaman selesai load sempurna
window.addEventListener('load', function() {
    setTimeout(function() {
        window.print();
    }, 600);
});
</script>

</body>
</html>
