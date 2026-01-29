@extends('layout.adminLayout')

@section('content')
    <style>
        .text-brand {
            color: #009981 !important;
        }

        .btn-brand {
            background-color: #009981;
            color: white;
        }

        .btn-brand:hover {
            background-color: #007a67;
            color: white;
        }

        .img-thumb {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #eee;
        }

        .bg-filter {
            background-color: #f8f9fa;
            border-bottom: 1px solid #eee;
        }
    </style>

    <div class="container-fluid px-4 py-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold text-brand"><i class="bi bi-box-seam me-2"></i>Quản lý Sản phẩm</h4>
                <a href="/product/create" class="btn btn-brand btn-sm shadow-sm">
                    <i class="bi bi-plus-lg me-1"></i> Thêm mới
                </a>
            </div>

            <div class="bg-filter p-3">
                <form action="" method="GET" class="row g-2">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-white text-muted"><i class="bi bi-search"></i></span>
                            <input type="text" name="keyword" class="form-control" placeholder="Tìm theo tên sản phẩm..."
                                value="<?= htmlspecialchars($filters['keyword'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="col-md-2 d-flex gap-1">
                        <button type="submit" class="btn btn-primary flex-grow-1"><i class="bi bi-search"></i> Tìm
                            kiếm</button>
                        <a href="/product" class="btn btn-outline-secondary" title="Làm mới"><i
                                class="bi bi-arrow-counterclockwise"></i></a>
                    </div>
                </form>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_SESSION['success']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <!-- Bảng dữ liệu -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Sản phẩm</th>
                            <th>Danh mục</th>
                            <th>Thương hiệu</th> 
                            <th>Giá nhập</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center pe-4">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($products)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                Không tìm thấy sản phẩm nào.
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach($products as $p): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <img src="<?= $p['image'] ?>" class="img-thumb me-3"
                                        onerror="this.src='https://placehold.co/60x60?text=No+Img'">
                                    <div>
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($p['name']) ?></div>
                                        <small class="text-muted">ID: #<?= $p['id'] ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-secondary border">
                                    <?= htmlspecialchars($p['category_name'] ?? 'Không có') ?>
                                </span>
                            </td>
                            <td>
                                <!-- Hiển thị Thương hiệu -->
                                <span class="badge bg-light text-dark border">
                                    <i class="bi bi-star-fill text-warning me-1" style="font-size: 0.6rem;"></i>
                                    <?= htmlspecialchars($p['brand_name'] ?? 'N/A') ?>
                                </span>
                            </td>
                            <td>
                                <div class="fw-bold text-brand"><?= number_format($p['price'], 0, ',', '.') ?> đ</div>
                            </td>
                            <td class="text-center">
                                <?php if($p['status'] == 1): ?>
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Đang bán</span>
                                <?php else: ?>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3">Tạm
                                    ẩn</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center pe-4 text-nowrap">
                                <!-- Nút Xem Chi tiết Biến thể -->
                                <a href="/productvariant?product_id=<?= $p['id'] ?>"
                                    class="btn btn-sm btn-light border text-info me-1" title="Xem chi tiết biến thể">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <a href="/product/edit/<?= $p['id'] ?>" class="btn btn-sm btn-light border text-primary"
                                    title="Sửa">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="/product/delete/<?= $p['id'] ?>"
                                    class="btn btn-sm btn-light border text-danger ms-1"
                                    onclick="return confirm('Xóa sản phẩm này (bao gồm cả các biến thể)?');" title="Xóa">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Phân trang (Pagination) -->
            <?php if(isset($totalPages) && $totalPages > 1): ?>
            <div class="card-footer bg-white py-3">
                <nav>
                    <ul class="pagination justify-content-center mb-0">
                        <?php
                        $page = $page ?? 1;
                        // Hợp nhất tham số filter hiện tại vào link phân trang
                        $queryParams = $filters ?? [];
                        ?>

                        <!-- Nút Previous -->
                        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link"
                                href="?<?= http_build_query(array_merge($queryParams, ['page' => $page - 1])) ?>">
                                <i class="bi bi-chevron-left"></i>
                            </a>
                        </li>

                        <!-- Các trang số -->
                        <?php for($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link" href="?<?= http_build_query(array_merge($queryParams, ['page' => $i])) ?>">
                                <?= $i ?>
                            </a>
                        </li>
                        <?php endfor; ?>

                        <!-- Nút Next -->
                        <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                            <a class="page-link"
                                href="?<?= http_build_query(array_merge($queryParams, ['page' => $page + 1])) ?>">
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            <?php endif; ?>
        </div>
    </div>
@endsection
