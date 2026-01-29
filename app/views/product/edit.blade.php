@extends('layout.adminLayout')

@section('content')
    <?php 
    // Khởi tạo biến để tránh lỗi
    $errors = $errors ?? []; 
    ?>

    <style>
        .text-brand { color: #009981 !important; }
        .btn-brand { background-color: #009981; color: white; }
        .btn-brand:hover { background-color: #007a67; color: white; }
        .current-img { 
            width: 100%; 
            max-height: 200px; 
            object-fit: contain; 
            border-radius: 8px; 
            border: 1px solid #ddd; 
            background: #f8f9fa;
        }
        .preview-img {
            width: 100%;
            max-height: 200px;
            object-fit: contain;
            border-radius: 8px;
            border: 1px dashed #ccc;
            display: none; /* Ẩn mặc định */
            margin-top: 10px;
            background: #f8f9fa;
        }
        .form-section-title {
            font-size: 0.85rem;
            font-weight: 700;
            color: #6c757d;
            text-transform: uppercase;
            margin-bottom: 1rem;
            border-bottom: 1px solid #eee;
            padding-bottom: 0.5rem;
        }
    </style>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-11">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold text-brand m-0">Cập nhật Sản phẩm: <?= htmlspecialchars($product['name']) ?></h4>
                    <a href="/product" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Quay lại
                    </a>
                </div>

                <?php if (isset($mess)): ?>
                <div class="alert alert-danger d-flex align-items-center mb-4 shadow-sm border-0" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                    <div><?= $mess ?></div>
                </div>
                <?php endif; ?>

                <form action="/product/update/<?= $product['id'] ?>" method="POST" enctype="multipart/form-data">
                    <div class="row g-4">
                        
                        <!-- CỘT TRÁI: THÔNG TIN CHÍNH -->
                        <div class="col-lg-8">
                            <div class="card border-0 shadow-sm p-4 h-100">
                                <div class="form-section-title"><i class="bi bi-card-text me-1"></i> Thông tin cơ bản</div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Tên sản phẩm <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control form-control-lg <?= isset($errors['name']) ? 'is-invalid' : '' ?>" 
                                           value="<?= htmlspecialchars($product['name']) ?>" 
                                           placeholder="Nhập tên sản phẩm...">
                                    <?php if(isset($errors['name'])): ?>
                                        <div class="invalid-feedback"><?= $errors['name'] ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Giá hiển thị (VNĐ) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" name="price" class="form-control <?= isset($errors['price']) ? 'is-invalid' : '' ?>" 
                                               value="<?= $product['price'] ?>" min="0">
                                        <span class="input-group-text">VNĐ</span>
                                        <?php if(isset($errors['price'])): ?>
                                            <div class="invalid-feedback"><?= $errors['price'] ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <small class="text-muted">Giá hiển thị đại diện (thường là giá thấp nhất của biến thể).</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Mô tả ngắn</label>
                                    <textarea name="short_description" class="form-control" rows="3" placeholder="Mô tả tóm tắt..."><?= htmlspecialchars($product['short_description'] ?? '') ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Mô tả chi tiết</label>
                                    <textarea name="description" class="form-control" rows="6" placeholder="Thông tin chi tiết..."><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card border-0 shadow-sm p-4 h-100">
                                <div class="form-section-title"><i class="bi bi-gear me-1"></i> Thiết lập & Ảnh</div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Danh mục <span class="text-danger">*</span></label>
                                    <select name="category_id" class="form-select <?= isset($errors['category_id']) ? 'is-invalid' : '' ?>">
                                        <?php foreach ($categories as $c): ?>
                                            <option value="<?= $c['id'] ?>" <?= ($product['category_id'] == $c['id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($c['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if(isset($errors['category_id'])): ?>
                                        <div class="invalid-feedback"><?= $errors['category_id'] ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Thương hiệu</label>
                                    <select name="brand_id" class="form-select">
                                        <option value="">-- Chọn thương hiệu --</option>
                                        <?php foreach ($brands as $b): ?>
                                            <option value="<?= $b['id'] ?>" <?= ($product['brand_id'] == $b['id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($b['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Trạng thái</label>
                                    <select name="status" class="form-select">
                                        <option value="1" <?= $product['status'] == 1 ? 'selected' : '' ?>>Đang bán</option>
                                        <option value="0" <?= $product['status'] == 0 ? 'selected' : '' ?>>Tạm ẩn</option>
                                    </select>
                                </div>

                                <hr>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Ảnh đại diện</label>
                                    <input type="file" name="image" id="imageInput" class="form-control mb-2" accept="image/*">
                                    <div class="form-text small mb-2">Định dạng: jpg, png, webp.</div>
                                    
                                    <div class="text-center">
                                        <!-- Ảnh hiện tại -->
                                        <?php if(!empty($product['image'])): ?>
                                            <div id="currentImageContainer">
                                                <small class="d-block text-muted mb-1">Hiện tại</small>
                                                <img src="/<?= $product['image'] ?>" class="current-img" onerror="this.src='https://placehold.co/300x300?text=No+Img'">
                                            </div>
                                        <?php endif; ?>
                                        
                                        <!-- Ảnh xem trước -->
                                        <div id="previewContainer" style="display: none;">
                                            <small class="d-block text-muted mb-1">Mới chọn</small>
                                            <img id="imagePreview" src="#" alt="Ảnh xem trước" class="preview-img">
                                        </div>
                                    </div>
                                </div>

                                <div class="d-grid mt-4">
                                    <button type="submit" class="btn btn-brand fw-bold py-2">
                                        <i class="bi bi-arrow-repeat me-1"></i> Cập nhật
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const imageInput = document.getElementById('imageInput');
        const imagePreview = document.getElementById('imagePreview');
        const previewContainer = document.getElementById('previewContainer');
        const currentImageContainer = document.getElementById('currentImageContainer');

        imageInput.onchange = evt => {
            const [file] = imageInput.files;
            if (file) {
                imagePreview.src = URL.createObjectURL(file);
                imagePreview.style.display = 'block';
                previewContainer.style.display = 'block';
                if(currentImageContainer) currentImageContainer.style.display = 'none'; // Ẩn ảnh cũ
            } else {
                imagePreview.style.display = 'none';
                previewContainer.style.display = 'none';
                if(currentImageContainer) currentImageContainer.style.display = 'block';
            }
        }
    </script>
@endsection