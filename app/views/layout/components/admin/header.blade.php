<header class="top-header">
    <!-- Nút Toggle Sidebar (Mobile) -->
    <button class="btn btn-light d-lg-none" id="sidebarToggle">
        <i class="bi bi-list fs-4"></i>
    </button>

    <!-- Tiêu đề trang -->
    <div class="d-none d-md-block fw-bold text-secondary">
        Quản trị hệ thống
    </div>

    <!-- User Profile -->
    <div class="d-flex align-items-center">
        <div class="dropdown">
            <button class="btn btn-outline-success btn-sm dropdown-toggle d-flex align-items-center gap-2"
                type="button" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle"></i> Admin
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="/profile">Hồ sơ</a></li>
                <li><a class="dropdown-item" href="/settings">Cài đặt</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="/auth">Login / Logout</a></li>
            </ul>
        </div>
    </div>
</header>