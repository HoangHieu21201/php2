@extends('layout.adminLayout')

@section('content')
    <style>
        .text-brand { color: #009981 !important; }
        .btn-brand { background-color: #009981; color: white; }
        .btn-brand:hover { background-color: #007a67; color: white; }
        .current-img { width: 100%; max-height: 200px; object-fit: contain; border-radius: 8px; border: 1px solid #ddd; background: #f8f9fa; }
        .preview-img { width: 100%; max-height: 200px; object-fit: contain; border-radius: 8px; border: 1px dashed #ccc; display: none; margin-top: 10px; background: #f8f9fa; }
        .form-section-title { font-size: 0.85rem; font-weight: 700; color: #6c757d; text-transform: uppercase; margin-bottom: 1rem; border-bottom: 1px solid #eee; padding-bottom: 0.5rem; }
    </style>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-11">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold text-brand m-0">Cập nhật Sản phẩm: {{ $product['name'] }}</h4>
                    <a href="/product" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Quay lại
                    </a>
                </div>

                @if (isset($_SESSION['error']))
                <div class="alert alert-danger d-flex align-items-center mb-4 shadow-sm border-0" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                    <div>{{ $_SESSION['error'] }}</div>
                    @php unset($_SESSION['error']); @endphp
                </div>
                @endif

                <form action="/product/update/{{ $product['id'] }}" method="POST" enctype="multipart/form-data">
                    <div class="row g-4">
                        <div class="col-lg-8">
                            <div class="card border-0 shadow-sm p-4 h-100">
                                <div class="form-section-title"><i class="bi bi-card-text me-1"></i> Thông tin cơ bản</div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Tên sản phẩm <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control form-control-lg" 
                                           value="{{ $product['name'] }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Mô tả ngắn</label>
                                    <textarea name="short_description" class="form-control" rows="5">{{ $product['short_description'] ?? '' }}</textarea>
                                </div>

                                <div class="alert alert-light border mt-4">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="bi bi-layers-fill text-brand me-2"></i>
                                            <strong>Quản lý Biến thể</strong>
                                            <p class="mb-0 small text-muted">Giá và kho được quản lý tại đây.</p>
                                        </div>
                                        <a href="/productvariant?product_id={{ $product['id'] }}" class="btn btn-outline-info btn-sm">
                                            Đến trang Biến thể <i class="bi bi-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card border-0 shadow-sm p-4 h-100">
                                <div class="form-section-title"><i class="bi bi-gear me-1"></i> Phân loại & Ảnh</div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Danh mục <span class="text-danger">*</span></label>
                                    <select name="category_id" class="form-select" required>
                                        @foreach ($categories as $c)
                                            <option value="{{ $c['id'] }}" {{ ($product['category_id'] == $c['id']) ? 'selected' : '' }}>
                                                {{ $c['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Thương hiệu</label>
                                    <select name="brand_id" class="form-select">
                                        <option value="">-- Chọn thương hiệu --</option>
                                        @foreach ($brands as $b)
                                            <option value="{{ $b['id'] }}" {{ ($product['brand_id'] == $b['id']) ? 'selected' : '' }}>
                                                {{ $b['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <hr>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Ảnh đại diện</label>
                                    <input type="file" name="image" id="imageInput" class="form-control mb-2" accept="image/*">
                                    
                                    <div class="text-center">
                                        @if(!empty($product['image']))
                                            <div id="currentImageContainer">
                                                <small class="d-block text-muted mb-1">Hiện tại</small>
                                                <img src="/{{ $product['image'] }}" class="current-img" onerror="this.src='https://placehold.co/300x300?text=No+Img'">
                                            </div>
                                        @endif
                                        
                                        <div id="previewContainer" style="display: none;">
                                            <small class="d-block text-muted mb-1">Mới chọn</small>
                                            <img id="imagePreview" src="#" class="preview-img">
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

        if(imageInput) {
            imageInput.onchange = evt => {
                const [file] = imageInput.files;
                if (file) {
                    imagePreview.src = URL.createObjectURL(file);
                    previewContainer.style.display = 'block';
                    if(currentImageContainer) currentImageContainer.style.display = 'none';
                } else {
                    previewContainer.style.display = 'none';
                    if(currentImageContainer) currentImageContainer.style.display = 'block';
                }
            }
        }
    </script>
@endsection