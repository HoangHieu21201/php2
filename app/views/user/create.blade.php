@extends('layout.adminLayout')

@section('content')
    <style>
        :root {
            --primary-color: #009981;
        }
        .text-brand { color: var(--primary-color) !important; }
        .btn-brand { background-color: var(--primary-color); color: white; }
        .btn-brand:hover { background-color: #007a67; color: white; }
    </style>

    <div class="container py-5">
        <div class="card border-0 shadow-sm" style="max-width: 800px; margin: 0 auto;">
            <div class="card-header bg-white py-3">
                <h4 class="mb-0 fw-bold text-brand"><i class="bi bi-person-plus me-2"></i>Thêm người dùng mới</h4>
            </div>
            
            <div class="card-body p-4">
                @if (isset($_SESSION['error']))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    {{ $_SESSION['error'] }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
                @endif

                <form action="/user/store" method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Họ và Tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="phone" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Mật khẩu <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" name="password" required>
                        </div>

                        {{-- ĐÃ XÓA INPUT ĐỊA CHỈ Ở ĐÂY --}}

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Vai trò</label>
                            <select class="form-select" name="role">
                                <option value="0">Khách hàng</option>
                                <option value="1">Admin</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Trạng thái</label>
                            <select class="form-select" name="status">
                                <option value="1" selected>Hoạt động</option>
                                <option value="0">Khóa</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-end gap-2">
                        <a href="/user" class="btn btn-light border">Quay lại</a>
                        <button type="submit" class="btn btn-brand px-4">Lưu người dùng</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection