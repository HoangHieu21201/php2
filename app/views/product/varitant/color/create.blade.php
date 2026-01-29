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
                        <h4 class="fw-bold text-brand m-0">Thêm Màu</h4>
                        <a href="/brand" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> Quay
                            lại</a>
                    </div>
                    <?php if (isset($mess)): ?>
                    <div class="alert alert-success d-flex align-items-center" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <div><?= $mess ?></div>
                    </div>
                    <?php endif; ?>
                    <form action="/color/store" method="POST" enctype="multipart/form-data">
                        <div class="card p-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tên Màu <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" required
                                    placeholder="Nhập tên hãng...">
                            </div>

                            <button type="submit" class="btn btn-brand w-100 fw-bold py-2"><i class="bi bi-save me-1"></i>
                                Lưu Màu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection
