<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root { --primary-color: #009981; }
        .text-brand { color: var(--primary-color) !important; }
        .btn-brand { background-color: var(--primary-color); color: white; }
        .btn-brand:hover { background-color: #007a67; color: white; }
        .card { border: none; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); border-radius: 0.5rem; }
        .form-label { font-weight: 600; font-size: 0.9rem; color: #555; }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold text-brand m-0">Cập nhật Sản phẩm #<?= $product['id'] ?></h4>
                    <a href="/product" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> Quay lại</a>
                </div>
<?php if (isset($mess)): ?>
                    <div class="alert alert-success d-flex align-items-center" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <div><?= $mess ?></div>
                    </div>
                <?php endif; ?>
                <form action="/product/update/<?= $product['id'] ?>" method="POST" enctype="multipart/form-data">
                    <div class="card p-4">
                        <div class="row g-3">
                            <!-- Cột trái -->
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($product['name']) ?>">
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Giá bán</label>
                                        <input type="number" name="price" class="form-control" required value="<?= $product['price'] ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Giá gốc (VND)</label>
                                        <input type="number" name="sale_price" class="form-control" value="<?= $product['sale_price'] ?>">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Mô tả ngắn</label>
                                    <textarea name="short_description" class="form-control" rows="2"><?= htmlspecialchars($product['short_description']) ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Mô tả chi tiết</label>
                                    <textarea name="description" class="form-control" rows="5"><?= htmlspecialchars($product['description']) ?></textarea>
                                </div>
                            </div>

                            <!-- Cột phải -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Ảnh hiện tại</label>
                                    <div class="mb-2">
                                        <?php if(!empty($product['image'])): ?>
                                            <img src="/uploads/<?= $product['image'] ?>" class="img-thumbnail" style="max-height: 150px;">
                                        <?php else: ?>
                                            <div class="text-muted small fst-italic">Chưa có ảnh</div>
                                        <?php endif; ?>
                                    </div>
                                    <label class="form-label small text-muted">Thay ảnh mới (nếu muốn)</label>
                                    <input type="file" name="image" class="form-control form-control-sm">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Danh mục <span class="text-danger">*</span></label>
                                    <select name="category_id" class="form-select" required>
                                        <?php foreach($categories as $c): ?>
                                            <option value="<?= $c['id'] ?>" <?= $c['id'] == $product['category_id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($c['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Số lượng kho</label>
                                    <input type="number" name="quantity" class="form-control" value="<?= $product['quantity'] ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Trạng thái</label>
                                    <select name="status" class="form-select">
                                        <option value="1" <?= $product['status'] == 1 ? 'selected' : '' ?>>Đang bán</option>
                                        <option value="0" <?= $product['status'] == 0 ? 'selected' : '' ?>>Tạm ẩn</option>
                                    </select>
                                </div>
                                
                                <hr>
                                <button type="submit" class="btn btn-brand w-100 fw-bold py-2"><i class="bi bi-check-lg me-1"></i> Cập nhật</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>