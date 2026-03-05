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

        /* Custom Switch */
        .form-check-input.status-switch {
            width: 2.5em;
            height: 1.25em;
            cursor: pointer;
            transition: 0.3s;
        }

        .form-check-input.status-switch:checked {
            background-color: #009981;
            border-color: #009981;
        }

        /* Toast Container */
        .toast-container {
            z-index: 9999;
        }
        /* Badge */
        .badge-active {
            background-color: rgba(0, 153, 129, 0.1);
            color: #009981;
            border: 1px solid rgba(0, 153, 129, 0.2);
        }

        .badge-inactive {
            background-color: rgba(108, 117, 125, 0.1);
            color: #6c757d;
            border: 1px solid rgba(108, 117, 125, 0.2);
        }
    </style>

    <div class="container-fluid px-4 py-4 position-relative">

        <!-- Toast Notification -->
        <div class="toast-container position-fixed top-0 end-0 p-3"></div>

        <div class="card border-0 shadow-sm">
            <!-- Header -->
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold text-brand"><i class="bi bi-box-seam me-2"></i>Quản lý Sản phẩm</h4>
                <a href="/product/create" class="btn btn-brand btn-sm shadow-sm">
                    <i class="bi bi-plus-lg me-1"></i> Thêm mới
                </a>
            </div>

            <!-- Filter -->
            <div class="bg-filter p-3">
                <form action="/product" method="GET" class="row g-2">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-white text-muted"><i class="bi bi-search"></i></span>
                            <input type="text" name="keyword" class="form-control" placeholder="Tìm theo tên sản phẩm..."
                                value="{{ $filters['keyword'] ?? '' }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Tìm kiếm</button>
                    </div>
                    <div class="col-md-1">
                        <a href="/product" class="btn btn-outline-secondary w-100" title="Làm mới"><i
                                class="bi bi-arrow-counterclockwise"></i></a>
                    </div>
                </form>
            </div>

            <!-- Thông báo Session -->
            @if (isset($_SESSION['success']))
                <div class="alert alert-success d-flex align-items-center m-3" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <div>{{ $_SESSION['success'] }}</div>
                    @php unset($_SESSION['success']); @endphp
                </div>
            @endif

            @if (isset($_SESSION['error']))
                <div class="alert alert-danger d-flex align-items-center m-3" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <div>{{ $_SESSION['error'] }}</div>
                    @php unset($_SESSION['error']); @endphp
                </div>
            @endif

            <!-- Bảng dữ liệu -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Sản phẩm</th>
                            <th>Danh mục</th>
                            <th>Thương hiệu</th>
                            <th class="text-center">Biến thể</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center pe-4" style="width: 180px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (empty($products))
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                    Không tìm thấy sản phẩm nào.
                                </td>
                            </tr>
                        @else
                            @foreach ($products as $p)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $p['image'] }}" class="img-thumb me-3"
                                                onerror="this.src='https://placehold.co/50x50?text=No+Img'">
                                            <div>
                                                <div class="fw-bold text-dark">{{ $p['name'] }}</div>
                                                <small class="text-muted">ID: #{{ $p['id'] }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-secondary border text-dark">
                                            {{ $p['category_name'] ?? '---' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-secondary border text-dark">
                                            {{ $p['brand_name'] ?? '---' }}
                                        </span>
                                    </td>

                                    <!-- Cột Số lượng Biến thể -->
                                    <td class="text-center">
                                        <span
                                            class="badge {{ $p['variant_count'] > 0 ? 'bg-info' : 'bg-secondary' }} bg-opacity-10 text-dark border px-2 py-1">
                                            {{ $p['variant_count'] }} <small class="fw-normal text-muted">Biến thể</small>
                                        </span>
                                        @if ($p['active_variant_count'] > 0)
                                            <div class="small text-success mt-1" style="font-size: 0.7em;">
                                                <i class="bi bi-check-circle-fill"></i> {{ $p['active_variant_count'] }}
                                                Hoạt động
                                            </div>
                                        @endif
                                    </td>

                                    {{-- trạng thái --}}
                                    <td class="text-center">
                                        @if ($p['status'] == 1)
                                            <span class="badge badge-active  rounded-pill px-3 py-2">
                                                <i class="bi bi-check-circle-fill me-1"></i> Hoạt động
                                            </span>
                                        @else
                                            <span class="badge badge-inactive rounded-pill px-3 py-2">
                                                <i class="bi bi-dash-circle-fill me-1"></i> Đang ẩn
                                            </span>
                                        @endif
                                    </td>

                                    <td class="text-center pe-4">
                                        <div class="btn-group btn-group-sm">
                                            <a href="/productVariant/create?product_id={{ $p['id'] }}"
                                                class="btn btn-outline-info" title="Quản lý biến thể">
                                                <i class="bi bi-layers"></i>
                                            </a>
                                            <a href="/product/edit/{{ $p['id'] }}" class="btn btn-outline-primary"
                                                title="Sửa thông tin">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="/product/delete/{{ $p['id'] }}" class="btn btn-outline-danger"
                                                onclick="return confirm('Xóa sản phẩm này sẽ xóa luôn các biến thể bên trong. Tiếp tục?');"
                                                title="Xóa">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function toggleStatus(checkbox, id) {
            const newStatus = checkbox.checked ? 1 : 0;
            const originalState = !checkbox.checked;

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
