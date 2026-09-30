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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">
    <?php include 'views/layout/navbar.php'; ?>

    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">

                <?php if(isset($_GET['pesan']) && $_GET['pesan']=='sukses_masuk'): ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Data kendaraan masuk telah disimpan.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                </script>
                <?php endif; ?>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="card-title fw-bold mb-0 text-dark">
                            <i class="fa-solid fa-arrow-right-to-bracket text-primary me-2"></i>Form Parkir Masuk
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="index.php?page=parkir_masuk" method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nomor Plat Kendaraan</label>
                                <input type="text"
                                       name="nomor_plat"
                                       class="form-control text-uppercase fw-bold"
                                       placeholder="Contoh: L 1234 AB"
                                       required
                                       autofocus>
                                <div class="form-text">Gunakan spasi antara kode wilayah dan nomor seri.</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Jenis Kendaraan</label>
                                <select name="jenis_kendaraan" class="form-select" required>
                                    <option value="Roda 2">Roda 2 (Sepeda Motor)</option>
                                    <option value="Roda 4">Roda 4 (Mobil)</option>
                                </select>
                            </div>

                            <!-- Info Tarif Bersih (Format Tabel Ringkas) -->
                            <div class="card bg-light border mb-4">
                                <div class="card-body p-3">
                                    <div class="small fw-bold text-muted mb-2">Informasi Tarif Parkir:</div>
                                    <table class="table table-sm table-bordered bg-white mb-0 text-center small">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Jenis</th>
                                                <th>Tarif Awal (≤ 2 Jam)</th>
                                                <th>Per Jam Berikutnya</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="fw-semibold">Roda 2</td>
                                                <td>Rp 2.000</td>
                                                <td>+ Rp 1.000 / jam</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold">Roda 4</td>
                                                <td>Rp 5.000</td>
                                                <td>+ Rp 1.000 / jam</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 fw-semibold py-2">
                                <i class="fa-solid fa-check me-1"></i> Simpan Transaksi Masuk
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>
</html>