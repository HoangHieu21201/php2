@extends('layout.adminLayout')

@section('content')
    <style>
        :root {
            --primary-color: #009981;
        }
        .text-brand { color: var(--primary-color) !important; }
        .btn-brand { background-color: var(--primary-color); color: white; }
        .btn-brand:hover { background-color: #007a67; color: white; }
        
        .nav-tabs .nav-link { color: #6c757d; }
        .nav-tabs .nav-link.active { color: var(--primary-color); font-weight: bold; border-bottom: 2px solid var(--primary-color); }
    </style>

    <div class="container py-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h4 class="mb-0 fw-bold text-brand"><i class="bi bi-pencil-square me-2"></i>Cập nhật: {{ $user['name'] }}</h4>
            </div>
            
            <div class="card-body">
                @if (isset($_SESSION['success']))
                <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                    {{ $_SESSION['success'] }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
                @endif

                <!-- Tabs Navigation -->
                <ul class="nav nav-tabs mb-4" id="userTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab">Thông tin chung</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="address-tab" data-bs-toggle="tab" data-bs-target="#address" type="button" role="tab">Sổ địa chỉ</button>
                    </li>
                </ul>

                <!-- Tabs Content -->
                <div class="tab-content" id="userTabContent">
                    
                    <!-- Tab 1: Thông tin cơ bản -->
                    <div class="tab-pane fade show active" id="info" role="tabpanel">
                        <form action="/user/update/{{ $user['id'] }}" method="POST">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Họ và Tên</label>
                                    <input type="text" class="form-control" name="name" value="{{ $user['name'] }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Số điện thoại</label>
                                    <input type="text" class="form-control" name="phone" value="{{ $user['phone'] }}" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold">Email</label>
                                    <input type="email" class="form-control" name="email" value="{{ $user['email'] }}" required disabled>
                                </div>
                                

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Mật khẩu mới</label>
                                    <input type="password" class="form-control" name="password" placeholder="Để trống nếu không đổi">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Vai trò</label>
                                    <select class="form-select" name="role">
                                        <option value="0" {{ $user['role'] == 0 ? 'selected' : '' }}>Khách hàng</option>
                                        <option value="1" {{ $user['role'] == 1 ? 'selected' : '' }}>Admin</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Trạng thái</label>
                                    <select class="form-select" name="status">
                                        <option value="1" {{ $user['status'] == 1 ? 'selected' : '' }}>Hoạt động</option>
                                        <option value="0" {{ $user['status'] == 0 ? 'selected' : '' }}>Khóa</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-brand px-4">Lưu thay đổi</button>
                                <a href="/user" class="btn btn-light border ms-2">Quay lại</a>
                            </div>
                        </form>
                    </div>

                    <!-- Tab 2: Quản lý địa chỉ -->
                    <div class="tab-pane fade" id="address" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0 text-muted">Danh sách địa chỉ giao hàng</h5>
                            <button type="button" class="btn btn-brand btn-sm" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                <i class="bi bi-plus-lg me-1"></i> Thêm địa chỉ
                            </button>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Người nhận</th>
                                        <th>SĐT</th>
                                        <th>Địa chỉ chi tiết</th>
                                        <th class="text-center">Trạng thái</th>
                                        <th class="text-center">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($addresses) && count($addresses) > 0)
                                        @foreach($addresses as $addr)
                                        <tr>
                                            <td>{{ $addr['recipient_name'] }}</td>
                                            <td>{{ $addr['recipient_phone'] }}</td>
                                            <td>{{ $addr['address'] }}</td>
                                            <td class="text-center">
                                                @if($addr['is_default'] == 1)
                                                    <span class="badge bg-success">Mặc định</span>
                                                @else
                                                    <span class="badge bg-secondary">Phụ</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="/user/deleteAddress/{{ $addr['id'] }}/{{ $user['id'] }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Xóa địa chỉ này?')">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-3">Chưa có địa chỉ phụ nào.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal Thêm Địa Chỉ -->
    <div class="modal fade" id="addAddressModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="/user/addAddress/{{ $user['id'] }}" method="POST">
                <div class="modal-content">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold text-brand">Thêm địa chỉ mới</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Tên người nhận</label>
                            <input type="text" class="form-control" name="recipient_name" value="{{ $user['name'] }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control" name="recipient_phone" value="{{ $user['phone'] }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Địa chỉ chi tiết</label>
                            <textarea class="form-control" name="address_detail" rows="3" required placeholder="Số nhà, đường, phường/xã..."></textarea>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_default" name="is_default">
                            <label class="form-check-label" for="is_default">
                                Đặt làm địa chỉ mặc định
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-brand">Lưu địa chỉ</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection