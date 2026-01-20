<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Danh mục</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root { --primary-color: #009981; --primary-hover: #007a67; }
        body { background-color: #f4f6f9; font-family: 'Segoe UI', sans-serif; }
        .text-brand { color: var(--primary-color) !important; }
        .btn-primary { background-color: var(--primary-color); border-color: var(--primary-color); }
        .btn-primary:hover { background-color: var(--primary-hover); border-color: var(--primary-hover); }
        .form-label.required::after { content: " *"; color: red; }
        .card { border: none; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); border-radius: 0.5rem; }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold text-brand mb-0">Tạo Danh mục mới</h4>
                    <a href="/category" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Quay lại</a>
                </div>
<!-- thông báo $mess --> 
                <?php if (isset($mess)): ?>
                    <div class="alert alert-success d-flex align-items-center" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <div><?= $mess ?></div>
                    </div>
                <?php endif; ?>

                <!-- Form Card -->
                <div class="card">
                    <div class="card-body p-4">
                        
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger d-flex align-items-center" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <div><?= $error ?></div>
                            </div>
                        <?php endif; ?>

                        <form action="/category/store" method="POST">
                            <div class="mb-3">
                                <label class="form-label required fw-bold text-secondary">Tên danh mục</label>
                                <input type="text" name="name" class="form-control" placeholder="Ví dụ: Điện thoại, Laptop..." >
                                <div class="form-text">Tên danh mục nên ngắn gọn và rõ ràng.</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-secondary">Trạng thái</label>
                                <select name="status" class="form-select">
                                    <option value="1">Hoạt động (Hiện lên web)</option>
                                    <option value="0">Vô hiệu hóa (Ẩn đi)</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold text-secondary">Mô tả</label>
                                <textarea name="description" class="form-control" rows="4" placeholder="Nhập mô tả chi tiết cho danh mục..."></textarea>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary py-2 fw-bold">
                                    <i class="bi bi-save me-1"></i> Lưu danh mục
                                </button>
                                <a href="/category" class="btn btn-light text-muted border-0">Hủy bỏ</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>