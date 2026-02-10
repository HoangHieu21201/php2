@extends('layout.adminLayout')

@section('content')
    <style>
        :root {
            --brand-color: #009981;
        }

        .text-brand {
            color: var(--brand-color) !important;
        }

        .bg-brand {
            background-color: var(--brand-color) !important;
        }

        .btn-brand {
            background-color: var(--brand-color);
            border-color: var(--brand-color);
            color: white;
        }

        .btn-brand:hover {
            background-color: #007a67;
            border-color: #007a67;
            color: white;
        }

        .step-wizard {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            position: relative;
            z-index: 1;
        }

        .step-wizard-item {
            position: relative;
            text-align: center;
            flex: 1;
        }

        .progress-count {
            height: 40px;
            width: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-weight: 600;
            margin: 0 auto;
            position: relative;
            z-index: 10;
            background-color: #fff;
            border: 3px solid #e3e6f0;
            color: #e3e6f0;
            transition: all 0.3s;
        }

        .step-wizard-item.active .progress-count {
            border-color: var(--brand-color);
            background-color: var(--brand-color);
            color: #fff;
            box-shadow: 0 0 0 3px rgba(0, 153, 129, 0.2);
        }

        .step-wizard-item::after {
            content: "";
            position: absolute;
            width: 100%;
            height: 3px;
            background-color: #e3e6f0;
            top: 20px;
            left: -50%;
            z-index: 5;
        }

        .step-wizard-item:first-child::after {
            content: none;
        }

        .step-wizard-item.active::after {
            background-color: var(--brand-color);
        }

        .step-label {
            font-size: 13px;
            margin-top: 10px;
            font-weight: 600;
            color: #858796;
        }

        .step-wizard-item.active .step-label {
            color: var(--brand-color);
        }

        .product-img-thumb {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #dee2e6;
        }

        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #f0f0f0;
        }
    </style>

    @php
        $statusFlow = ['pending', 'approved', 'shipping', 'completed'];
        $currentStatusIndex = array_search($order['status'], $statusFlow);

        if ($order['status'] == 'cancelled' || $order['status'] == 'returns' || $order['status'] == 'returned') {
            $currentStatusIndex = -1;
        }
    @endphp

    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                Chi tiết đơn hàng <span class="text-brand font-weight-bold">#{{ $order['id'] }}</span>
            </h1>
            <div>
                <a href="/order/print/{{ $order['id'] }}" target="_blank" class="btn btn-sm btn-dark shadow-sm mr-2">
                    <i class="fas fa-print fa-sm text-white-50 mr-1"></i> In Hóa đơn
                </a>
                <a href="/order" class="btn btn-sm btn-secondary shadow-sm">
                    <i class="fas fa-arrow-left fa-sm text-white-50 mr-1"></i> Quay lại
                </a>
            </div>
        </div>

        @if (isset($_SESSION['success']))
            <div class="alert alert-success alert-dismissible fade show shadow-sm">
                <i class="fas fa-check-circle mr-2"></i> {{ $_SESSION['success'] }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <?php unset($_SESSION['success']); ?>
            </div>
        @endif

        @if (isset($_SESSION['error']))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm">
                <i class="fas fa-exclamation-triangle mr-2"></i> {{ $_SESSION['error'] }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <?php unset($_SESSION['error']); ?>
            </div>
        @endif

        @if ($currentStatusIndex >= 0)
            <div class="card shadow mb-4">
                <div class="card-body pt-4 pb-4">
                    <ul class="step-wizard list-unstyled mb-0">
                        <li class="step-wizard-item {{ $currentStatusIndex >= 0 ? 'active' : '' }}">
                            <div class="progress-count">1</div>
                            <div class="step-label">Chờ xử lý</div>
                        </li>
                        <li class="step-wizard-item {{ $currentStatusIndex >= 1 ? 'active' : '' }}">
                            <div class="progress-count">2</div>
                            <div class="step-label">Đã duyệt</div>
                        </li>
                        <li class="step-wizard-item {{ $currentStatusIndex >= 2 ? 'active' : '' }}">
                            <div class="progress-count">3</div>
                            <div class="step-label">Đang giao</div>
                        </li>
                        <li class="step-wizard-item {{ $currentStatusIndex >= 3 ? 'active' : '' }}">
                            <div class="progress-count">4</div>
                            <div class="step-label">Hoàn thành</div>
                        </li>
                    </ul>
                </div>
            </div>
        @elseif($order['status'] == 'cancelled')
            <div class="alert alert-danger text-center font-weight-bold shadow-sm mb-4 py-3">
                <i class="fas fa-times-circle mr-2"></i> ĐƠN HÀNG ĐÃ BỊ HỦY
            </div>
        @elseif($order['status'] == 'returns' || $order['status'] == 'returned')
            <div class="alert alert-secondary text-center font-weight-bold shadow-sm mb-4 py-3">
                <i class="fas fa-undo mr-2"></i> ĐƠN HÀNG TRONG QUÁ TRÌNH HOÀN TRẢ
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-brand">Danh sách sản phẩm</h6>
                        <span class="badge badge-secondary badge-pill">{{ count($details) }} sản phẩm</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-secondary">
                                    <tr>
                                        <th class="pl-4 border-top-0">Sản phẩm</th>
                                        <th class="text-center border-top-0">SL</th>
                                        <th class="text-right border-top-0">Đơn giá</th>
                                        <th class="text-right pr-4 border-top-0">Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($details as $item)
                                        <tr>
                                            <td class="pl-4">
                                                <div class="d-flex align-items-center">
                                                    @if (!empty($item['variant_image']))
                                                        <img src="/{{ $item['variant_image'] }}"
                                                            class="product-img-thumb mr-3">
                                                    @else
                                                        <img src="https://placehold.co/50x50?text=NoImg"
                                                            class="product-img-thumb mr-3">
                                                    @endif
                                                    <div>
                                                        <div class="font-weight-bold text-dark">{{ $item['product_name'] }}
                                                        </div>
                                                        <small class="text-muted">Biến thể ID:
                                                            #{{ $item['product_variant_id'] }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">{{ $item['quantity'] }}</td>
                                            <td class="text-right">{{ number_format($item['price'], 0, ',', '.') }}đ</td>
                                            <td class="text-right pr-4 font-weight-bold text-dark">
                                                {{ number_format($item['total_price'], 0, ',', '.') }}đ</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-light">
                                    <tr>
                                        <td colspan="3" class="text-right font-weight-bold border-top">Tạm tính:</td>
                                        <td class="text-right pr-4 border-top">
                                            {{ number_format($order['subtotal'], 0, ',', '.') }}đ</td>
                                    </tr>
                                    @if ($order['discount_amount'] > 0)
                                        <tr>
                                            <td colspan="3"
                                                class="text-right font-weight-bold text-success border-top-0">Giảm giá:</td>
                                            <td class="text-right pr-4 text-success border-top-0">
                                                -{{ number_format($order['discount_amount'], 0, ',', '.') }}đ</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td colspan="3" class="text-right font-weight-bold h5 text-brand pt-3">Tổng cộng:
                                        </td>
                                        <td class="text-right pr-4 font-weight-bold h5 text-danger pt-3">
                                            {{ number_format($order['total_amount'], 0, ',', '.') }}đ</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-brand text-white">
                        <h6 class="m-0 font-weight-bold"><i class="fas fa-cog mr-2"></i>Xử lý đơn hàng</h6>
                    </div>
                    <div class="card-body">
                        <form action="/order/updateStatus/{{ $order['id'] }}" method="POST">

                            <div class="form-group mb-3">
                                <label class="small font-weight-bold text-secondary text-uppercase mb-1">Thanh toán</label>
                                <select class="form-control" name="payment_status" id="paymentStatusSelect">
                                    <option value="unpaid" {{ $order['payment_status'] == 'unpaid' ? 'selected' : '' }}>
                                        Chưa thanh toán</option>
                                    <option value="paid" {{ $order['payment_status'] == 'paid' ? 'selected' : '' }}>Đã
                                        thanh toán</option>
                                </select>
                            </div>

                            <div class="form-group mb-4">
                                <label class="small font-weight-bold text-secondary text-uppercase mb-1">Trạng thái vận
                                    đơn</label>
                                <select class="form-control" name="status" id="orderStatusSelect">
                                    <option value="{{ $order['status'] }}" selected disabled>
                                        -> Hiện tại:
                                        @if ($order['status'] == 'pending')
                                            Chờ xử lý
                                        @elseif($order['status'] == 'approved')
                                            Đã duyệt
                                        @elseif($order['status'] == 'shipping')
                                            Đang giao hàng
                                        @elseif($order['status'] == 'completed')
                                            Hoàn thành
                                        @elseif($order['status'] == 'cancelled')
                                            Đã hủy
                                        @elseif($order['status'] == 'returns')
                                            Hoàn hàng
                                        @elseif($order['status'] == 'returned')
                                            Đã nhận hoàn
                                        @endif
                                    </option>

                                    @if ($order['status'] == 'pending')
                                        <option value="approved">✅ Duyệt đơn hàng</option>
                                        <option value="cancelled">❌ Hủy đơn hàng</option>
                                    @elseif($order['status'] == 'approved')
                                        <option value="shipping">🚚 Giao cho vận chuyển</option>
                                        <option value="cancelled">❌ Hủy đơn hàng</option>
                                    @elseif($order['status'] == 'shipping')
                                        <option value="completed" id="optCompleted">🎉 Giao thành công (Hoàn thành)
                                        </option>
                                        <option value="returns">↩️ Khách trả hàng (Hoàn hàng)</option>
                                    @elseif($order['status'] == 'returns')
                                        <option value="returned">📦 Đã nhận lại hàng hoàn</option>
                                    @endif
                                </select>
                                <small class="form-text text-danger d-none" id="paymentWarning">
                                    * Bạn phải chọn "Đã thanh toán" mới được phép Hoàn thành đơn.
                                </small>
                            </div>

                            @if (!in_array($order['status'], ['completed', 'cancelled', 'returned']))
                                <button type="submit" class="btn btn-brand btn-block font-weight-bold shadow-sm"
                                    id="btnUpdateStatus">
                                    <i class="fas fa-save mr-2"></i> Cập nhật thay đổi
                                </button>
                            @else
                                <button type="button" class="btn btn-secondary btn-block" disabled>
                                    Không thể thay đổi
                                </button>
                            @endif
                        </form>
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-brand">Thông tin giao hàng</h6>
                    </div>
                    <div class="card-body">
                        <div class="media mb-3 pb-3 border-bottom">
                            <div class="rounded-circle bg-light p-3 mr-3 text-primary d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px;">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="media-body">
                                <div class="small text-muted text-uppercase font-weight-bold mb-1">Người nhận</div>
                                <div class="font-weight-bold text-dark">{{ $order['recipient_name'] }}</div>
                            </div>
                        </div>
                        <div class="media mb-3 pb-3 border-bottom">
                            <div class="rounded-circle bg-light p-3 mr-3 text-success d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px;">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="media-body">
                                <div class="small text-muted text-uppercase font-weight-bold mb-1">Điện thoại</div>
                                <a href="tel:{{ $order['recipient_phone'] }}"
                                    class="text-dark font-weight-bold text-decoration-none">
                                    {{ $order['recipient_phone'] }}
                                </a>
                            </div>
                        </div>
                        <div class="media">
                            <div class="rounded-circle bg-light p-3 mr-3 text-warning d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px;">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="media-body">
                                <div class="small text-muted text-uppercase font-weight-bold mb-1">Địa chỉ</div>
                                <div class="text-dark small" style="line-height: 1.5;">{{ $order['recipient_address'] }}
                                </div>
                            </div>
                        </div>

                        @if (!empty($order['note']))
                            <div class="alert alert-warning mt-3 mb-0 small border-left-warning">
                                <i class="fas fa-sticky-note mr-1"></i> <strong>Ghi chú:</strong><br>
                                {{ $order['note'] }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const paymentSelect = document.getElementById('paymentStatusSelect');
            const statusSelect = document.getElementById('orderStatusSelect');
            const warningText = document.getElementById('paymentWarning');
            const optCompleted = document.getElementById('optCompleted');

            function checkLogic() {
                if (optCompleted) {
                    if (paymentSelect.value === 'unpaid') {
                        optCompleted.disabled = true;
                        if (statusSelect.value === 'completed') {
                            statusSelect.value = statusSelect.options[0].value;
                        }
                        warningText.classList.remove('d-none');
                    } else {
                        optCompleted.disabled = false;
                        warningText.classList.add('d-none');
                    }
                }
            }

            paymentSelect.addEventListener('change', checkLogic);
            statusSelect.addEventListener('change', checkLogic);

            checkLogic();
        });
    </script>
@endsection
