<?php
if(session_status()===PHP_SESSION_NONE) session_start();
if(!isset($_SESSION['id_admin'])) { header("Location: index.php"); exit; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parkir Keluar - Sistem Parkir</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .id-tag { font-size: .78rem; color: var(--ink-2); border: 1px solid var(--line); border-radius: 4px; padding: .1rem .5rem; }

        .detail { margin: 0; }
        .detail > div { display: flex; justify-content: space-between; align-items: center; gap: 1rem; padding: .7rem 0; border-bottom: 1px solid var(--line); }
        .detail > div:first-child { padding-top: 0; }
        .detail dt { font-weight: 500; font-size: .9rem; color: var(--ink-2); }
        .detail dd { margin: 0; font-weight: 600; text-align: right; }

        .total { display: flex; justify-content: space-between; align-items: baseline; gap: 1rem; margin: 1.25rem 0; padding: 1rem 1.1rem; background: var(--paper); border: 1px solid var(--line); border-radius: 6px; }
        .total span { font-weight: 600; }
        .total strong { font-size: 1.9rem; font-weight: 700; color: var(--ok); }

        .empty-state { padding: 2.5rem 1.5rem; text-align: center; color: var(--ink-2); border: 1px dashed #c4cad3; border-radius: 8px; }
        .empty-state i { font-size: 1.6rem; margin-bottom: .75rem; color: #8d97a4; }
        .empty-state p { margin: 0; font-size: .92rem; }
    </style>
</head>
<body class="bg-light">
    <?php include 'views/layout/navbar.php'; ?>

    <main class="container pb-5">
        <div class="page-head">
            <h1>Parkir keluar</h1>
            <p>Cari kendaraan berdasarkan nomor plat, lalu proses pembayaran.</p>
        </div>

        <?php if(isset($_GET['pesan']) && $_GET['pesan']=='sukses_keluar'): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Pembayaran selesai',
                html: 'Kendaraan telah diproses keluar.<br><strong class="fs-5 text-success">Total bayar: Rp <?php echo number_format(isset($_GET["bayar"]) ? (int)$_GET["bayar"] : 0, 0, ",", "."); ?></strong>',
                confirmButtonColor: '#1b4f8f'
            });
        </script>
        <?php endif; ?>

        <div class="row g-4">
            <!-- Pencarian -->
            <div class="col-lg-5">
                <form class="panel" action="index.php" method="GET">
                    <input type="hidden" name="page" value="parkir_keluar">
                    <div class="panel-head">
                        <h2>Cari kendaraan</h2>
                    </div>
                    <div class="panel-body">
                        <label class="field-label" for="cari_plat">Nomor plat</label>
                        <div class="input-group">
                            <input type="text"
                                   id="cari_plat"
                                   name="cari_plat"
                                   class="form-control text-uppercase fw-semibold"
                                   placeholder="L 1234 AB"
                                   value="<?php echo isset($_GET['cari_plat']) ? htmlspecialchars($_GET['cari_plat']) : ''; ?>"
                                   autocomplete="off"
                                   required
                                   autofocus>
                            <button type="submit" class="btn btn-signal px-3">
                                <i class="fa-solid fa-magnifying-glass me-1"></i> Cari
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Hasil -->
            <div class="col-lg-7">
                <?php if(isset($_GET['cari_plat'])): ?>

                    <?php if($kendaraan): ?>
                    <div class="panel">
                        <div class="panel-head">
                            <h2>Rincian pembayaran</h2>
                            <span class="id-tag">Transaksi #<?php echo (int)$kendaraan['id_parkir']; ?></span>
                        </div>
                        <div class="panel-body">
                            <dl class="detail">
                                <div>
                                    <dt>Nomor plat</dt>
                                    <dd><span class="plate"><?php echo htmlspecialchars($kendaraan['nomor_plat']); ?></span></dd>
                                </div>
                                <div>
                                    <dt>Jenis kendaraan</dt>
                                    <dd><?php echo htmlspecialchars($kendaraan['jenis_kendaraan']); ?></dd>
                                </div>
                                <div>
                                    <dt>Waktu masuk</dt>
                                    <dd><?php echo date('d/m/Y H:i', strtotime($kendaraan['waktu_masuk'])); ?> WIB</dd>
                                </div>
                                <div>
                                    <dt>Waktu keluar</dt>
                                    <dd><?php echo date('d/m/Y H:i', strtotime($waktu_keluar)); ?> WIB</dd>
                                </div>
                                <div>
                                    <dt>Durasi parkir</dt>
                                    <dd><?php echo htmlspecialchars($durasi_jam); ?> jam</dd>
                                </div>
                            </dl>

                            <div class="total">
                                <span>Total biaya parkir</span>
                                <strong>Rp <?php echo number_format($tagihan, 0, ',', '.'); ?></strong>
                            </div>

                            <form action="index.php?page=parkir_keluar" method="POST">
                                <input type="hidden" name="id_parkir" value="<?php echo (int)$kendaraan['id_parkir']; ?>">
                                <input type="hidden" name="waktu_keluar" value="<?php echo htmlspecialchars($waktu_keluar); ?>">
                                <input type="hidden" name="total_bayar" value="<?php echo htmlspecialchars($tagihan); ?>">
                                <button type="submit" class="btn btn-confirm w-100">
                                    <i class="fa-solid fa-check-circle me-1"></i> Terima pembayaran &amp; keluarkan kendaraan
                                </button>
                            </form>
                        </div>
                    </div>

                    <?php else: ?>
                    <div class="notice">
                        <i class="fa-solid fa-circle-exclamation mt-1"></i>
                        <div>
                            Kendaraan dengan plat <strong><?php echo htmlspecialchars(strtoupper($_GET['cari_plat'])); ?></strong> tidak ditemukan di daftar parkir aktif. Periksa spasi dan huruf pada nomor plat, lalu coba lagi.
                        </div>
                    </div>
                    <?php endif; ?>

                <?php else: ?>
                    <div class="empty-state">
                        <i class="fa-solid fa-receipt d-block"></i>
                        <p>Masukkan nomor plat untuk melihat durasi dan tagihan parkir.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>
</html>