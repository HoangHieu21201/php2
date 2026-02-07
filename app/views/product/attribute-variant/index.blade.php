@extends('layout.adminLayout')

@section('content')
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

        .img-thumb {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #eee;
        }

        .bg-filter {
            background-color: #f8f9fa;
            border-bottom: 1px solid #eee;
        }

        /* Custom Switch Size */
        .form-check-input.status-switch {
            width: 2.5em;
            height: 1.25em;
            cursor: pointer;
            transition: 0.2s;
        }

        .form-check-input.status-switch:checked {
            background-color: #009981;
            border-color: #009981;
        }

        /* Toast Container */
        .toast-container {
            z-index: 9999;
        }
    </style>

    <div class="container-fluid px-4 py-4 position-relative">

        <!-- Toast Notification Container -->
        <div class="toast-container position-fixed top-0 end-0 p-3"></div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h4 class="mb-0 fw-bold text-brand"><i class="bi bi-layers me-2"></i>Cấu hình Biến thể Sản phẩm</h4>
                <p class="text-muted small mb-0 mt-1">Chọn sản phẩm bên dưới để thêm hoặc quản lý các biến thể (Màu, Size,
                    Giá...)</p>
            </div>

            <!-- Filter -->
            <div class="card-body bg-light border-bottom">
                <form action="/productvariant" method="GET" class="row g-2">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-white text-muted border-end-0"><i
                                    class="bi bi-search"></i></span>
                            <input type="text" name="keyword" class="form-control border-start-0"
                                placeholder="Tìm tên sản phẩm..." value="{{ $filters['keyword'] ?? '' }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Tìm kiếm</button>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Hình ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>Danh mục</th>
                            <th>Thương hiệu</th>
                            <th class="text-center">Biến thể</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (empty($products))
                            <tr>
                                <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary opacity-50"></i>

                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary opacity-50"></i>

                                    Không tìm thấy sản phẩm nào.
                                </td>
                            </tr>
                        @else
                            @foreach ($products as $p)
                                <tr>
                                    <td class="ps-4">
                                        <img src="{{ !empty($p['image']) ? $p['image'] : 'https://placehold.co/50x50' }}"
                                            class="img-thumb">
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $p['name'] }}</div>
                                        <small class="text-muted">ID: #{{ $p['id'] }}</small>
                                    </td>
                                    <td><span
                                            class="badge bg-light text-secondary border">{{ $p['category_name'] ?? '---' }}</span>
                                    </td>
                                    <td><span
                                            class="badge bg-light text-secondary border">{{ $p['brand_name'] ?? '---' }}</span>
                                    </td>

                                    <!-- Cột Số lượng Biến thể (Dùng số liệu từ Controller) -->
                                    <td class="text-center">
                                        <span
                                            class="badge {{ ($p['variant_count'] ?? 0) > 0 ? 'bg-primary' : 'bg-secondary' }} bg-opacity-10 text-dark border px-3 py-2">
                                            {{ $p['variant_count'] ?? 0 }} <span class="text-muted fw-normal">biến
                                                thể</span>
                                        </span>
                                        @if (($p['active_variant_count'] ?? 0) > 0)
                                            <div class="small text-success mt-1" style="font-size: 0.7em;">
                                                <i class="bi bi-check-circle-fill"></i> {{ $p['active_variant_count'] }}
                                                active
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Cột Trạng thái (Switch Ajax) -->
                                    <td class="text-center">
                                        <div class="form-check form-switch d-inline-block">
                                            <input class="form-check-input status-switch" type="checkbox" role="switch"
                                                {{ $p['status'] == 1 ? 'checked' : '' }}
                                                onchange="toggleStatus(this, {{ $p['id'] }})"
                                                title="Bật/Tắt hiển thị sản phẩm">
                                        </div>
                                    </td>

                                    <td class="text-center">
                                        <a href="/productvariant/create?product_id={{ $p['id'] }}"
                                            class="btn btn-sm btn-brand shadow-sm">
                                            <i class="bi bi-gear-fill me-1"></i> Cấu hình
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Script xử lý Ajax Status -->
    <script>
        function toggleStatus(checkbox, id) {
            const newStatus = checkbox.checked ? 1 : 0;
            const originalState = !checkbox.checked; // Lưu trạng thái cũ để revert nếu lỗi

            // Gọi API updateStatus trong ProductController
            fetch('/product/quickUpdateStatus', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id: id,
                        status: newStatus
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message, 'success');
                    } else {
                        // Nếu lỗi (vd: không có biến thể active), quay lại trạng thái cũ
                        checkbox.checked = originalState;
                        showToast(data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    checkbox.checked = originalState;
                    showToast('Lỗi kết nối máy chủ!', 'error');
                });
        }

        // Hàm hiển thị Toast
        function showToast(message, type = 'success') {
            const container = document.querySelector('.toast-container');
            const bgClass = type === 'success' ? 'bg-success' : 'bg-danger';
            const icon = type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill';

            const html = `
                <div class="toast align-items-center text-white ${bgClass} border-0 mb-2 shadow" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body d-flex align-items-center gap-2">
                            <i class="bi ${icon} fs-5"></i>
                            <div>${message}</div>
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            `;
            const template = document.createElement('template');
            template.innerHTML = html.trim();
            const toastEl = template.content.firstChild;
            container.appendChild(toastEl);
            const toast = new bootstrap.Toast(toastEl, {
                delay: 4000
            });
            toast.show();
            toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
        }
    </script>
@endsection
