@extends('layout.adminLayout')

@section('content')
<style>
    .text-brand { color: #009981 !important; }
    .btn-brand { background-color: #009981; color: white; }
    .btn-brand:hover { background-color: #007a67; color: white; }
</style>

<div class="container-fluid px-4 py-4">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-bold text-brand"><i class="bi bi-ticket-perforated me-2"></i>Mã giảm giá</h4>
            <a href="/coupon/create" class="btn btn-brand btn-sm shadow-sm">
                <i class="bi bi-plus-lg me-1"></i> Thêm mới
            </a>
        </div>

        <?php if (isset($mess)): ?>
        <div class="alert alert-success d-flex align-items-center m-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <div><?= $mess ?></div>
        </div>
        <?php endif; ?>

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
                            <span class="fw-bold text-primary bg-light px-2 py-1 border rounded">
                                <?= htmlspecialchars($c['code']) ?>
                            </span>
                        </td>
                        <td>
                            <?php if($c['type'] == 'percent'): ?>
                                <span class="badge bg-info bg-opacity-10 text-info border border-info">Phần trăm (%)</span>
                            <?php else: ?>
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning">Tiền mặt (VNĐ)</span>
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
                                <span class="text-success"><i class="bi bi-calendar-check me-1"></i><?= date('d/m/Y H:i', strtotime($c['start_date'])) ?></span>
                                <span class="text-danger mt-1"><i class="bi bi-calendar-x me-1"></i><?= date('d/m/Y H:i', strtotime($c['end_date'])) ?></span>
                            </div>
                        </td>
                        <td class="text-center">
                            <?php if($c['status'] == 1): ?>
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Hoạt động</span>
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