@extends('layout.adminLayout')

@section('content')
    <style>
        :root {
            --primary-color: #009981;
            --primary-hover: #007a67;
        }

        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Brand Colors */
        .text-brand {
            color: var(--primary-color) !important;
        }

        .bg-brand {
            background-color: var(--primary-color) !important;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
        }

        /* Card Styling */
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border-radius: 0.5rem;
        }

        .card-header {
            background-color: white;
            border-bottom: 1px solid #f0f0f0;
            padding: 1rem 1.5rem;
        }

        /* Table Styling */
        .table thead th {
            background-color: #f8f9fa;
            color: #6c757d;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            border-bottom: 2px solid #e9ecef;
        }

        .table-hover tbody tr:hover {
            background-color: #f1fcf9;
        }

        /* Badge */
        .badge-active {
            background-color: rgba(0, 153, 129, 0.1);
            color: #009981;
            border: 1px solid rgba(0, 153, 129, 0.2);
        }

        .badge-inactive {
            background-color: rgba(108, 117, 125, 0.1);
            color: #6c757d;
            border: 1px solid rgba(108, 117, 125, 0.2);
        }
    </style>
    <!-- Main Content -->
    <div class="container-fluid px-4">
        <div class="card">
            <!-- Toolbar -->
            <div class="card-body border-bottom bg-light py-3">
                <div class="row g-3 align-items-center">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i
                                    class="bi bi-search text-muted"></i></span>
                            <input type="text" class="form-control border-start-0 ps-0" placeholder="Tìm kiếm danh mục...">
                        </div>
                    </div>
                    <div class="col-md-8 text-md-end">
                        <a href="/category/create" class="btn btn-primary px-4 shadow-sm">
                            <i class="bi bi-plus-lg me-1"></i> Tạo danh mục
                        </a>
                    </div>
                </div>
            </div>


            <!-- Table -->
            <div class="table-responsive">
                <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success d-flex align-items-center" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <div><?= $_SESSION['success'] ?></div>
                </div>
                <?php unset($_SESSION['success']); ?>
                <?php endif; ?>
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 60px;">ID</th>
                            <th>Tên danh mục</th>
                            <th>Mô tả</th>
                            <th class="text-center" style="width: 150px;">Trạng thái</th>
                            <th class="text-center" style="width: 150px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (empty($categories))
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Chưa có dữ liệu.</td>
                            </tr>
                        @else
                            @foreach ($categories as $cate)
                                <tr>
                                    <td class="text-center text-muted fw-bold">#<?= $cate['id'] ?></td>
                                    <td>
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($cate['name']) ?></div>
                                    </td>
                                    <td class="text-muted small text-truncate" style="max-width: 300px;">
                                        <?= htmlspecialchars($cate['description'] ?? 'Chưa có mô tả') ?>
                                    </td>
                                    <td class="text-center">
                                        @if ($cate['status'] == 1)
                                            <span class="badge badge-active  rounded-pill px-3 py-2">
                                                <i class="bi bi-check-circle-fill me-1"></i> Hoạt động
                                            </span>
                                        @else: ?>
                                            <span class="badge badge-inactive rounded-pill px-3 py-2">
                                                <i class="bi bi-dash-circle-fill me-1"></i> Đang ẩn
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="/category/edit/<?= $cate['id'] ?>"
                                            class="btn btn-sm btn-light border text-primary me-1" title="Sửa">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="/category/delete/<?= $cate['id'] ?>"
                                            class="btn btn-sm btn-light border text-danger"
                                            onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');"
                                            title="Xóa">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <!-- Footer pagination mockup -->
            <div class="card-footer bg-white py-3">
                <small class="text-muted">Hiển thị toàn bộ danh sách</small>
            </div>
        </div>
    </div>
@endsection
