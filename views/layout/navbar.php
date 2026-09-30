<?php if(session_status()===PHP_SESSION_NONE) session_start(); ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark py-2 mb-4 shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="index.php?page=dashboard">
            <i class="fa-solid fa-square-parking text-primary fs-4"></i>
            <span>Aplikasi Parkir</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMenu">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3">
                <li class="nav-item">
                    <a class="nav-link <?php echo (isset($_GET['page']) && $_GET['page']=='dashboard' ? 'active fw-bold' : ''); ?>"
                       href="index.php?page=dashboard">
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (isset($_GET['page']) && $_GET['page']=='parkir_masuk' ? 'active fw-bold' : ''); ?>"
                       href="index.php?page=parkir_masuk">
                        Parkir Masuk
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (isset($_GET['page']) && $_GET['page']=='parkir_keluar' ? 'active fw-bold' : ''); ?>"
                       href="index.php?page=parkir_keluar">
                        Parkir Keluar
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (isset($_GET['page']) && $_GET['page']=='laporan' ? 'active fw-bold' : ''); ?>"
                       href="index.php?page=laporan">
                        Laporan
                    </a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-3">
                <span class="navbar-text small text-white-50">
                    Petugas: <strong class="text-white"><?php echo isset($_SESSION['nama_petugas']) ? htmlspecialchars($_SESSION['nama_petugas']) : 'Admin'; ?></strong>
                </span>
                <button onclick="konfirmasiKeluar()" class="btn btn-outline-danger btn-sm">
                    Keluar
                </button>
            </div>
        </div>
    </div>
</nav>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function konfirmasiKeluar() {
    Swal.fire({
        title: 'Konfirmasi Keluar',
        text: 'Apakah Anda yakin ingin keluar dari sistem?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Keluar',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "index.php?page=logout";
        }
    });
}
</script>