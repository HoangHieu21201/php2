    <aside class="sidebar" id="sidebar">
        <style>
            #sidebar {
                background-color: #009981;
                color: white;
                min-height: 100vh;
            }

            .sidebar-brand {
                color: white !important;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }

            .sidebar-menu .nav-link {
                color: rgba(255, 255, 255, 0.85) !important;
                transition: all 0.2s;
            }

            .sidebar-menu .nav-link:hover,
            .sidebar-menu .nav-link.active {
                color: white !important;
                background-color: rgba(255, 255, 255, 0.15);
                border-right: 3px solid #ffffff;
                font-weight: 600;
            }

            .sub-link {
                color: rgba(255, 255, 255, 0.7) !important;
            }

            .sub-link:hover {
                color: white !important;
                transform: translateX(5px);
                background: none !important;
            }

            .nav-link.sub-link.active-sub {
                color: white !important;
                font-weight: bold;
                opacity: 1;
            }
        </style>

        <a href="/" class="sidebar-brand text-decoration-none py-3 d-block px-3">
            <i class="bi bi-shield-lock-fill me-2"></i> Admin Panel
        </a>

        @php
            $currentUri = $_SERVER['REQUEST_URI'];
            $isProductGroup =
                strpos($currentUri, '/product') !== false ||
                strpos($currentUri, '/productvariant') !== false ||
                strpos($currentUri, '/color') !== false ||
                strpos($currentUri, '/size') !== false;
        @endphp
        @if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 1)
            <ul class="sidebar-menu p-0 list-unstyled mt-2">
                <li class="nav-item">
                    <a class="nav-link px-3 py-2 {{ $currentUri == '/' || strpos($currentUri, '/home') !== false ? 'active' : '' }}"
                        href="/">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link px-3 py-2 d-flex justify-content-between align-items-center {{ $isProductGroup ? 'active' : '' }}"
                        data-bs-toggle="collapse" href="#productSubmenu" role="button"
                        aria-expanded="{{ $isProductGroup ? 'true' : 'false' }}" aria-controls="productSubmenu">
                        <span><i class="bi bi-box-seam me-2"></i> Sản phẩm</span>
                        <i class="bi bi-chevron-down" style="font-size: 0.8rem;"></i>
                    </a>

                    <div class="collapse {{ $isProductGroup ? 'show' : '' }}" id="productSubmenu">
                        <ul class="list-unstyled fw-normal pb-1 small my-0 bg-black bg-opacity-10">
                            <li>
                                <a href="/product"
                                    class="nav-link sub-link ps-5 py-2 {{ $currentUri == '/product' || strpos($currentUri, '/product/') !== false ? 'active-sub' : '' }}">
                                    <i class="bi bi-dot me-1"></i> Danh sách
                                </a>
                            </li>
                            <li>
                                <a href="/productvariant"
                                    class="nav-link sub-link ps-5 py-2 {{ strpos($currentUri, '/productvariant') !== false ? 'active-sub' : '' }}">
                                    <i class="bi bi-dot me-1"></i> Biến thể
                                </a>
                            </li>
                            <li>
                                <a href="/color"
                                    class="nav-link sub-link ps-5 py-2 {{ strpos($currentUri, '/color') !== false ? 'active-sub' : '' }}">
                                    <i class="bi bi-dot me-1"></i> Màu sắc
                                </a>
                            </li>
                            <li>
                                <a href="/size"
                                    class="nav-link sub-link ps-5 py-2 {{ strpos($currentUri, '/size') !== false ? 'active-sub' : '' }}">
                                    <i class="bi bi-dot me-1"></i> Kích thước
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link px-3 py-2 {{ strpos($currentUri, '/category') !== false ? 'active' : '' }}"
                        href="/category">
                        <i class="bi bi-tags me-2"></i> Danh mục
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 py-2 {{ strpos($currentUri, '/brand') !== false ? 'active' : '' }}"
                        href="/brand">
                        <i class="bi bi-star me-2"></i> Thương hiệu
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 py-2 {{ strpos($currentUri, '/user') !== false ? 'active' : '' }}"
                        href="/user">
                        <i class="bi bi-people me-2"></i> Người dùng
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 py-2 {{ strpos($currentUri, '/coupon') !== false ? 'active' : '' }}"
                        href="/coupon">
                        <i class="bi bi-ticket me-2"></i> Mã giảm giá
                    </a>
                </li>
            </ul>
        @endif
    </aside>
