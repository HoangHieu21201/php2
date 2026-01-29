<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký tài khoản - Hệ thống quản lý</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0f2f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
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
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card p-4">
                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <i class="bi bi-person-plus-fill fs-1 text-brand"></i>
                        </div>
                        <h4 class="fw-bold mb-1">Tạo tài khoản mới</h4>
                        <p class="text-muted small">Điền thông tin để đăng ký thành viên.</p>
                    </div>

                    <form action="/auth/storeRegister" method="POST">
                        
                        <!-- Họ và tên -->
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold small">Họ và tên</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                                <input type="text" class="form-control border-start-0 ps-0 bg-light" id="name" name="name"
                                    value="<?= htmlspecialchars($_SESSION['old']['name'] ?? '') ?>" 
                                    placeholder="Nguyễn Văn A" required>
                            </div>
                            <?php if (isset($_SESSION['errors']['name'])): ?>
                                <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle"></i> <?= $_SESSION['errors']['name'] ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold small">Địa chỉ Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                                <input type="email" class="form-control border-start-0 ps-0 bg-light" id="email" name="email"
                                    value="<?= htmlspecialchars($_SESSION['old']['email'] ?? '') ?>" 
                                    placeholder="name@example.com" required>
                            </div>
                            <?php if (isset($_SESSION['errors']['email'])): ?>
                                <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle"></i> <?= $_SESSION['errors']['email'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="row">
                            <!-- Số điện thoại -->
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label fw-semibold small">Số điện thoại</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-telephone text-muted"></i></span>
                                    <input type="text" class="form-control border-start-0 ps-0 bg-light" id="phone" name="phone"
                                        value="<?= htmlspecialchars($_SESSION['old']['phone'] ?? '') ?>" 
                                        placeholder="0912..." required>
                                </div>
                                <?php if (isset($_SESSION['errors']['phone'])): ?>
                                    <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle"></i> <?= $_SESSION['errors']['phone'] ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Địa chỉ -->
                            <div class="col-md-6 mb-3">
                                <label for="address" class="form-label fw-semibold small">Địa chỉ</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-geo-alt text-muted"></i></span>
                                    <input type="text" class="form-control border-start-0 ps-0 bg-light" id="address" name="address"
                                        value="<?= htmlspecialchars($_SESSION['old']['address'] ?? '') ?>" 
                                        placeholder="Số nhà, Phường/Xã...">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Mật khẩu -->
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label fw-semibold small">Mật khẩu</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-key text-muted"></i></span>
                                    <input type="password" class="form-control border-start-0 ps-0 bg-light" id="password" name="password"
                                        placeholder="Tối thiểu 6 ký tự" required>
                                </div>
                                <?php if (isset($_SESSION['errors']['password'])): ?>
                                    <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle"></i> <?= $_SESSION['errors']['password'] ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Nhập lại mật khẩu -->
                            <div class="col-md-6 mb-3">
                                <label for="confirm_password" class="form-label fw-semibold small">Xác nhận mật khẩu</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-shield-check text-muted"></i></span>
                                    <input type="password" class="form-control border-start-0 ps-0 bg-light" id="confirm_password" name="confirm_password"
                                        placeholder="Nhập lại mật khẩu" required>
                                </div>
                                <?php if (isset($_SESSION['errors']['confirm_password'])): ?>
                                    <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle"></i> <?= $_SESSION['errors']['confirm_password'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Điều khoản -->
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                            <label class="form-check-label small text-muted" for="terms">
                                Tôi đồng ý với <a href="#" class="text-decoration-none text-brand fw-semibold">Điều khoản dịch vụ</a> và <a href="#" class="text-decoration-none text-brand fw-semibold">Chính sách bảo mật</a>
                            </label>
                            <?php if (isset($_SESSION['errors']['terms'])): ?>
                                <div class="text-danger small mt-1 d-block"><i class="bi bi-exclamation-circle"></i> <?= $_SESSION['errors']['terms'] ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Nút Đăng ký -->
                        <button type="submit" class="btn btn-brand w-100 py-2 mb-3">
                            Đăng ký tài khoản
                        </button>

                        <!-- Thông báo chung -->
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger text-center py-2 small mb-3">
                                <i class="bi bi-exclamation-circle me-1"></i> <?= $_SESSION['error'] ?>
                                <?php unset($_SESSION['error']); ?>
                            </div>
                        <?php endif; ?>

                        <div class="position-relative my-4 text-center">
                            <hr class="text-secondary opacity-25 m-0">
                            <span class="position-absolute top-50 start-50 translate-middle bg-white px-2 text-muted small text-uppercase" style="background-color: #fff;">Hoặc</span>
                        </div>

                        <!-- Google Signup -->
                        <a href="/auth/loginWithGoogle" class="btn btn-outline-secondary w-100 py-2 d-flex align-items-center justify-content-center gap-2 bg-white">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" width="18" height="18" alt="Google">
                            <span class="fw-semibold small text-dark">Đăng ký với Google</span>
                        </a>
                    </form>

                    <div class="text-center mt-4 pt-3 border-top">
                        <p class="small text-muted mb-0">
                            Đã có tài khoản? 
                            <a href="/auth/login" class="text-decoration-none fw-bold text-brand">Đăng nhập ngay</a>
                        </p>
                    </div>
                </div>
                
                <div class="text-center mt-3">
                    <a href="/userclient" class="text-decoration-none text-muted small"><i class="bi bi-arrow-left me-1"></i> Quay về trang chủ</a>
                </div>
            </div>
        </div>
    </main>

    <!-- Clear Session Errors & Old Input -->
    <?php 
        unset($_SESSION['errors']); 
        unset($_SESSION['old']); 
    ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>