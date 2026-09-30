<?php
if(session_status()===PHP_SESSION_NONE) session_start();
if(!isset($_SESSION['id_admin'])) { header("Location: index.php"); exit; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Harian - Sistem Parkir</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php include 'views/layout/navbar.php'; ?>

    <div class="container">

        <!-- Alert sukses hapus -->
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

        <!-- Filter tanggal & tombol export -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-3">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <form action="index.php" method="GET" class="d-flex align-items-center gap-2">
                        <input type="hidden" name="page" value="laporan">
                        <label class="fw-semibold mb-0 text-muted small">Tanggal:</label>
                        <input type="date" name="tanggal" class="form-control" value="<?php echo $tanggal; ?>" required>
                        <button type="submit" class="btn btn-primary fw-semibold px-3">Tampilkan</button>
                    </form>
                    <div class="d-flex gap-2">
                        <a href="index.php?page=cetak_pdf&tanggal=<?php echo $tanggal; ?>"
                           target="_blank"
                           class="btn btn-danger fw-semibold">
                            <i class="fa-solid fa-file-pdf me-1"></i> Unduh PDF
                        </a>
                        <a href="index.php?page=export_csv&tanggal=<?php echo $tanggal; ?>"
                           class="btn btn-success fw-semibold">
                            <i class="fa-solid fa-file-excel me-1"></i> Unduh Spreadsheet
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel laporan -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0">
                    <i class="fa-solid fa-file-lines me-1"></i>
                    Laporan Harian: <?php echo date('d F Y', strtotime($tanggal)); ?>
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0 align-middle">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>No</th>
                                <th>No. Plat</th>
                                <th>Jenis</th>
                                <th>Waktu Masuk</th>
                                <th>Waktu Keluar</th>
                                <th>Status</th>
                                <th>Total Bayar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $ada_data = false;
                            while($row = $data_laporan->fetch_assoc()):
                                $ada_data = true;
                            ?>
                            <tr class="text-center">
                                <td><?php echo $no++; ?></td>
                                <td class="fw-bold text-primary"><?php echo htmlspecialchars($row['nomor_plat']); ?></td>
                                <td><?php echo $row['jenis_kendaraan']; ?></td>
                                <td><?php echo date('H:i', strtotime($row['waktu_masuk'])); ?></td>
                                <td><?php echo $row['waktu_keluar'] ? date('H:i', strtotime($row['waktu_keluar'])) : '-'; ?></td>
                                <td>
                                    <?php if($row['status']=='Selesai'): ?>
                                        <span class="badge bg-success">Selesai</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">Parkir</span>
                                    <?php endif; ?>
                                </td>
                                <td class="fw-semibold text-success">
                                    <?php echo $row['total_bayar'] > 0 ? 'Rp '.number_format($row['total_bayar'],0,',','.') : '-'; ?>
                                </td>
                                <td>
                                    <button
                                        onclick="konfirmasiHapus(<?php echo $row['id_parkir']; ?>, '<?php echo htmlspecialchars($row['nomor_plat']); ?>', '<?php echo $tanggal; ?>')"
                                        class="btn btn-sm btn-outline-danger fw-semibold">
                                        <i class="fa fa-trash me-1"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                            <?php endwhile; ?>

                            <?php if(!$ada_data): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    Tidak ada data parkir pada tanggal ini.
                                </td>
                            </tr>
                            <?php else: ?>
                            <tr class="table-light fw-bold">
                                <td colspan="7" class="text-end pe-3">Total Pendapatan:</td>
                                <td class="text-center text-success">
                                    Rp <?php echo number_format($total_pendapatan,0,',','.'); ?>
                                </td>
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
    function konfirmasiHapus(id, plat, tanggal) {
        Swal.fire({
            title: 'Hapus Data Parkir?',
            html: 'Data kendaraan plat <strong>' + plat + '</strong> akan dihapus permanen.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'index.php?page=hapus&id=' + id + '&from=laporan&tanggal=' + tanggal;
            }
        });
    }
    </script>

</body>
</html>