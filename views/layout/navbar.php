<?php
if(session_status()===PHP_SESSION_NONE) session_start();

$halaman = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$menu = [
    'dashboard'     => ['Dashboard',     'fa-gauge-high'],
    'parkir_masuk'  => ['Parkir masuk',  'fa-arrow-right-to-bracket'],
    'parkir_keluar' => ['Parkir keluar', 'fa-arrow-right-from-bracket'],
    'laporan'       => ['Laporan',       'fa-file-lines'],
];
$nama_petugas = isset($_SESSION['nama_petugas']) ? $_SESSION['nama_petugas'] : 'Admin';
$inisial = strtoupper(substr($nama_petugas, 0, 1));
?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Barlow+Semi+Condensed:wght@600&family=Public+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    :root {
        --ink: #22262b;
        --ink-2: #5b6572;
        --paper: #f2f3f5;
        --line: #d9dde3;
        --signal: #1b4f8f;
        --signal-dark: #153f73;
        --ok: #1f6b45;
        --bad: #a5281b;
        --plate-text: #f5f5f0;
    }
    body { font-family: 'Public Sans', system-ui, sans-serif; color: var(--ink); font-variant-numeric: tabular-nums; }
    body.bg-light { background-color: var(--paper) !important; }

    /* ---------- Navbar ---------- */
    .app-nav { background: var(--ink); position: sticky; top: 0; z-index: 1030; margin-bottom: 2rem; }
    .app-nav .navbar { --bs-navbar-nav-link-padding-x: .85rem; --bs-navbar-padding-y: 0; min-height: 56px; }
    .app-nav .navbar-toggler { border-color: #454d57; margin: .6rem 0; }
    .app-nav .navbar-toggler:focus { box-shadow: 0 0 0 3px rgba(110,168,234,.4); }

    .brand { display: flex; align-items: center; gap: .65rem; color: #fff; text-decoration: none; font-weight: 600; }
    .brand:hover { color: #fff; }
    .brand-mark {
        width: 30px; height: 30px; display: grid; place-items: center;
        background: var(--signal); border: 1.5px solid rgba(255,255,255,.85); border-radius: 5px;
        font-weight: 700; font-size: 1.05rem; line-height: 1;
    }

    .app-nav .navbar-nav .nav-link {
        display: flex; align-items: center; gap: .5rem; position: relative;
        padding-top: .9rem; padding-bottom: .9rem;
        color: #aeb6c1; font-size: .9rem; font-weight: 500;
    }
    .app-nav .navbar-nav .nav-link:hover,
    .app-nav .navbar-nav .nav-link.active { color: #fff; }
    .app-nav .nav-link i { font-size: .8rem; opacity: .75; }

    .user-chip { display: flex; align-items: center; gap: .6rem; color: #fff; font-size: .85rem; line-height: 1.25; }
    .user-chip .avatar {
        width: 30px; height: 30px; border-radius: 50%; background: #3a414a;
        display: grid; place-items: center; font-weight: 600; font-size: .8rem;
    }
    .user-chip small { display: block; color: #8d97a4; font-size: .72rem; }
    .btn-logout {
        color: #c5ccd6; background: transparent; border: 1px solid #454d57;
        border-radius: 6px; padding: .35rem .8rem; font-size: .85rem;
    }
    .btn-logout:hover { color: #fff; border-color: #7b8593; background: rgba(255,255,255,.06); }

    @media (min-width: 992px) {
        .app-nav .navbar-nav .nav-link.active::after {
            content: ""; position: absolute; left: .85rem; right: .85rem; bottom: 0; height: 2px; background: #6ea8ea;
        }
        .user-chip-wrap { padding-left: 1.25rem; margin-left: .5rem; border-left: 1px solid #3a414a; }
    }
    @media (max-width: 991.98px) {
        .app-nav .navbar-collapse { padding: .25rem 0 1rem; }
        .app-nav .navbar-nav .nav-link { padding: .65rem .75rem; border-radius: 6px; }
        .app-nav .navbar-nav .nav-link.active { background: rgba(255,255,255,.08); }
        .user-chip-wrap { padding-top: .9rem; margin-top: .5rem; border-top: 1px solid #3a414a; justify-content: space-between; }
    }

    /* ---------- Komponen halaman (dipakai bersama) ---------- */
    .page-head { margin-bottom: 1.5rem; }
    .page-head h1 { font-size: 1.4rem; font-weight: 700; margin: 0; }
    .page-head p { margin: .25rem 0 0; color: var(--ink-2); font-size: .92rem; }

    .panel { background: #fff; border: 1px solid var(--line); border-radius: 8px; }
    .panel-head { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: .9rem 1.25rem; border-bottom: 1px solid var(--line); }
    .panel-head h2 { font-size: .98rem; font-weight: 600; margin: 0; }
    .panel-body { padding: 1.25rem; }

    .field-label { display: block; font-size: .85rem; font-weight: 600; margin-bottom: .4rem; }
    .field-hint { font-size: .8rem; color: var(--ink-2); margin-top: .45rem; }
    .form-control { border-color: #c4cad3; border-radius: 6px; }
    .form-control:focus { border-color: var(--signal); box-shadow: 0 0 0 3px rgba(27,79,143,.18); }

    .btn-signal { background: var(--signal); border: 1px solid var(--signal); color: #fff; font-weight: 600; border-radius: 6px; padding: .6rem 1rem; }
    .btn-signal:hover, .btn-signal:focus-visible { background: var(--signal-dark); border-color: var(--signal-dark); color: #fff; }
    .btn-confirm { background: var(--ok); border: 1px solid var(--ok); color: #fff; font-weight: 600; border-radius: 6px; padding: .7rem 1rem; }
    .btn-confirm:hover, .btn-confirm:focus-visible { background: #185338; border-color: #185338; color: #fff; }

    /* Plat nomor: satu-satunya elemen yang sengaja dibuat menonjol */
    .plate, .plate-input {
        font-family: 'Barlow Semi Condensed', sans-serif; font-weight: 600; letter-spacing: .1em;
        background: var(--ink); color: var(--plate-text);
        outline: 2px solid var(--plate-text); outline-offset: -4px;
    }
    .plate { display: inline-block; padding: .2rem .9rem; border-radius: 5px; font-size: 1.15rem; }
    .plate-input { font-size: 1.9rem; text-align: center; text-transform: uppercase; border: 0; border-radius: 6px; padding: .6rem 1rem; }
    .plate-input::placeholder { color: #6f7884; }
    .plate-input:focus { background: var(--ink); color: var(--plate-text); box-shadow: 0 0 0 3px rgba(27,79,143,.35); }

    .notice { display: flex; gap: .7rem; padding: .85rem 1rem; font-size: .92rem; background: #fff8e6; border: 1px solid #e3c98a; border-radius: 6px; }
    .swal2-popup { font-family: inherit; }
</style>

<nav class="app-nav navbar-dark">
    <div class="navbar navbar-expand-lg">
        <div class="container">
            <a class="brand" href="index.php?page=dashboard">
                <span class="brand-mark">P</span>
                <span>Aplikasi Parkir</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu" aria-controls="navbarMenu" aria-expanded="false" aria-label="Buka menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMenu">
                <ul class="navbar-nav me-auto mb-lg-0 ms-lg-3">
                    <?php foreach($menu as $kunci => $item): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $halaman === $kunci ? 'active' : ''; ?>"
                           href="index.php?page=<?php echo $kunci; ?>"
                           <?php echo $halaman === $kunci ? 'aria-current="page"' : ''; ?>>
                            <i class="fa-solid <?php echo $item[1]; ?>"></i><?php echo $item[0]; ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>

                <div class="user-chip-wrap d-flex align-items-center gap-3">
                    <div class="user-chip">
                        <span class="avatar"><?php echo htmlspecialchars($inisial); ?></span>
                        <span>
                            <?php echo htmlspecialchars($nama_petugas); ?>
                            <small></small>
                        </span>
                    </div>
                    <button type="button" onclick="konfirmasiKeluar()" class="btn-logout">
                        <i class="fa-solid fa-right-from-bracket me-1"></i>Keluar
                    </button>
                </div>
            </div>
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function konfirmasiKeluar() {
    Swal.fire({
        title: 'Keluar dari sistem?',
        text: 'Sesi Anda akan diakhiri.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#a5281b',
        cancelButtonColor: '#5b6572',
        confirmButtonText: 'Keluar',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "index.php?page=logout";
        }
    });
}
</script>