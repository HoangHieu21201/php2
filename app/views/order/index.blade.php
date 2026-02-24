@extends('layout.adminLayout')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --brand-color: #009981;
        }

        .custom-tabs {
            border-bottom: 1px solid #dee2e6;
        }

        .custom-tabs .nav-link {
            color: #6c757d;
            font-weight: 600;
            border: none;
            border-bottom: 3px solid transparent;
            padding: 12px 20px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .custom-tabs .nav-link:hover {
            color: var(--brand-color);
            background: rgba(0, 153, 129, 0.05);
        }

        .custom-tabs .nav-link.active {
            color: var(--brand-color);
            background: transparent;
            border-bottom: 3px solid var(--brand-color);
        }

        .custom-tabs .badge {
            margin-left: 8px;
            vertical-align: text-top;
        }

        .btn-action-icon {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .btn-action-text {
            padding: 4px 12px;
            font-weight: 600;
            font-size: 0.85rem;
            border-radius: 20px;
        }

        .text-brand {
            color: var(--brand-color) !important;
        }

        .status-label {
            padding: 6px 12px;
            border-radius: 30px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
        }

        .st-pending {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }

        .st-approved {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        .st-shipping {
            background: #cce5ff;
            color: #004085;
            border: 1px solid #b8daff;
        }

        .st-completed {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .st-cancelled {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .st-returns {
            background: #e2e3e5;
            color: #383d41;
            border: 1px solid #d6d8db;
        }

        .form-switch .form-check-input {
            width: 2.5em;
            height: 1.25em;
            cursor: pointer;
        }

        .form-switch .form-check-input:checked {
            background-color: var(--brand-color);
            border-color: var(--brand-color);
        }

        /* Pagination Style */
        .pagination .page-link {
            color: #333;
            border: none;
            margin: 0 2px;
            border-radius: 4px;
        }

        .pagination .page-link:hover {
            background-color: #f0f0f0;
            color: var(--brand-color);
        }

        .pagination .page-item.active .page-link {
            background-color: var(--brand-color);
            color: white;
        }

        .pagination .page-item.disabled .page-link {
            color: #ccc;
        }
    </style>

    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
            <h1 class="h3 mb-0 text-brand font-weight-bold">Quản lý Đơn hàng</h1>
            {{-- <ol class="breadcrumb m-0 bg-transparent p-0">
                <li class="breadcrumb-item"><a href="/admin" class="text-decoration-none" style="color: #009981;">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">Đơn hàng</li>
            </ol> --}}
        </div>

        <!-- TABS NAVIGATOR -->
        <div class="card shadow mb-4">
            <div class="card-header bg-white border-bottom-0 pb-0">
                <ul class="nav nav-tabs custom-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link {{ $currentStatus == 'all' ? 'active' : '' }}" href="/order?status=all">
                            Tất cả <span class="badge bg-secondary text-white rounded-pill">{{ $stats['all'] ?? 0 }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $currentStatus == 'pending' ? 'active' : '' }}" href="/order?status=pending">
                            <i class="bi bi-hourglass-split me-1 text-warning"></i> Chờ xử lý
                            <span class="badge bg-warning text-dark rounded-pill">{{ $stats['pending'] ?? 0 }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $currentStatus == 'approved' ? 'active' : '' }}"
                            href="/order?status=approved">
                            <i class="bi bi-check2-circle me-1 text-info"></i> Đã duyệt
                            <span class="badge bg-info text-white rounded-pill">{{ $stats['approved'] ?? 0 }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $currentStatus == 'shipping' ? 'active' : '' }}"
                            href="/order?status=shipping">
                            <i class="bi bi-truck me-1 text-primary"></i> Đang giao
                            <span class="badge bg-primary text-white rounded-pill">{{ $stats['shipping'] ?? 0 }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $currentStatus == 'completed' ? 'active' : '' }}"
                            href="/order?status=completed">
                            <i class="bi bi-check-circle-fill me-1 text-success"></i> Hoàn thành
                            <span class="badge bg-success text-white rounded-pill">{{ $stats['completed'] ?? 0 }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $currentStatus == 'returns' ? 'active' : '' }}" href="/order?status=returns">
                            <i class="bi bi-arrow-counterclockwise me-1 text-secondary"></i> Hàng hoàn
                            <span class="badge bg-secondary text-white rounded-pill">{{ $stats['returns'] ?? 0 }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $currentStatus == 'cancelled' ? 'active' : '' }}"
                            href="/order?status=cancelled">
                            <i class="bi bi-x-circle me-1 text-danger"></i> Đã hủy
                            <span class="badge bg-danger text-white rounded-pill">{{ $stats['cancelled'] ?? 0 }}</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body">
                @if (isset($_SESSION['success']))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ $_SESSION['success'] }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <?php unset($_SESSION['success']); ?>
                    </div>
                @endif
                @if (isset($_SESSION['error']))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i> {{ $_SESSION['error'] }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <?php unset($_SESSION['error']); ?>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle" width="100%">
                        <thead class="bg-light text-secondary">
                            <tr>
                                <th width="80">Mã ĐH</th>
                                <th>Khách hàng</th>
                                <th>Tổng tiền</th>
                                <th width="140" class="text-center">Thanh toán</th>
                                <th class="text-center">Trạng thái</th>
                                <th style="min-width: 160px;" class="text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($orders) > 0)
                                @foreach ($orders as $order)
                                    <tr>
                                        <td class="font-weight-bold text-brand">#{{ $order['id'] }}</td>
                                        <td>
                                            <div class="font-weight-bold text-dark">{{ $order['recipient_name'] }}</div>
                                            <small class="text-muted"><i class="bi bi-telephone"></i>
                                                {{ $order['recipient_phone'] }}</small><br>
                                            <small class="text-muted text-truncate d-inline-block"
                                                style="max-width: 200px;">{{ $order['recipient_address'] }}</small>
                                        </td>
                                        <td class="font-weight-bold text-danger">
                                            {{ number_format($order['total_amount'], 0, ',', '.') }}đ</td>

                                        <!-- NÚT GẠT THANH TOÁN -->
                                        <td class="text-center">
                                            @if (!in_array($order['status'], ['cancelled', 'returns']))
                                                <div class="d-flex flex-column align-items-center">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input payment-toggle" type="checkbox"
                                                            role="switch" id="paySwitch_{{ $order['id'] }}"
                                                            data-id="{{ $order['id'] }}" data-tab="{{ $currentStatus }}"
                                                            {{ $order['payment_status'] == 'paid' ? 'checked' : '' }}
                                                            onchange="togglePayment(this)">
                                                        <label
                                                            class="form-check-label small font-weight-bold {{ $order['payment_status'] == 'paid' ? 'text-success' : 'text-secondary' }}"
                                                            for="paySwitch_{{ $order['id'] }}"
                                                            id="payLabel_{{ $order['id'] }}">
                                                            {{ $order['payment_status'] == 'paid' ? 'Đã TT' : 'Chưa TT' }}
                                                        </label>
                                                    </div>
                                                    <div class="small text-muted mt-1" style="font-size: 10px;">
                                                        {{ strtoupper($order['payment_method']) }}</div>
                                                </div>
                                            @else
                                                <span
                                                    class="badge bg-light border text-secondary">{{ $order['payment_status'] == 'paid' ? 'Đã TT' : 'Chưa TT' }}</span>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            @if ($order['status'] == 'pending')
                                                <span class="status-label st-pending">Chờ xử lý</span>
                                            @elseif($order['status'] == 'approved')
                                                <span class="status-label st-approved">Đã duyệt</span>
                                            @elseif($order['status'] == 'shipping')
                                                <span class="status-label st-shipping">Đang giao</span>
                                            @elseif($order['status'] == 'completed')
                                                <span class="status-label st-completed">Hoàn thành</span>
                                            @elseif($order['status'] == 'cancelled')
                                                <span class="status-label st-cancelled">Đã hủy</span>
                                            @elseif($order['status'] == 'returns')
                                                <span class="status-label st-returns">Hoàn hàng</span>
                                            @endif
                                            <div class="small text-muted mt-1">
                                                {{ date('d/m H:i', strtotime($order['created_at'])) }}</div>
                                        </td>

                                        <td class="text-center">
                                            <div class="d-flex gap-2 justify-content-center align-items-center">

                                                @if ($order['status'] == 'pending')
                                                    <a href="/order/quickUpdate/{{ $order['id'] }}?status=approved&tab={{ $currentStatus }}"
                                                        class="btn btn-success btn-sm btn-action-text shadow-sm"
                                                        onclick="return confirm('Duyệt đơn hàng này?')">
                                                        <i class="bi bi-check-lg"></i> Duyệt
                                                    </a>
                                                    <a href="/order/quickUpdate/{{ $order['id'] }}?status=cancelled&tab={{ $currentStatus }}"
                                                        class="btn btn-outline-danger btn-sm btn-action-icon"
                                                        onclick="return confirm('Hủy đơn hàng này?')" title="Hủy đơn">
                                                        <i class="bi bi-x-lg"></i>
                                                    </a>
                                                @elseif($order['status'] == 'approved')
                                                    <a href="/order/quickUpdate/{{ $order['id'] }}?status=shipping&tab={{ $currentStatus }}"
                                                        class="btn btn-primary btn-sm btn-action-text shadow-sm">
                                                        <i class="bi bi-truck"></i> Giao hàng
                                                    </a>
                                                    <a href="/order/quickUpdate/{{ $order['id'] }}?status=cancelled&tab={{ $currentStatus }}"
                                                        class="btn btn-outline-danger btn-sm btn-action-icon"
                                                        onclick="return confirm('Hủy đơn hàng này?')" title="Hủy đơn">
                                                        <i class="bi bi-x-lg"></i>
                                                    </a>
                                                @elseif($order['status'] == 'shipping')
                                                    @if ($order['payment_status'] == 'paid')
                                                        <a href="/order/quickUpdate/{{ $order['id'] }}?status=completed&tab={{ $currentStatus }}"
                                                            class="btn btn-success btn-sm btn-action-text shadow-sm"
                                                            onclick="return confirm('Xác nhận hoàn thành?')">
                                                            <i class="bi bi-check-circle"></i> Hoàn thành
                                                        </a>
                                                    @else
                                                        <button class="btn btn-secondary btn-sm btn-action-text" disabled
                                                            title="Gạt nút thanh toán để mở khóa">
                                                            Chờ TT
                                                        </button>
                                                    @endif
                                                    <a href="/order/quickUpdate/{{ $order['id'] }}?status=returns&tab={{ $currentStatus }}"
                                                        class="btn btn-outline-secondary btn-sm btn-action-icon"
                                                        title="Khách trả hàng">
                                                        <i class="bi bi-arrow-counterclockwise"></i>
                                                    </a>
                                                @elseif($order['status'] == 'completed')
                                                    <a href="/order/quickUpdate/{{ $order['id'] }}?status=returns&tab={{ $currentStatus }}"
                                                        class="btn btn-outline-secondary btn-sm btn-action-icon"
                                                        title="Nhận hàng hoàn"
                                                        onclick="return confirm('Nhận lại hàng hoàn cho đơn này?')">
                                                        <i class="bi bi-arrow-counterclockwise"></i>
                                                    </a>
                                                @elseif($order['status'] == 'returns')
                                                    <a href="/order/quickUpdate/{{ $order['id'] }}?status=returned&tab={{ $currentStatus }}"
                                                        class="btn btn-dark btn-sm btn-action-text shadow-sm">
                                                        <i class="bi bi-box-seam"></i> Đã nhận
                                                    </a>
                                                @endif

                                                <a href="/order/detail/{{ $order['id'] }}"
                                                    class="btn btn-sm btn-light border btn-action-icon text-primary"
                                                    title="Xem chi tiết">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <p class="text-muted m-0 fst-italic">Không có đơn hàng nào trong mục này.</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION -->
                @if ($totalPages > 1)
                    <nav aria-label="Page navigation" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <li class="page-item {{ $currentPage == 1 ? 'disabled' : '' }}">
                                <a class="page-link"
                                    href="/order?status={{ $currentStatus }}&page={{ $currentPage - 1 }}">Trước</a>
                            </li>

                            @for ($i = 1; $i <= $totalPages; $i++)
                                <li class="page-item {{ $currentPage == $i ? 'active' : '' }}">
                                    <a class="page-link"
                                        href="/order?status={{ $currentStatus }}&page={{ $i }}">{{ $i }}</a>
                                </li>
                            @endfor

                            <li class="page-item {{ $currentPage == $totalPages ? 'disabled' : '' }}">
                                <a class="page-link"
                                    href="/order?status={{ $currentStatus }}&page={{ $currentPage + 1 }}">Sau</a>
                            </li>
                        </ul>
                    </nav>
                @endif
            </div>
        </div>
    </div>

    <script>
        function togglePayment(el) {
            const id = el.getAttribute('data-id');
            const tab = el.getAttribute('data-tab');
            const status = el.checked ? 'paid' : 'unpaid';
            const label = document.getElementById('payLabel_' + id);

            if (el.checked) {
                label.innerText = 'Đã TT';
                label.classList.remove('text-secondary');
                label.classList.add('text-success');
            } else {
                label.innerText = 'Chưa TT';
                label.classList.remove('text-success');
                label.classList.add('text-secondary');
            }

            window.location.href = `/order/updatePayment/${id}?payment_status=${status}&tab=${tab}`;
        }
    </script>
@endsection
