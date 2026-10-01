<?php
if(session_status()===PHP_SESSION_NONE) session_start();
if(!isset($_SESSION['id_admin'])) { header("Location: index.php"); exit; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistem Parkir</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php include 'views/layout/navbar.php'; ?>

    <div class="container">

        <?php if(isset($_GET['pesan']) && $_GET['pesan']=='welcome'): ?>
        <script>
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Selamat datang, <?php echo htmlspecialchars($_SESSION['nama_petugas']); ?>!',
                showConfirmButton: false,
                timer: 2500
            });
        </script>
        <?php endif; ?>

        <?php if(isset($_GET['pesan']) && $_GET['pesan']=='hapus_sukses'): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Data Dihapus',
                text: 'Data parkir berhasil dihapus.',
                timer: 2000,
                showConfirmButton: false
            });
        </script>
        <?php endif; ?>

        <!-- Kartu Statistik -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center gap-3 p-4">
                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white"
                             style="width:52px;height:52px;font-size:22px;">
                            <i class="fa-solid fa-car"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-semibold">Sedang Parkir</div>
                            <div class="fw-bold fs-3"><?php echo $kendaraan_aktif->num_rows; ?> <small class="fs-6 text-muted">Unit</small></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center gap-3 p-4">
                        <div class="rounded-circle bg-success d-flex align-items-center justify-content-center text-white"
                             style="width:52px;height:52px;font-size:22px;">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-semibold">Selesai Hari Ini</div>
                            <div class="fw-bold fs-3"><?php echo $total_selesai; ?> <small class="fs-6 text-muted">Unit</small></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center gap-3 p-4">
                        <div class="rounded-circle bg-warning d-flex align-items-center justify-content-center text-dark"
                             style="width:52px;height:52px;font-size:22px;">
                            <i class="fa-solid fa-money-bill-wave"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-semibold">Pendapatan Hari Ini</div>
                            <div class="fw-bold fs-5">Rp <?php echo number_format($pendapatan,0,',','.'); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel kendaraan aktif -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h6 class="fw-bold mb-0"><i class="fa-solid fa-list me-1"></i> Kendaraan Sedang Parkir</h6>
                <a href="index.php?page=parkir_masuk" class="btn btn-sm btn-success fw-bold">
                    <i class="fa-solid fa-plus me-1"></i> Catat Masuk
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-secondary text-center">
                            <tr>
                                <th>No</th>
                                <th>No. Plat</th>
                                <th>Jenis Kendaraan</th>
                                <th>Waktu Masuk</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <?php $no = 1; $tmp = []; while($row = $kendaraan_aktif->fetch_assoc()) { $tmp[] = $row; } ?>
                            <?php foreach($tmp as $row): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td class="fw-bold text-primary"><?php echo htmlspecialchars($row['nomor_plat']); ?></td>
                                <td>
                                    <?php if($row['jenis_kendaraan'] == 'Roda 2'): ?>
                                        <span class="badge bg-info text-dark">Roda 2</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Roda 4</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($row['waktu_masuk'])); ?></td>
                                <td class="d-flex justify-content-center gap-2">
                                    <a href="index.php?page=parkir_keluar&cari_plat=<?php echo urlencode($row['nomor_plat']); ?>"
                                       class="btn btn-sm btn-outline-danger fw-semibold">
                                        <i class="fa-solid fa-arrow-right-from-bracket me-1"></i> Keluarkan
                                    </a>
                                    <button
                                        onclick="konfirmasiHapusDashboard(<?php echo $row['id_parkir']; ?>, '<?php echo htmlspecialchars($row['nomor_plat']); ?>')"
                                        class="btn btn-sm btn-outline-secondary fw-semibold">
                                        <i class="fa fa-trash me-1"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if(empty($tmp)): ?>
                            <tr>
                                <td colspan="5" class="text-muted py-4">Tidak ada kendaraan yang sedang parkir.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <div class="mt-5 py-3 text-center text-muted small border-top">
        Sistem Parkir &copy; <?php echo date('Y'); ?>
    </div>

    <script>
    function konfirmasiHapusDashboard(id, plat) {
        Swal.fire({
            title: 'Hapus Data?',
            html: 'Plat <strong>' + plat + '</strong> akan dihapus permanen.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'index.php?page=hapus&id=' + id + '&from=dashboard';
            }
        });
    }
    </script>

</body>
</html>
