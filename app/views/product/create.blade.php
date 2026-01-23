@extends('layout.adminLayout')
@section('content')
    {{-- 2. Phần CSS riêng cho trang này (nếu cần), nhưng tốt nhất nên để chung vào file CSS global --}}
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

        .form-label {
            font-weight: 600;
            font-size: 0.9rem;
            color: #555;
        }
    </style>


    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold text-brand m-0">Thêm Sản phẩm mới</h4>
                    <a href="/product" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> Quay lại</a>
                </div>
                {{-- tìm kiếm --}}

                @if (isset($mess))
                    <div class="alert alert-success d-flex align-items-center" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <div><?= $mess ?></div>
                    </div>
                @endif
                <form action="/product/store" method="POST" enctype="multipart/form-data">
                    <div class="card p-4">
                        <div class="row g-3">
                            <!-- Cột trái: Thông tin chính -->
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control"
                                        placeholder="Nhập tên Figure...">
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Giá bán (VND) <span class="text-danger">*</span></label>
                                        <input type="number" name="price" class="form-control" placeholder="500000">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Giá gốc (VND)</label>
                                        <input type="number" name="sale_price" class="form-control" placeholder="0">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Mô tả ngắn</label>
                                    <textarea name="short_description" class="form-control" rows="2"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Mô tả chi tiết</label>
                                    <textarea name="description" class="form-control" rows="5"></textarea>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Ảnh đại diện</label>
                                    <input type="file" name="image" class="form-control">
                                    <div class="form-text text-muted small">Định dạng: jpg, png, webp</div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Danh mục <span class="text-danger">*</span></label>
                                    <select name="category_id" class="form-select">
                                        <option value="">-- Chọn danh mục --</option>
                                        @foreach ($categories as $c)
                                            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Thương hiệu</label>
                                    <select name="brand_id" class="form-select">
                                        <option value="">-- Chọn thương hiệu --</option>
                                        @foreach ($brands as $b)
                                            <option value="<?= $b['id'] ?>"><?= $b['name'] ?></option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Số lượng kho</label>
                                    <input type="number" name="quantity" class="form-control" value="0">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Trạng thái</label>
                                    <select name="status" class="form-select">
                                        <option value="1">Đang bán</option>
                                        <option value="0">Tạm ẩn</option>
                                    </select>
                                </div>

                                <hr>
                                <button type="submit" class="btn btn-brand w-100 fw-bold py-2"><i
                                        class="bi bi-save me-1"></i> Lưu sản phẩm</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
