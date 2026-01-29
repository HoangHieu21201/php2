@extends('layout.adminLayout')

@section('content')
    <?php
    // Khởi tạo biến lỗi
    $errors = $errors ?? [];
    ?>

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

        .current-img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .preview-img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px dashed #ccc;
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
            <div class="col-lg-10">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold text-brand m-0">Cập nhật Biến thể: <?= htmlspecialchars($variant['sku']) ?></h4>
                    <a href="/productvariant" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Quay lại
                    </a>
                </div>

                <?php if (isset($mess)): ?>
                <div class="alert alert-danger d-flex align-items-center mb-4 shadow-sm border-0" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                    <div><?= $mess ?></div>
                </div>
                <?php endif; ?>

                <form action="/productvariant/update/<?= $variant['id'] ?>" method="POST" enctype="multipart/form-data">
                    <div class="card border-0 shadow-sm p-4">

                        <!-- Block 1: Thông tin chung -->
                        <div class="form-section-title"><i class="bi bi-info-circle me-1"></i> Thông tin chung</div>
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Sản phẩm gốc <span class="text-danger">*</span></label>
                                <select name="product_id"
                                    class="form-select <?= isset($errors['product_id']) ? 'is-invalid' : '' ?>">
                                    <?php foreach($products as $p): ?>
                                    <option value="<?= $p['id'] ?>"
                                        <?= $variant['product_id'] == $p['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($p['name']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if(isset($errors['product_id'])): ?>
                                <div class="invalid-feedback"><?= $errors['product_id'] ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Mã SKU</label>
                                <input type="text" name="sku"
                                    class="form-control <?= isset($errors['sku']) ? 'is-invalid' : '' ?>"
                                    value="<?= htmlspecialchars($variant['sku']) ?>" placeholder="Để trống sẽ tự tạo lại">
                                <small class="text-muted d-block mt-1 fst-italic" style="font-size: 0.8rem;">
                                    Mã kho duy nhất. Nếu để trống, hệ thống sẽ tự tạo lại từ thuộc tính.
                                </small>
                                <?php if(isset($errors['sku'])): ?>
                                <div class="invalid-feedback"><?= $errors['sku'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Block 2: Thuộc tính -->
                        <div class="form-section-title mt-2"><i class="bi bi-tags me-1"></i> Thuộc tính biến thể</div>
                        <div class="row mb-4 p-3 bg-light rounded mx-0 border-start border-4 border-success">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Màu sắc</label>
                                <select name="color_id" class="form-select">
                                    <?php foreach($colors as $c): ?>
                                    <option value="<?= $c['id'] ?>"
                                        <?= $variant['color_id'] == $c['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($c['name']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Kích thước (Size)</label>
                                <select name="size_id" class="form-select">
                                    <?php foreach($sizes as $s): ?>
                                    <option value="<?= $s['id'] ?>"
                                        <?= $variant['size_id'] == $s['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($s['name']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Block 3: Giá bán & Kho -->
                        <div class="form-section-title mt-2"><i class="bi bi-currency-dollar me-1"></i> Giá bán & Kho</div>
                        <div class="row mb-4">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Giá gốc (VNĐ) <span class="text-danger">*</span></label>
                                <input type="number" name="price"
                                    class="form-control <?= isset($errors['price']) ? 'is-invalid' : '' ?>"
                                    value="<?= $variant['price'] ?>" min="0" placeholder="0">
                                <?php if(isset($errors['price'])): ?>
                                <div class="invalid-feedback"><?= $errors['price'] ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Giá khuyến mãi</label>
                                <input type="number" name="sale_price"
                                    class="form-control <?= isset($errors['sale_price']) ? 'is-invalid' : '' ?>"
                                    value="<?= $variant['sale_price'] ?>" min="0" placeholder="0">
                                <?php if(isset($errors['sale_price'])): ?>
                                <div class="invalid-feedback"><?= $errors['sale_price'] ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Số lượng kho <span class="text-danger">*</span></label>
                                <input type="number" name="quantity"
                                    class="form-control <?= isset($errors['quantity']) ? 'is-invalid' : '' ?>"
                                    value="<?= $variant['quantity'] ?>" min="0" placeholder="0">
                                <?php if(isset($errors['quantity'])): ?>
                                <div class="invalid-feedback"><?= $errors['quantity'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Block 4: Ảnh & Trạng thái -->
                        <div class="form-section-title mt-2"><i class="bi bi-image me-1"></i> Hình ảnh & Trạng thái</div>
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Ảnh biến thể</label>
                                <div class="mb-2">
                                    <input type="file" name="image" id="imageInput" class="form-control"
                                        accept="image/*">
                                </div>

                                <div class="d-flex gap-3 align-items-end">
                                    <!-- Ảnh hiện tại -->
                                    <?php if(!empty($variant['image'])): ?>
                                    <div class="text-center">
                                        <small class="d-block text-muted mb-1" style="font-size: 0.75rem;">Hiện tại</small>
                                        <img src="/<?= $variant['image'] ?>" class="current-img"
                                            onerror="this.src='https://placehold.co/120x120?text=No+Img'">
                                    </div>
                                    <?php endif; ?>

                                    <!-- Ảnh xem trước -->
                                    <div class="text-center" id="previewContainer" style="display: none;">
                                        <small class="d-block text-muted mb-1" style="font-size: 0.75rem;">Mới
                                            chọn</small>
                                        <img id="imagePreview" src="#" alt="Ảnh xem trước" class="preview-img">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Trạng thái</label>
                                <select name="status" class="form-select">
                                    <option value="1" <?= $variant['status'] == 1 ? 'selected' : '' ?>>Hoạt động
                                    </option>
                                    <option value="0" <?= $variant['status'] == 0 ? 'selected' : '' ?>>Ẩn</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                            <a href="/productvariant" class="btn btn-light border me-2 px-4">Hủy bỏ</a>
                            <button type="submit" class="btn btn-brand px-5 fw-bold py-2 shadow-sm">
                                <i class="bi bi-save me-1"></i> Cập nhật biến thể
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Script hiển thị thumbnail ảnh -->
    <script>
        const imageInput = document.getElementById('imageInput');
        const imagePreview = document.getElementById('imagePreview');
        const previewContainer = document.getElementById('previewContainer');

        imageInput.onchange = evt => {
            const [file] = imageInput.files;
            if (file) {
                imagePreview.src = URL.createObjectURL(file);
                previewContainer.style.display = 'block';
            } else {
                previewContainer.style.display = 'none';
            }
        }
    </script>
@endsection