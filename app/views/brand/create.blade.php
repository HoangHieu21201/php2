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
                    <h4 class="fw-bold text-brand m-0">Thêm Thương hiệu</h4>
                    <a href="/brand" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> Quay
                        lại</a>
                </div>
                <?php if (isset($mess)): ?>
                <div class="alert alert-success d-flex align-items-center" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <div><?= $mess ?></div>
                </div>
                <?php endif; ?>
                <?php unset($_SESSION['success']); ?>
                <form action="/brand/store" method="POST" enctype="multipart/form-data">
                    <div class="card p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tên thương hiệu <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required
                                placeholder="Nhập tên hãng...">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Slug (URL)</label>
                            <input type="text" name="slug" class="form-control"
                                placeholder="Để trống sẽ tự tạo theo tên">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Logo</label>
                            <div class="mb-2">
                                {{-- thumbnail khi moi them anh   --}}
                                <img id="img-preview" src="#" alt="Preview Image" class="img-thumbnail"
                                    style="display: none; height: 180px;">
                                <script>
                                    document.querySelector('input[name="image"]').addEventListener('change', function(event) {
                                        const [file] = event.target.files;
                                        if (file) {
                                            const imgPreview = document.getElementById('img-preview');
                                            imgPreview.src = URL.createObjectURL(file);
                                            imgPreview.style.display = 'block';
                                        }
                                    });
                                </script>

                            </div>
                            <input type="file" name="image" class="form-control">
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Trạng thái</label>
                            <select name="status" class="form-select">
                                <option value="1">Hiển thị</option>
                                <option value="0">Ẩn</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-brand w-100 fw-bold py-2"><i class="bi bi-save me-1"></i>
                            Lưu thương hiệu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
