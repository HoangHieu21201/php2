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
    </style>


    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold text-brand m-0">Sửa Người dùng #<?= $user['id'] ?></h4>
                    <a href="/user" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> Quay lại</a>
                </div>

                <div class="card p-4 border-0 shadow-sm">
                    <form action="/user/update/<?= $user['id'] ?>" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Họ và tên</label>
                            <input type="text" name="name" class="form-control" required
                                value="<?= htmlspecialchars($user['name']) ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" name="email" class="form-control" required
                                value="<?= htmlspecialchars($user['email']) ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Đổi Mật khẩu</label>
                            <input type="password" name="password" class="form-control"
                                placeholder="Để trống nếu không muốn đổi">
                            <div class="form-text text-muted small">Chỉ nhập nếu bạn muốn đặt lại mật khẩu mới.</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Số điện thoại</label>
                                <input type="text" name="phone" class="form-control"
                                    value="<?= htmlspecialchars($user['phone']) ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Vai trò</label>
                                <select name="role" class="form-select">
                                    <option value="0" <?= $user['role'] == 0 ? 'selected' : '' ?>>Khách hàng</option>
                                    <option value="1" <?= $user['role'] == 1 ? 'selected' : '' ?>>Admin (Quản trị)
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Địa chỉ</label>
                            <input type="text" name="address" class="form-control"
                                value="<?= htmlspecialchars($user['address']) ?>">
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Trạng thái</label>
                            <select name="status" class="form-select">
                                <option value="1" <?= $user['status'] == 1 ? 'selected' : '' ?>>Hoạt động</option>
                                <option value="0" <?= $user['status'] == 0 ? 'selected' : '' ?>>Khóa</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-brand w-100 fw-bold py-2">Cập nhật</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
