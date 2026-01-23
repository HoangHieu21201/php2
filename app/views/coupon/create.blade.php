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

        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border-radius: 0.5rem;
        }
    </style>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold text-brand m-0">Thêm Mã giảm giá</h4>
                    <a href="/coupon" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Quay lại
                    </a>
                </div>

                <?php if (isset($mess)): ?>
                <div class="alert alert-success d-flex align-items-center" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <div><?= $mess ?></div>
                </div>
                <?php endif; ?>

                <form action="/coupon/store" method="POST">
                    <div class="card p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Mã Coupon <span class="text-danger">*</span></label>
                                <input type="text" name="code" 
                                       class="form-control <?= isset($errors['code']) ? 'is-invalid' : '' ?>" 
                                       value="<?= htmlspecialchars($old['code'] ?? '') ?>"
                                       placeholder="VD: SALE50">
                                <?php if(isset($errors['code'])): ?>
                                    <div class="invalid-feedback"><?= $errors['code'] ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Trạng thái</label>
                                <select name="status" class="form-select">
                                    <option value="1" <?= (isset($old['status']) && $old['status'] == 1) ? 'selected' : '' ?>>Hoạt động</option>
                                    <option value="0" <?= (isset($old['status']) && $old['status'] == 0) ? 'selected' : '' ?>>Tạm khóa</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Loại giảm <span class="text-danger">*</span></label>
                                <select name="type" class="form-select <?= isset($errors['type']) ? 'is-invalid' : '' ?>">
                                    <option value="fixed" <?= (isset($old['type']) && $old['type'] == 'fixed') ? 'selected' : '' ?>>Tiền mặt (VNĐ)</option>
                                    <option value="percent" <?= (isset($old['type']) && $old['type'] == 'percent') ? 'selected' : '' ?>>Phần trăm (%)</option>
                                </select>
                                <?php if(isset($errors['type'])): ?>
                                    <div class="invalid-feedback"><?= $errors['type'] ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Giá trị giảm <span class="text-danger">*</span></label>
                                <input type="number" name="value" 
                                       class="form-control <?= isset($errors['value']) ? 'is-invalid' : '' ?>"
                                       value="<?= $old['value'] ?? '' ?>" min="0">
                                <?php if(isset($errors['value'])): ?>
                                    <div class="invalid-feedback"><?= $errors['value'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Đơn tối thiểu</label>
                                <input type="number" name="min_order_value" 
                                       class="form-control <?= isset($errors['min_order_value']) ? 'is-invalid' : '' ?>"
                                       value="<?= $old['min_order_value'] ?? 0 ?>" min="0">
                                <?php if(isset($errors['min_order_value'])): ?>
                                    <div class="invalid-feedback"><?= $errors['min_order_value'] ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Lượt dùng tối đa</label>
                                <input type="number" name="max_usage" 
                                       class="form-control <?= isset($errors['max_usage']) ? 'is-invalid' : '' ?>"
                                       value="<?= $old['max_usage'] ?? '' ?>" placeholder="Để trống = Không giới hạn">
                                <?php if(isset($errors['max_usage'])): ?>
                                    <div class="invalid-feedback"><?= $errors['max_usage'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Ngày bắt đầu</label>
                                <input type="datetime-local" name="start_date" 
                                       class="form-control"
                                       value="<?= $old['start_date'] ?? '' ?>">
                                <small class="text-muted">Mặc định: Ngay bây giờ</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Ngày kết thúc</label>
                                <input type="datetime-local" name="end_date" 
                                       class="form-control <?= isset($errors['end_date']) ? 'is-invalid' : '' ?>"
                                       value="<?= $old['end_date'] ?? '' ?>">
                                <?php if(isset($errors['end_date'])): ?>
                                    <div class="invalid-feedback"><?= $errors['end_date'] ?></div>
                                <?php else: ?>
                                    <small class="text-muted">Để trống: Vĩnh viễn</small>
                                <?php endif; ?>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-brand w-100 fw-bold py-2 mt-2">
                            <i class="bi bi-save me-1"></i> Lưu mã giảm giá
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection