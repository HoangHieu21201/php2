@extends('layout.adminLayout')

@section('content')
    @php
        $old = $_SESSION['old'] ?? [];
        unset($_SESSION['old']);
    @endphp

    <style>
        .text-brand { color: #009981 !important; }
        .btn-brand { background-color: #009981; color: white; }
        .btn-brand:hover { background-color: #007a67; color: white; }
        .preview-img { width: 100%; max-height: 250px; object-fit: contain; border-radius: 8px; border: 1px dashed #ccc; display: none; margin-top: 10px; background: #f8f9fa; }
        .form-section-title { font-size: 0.85rem; font-weight: 700; color: #6c757d; text-transform: uppercase; margin-bottom: 1rem; border-bottom: 1px solid #eee; padding-bottom: 0.5rem; }
    </style>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-11">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold text-brand m-0">Thêm Sản phẩm mới</h4>
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

                <form action="/product/store" method="POST" enctype="multipart/form-data">
                    <div class="row g-4">
                        <!-- CỘT TRÁI -->
                        <div class="col-lg-8">
                            <div class="card border-0 shadow-sm p-4 h-100">
                                <div class="form-section-title"><i class="bi bi-card-text me-1"></i> Thông tin cơ bản</div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Tên sản phẩm <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control form-control-lg" 
                                           placeholder="Nhập tên sản phẩm..."
                                           value="{{ $old['name'] ?? '' }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Mô tả ngắn</label>
                                    <textarea name="short_description" class="form-control" rows="4" 
                                              placeholder="Mô tả tóm tắt sản phẩm...">{{ $old['short_description'] ?? '' }}</textarea>
                                </div>
                                
                                <div class="alert alert-info small border-0 bg-light text-muted mt-4">
                                    <i class="bi bi-info-circle me-1"></i> 
                                    Sản phẩm mới sẽ ở trạng thái <strong>Ẩn</strong>. Bạn cần cấu hình biến thể (Màu, Size, Giá) trước khi hiển thị bán.
                                </div>
                            </div>
                        </div>

                        <!-- CỘT PHẢI -->
                        <div class="col-lg-4">
                            <div class="card border-0 shadow-sm p-4 h-100">
                                <div class="form-section-title"><i class="bi bi-gear me-1"></i> Phân loại & Ảnh</div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Danh mục <span class="text-danger">*</span></label>
                                    <select name="category_id" class="form-select" required>
                                        <option value="">-- Chọn danh mục --</option>
                                        @foreach ($categories as $c)
                                            <option value="{{ $c['id'] }}" {{ (isset($old['category_id']) && $old['category_id'] == $c['id']) ? 'selected' : '' }}>
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
                                            <option value="{{ $b['id'] }}" {{ (isset($old['brand_id']) && $old['brand_id'] == $b['id']) ? 'selected' : '' }}>
                                                {{ $b['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <hr>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Ảnh đại diện</label>
                                    <input type="file" name="image" id="imageInput" class="form-control" accept="image/*">
                                    <div class="form-text small">Định dạng: JPG, PNG, WEBP.</div>
                                    <img id="imagePreview" src="#" class="preview-img shadow-sm">
                                </div>

                                <div class="d-grid mt-4">
                                    <button type="submit" class="btn btn-brand fw-bold py-2">
                                        <i class="bi bi-save me-1"></i> Lưu & Tiếp tục
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
    </script>
@endsection