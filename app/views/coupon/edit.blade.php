@extends('layout.adminLayout')

@section('content')
<style>
    .text-brand { color: #009981 !important; }
    .btn-brand { background-color: #009981; color: white; }
    .btn-brand:hover { background-color: #007a67; color: white; }
</style>

<div class="container-fluid px-4 py-4">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h4 class="mb-0 fw-bold text-brand"><i class="bi bi-pencil-square me-2"></i>Cập nhật mã: <?= htmlspecialchars($coupon['code']) ?></h4>
        </div>
        
        <div class="card-body">
            <!-- Form Action trỏ về controller cập nhật -->
            <form action="/coupon/update/<?= $coupon['id'] ?>" method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="code" class="form-label fw-bold">Mã Coupon <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="code" name="code" 
                               value="<?= htmlspecialchars($coupon['code']) ?>" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="type" class="form-label fw-bold">Loại giảm <span class="text-danger">*</span></label>
                        <select class="form-select" id="type" name="type">
                            <option value="fixed" <?= $coupon['type'] == 'fixed' ? 'selected' : '' ?>>Giảm theo tiền mặt (VNĐ)</option>
                            <option value="percent" <?= $coupon['type'] == 'percent' ? 'selected' : '' ?>>Giảm theo phần trăm (%)</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="value" class="form-label fw-bold">Giá trị giảm <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="value" name="value" 
                               value="<?= $coupon['value'] ?>" min="0" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="min_order_value" class="form-label fw-bold">Đơn hàng tối thiểu</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="min_order_value" name="min_order_value" 
                                   value="<?= $coupon['min_order_value'] ?>">
                            <span class="input-group-text">VNĐ</span>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label fw-bold">Trạng thái</label>
                        <select class="form-select" id="status" name="status">
                            <option value="1" <?= $coupon['status'] == 1 ? 'selected' : '' ?>>Hoạt động</option>
                            <option value="0" <?= $coupon['status'] == 0 ? 'selected' : '' ?>>Tạm khóa</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="start_date" class="form-label fw-bold">Ngày bắt đầu</label>
                        <!-- Format date cho input datetime-local: Y-m-d\TH:i -->
                        <input type="datetime-local" class="form-control" id="start_date" name="start_date" 
                               value="<?= date('Y-m-d\TH:i', strtotime($coupon['start_date'])) ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="end_date" class="form-label fw-bold">Ngày kết thúc</label>
                        <input type="datetime-local" class="form-control" id="end_date" name="end_date"
                               value="<?= date('Y-m-d\TH:i', strtotime($coupon['end_date'])) ?>">
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <a href="/coupon" class="btn btn-light border me-2">Quay lại</a>
                    <button type="submit" class="btn btn-brand px-4"><i class="bi bi-arrow-repeat me-2"></i>Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection