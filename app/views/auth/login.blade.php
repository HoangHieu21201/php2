<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Hệ thống quản lý</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Fonts -->
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
                            <i class="bi bi-shield-lock-fill fs-1 text-brand"></i>
                        </div>
                        <h4 class="fw-bold mb-1">Chào mừng trở lại!</h4>
                        <p class="text-muted small">Vui lòng đăng nhập để tiếp tục.</p>
                    </div>

                    <!-- Hiển thị thông báo lỗi/thành công -->
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success text-center py-2 small mb-3">
                            <i class="bi bi-check-circle me-1"></i> <?= $_SESSION['success'] ?>
                            <?php unset($_SESSION['success']); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger text-center py-2 small mb-3">
                            <i class="bi bi-exclamation-circle me-1"></i> <?= $_SESSION['error'] ?>
                            <?php unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>

                    <form action="/auth/handleLogin" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold small">Địa chỉ Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                                <input type="email" class="form-control border-start-0 ps-0 bg-light" id="email" name="email"
                                    placeholder="name@example.com" required autofocus>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold small">Mật khẩu</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-key text-muted"></i></span>
                                <input type="password" class="form-control border-start-0 ps-0 bg-light" id="password" name="password"
                                    placeholder="Nhập mật khẩu của bạn" required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                <label class="form-check-label small text-muted" for="remember">Ghi nhớ đăng nhập</label>
                            </div>
                            <a href="/auth/forgotPassword" class="text-decoration-none small text-brand fw-semibold">Quên mật khẩu?</a>
                        </div>

                        <button type="submit" class="btn btn-brand w-100 py-2 mb-3">
                            Đăng nhập
                        </button>

                        <div class="position-relative my-4 text-center">
                            <hr class="text-secondary opacity-25 m-0">
                            <span class="position-absolute top-50 start-50 translate-middle bg-white px-2 text-muted small text-uppercase" style="background-color: #fff;">Hoặc</span>
                        </div>

                        <a href="/auth/loginWithGoogle" class="btn btn-outline-secondary w-100 py-2 d-flex align-items-center justify-content-center gap-2 bg-white">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" width="18" height="18" alt="Google">
                            <span class="fw-semibold small text-dark">Đăng nhập với Google</span>
                        </a>
                    </form>

                    <div class="text-center mt-4 pt-3 border-top">
                        <p class="small text-muted mb-0">
                            Bạn chưa có tài khoản? 
                            <a href="/auth/register" class="text-decoration-none fw-bold text-brand">Đăng ký ngay</a>
                        </p>
                    </div>
                </div>
                
                <div class="text-center mt-3">
                    <a href="/userclient" class="text-decoration-none text-muted small"><i class="bi bi-arrow-left me-1"></i> Quay về trang chủ</a>
                </div>
            </div>
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>