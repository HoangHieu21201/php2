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
            <!-- Header & Add Button -->
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold text-brand"><i class="bi bi-layers me-2"></i>Biến thể Sản phẩm</h4>
                <a href="/productvariant/create" class="btn btn-brand btn-sm shadow-sm">
                    <i class="bi bi-plus-lg me-1"></i> Thêm mới
                </a>
            </div>

            <!-- Filter & Search Bar -->
            <div class="bg-filter p-3">
                <form action="" method="GET" class="row g-2">
                    <!-- Tìm kiếm từ khóa -->
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text bg-white text-muted"><i class="bi bi-search"></i></span>
                            <input type="text" name="keyword" class="form-control"
                                placeholder="Tìm theo tên SP hoặc SKU..."
                                value="<?= htmlspecialchars($filters['keyword'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <select name="product_id" class="form-select">
                            <option value="">-- Tất cả Sản phẩm --</option>
                            <?php foreach($products as $p): ?>
                            <option value="<?= $p['id'] ?>" <?= $filters['product_id'] == $p['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($p['name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select name="color_id" class="form-select">
                            <option value="">-- Màu --</option>
                            <?php foreach($colors as $c): ?>
                            <option value="<?= $c['id'] ?>" <?= $filters['color_id'] == $c['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($c['name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select name="size_id" class="form-select">
                            <option value="">-- Size --</option>
                            <?php foreach($sizes as $s): ?>
                            <option value="<?= $s['id'] ?>" <?= $filters['size_id'] == $s['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($s['name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-2 d-flex gap-1">
                        <button type="submit" class="btn btn-primary flex-grow-1"><i class="bi bi-funnel"></i> Lọc</button>
                        <a href="/productvariant" class="btn btn-outline-secondary" title="Reset"><i
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

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Biến thể (SKU)</th>
                            <th>Sản phẩm gốc</th>
                            <th>Thuộc tính</th>
                            <th>Giá bán</th>
                            <th class="text-center">Kho</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center pe-4">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($variants)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Không tìm thấy kết quả phù hợp.
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach($variants as $v): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <img src="<?= $v['image'] ?>" class="img-thumb me-3"
                                        onerror="this.src='https://placehold.co/60x60?text=No+Img'">
                                    <div>
                                        <div class="fw-bold text-dark text-nowrap"><?= htmlspecialchars($v['sku']) ?></div>
                                        <small class="text-muted">ID: #<?= $v['id'] ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-bold text-secondary">
                                    <?= htmlspecialchars($v['product_name'] ?? 'N/A') ?>
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1 flex-wrap" style="max-width: 150px;">
                                    <span class="badge bg-white text-dark border shadow-sm">
                                        <?= htmlspecialchars($v['color_name'] ?? '--') ?>
                                    </span>
                                    <span class="badge bg-white text-dark border shadow-sm">
                                        <?= htmlspecialchars($v['size_name'] ?? '--') ?>
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-brand"><?= number_format($v['sale_price'], 0, ',', '.') ?> đ</div>
                                <?php if($v['price'] > 0): ?>
                                <small class="text-decoration-line-through text-muted" style="font-size: 0.8rem;">
                                    <?= number_format($v['price'], 0, ',', '.') ?> đ
                                </small>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <span class="fw-bold <?= $v['quantity'] > 0 ? 'text-dark' : 'text-danger' ?>">
                                    <?= $v['quantity'] ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <?php if($v['status'] == 1): ?>
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Hiện</span>
                                <?php else: ?>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3">Ẩn</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center pe-4 text-nowrap">
                                <a href="/productvariant/edit/<?= $v['id'] ?>"
                                    class="btn btn-sm btn-light border text-primary" title="Sửa">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="/productvariant/delete/<?= $v['id'] ?>"
                                    class="btn btn-sm btn-light border text-danger ms-1"
                                    onclick="return confirm('Xóa biến thể này?');" title="Xóa">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if($totalPages > 1): ?>
            <div class="card-footer bg-white py-3">
                <nav>
                    <ul class="pagination justify-content-center mb-0">
                        <!-- Nút Previous -->
                        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link"
                                href="?<?= http_build_query(array_merge($filters, ['page' => $page - 1])) ?>">
                                <i class="bi bi-chevron-left"></i>
                            </a>
                        </li>

                        <!-- Các trang số -->
                        <?php for($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link" href="?<?= http_build_query(array_merge($filters, ['page' => $i])) ?>">
                                <?= $i ?>
                            </a>
                        </li>
                        <?php endfor; ?>

                        <!-- Nút Next -->
                        <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                            <a class="page-link"
                                href="?<?= http_build_query(array_merge($filters, ['page' => $page + 1])) ?>">
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
