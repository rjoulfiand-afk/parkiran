<?php
if(session_status()===PHP_SESSION_NONE) session_start();
if(!isset($_SESSION['id_admin'])) { header("Location: index.php"); exit; }

if(!isset($tanggal)) $tanggal = date('Y-m-d');

if(!function_exists('tgl_indo')) {
    function tgl_indo($t) {
        $bulan = [1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        $ts = strtotime($t);
        return date('j', $ts).' '.$bulan[(int)date('n', $ts)].' '.date('Y', $ts);
    }
}

$baris = [];
while($r = $data_laporan->fetch_assoc()) { $baris[] = $r; }
$jml_total   = count($baris);
$jml_selesai = 0;
foreach($baris as $b) { if($b['status'] == 'Selesai') $jml_selesai++; }
$jml_parkir  = $jml_total - $jml_selesai;
$tgl = htmlspecialchars($tanggal);
$rp  = function($n) { return 'Rp '.number_format($n, 0, ',', '.'); };
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Harian - Sistem Parkir</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f8fafc; color: #1e293b; font-size: 0.95rem; }
        
        .box-panel {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            overflow: hidden;
        }

        .toolbar-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            padding: 12px 16px;
        }
        .filter-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .btn-filter {
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 14px;
            font-size: 0.875rem;
            font-weight: 600;
        }
        .btn-export {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            color: #334155;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
        }
        .btn-export:hover {
            background: #f1f5f9;
            color: #0f172a;
            border-color: #94a3b8;
        }

        /* 4 Ringkasan Angka */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            border-top: 1px solid #e2e8f0;
            border-bottom: 1px solid #cbd5e1;
        }
        .summary-item {
            padding: 14px 18px;
            border-right: 1px solid #e2e8f0;
        }
        .summary-item:last-child { border-right: none; }
        .summary-item .label {
            font-size: 0.78rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
            font-weight: 600;
        }
        .summary-item .val {
            font-size: 1.35rem;
            font-weight: 700;
        }

        /* Hijau Segar Elegan (Emerald Medium) */
        .text-green-fresh {
            color: #16a34a !important;
        }

        @media (max-width: 768px) {
            .summary-grid { grid-template-columns: repeat(2, 1fr); }
            .summary-item:nth-child(2) { border-right: none; }
            .summary-item:nth-child(n+3) { border-top: 1px solid #e2e8f0; }
        }

        /* Header Blok Berwarna Slate Navy Sesuai Tema */
        .table-laporan {
            width: 100%;
            margin-bottom: 0;
            font-size: 0.9rem;
        }
        .table-laporan thead th {
            background: #1e293b;
            color: #ffffff;
            font-weight: 600;
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 11px 14px;
            border: none;
        }
        .table-laporan td {
            padding: 11px 14px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }
        .table-laporan tbody tr:hover {
            background-color: #f8fafc;
        }
        .table-laporan tfoot td {
            background: #f1f5f9;
            font-weight: 700;
            border-top: 2px solid #cbd5e1;
            padding: 13px 14px;
        }
    </style>
</head>
<body>
    <?php include 'views/layout/navbar.php'; ?>

    <main class="container pb-5">
        <div class="mb-3">
            <h4 class="fw-bold mb-0 text-dark">Laporan Transaksi Parkir</h4>
            <div class="text-muted small">Periode: <strong><?php echo tgl_indo($tanggal); ?></strong></div>
        </div>

        <div class="box-panel mb-4 shadow-sm">
            <div class="toolbar-container">
                <form action="index.php" method="GET" class="filter-group m-0">
                    <input type="hidden" name="page" value="laporan">
                    <span class="small fw-semibold text-secondary">Tanggal:</span>
                    <input type="date" name="tanggal" class="form-control form-control-sm" style="width: 160px;" value="<?php echo $tgl; ?>" required>
                    <button type="submit" class="btn btn-dark btn-sm btn-filter">
                        <i class="fa-solid fa-search"></i> Tampilkan
                    </button>
                </form>

                <div class="d-flex align-items-center gap-2">
                    <a href="index.php?page=cetak_pdf&tanggal=<?php echo urlencode($tanggal); ?>" target="_blank" class="btn-export">
                        <i class="fa-solid fa-print text-danger"></i> Cetak / PDF
                    </a>
                    <a href="index.php?page=export_csv&tanggal=<?php echo urlencode($tanggal); ?>" class="btn-export">
                        <i class="fa-solid fa-file-excel text-success"></i> Ekspor Excel
                    </a>
                </div>
            </div>

            <div class="summary-grid">
                <div class="summary-item">
                    <div class="label">Total Pendapatan</div>
            
                    <div class="val text-green-fresh"><?php echo $rp($total_pendapatan); ?></div>
                </div>
                <div class="summary-item">
                    <div class="label">Total Kendaraan</div>
                    <div class="val text-dark"><?php echo $jml_total; ?> <small class="fs-6 fw-normal text-muted">Unit</small></div>
                </div>
                <div class="summary-item">
                    <div class="label">Sudah Selesai</div>
                    <div class="val text-dark"><?php echo $jml_selesai; ?> <small class="fs-6 fw-normal text-muted">Unit</small></div>
                </div>
                <div class="summary-item">
                    <div class="label">Sedang Parkir</div>
                    <div class="val text-warning text-dark"><?php echo $jml_parkir; ?> <small class="fs-6 fw-normal text-muted">Unit</small></div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table-laporan">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 5%;">No</th>
                            <th style="width: 20%;">No. Plat</th>
                            <th style="width: 15%;">Jenis</th>
                            <th class="text-center" style="width: 15%;">Waktu Masuk</th>
                            <th class="text-center" style="width: 15%;">Waktu Keluar</th>
                            <th class="text-center" style="width: 13%;">Status</th>
                            <th class="text-end" style="width: 17%;">Total Bayar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($jml_total == 0): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                Tidak ada data transaksi parkir pada tanggal ini.
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach($baris as $i => $row): ?>
                        <tr>
                            <td class="text-center text-muted"><?php echo $i + 1; ?></td>
                            <td class="fw-bold text-dark"><?php echo htmlspecialchars($row['nomor_plat']); ?></td>
                            <td><?php echo htmlspecialchars($row['jenis_kendaraan']); ?></td>
                            <td class="text-center"><?php echo date('H:i', strtotime($row['waktu_masuk'])); ?></td>
                            <td class="text-center"><?php echo $row['waktu_keluar'] ? date('H:i', strtotime($row['waktu_keluar'])) : '<span class="text-muted">-</span>'; ?></td>
                            <td class="text-center">
                                <?php if($row['status'] == 'Selesai'): ?>
                                    <span class="badge bg-success px-2 py-1">Selesai</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark px-2 py-1">Parkir</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end fw-bold">
                                <?php echo $row['total_bayar'] > 0 ? $rp($row['total_bayar']) : '-'; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                    <?php if($jml_total > 0): ?>
                    <tfoot>
                        <tr>
                            <td colspan="6" class="text-end">TOTAL PENDAPATAN :</td>
                            <td class="text-end text-green-fresh fs-6"><?php echo $rp($total_pendapatan); ?></td>
                        </tr>
                    </tfoot>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </main>
</body>
</html>