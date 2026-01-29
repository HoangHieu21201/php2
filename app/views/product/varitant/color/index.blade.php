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
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #eee;
        }
    </style>

    <div class="container-fluid px-4 py-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold text-brand"><i class="bi bi-box-seam me-2"></i>Màu</h4>
                <a href="/color/create" class="btn btn-brand btn-sm shadow-sm">
                    <i class="bi bi-plus-lg me-1"></i> Thêm mới
                </a>
            </div>
            <div class="m-2">
                <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($_SESSION['success']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
                <?php endif; ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Màu</th>
                                <th class="text-center pe-4">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($colors)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">Chưa có màu nào.</td>
                            </tr>
                            <?php else: ?>
                            <?php foreach($colors as $color): ?>
                            <tr>
                                <td>
                                    <span class="badge bg-light text-secondary border">
                                        <?= htmlspecialchars($color['name'] ?? 'Không có') ?>
                                    </span>
                                </td>

                                <td class="text-center pe-4">
                                    <a href="/color/edit/<?= $color['id'] ?>"
                                        class="btn btn-sm btn-light border text-primary" title="Sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="/color/delete/<?= $color['id'] ?>"
                                        class="btn btn-sm btn-light border text-danger ms-1"
                                        onclick="return confirm('Xóa Màu này?');" title="Xóa">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endsection
