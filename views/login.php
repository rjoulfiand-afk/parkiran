<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Parkir</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #f0f2f5;
        }
        .login-card {
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }
        .login-header {
            background-color: #212529;
            color: white;
            border-radius: 8px 8px 0 0;
            padding: 20px;
            text-align: center;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100">
    <div class="login-card shadow" style="width: 380px;">
        <div class="login-header">
            <i class="fa-solid fa-square-parking fa-2x mb-2"></i>
            <h5 class="mb-0 fw-bold">SISTEM PARKIR</h5>
            <small class="text-white-50">Login Admin</small>
        </div>
        <div class="p-4 bg-white" style="border-radius: 0 0 8px 8px;">
            <form action="index.php?page=login" method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Username</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fa fa-user text-muted"></i></span>
                        <input type="text" name="username" class="form-control" placeholder="Masukkan username" required autofocus>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold small">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fa fa-lock text-muted"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-dark w-100 fw-bold">
                    <i class="fa fa-sign-in-alt me-1"></i> MASUK
                </button>
            </form>
        </div>
    </div>

    <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'gagal'): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Login Gagal',
            text: 'Username atau password salah!',
            confirmButtonColor: '#212529'
        });
    </script>
    <?php endif; ?>
</body>
</html>