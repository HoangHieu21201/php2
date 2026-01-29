@extends('layout.adminLayout')

@section('content')
    <?php 
    // Khởi tạo biến để tránh lỗi Undefined khi mới vào trang
    $old = $old ?? []; 
    $errors = $errors ?? []; 
    ?>

    <style>
        .text-brand { color: #009981 !important; }
        .btn-brand { background-color: #009981; color: white; }
        .btn-brand:hover { background-color: #007a67; color: white; }
        .preview-img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px dashed #ccc;
            display: none; /* Ẩn mặc định */
            margin-top: 10px;
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
                    <h4 class="fw-bold text-brand m-0">Thêm Biến thể Sản phẩm</h4>
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

                <form action="/productvariant/store" method="POST" enctype="multipart/form-data">
                    <div class="card border-0 shadow-sm p-4">
                        
                        <!-- Block 1: Thông tin cơ bản -->
                        <div class="form-section-title"><i class="bi bi-info-circle me-1"></i> Thông tin chung</div>
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Sản phẩm gốc <span class="text-danger">*</span></label>
                                <!-- Thêm id="productSelect" để dùng JS -->
                                <select name="product_id" id="productSelect" class="form-select <?= isset($errors['product_id']) ? 'is-invalid' : '' ?>">
                                    <option value="">-- Chọn sản phẩm --</option>
                                    <?php foreach($products as $p): ?>
                                        <option value="<?= $p['id'] ?>" 
                                                data-price="<?= $p['price'] ?>"
                                                <?= (isset($old['product_id']) && $old['product_id'] == $p['id']) ? 'selected' : '' ?>>
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
                                <input type="text" name="sku" class="form-control <?= isset($errors['sku']) ? 'is-invalid' : '' ?>" 
                                       value="<?= $old['sku'] ?? '' ?>" 
                                       placeholder="Để trống sẽ tự tạo">
                                <small class="text-muted d-block mt-1 fst-italic" style="font-size: 0.8rem;">
                                </small>
                                <?php if(isset($errors['sku'])): ?>
                                    <div class="invalid-feedback"><?= $errors['sku'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="form-section-title mt-2"><i class="bi bi-tags me-1"></i> Thuộc tính biến thể</div>
                        <div class="row mb-4 p-3 bg-light rounded mx-0 border-start border-4 border-success">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Màu sắc</label>
                                <select name="color_id" class="form-select">
                                    <option value="">-- Chọn màu --</option>
                                    <?php foreach($colors as $c): ?>
                                        <option value="<?= $c['id'] ?>" <?= (isset($old['color_id']) && $old['color_id'] == $c['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($c['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Kích thước (Size)</label>
                                <select name="size_id" class="form-select">
                                    <option value="">-- Chọn size --</option>
                                    <?php foreach($sizes as $s): ?>
                                        <option value="<?= $s['id'] ?>" <?= (isset($old['size_id']) && $old['size_id'] == $s['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($s['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Block 3: Giá và Kho -->
                        <div class="form-section-title mt-2"><i class="bi bi-currency-dollar me-1"></i> Giá bán & Kho</div>
                        <div class="row mb-4">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Giá gốc (VNĐ) <span class="text-danger">*</span></label>
                                <!-- Thêm id="priceInput" -->
                                <input type="number" name="price" id="priceInput" class="form-control <?= isset($errors['price']) ? 'is-invalid' : '' ?>" 
                                       value="<?= $old['price'] ?? '' ?>" min="0" placeholder="0">
                                <!-- Thêm hint hiển thị giá gốc -->
                                <div id="basePriceHint" class="form-text text-primary small mt-1"></div>
                                <?php if(isset($errors['price'])): ?>
                                    <div class="invalid-feedback"><?= $errors['price'] ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Giá khuyến mãi</label>
                                <input type="number" name="sale_price" class="form-control <?= isset($errors['sale_price']) ? 'is-invalid' : '' ?>" 
                                       value="<?= $old['sale_price'] ?? 0 ?>" min="0" placeholder="0">
                                <?php if(isset($errors['sale_price'])): ?>
                                    <div class="invalid-feedback"><?= $errors['sale_price'] ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Số lượng kho <span class="text-danger">*</span></label>
                                <input type="number" name="quantity" class="form-control <?= isset($errors['quantity']) ? 'is-invalid' : '' ?>" 
                                       value="<?= $old['quantity'] ?? 0 ?>" min="0" placeholder="0">
                                <?php if(isset($errors['quantity'])): ?>
                                    <div class="invalid-feedback"><?= $errors['quantity'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Block 4: Ảnh và Trạng thái -->
                        <div class="form-section-title mt-2"><i class="bi bi-image me-1"></i> Hình ảnh & Trạng thái</div>
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Ảnh biến thể (Tùy chọn)</label>
                                <input type="file" name="image" id="imageInput" class="form-control" accept="image/*">
                                <small class="text-muted">Nên upload ảnh đúng màu sắc của biến thể này.</small>
                                <!-- Thumbnail Preview -->
                                <div>
                                    <img id="imagePreview" src="#" alt="Ảnh xem trước" class="preview-img">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Trạng thái</label>
                                <select name="status" class="form-select">
                                    <option value="1" <?= (isset($old['status']) && $old['status'] == 1) ? 'selected' : '' ?>>Hoạt động</option>
                                    <option value="0" <?= (isset($old['status']) && $old['status'] == 0) ? 'selected' : '' ?>>Ẩn</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                            <a href="/productvariant" class="btn btn-light border me-2 px-4">Hủy bỏ</a>
                            <button type="submit" class="btn btn-brand px-5 fw-bold py-2 shadow-sm">
                                <i class="bi bi-save me-1"></i> Lưu biến thể
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // 1. Script hiển thị thumbnail ảnh
        const imageInput = document.getElementById('imageInput');
        const imagePreview = document.getElementById('imagePreview');

        if(imageInput) {
            imageInput.onchange = evt => {
                const [file] = imageInput.files;
                if (file) {
                    imagePreview.src = URL.createObjectURL(file);
                    imagePreview.style.display = 'block';
                } else {
                    imagePreview.style.display = 'none';
                }
            }
        }

        // 2. Script tự động lấy giá gốc khi chọn sản phẩm
        const productSelect = document.getElementById('productSelect');
        const priceInput = document.getElementById('priceInput');
        const basePriceHint = document.getElementById('basePriceHint');

        if(productSelect && priceInput) {
            productSelect.addEventListener('change', function() {
                // Lấy option đang được chọn
                const selectedOption = this.options[this.selectedIndex];
                // Lấy giá từ data-price
                const basePrice = selectedOption.getAttribute('data-price');

                if (basePrice) {
                    // Nếu ô giá đang trống hoặc bằng 0, tự động điền
                    if (!priceInput.value || priceInput.value == 0) {
                        priceInput.value = basePrice;
                    }
                    // Hiển thị gợi ý bên dưới
                    basePriceHint.innerHTML = '<i class="bi bi-arrow-return-right"></i> Giá gốc của sản phẩm này là: <b>' + new Intl.NumberFormat('vi-VN').format(basePrice) + ' đ</b>';
                } else {
                    basePriceHint.innerHTML = '';
                }
            });
        }
    </script>
@endsection