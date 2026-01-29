@extends('layout.adminLayout')

@section('content')
    <style>
        :root {
            --primary-color: #009981;
        }

        .text-brand {
            color: var(--primary-color) !important;
        }

        .btn-brand {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-brand:hover {
            background-color: #007a67;
            color: white;
        }

        .bg-brand-light {
            background-color: rgba(0, 153, 129, 0.1);
            color: var(--primary-color);
        }

        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border-radius: 0.5rem;
        }
    </style>

    <div class="container-fluid py-5">
        <div class="card p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold text-brand m-0"><i class="bi bi-ticket-perforated me-2"></i>Quản lý Mã giảm giá</h4>
                <a href="/coupon/create" class="btn btn-brand btn-sm shadow-sm px-3 py-2">
                    <i class="bi bi-plus-lg me-1"></i> Thêm mới
                </a>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                <?= htmlspecialchars($_SESSION['success']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>
            <?php unset($_SESSION['success']); ?>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Mã Coupon</th>
                            <th>Loại giảm</th>
                            <th>Giá trị</th>
                            <th>Đơn tối thiểu</th>
                            <th>Thời gian hiệu lực</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center pe-4">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($coupons)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">Chưa có mã giảm giá nào.</td>
                        </tr>
                        <?php else: ?>
                        <?php foreach($coupons as $c): ?>
                        <tr>
                            <td class="ps-4">
                                <span class="fw-bold text-primary bg-light px-2 py-1 border rounded font-monospace">
                                    <?= htmlspecialchars($c['code']) ?>
                                </span>
                            </td>
                            <td>
                                <?php if($c['type'] == 'percent'): ?>
                                    <span class="badge bg-info bg-opacity-10 text-info border border-info">Phần trăm (%)</span>
                                <?php else: ?>
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning">Tiền mặt</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">
                                    <?= $c['type'] == 'percent' ? $c['value'] . '%' : number_format($c['value'], 0, ',', '.') . ' đ' ?>
                                </div>
                            </td>
                            <td>
                                <small class="text-muted"><?= number_format($c['min_order_value'], 0, ',', '.') ?> đ</small>
                            </td>
                            <td>
                                <div class="d-flex flex-column" style="font-size: 0.85rem;">
                                    <span class="text-success"><i class="bi bi-calendar-check me-1"></i><?= date('d/m/Y', strtotime($c['start_date'])) ?></span>
                                    <?php if($c['end_date']): ?>
                                    <span class="text-danger mt-1"><i class="bi bi-calendar-x me-1"></i><?= date('d/m/Y', strtotime($c['end_date'])) ?></span>
                                    <?php else: ?>
                                    <span class="text-muted mt-1"><i class="bi bi-infinity me-1"></i>Vĩnh viễn</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="text-center">
                                <?php if($c['status'] == 1): ?>
                                <span class="badge bg-brand-light rounded-pill px-3">Hoạt động</span>
                                <?php else: ?>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3">Tạm khóa</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center pe-4">
                                <a href="/coupon/edit/<?= $c['id'] ?>" class="btn btn-sm btn-light border text-primary" title="Sửa">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="/coupon/delete/<?= $c['id'] ?>" class="btn btn-sm btn-light border text-danger ms-1" 
                                   onclick="return confirm('Bạn có chắc muốn xóa mã này?');" title="Xóa">
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