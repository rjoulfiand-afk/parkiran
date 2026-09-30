<?php
if(session_status()===PHP_SESSION_NONE) session_start();
if(!isset($_SESSION['id_admin'])) { header("Location: index.php"); exit; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parkir Masuk - Sistem Parkir</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php include 'views/layout/navbar.php'; ?>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">

                <?php if(isset($_GET['pesan']) && $_GET['pesan']=='sukses_masuk'): ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Kendaraan berhasil dicatat masuk.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                </script>
                <?php endif; ?>

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-success text-white py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="fa-solid fa-arrow-right-to-bracket me-2"></i> Catat Kendaraan Masuk
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="index.php?page=parkir_masuk" method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nomor Plat Kendaraan</label>
                                <input type="text"
                                       name="nomor_plat"
                                       class="form-control form-control-lg text-uppercase fw-bold"
                                       placeholder="Contoh: L 1234 AB"
                                       required
                                       autofocus>
                                <div class="form-text">Masukkan nomor plat sesuai STNK.</div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Jenis Kendaraan</label>
                                <select name="jenis_kendaraan" class="form-select form-select-lg fw-bold" required>
                                    <option value="Roda 2">Motor (Roda 2)</option>
                                    <option value="Roda 4">Mobil (Roda 4)</option>
                                </select>
                            </div>
                            <div class="mb-3 p-3 rounded border" style="background:#f8f9fa;">
                                <div class="fw-semibold text-muted small mb-2">
                                    <i class="fa-solid fa-circle-info me-1"></i> Info Tarif Parkir
                                </div>
                                <div class="d-flex justify-content-between align-items-start gap-2">
                                    <div class="flex-fill p-2 bg-white rounded border text-center">
                                        <div class="small fw-bold text-secondary mb-1">
                                            <i class="fa-solid fa-motorcycle me-1"></i> Roda 2 (Motor)
                                        </div>
                                        <div class="small text-dark">Tarif awal <strong>Rp 2.000</strong></div>
                                        <div class="small text-muted">+Rp 1.000/jam setelah 2 jam</div>
                                    </div>
                                    <div class="flex-fill p-2 bg-white rounded border text-center">
                                        <div class="small fw-bold text-secondary mb-1">
                                            <i class="fa-solid fa-car me-1"></i> Roda 4 (Mobil)
                                        </div>
                                        <div class="small text-dark">Tarif awal <strong>Rp 5.000</strong></div>
                                        <div class="small text-muted">+Rp 1.000/jam setelah 2 jam</div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success w-100 fw-bold py-2">
                                <i class="fa-solid fa-floppy-disk me-1"></i> SIMPAN DATA MASUK
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>