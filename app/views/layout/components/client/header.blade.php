<header class="sticky-top bg-white shadow-sm">
    <!-- Main Header -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white py-3">
        <div class="container">
            <!-- Logo -->
            <!-- Sửa link Logo về UserClientController -->
            <a class="navbar-brand fw-bold text-uppercase" href="/userclient" style="color: #009981; letter-spacing: 1px;">
                <i class="bi bi-box-seam-fill me-1"></i> MyShop
            </a>

            <!-- Mobile Toggle -->
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menu Content -->
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 fw-semibold text-uppercase" style="font-size: 0.9rem;">
                    {{-- <li class="nav-item">
                        <!-- Sửa link Trang chủ về UserClientController -->
                        <a class="nav-link px-3" href="/userclient">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <!-- SỬA LINK: Trỏ về UserProductDetailController -->
                        <a class="nav-link px-3" href="/userproductdetail">Cửa hàng</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3" href="/blog">Tin tức</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3" href="/contact">Liên hệ</a>
                    </li> --}}
                </ul>

                <!-- Icons & User Actions -->
                <div class="d-flex align-items-center gap-3">
                    <!-- Search Icon -->
                    <div class="input-group d-none d-lg-flex" style="width: 250px;">
                        <input type="text" class="form-control border-end-0 rounded-start-pill bg-light" placeholder="Tìm kiếm...">
                        <button class="btn border border-start-0 rounded-end-pill bg-light text-muted" type="button">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>

                    <!-- Cart -->
                    <!-- SỬA LINK: Trỏ về UserCartController (index) -->
                    <a href="/usercart" class="position-relative text-dark fs-5">
                        <i class="bi bi-bag"></i>
                        <!-- Hiển thị số lượng (Tạm thời để logic check session hoặc model sau này) -->
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light" style="font-size: 0.6rem;">
                            <?= isset($_SESSION['cart_count']) ? $_SESSION['cart_count'] : 0 ?>
                        </span>
                    </a>

                    <!-- User Account -->
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown">
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                                <i class="bi bi-person-fill fs-5 text-secondary"></i>
                            </div>
                            <?php if(isset($_SESSION['user_id'])): ?>
                                <span class="d-none d-md-block small fw-bold"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Member') ?></span>
                            <?php else: ?>
                                <span class="d-none d-md-block small">Tài khoản</span>
                            <?php endif; ?>
                        </a>
                        
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                            <?php if(isset($_SESSION['user_id'])): ?>
                                <li><a class="dropdown-item" href="/profile"><i class="bi bi-person me-2"></i>Hồ sơ</a></li>
                                <li><a class="dropdown-item" href="/orders"><i class="bi bi-receipt me-2"></i>Đơn hàng</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="/auth/logout"><i class="bi bi-box-arrow-right me-2"></i>Đăng xuất</a></li>
                            <?php else: ?>
                                <li><a class="dropdown-item" href="/auth/login"><i class="bi bi-box-arrow-in-right me-2"></i>Đăng nhập</a></li>
                                <li><a class="dropdown-item" href="/auth/register"><i class="bi bi-person-plus me-2"></i>Đăng ký</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>