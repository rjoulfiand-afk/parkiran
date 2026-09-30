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
    <style>
        .vehicle-option {
            display: flex; align-items: center; gap: .8rem; height: 100%;
            padding: .8rem 1rem; cursor: pointer;
            background: #fff; border: 1px solid #c4cad3; border-radius: 6px;
        }
        .vehicle-option i { font-size: 1.3rem; color: var(--ink-2); width: 1.6rem; text-align: center; }
        .vehicle-option strong { display: block; font-size: .95rem; line-height: 1.2; }
        .vehicle-option small { color: var(--ink-2); font-size: .8rem; }
        .vehicle-option:hover { border-color: #8d97a4; }
        .btn-check:checked + .vehicle-option { border-color: var(--signal); box-shadow: inset 0 0 0 1px var(--signal); background: #f3f7fc; }
        .btn-check:checked + .vehicle-option i { color: var(--signal); }
        .btn-check:focus-visible + .vehicle-option { outline: 3px solid rgba(27,79,143,.35); outline-offset: 1px; }

        .rate-table { width: 100%; font-size: .9rem; }
        .rate-table th { font-weight: 500; color: var(--ink-2); font-size: .8rem; padding: 0 0 .5rem; border-bottom: 1px solid var(--line); }
        .rate-table td { padding: .7rem 0; border-bottom: 1px solid var(--line); }
        .rate-table tr:last-child td { border-bottom: 0; padding-bottom: 0; }
        .rate-table td:first-child { font-weight: 600; }
        .rate-table th:not(:first-child), .rate-table td:not(:first-child) { text-align: right; }
    </style>
</head>
<body class="bg-light">
    <?php include 'views/layout/navbar.php'; ?>

    <main class="container pb-5">
        <div class="page-head">
            <h1>Parkir masuk</h1>
            <p>Catat kendaraan yang masuk ke area parkir.</p>
        </div>

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

        <div class="row g-4">
            <div class="col-lg-7">
                <form class="panel" action="index.php?page=parkir_masuk" method="POST">
                    <div class="panel-head">
                        <h2>Data kendaraan</h2>
                    </div>
                    <div class="panel-body">
                        <div class="mb-4">
                            <label class="field-label" for="nomor_plat">Nomor plat</label>
                            <input type="text"
                                   id="nomor_plat"
                                   name="nomor_plat"
                                   class="form-control plate-input"
                                   placeholder="L 1234 AB"
                                   oninput="this.value = this.value.toUpperCase()"
                                   autocomplete="off"
                                   required
                                   autofocus>
                            <div class="field-hint">Gunakan spasi antara kode wilayah dan nomor seri.</div>
                        </div>

                        <div class="mb-4">
                            <div class="field-label" id="label-jenis">Jenis kendaraan</div>
                            <div class="row g-2" role="radiogroup" aria-labelledby="label-jenis">
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="jenis_kendaraan" id="roda2" value="Roda 2" checked required>
                                    <label class="vehicle-option" for="roda2">
                                        <i class="fa-solid fa-motorcycle"></i>
                                        <span><strong>Roda 2</strong><small>Sepeda motor</small></span>
                                    </label>
                                </div>
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="jenis_kendaraan" id="roda4" value="Roda 4">
                                    <label class="vehicle-option" for="roda4">
                                        <i class="fa-solid fa-car-side"></i>
                                        <span><strong>Roda 4</strong><small>Mobil</small></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-signal w-100">
                            <i class="fa-solid fa-check me-1"></i> Simpan transaksi masuk
                        </button>
                    </div>
                </form>
            </div>

            <div class="col-lg-5">
                <div class="panel">
                    <div class="panel-head">
                        <h2>Tarif parkir</h2>
                    </div>
                    <div class="panel-body">
                        <table class="rate-table">
                            <thead>
                                <tr>
                                    <th>Jenis</th>
                                    <th>Tarif awal (≤ 2 jam)</th>
                                    <th>Per jam berikutnya</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Roda 2</td>
                                    <td>Rp 2.000</td>
                                    <td>+ Rp 1.000</td>
                                </tr>
                                <tr>
                                    <td>Roda 4</td>
                                    <td>Rp 5.000</td>
                                    <td>+ Rp 1.000</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>