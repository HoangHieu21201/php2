<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu - Hệ thống quản lý</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0f2f5;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }
        .btn-brand {
            background-color: #009981;
            color: white;
            font-weight: 600;
        }
        .btn-brand:hover {
            background-color: #007a67;
            color: white;
        }
        .form-control:focus {
            border-color: #009981;
            box-shadow: 0 0 0 0.25rem rgba(0, 153, 129, 0.25);
        }
        .text-brand {
            color: #009981 !important;
        }
    </style>
</head>
<body>

    <main class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card p-4">
                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="bi bi-shield-check fs-2 text-brand"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold mb-1">Đặt lại mật khẩu</h4>
                        <p class="text-muted small px-3">Vui lòng nhập mật khẩu mới cho tài khoản của bạn.</p>
                    </div>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger text-center py-2 small mb-3">
                            <i class="bi bi-exclamation-circle me-1"></i> <?= $_SESSION['error'] ?>
                            <?php unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>

                    <form action="/auth/storeResetPassword" method="POST">
                        <input type="hidden" name="token" value="<?= htmlspecialchars($token ?? '') ?>">
                        <input type="hidden" name="email" value="<?= htmlspecialchars($email ?? '') ?>">

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold small">Mật khẩu mới</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-key text-muted"></i></span>
                                <input type="password" class="form-control border-start-0 ps-0 bg-light" id="password" name="password"
                                    placeholder="Tối thiểu 6 ký tự" required autofocus>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="confirm_password" class="form-label fw-semibold small">Xác nhận mật khẩu</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-shield-lock text-muted"></i></span>
                                <input type="password" class="form-control border-start-0 ps-0 bg-light" id="confirm_password" name="confirm_password"
                                    placeholder="Nhập lại mật khẩu mới" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-brand w-100 py-2 mb-3">
                            Đổi mật khẩu
                        </button>
                    </form>

                    <div class="text-center mt-3 pt-3 border-top">
                        <a href="/auth/login" class="text-decoration-none text-muted small fw-semibold">
                            <i class="bi bi-arrow-left me-1"></i> Quay lại Đăng nhập
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>