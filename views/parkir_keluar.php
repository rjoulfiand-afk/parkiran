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
</head>
<body class="bg-light">
    <?php include 'views/layout/navbar.php'; ?>

    <div class="container mb-5">

        <?php if(isset($_GET['pesan']) && $_GET['pesan']=='sukses_keluar'): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Pembayaran Selesai',
                html: 'Kendaraan telah diproses keluar.<br><strong class="fs-5 text-success">Total Bayar: Rp <?php echo number_format(isset($_GET["bayar"]) ? (int)$_GET["bayar"] : 0, 0, ",", "."); ?></strong>',
                confirmButtonColor: '#0d6efd'
            });
        </script>
        <?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">

                <!-- Form Pencarian -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="card-title fw-bold mb-0 text-dark">
                            <i class="fa-solid fa-arrow-right-from-bracket text-danger me-2"></i>Proses Parkir Keluar
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="index.php" method="GET">
                            <input type="hidden" name="page" value="parkir_keluar">
                            <label class="form-label fw-semibold">Pencarian Nomor Plat</label>
                            <div class="input-group">
                                <input type="text"
                                       name="cari_plat"
                                       class="form-control text-uppercase fw-bold"
                                       placeholder="Ketik nomor plat kendaraan..."
                                       value="<?php echo isset($_GET['cari_plat']) ? htmlspecialchars($_GET['cari_plat']) : ''; ?>"
                                       required
                                       autofocus>
                                <button type="submit" class="btn btn-primary px-4 fw-semibold">
                                    <i class="fa-solid fa-search me-1"></i> Cari
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <?php if(isset($_GET['cari_plat'])): ?>

                    <?php if($kendaraan): ?>
                    <!-- Rincian Transaksi -->
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold mb-0 text-dark">Rincian Pembayaran Parkir</h6>
                            <span class="badge bg-secondary">ID Transaksi: #<?php echo $kendaraan['id_parkir']; ?></span>
                        </div>
                        <div class="card-body p-4">
                            <table class="table table-bordered mb-4">
                                <tbody>
                                    <tr>
                                        <th class="table-light" style="width: 40%;">Nomor Plat</th>
                                        <td class="fw-bold text-primary fs-5"><?php echo htmlspecialchars($kendaraan['nomor_plat']); ?></td>
                                    </tr>
                                    <tr>
                                        <th class="table-light">Jenis Kendaraan</th>
                                        <td><?php echo htmlspecialchars($kendaraan['jenis_kendaraan']); ?></td>
                                    </tr>
                                    <tr>
                                        <th class="table-light">Waktu Masuk</th>
                                        <td><?php echo date('d/m/Y H:i', strtotime($kendaraan['waktu_masuk'])); ?> WIB</td>
                                    </tr>
                                    <tr>
                                        <th class="table-light">Waktu Keluar</th>
                                        <td><?php echo date('d/m/Y H:i', strtotime($waktu_keluar)); ?> WIB</td>
                                    </tr>
                                    <tr>
                                        <th class="table-light">Durasi Parkir</th>
                                        <td class="fw-bold text-danger"><?php echo $durasi_jam; ?> Jam</td>
                                    </tr>
                                    <tr class="table-light border-top border-dark">
                                        <th class="align-middle fs-6">Total Biaya Parkir</th>
                                        <td class="fw-bold fs-4 text-success">
                                            Rp <?php echo number_format($tagihan, 0, ',', '.'); ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <form action="index.php?page=parkir_keluar" method="POST">
                                <input type="hidden" name="id_parkir" value="<?php echo $kendaraan['id_parkir']; ?>">
                                <input type="hidden" name="waktu_keluar" value="<?php echo $waktu_keluar; ?>">
                                <input type="hidden" name="total_bayar" value="<?php echo $tagihan; ?>">
                                <button type="submit" class="btn btn-success w-100 fw-bold py-2">
                                    <i class="fa-solid fa-check-circle me-1"></i> Proses Selesai &amp; Terima Uang
                                </button>
                            </form>
                        </div>
                    </div>

                    <?php else: ?>
                    <div class="alert alert-warning border text-dark">
                        <i class="fa-solid fa-circle-exclamation me-1"></i>
                        Data kendaraan dengan plat <strong>"<?php echo htmlspecialchars(strtoupper($_GET['cari_plat'])); ?>"</strong> tidak ditemukan dalam daftar parkir aktif.
                    </div>
                    <?php endif; ?>

                <?php endif; ?>

            </div>
        </div>
    </div>
</body>
</html>