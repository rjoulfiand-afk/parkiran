<?php if(session_status()===PHP_SESSION_NONE) session_start(); ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm py-2 mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php?page=dashboard">
            <i class="fa-solid fa-square-parking me-1 text-warning"></i> E-Parkir
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarMenu">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?php echo (isset($_GET['page']) && $_GET['page']=='dashboard' ? 'active fw-bold' : ''); ?>"
                       href="index.php?page=dashboard">
                        <i class="fa-solid fa-gauge me-1"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (isset($_GET['page']) && $_GET['page']=='parkir_masuk' ? 'active fw-bold' : ''); ?>"
                       href="index.php?page=parkir_masuk">
                        <i class="fa-solid fa-arrow-right-to-bracket me-1"></i> Parkir Masuk
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (isset($_GET['page']) && $_GET['page']=='parkir_keluar' ? 'active fw-bold' : ''); ?>"
                       href="index.php?page=parkir_keluar">
                        <i class="fa-solid fa-arrow-right-from-bracket me-1"></i> Parkir Keluar
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (isset($_GET['page']) && $_GET['page']=='laporan' ? 'active fw-bold' : ''); ?>"
                       href="index.php?page=laporan">
                        <i class="fa-solid fa-file-lines me-1"></i> Laporan
                    </a>
                </li>
            </ul>
            <div class="d-flex align-items-center gap-2">
                <span class="text-white-50 small">
                    <i class="fa fa-user-circle me-1"></i>
                    <?php echo isset($_SESSION['nama_petugas']) ? htmlspecialchars($_SESSION['nama_petugas']) : ''; ?>
                </span>
                <button onclick="konfirmasiKeluar()" class="btn btn-warning btn-sm fw-bold text-dark px-3">
                    <i class="fa fa-sign-out-alt me-1"></i> Keluar
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
        title: 'Keluar dari Aplikasi?',
        text: 'Anda harus login kembali untuk mengakses sistem.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#212529',
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