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
            <div class="col-lg-6">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold text-brand m-0">Sửa Thương hiệu #<?= $brand['id'] ?></h4>
                    <a href="/brand" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> Quay
                        lại</a>
                </div>
                <?php if (isset($mess)): ?>
                <div class="alert alert-success d-flex align-items-center" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <div><?= $mess ?></div>
                </div>
                <?php endif; ?>
                <form action="/brand/update/<?= $brand['id'] ?>" method="POST" enctype="multipart/form-data">
                    <div class="card p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tên thương hiệu <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required
                                value="<?= htmlspecialchars($brand['name']) ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Slug (URL)</label>
                            <input type="text" name="slug" class="form-control"
                                value="<?= htmlspecialchars($brand['slug']) ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Logo hiện tại</label>
                            <div class="mb-2">
                                <?php if(!empty($brand['image'])): ?>
                                <img src="/<?= $brand['image'] ?>" class="img-thumbnail" style="height: 180px;">
                                <?php else: ?>
                                <span class="text-muted small fst-italic">Chưa có logo</span>
                                <?php endif; ?>
                            </div>
                            <input type="file" name="image" class="form-control form-control-sm">
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Trạng thái</label>
                            <select name="status" class="form-select">
                                <option value="1" <?= $brand['status'] == 1 ? 'selected' : '' ?>>Hiển thị</option>
                                <option value="0" <?= $brand['status'] == 0 ? 'selected' : '' ?>>Ẩn</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-brand w-100 fw-bold py-2"><i class="bi bi-check-lg me-1"></i>
                            Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
