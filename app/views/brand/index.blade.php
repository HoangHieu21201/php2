<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Thương hiệu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root { --primary-color: #009981; }
        .text-brand { color: var(--primary-color) !important; }
        .btn-brand { background-color: var(--primary-color); color: white; }
        .btn-brand:hover { background-color: #007a67; color: white; }
        .img-logo { width: 50px; height: 50px; object-fit: contain; border: 1px solid #eee; padding: 2px; background: #fff; border-radius: 4px; }
    </style>
</head>
<body>
    <?php require_once APP_PATH . '/views/layout/components/header.blade.php'; ?>

    <div class="container py-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold text-brand"><i class="bi bi-star me-2"></i>Thương hiệu</h4>
                <a href="/brand/create" class="btn btn-brand btn-sm shadow-sm"><i class="bi bi-plus-lg me-1"></i> Thêm mới</a>
            </div>
            <?php if (isset($mess)): ?>
                    <div class="alert alert-success d-flex align-items-center" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <div><?= $mess ?></div>
                    </div>
                <?php endif; ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4" style="width: 80px;">Logo</th>
                            <th>Tên thương hiệu</th>
                            <th>Slug (URL)</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center pe-4">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($brands)): ?>
                            <tr><td colspan="5" class="text-center py-5 text-muted">Chưa có thương hiệu nào.</td></tr>
                        <?php else: ?>
                            <?php foreach($brands as $brand): ?>
                            <tr>
                                <td class="ps-4">
                                    <img src="<?= $brand['image'] ?>" class="img-logo">
                                </td>
                                <td>
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($brand['name']) ?></div>
                                    <small class="text-muted">ID: #<?= $brand['id'] ?></small>
                                </td>
                                <td><code class="text-muted"><?= htmlspecialchars($brand['slug']) ?></code></td>
                                <td class="text-center">
                                    <?php if($brand['status'] == 1): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Hoạt động</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3">Ẩn</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center pe-4">
                                    <a href="/brand/edit/<?= $brand['id'] ?>" class="btn btn-sm btn-light border text-primary" title="Sửa"><i class="bi bi-pencil"></i></a>
                                    <a href="/brand/delete/<?= $brand['id'] ?>" class="btn btn-sm btn-light border text-danger ms-1" onclick="return confirm('Xóa thương hiệu này?');" title="Xóa"><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php require_once APP_PATH . '/views/layout/components/footer.blade.php'; ?>
</body>
</html>