<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản trị Hệ thống</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root { --primary-color: #009981; --primary-hover: #007a67; }
        body { background-color: #f4f6f9; font-family: 'Segoe UI', sans-serif; }
        
        .text-brand { color: var(--primary-color) !important; }
        .bg-brand { background-color: var(--primary-color) !important; }
        .btn-brand { background-color: var(--primary-color); color: white; }
        .btn-brand:hover { background-color: #007a67; color: white; }
        
        /* Navbar Styling */
        .navbar { box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .nav-link { font-weight: 500; color: #555; }
        .nav-link:hover, .nav-link.active { color: var(--primary-color); }
        
        /* Card & Table Styling Global */
        .card { border: none; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); border-radius: 0.5rem; }
        .img-thumb { width: 50px; height: 50px; object-fit: contain; border: 1px solid #eee; background: #fff; border-radius: 4px; padding: 2px; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold text-brand fs-4" href="/">
                <i class="bi bi-shield-lock-fill me-2"></i>Admin Panel
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="/"><i class="bi bi-speedometer2 me-1"></i> Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="/product"><i class="bi bi-box-seam me-1"></i> Sản phẩm</a></li>
                    <li class="nav-item"><a class="nav-link" href="/category"><i class="bi bi-tags me-1"></i> Danh mục</a></li>
                    <li class="nav-item"><a class="nav-link" href="/brand"><i class="bi bi-star me-1"></i> Thương hiệu</a></li>
                    <li class="nav-item"><a class="nav-link" href="/user"><i class="bi bi-people me-1"></i> Người dùng</a></li>
                </ul>
                <div class="ms-lg-3 ps-lg-3 border-start">
                    <div class="d-flex align-items-center">
                        <img src="https://via.placeholder.com/32" class="rounded-circle border me-2">
                        <small class="fw-bold text-secondary">Admin</small>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="container pb-5">