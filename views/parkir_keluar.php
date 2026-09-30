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
</head>
<body class="bg-light">
    <?php include 'views/layout/navbar.php'; ?>

    <div class="container">

        <?php if(isset($_GET['pesan']) && $_GET['pesan']=='sukses_keluar'): ?>
        <script>
            // Pop up total biaya setelah kendaraan keluar (sesuai soal)
            Swal.fire({
                icon: 'success',
                title: 'Transaksi Selesai!',
                html: 'Pembayaran diterima.<br><strong>Total: Rp <?php echo number_format(isset($_GET["bayar"]) ? (int)$_GET["bayar"] : 0, 0, ",", "."); ?></strong>',
                confirmButtonColor: '#212529',
                confirmButtonText: 'OK'
            });
        </script>
        <?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-md-7">

                <!-- Form Pencarian -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-danger text-white py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Parkir Keluar
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <label class="form-label fw-semibold">Cari Kendaraan Berdasarkan Nomor Plat</label>
                        <form action="index.php" method="GET" class="d-flex gap-2">
                            <input type="hidden" name="page" value="parkir_keluar">
                            <input type="text"
                                   name="cari_plat"
                                   class="form-control form-control-lg text-uppercase fw-bold"
                                   placeholder="Ketik nomor plat..."
                                   value="<?php echo isset($_GET['cari_plat']) ? htmlspecialchars($_GET['cari_plat']) : ''; ?>"
                                   required
                                   autofocus>
                            <button type="submit" class="btn btn-danger btn-lg fw-bold px-4">
                                <i class="fa fa-search"></i> Cari
                            </button>
                        </form>
                    </div>
                </div>

                <?php if(isset($_GET['cari_plat'])): ?>

                    <?php if($kendaraan): ?>
                    <!-- Rincian Tagihan -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white py-3 border-bottom">
                            <h6 class="fw-bold mb-0"><i class="fa-solid fa-receipt me-1"></i> Rincian Tagihan</h6>
                        </div>
                        <div class="card-body p-4">
                            <table class="table table-borderless mb-4">
                                <tr>
                                    <td class="text-muted fw-semibold" style="width:45%">Nomor Plat</td>
                                    <td class="fw-bold text-primary fs-5"><?php echo htmlspecialchars($kendaraan['nomor_plat']); ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold">Jenis Kendaraan</td>
                                    <td>
                                        <?php if($kendaraan['jenis_kendaraan']=='Roda 2'): ?>
                                            <span class="badge bg-info text-dark">Roda 2 (Motor)</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Roda 4 (Mobil)</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold">Waktu Masuk</td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($kendaraan['waktu_masuk'])); ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold">Waktu Keluar</td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($waktu_keluar)); ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold">Durasi Parkir</td>
                                    <td class="fw-bold text-danger"><?php echo $durasi_jam; ?> Jam</td>
                                </tr>
                                <tr class="border-top">
                                    <td class="fw-bold fs-5 pt-3">Total Bayar</td>
                                    <td class="fw-bold fs-4 text-success pt-3">Rp <?php echo number_format($tagihan,0,',','.'); ?></td>
                                </tr>
                            </table>

                            <!-- Form konfirmasi keluar -->
                            <form action="index.php?page=parkir_keluar" method="POST">
                                <input type="hidden" name="id_parkir"    value="<?php echo $kendaraan['id_parkir']; ?>">
                                <input type="hidden" name="waktu_keluar" value="<?php echo $waktu_keluar; ?>">
                                <input type="hidden" name="total_bayar"  value="<?php echo $tagihan; ?>">
                                <button type="submit" class="btn btn-success w-100 fw-bold py-2 fs-6">
                                    <i class="fa-solid fa-check-circle me-1"></i> KONFIRMASI KELUAR &amp; TERIMA PEMBAYARAN
                                </button>
                            </form>
                        </div>
                    </div>

                    <?php else: ?>
                    <div class="alert alert-warning fw-semibold" role="alert">
                        <i class="fa fa-triangle-exclamation me-1"></i>
                        Kendaraan dengan plat <strong>"<?php echo htmlspecialchars(strtoupper($_GET['cari_plat'])); ?>"</strong>
                        tidak ditemukan atau sudah keluar.
                    </div>
                    <?php endif; ?>

                <?php endif; ?>

            </div>
        </div>
    </div>

</body>
</html>