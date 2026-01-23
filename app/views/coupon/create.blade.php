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
    </style>

    <div class="container-fluid px-4 py-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h4 class="mb-0 fw-bold text-brand"><i class="bi bi-plus-circle me-2"></i>Thêm mới mã giảm giá</h4>
            </div>

            <div class="card-body">
                <!-- Form Action trỏ về controller xử lý -->
                <form action="/coupon/store" method="POST">
                    <div class="row">
                        <!-- Cột trái: Thông tin cơ bản -->
                        <div class="col-md-6 mb-3">
                            <label for="code" class="form-label fw-bold">Mã Coupon <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="code" name="code"
                                placeholder="VD: SALE50, TET2025" required>
                            <small class="text-muted">Mã phải là duy nhất, không dấu, viết liền.</small>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="type" class="form-label fw-bold">Loại giảm <span
                                    class="text-danger">*</span></label>
                            <select class="form-select" id="type" name="type">
                                <option value="fixed">Giảm theo tiền mặt (VNĐ)</option>
                                <option value="percent">Giảm theo phần trăm (%)</option>
                            </select>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="value" class="form-label fw-bold">Giá trị giảm <span
                                    class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="value" name="value" min="0"
                                required>
                            <small class="text-muted">Nhập số tiền hoặc số %</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="min_order_value" class="form-label fw-bold">Đơn hàng tối thiểu</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="min_order_value" name="min_order_value"
                                    value="0">
                                <span class="input-group-text">VNĐ</span>
                            </div>
                            <small class="text-muted">Giá trị đơn hàng tối thiểu để áp dụng mã.</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label fw-bold">Trạng thái</label>
                            <select class="form-select" id="status" name="status">
                                <option value="1" selected>Hoạt động</option>
                                <option value="0">Tạm khóa</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label fw-bold">Ngày bắt đầu</label>
                            <input type="datetime-local" class="form-control" id="start_date" name="start_date">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label fw-bold">Ngày kết thúc</label>
                            <input type="datetime-local" class="form-control" id="end_date" name="end_date">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <a href="/coupon" class="btn btn-light border me-2">Hủy bỏ</a>
                        <button type="submit" class="btn btn-brand px-4"><i class="bi bi-save me-2"></i>Lưu mã giảm
                            giá</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
