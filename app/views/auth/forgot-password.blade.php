<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu - Hệ thống quản lý</title>
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
                                <i class="bi bi-key-fill fs-2 text-brand"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold mb-1">Quên mật khẩu?</h4>
                        <p class="text-muted small px-3">Nhập email đã đăng ký, chúng tôi sẽ gửi hướng dẫn đặt lại mật khẩu cho bạn.</p>
                    </div>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger text-center py-2 small mb-3">
                            <i class="bi bi-exclamation-circle me-1"></i> <?= $_SESSION['error'] ?>
                            <?php unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success text-center py-2 small mb-3">
                            <i class="bi bi-check-circle me-1"></i> <?= $_SESSION['success'] ?>
                            <?php unset($_SESSION['success']); ?>
                        </div>
                    <?php endif; ?>

                    <form action="/auth/sendResetLink" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold small">Địa chỉ Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                                <input type="email" class="form-control border-start-0 ps-0 bg-light" id="email" name="email"
                                    placeholder="name@example.com" required autofocus>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-brand w-100 py-2 mb-3">
                            Gửi link xác nhận
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