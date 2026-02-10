@extends('layout.userLayout')

@section('content')
<style>
    :root { --primary-color: #009981; }
    .text-brand { color: var(--primary-color) !important; }
    .bg-brand { background-color: var(--primary-color) !important; }
    .btn-brand { background-color: var(--primary-color); color: white; border: 1px solid var(--primary-color); }
    .btn-brand:hover { background-color: #007a67; color: white; }
    .btn-outline-brand { color: var(--primary-color); border: 1px solid var(--primary-color); background: white; }
    .btn-outline-brand:hover { background-color: var(--primary-color); color: white; }

    .order-tabs .nav-link {
        color: #6c757d; font-weight: 600; border: none; border-bottom: 3px solid transparent;
        padding: 15px 20px; background: white;
    }
    .order-tabs .nav-link:hover { color: var(--primary-color); }
    .order-tabs .nav-link.active { color: var(--primary-color); border-bottom: 3px solid var(--primary-color); }
    
    .order-card { transition: all 0.2s; border: 1px solid #eee; }
    .order-card:hover { box-shadow: 0 5px 15px rgba(0,0,0,0.05); border-color: var(--primary-color); }
    .status-badge { font-size: 0.85rem; padding: 5px 12px; border-radius: 20px; }
    
    .product-thumb { width: 70px; height: 70px; object-fit: cover; border-radius: 6px; border: 1px solid #dee2e6; }
    
    .pagination .page-link { color: #333; border: none; margin: 0 2px; border-radius: 50%; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; }
    .pagination .page-link:hover { background-color: #f0f0f0; }
    .pagination .page-item.active .page-link { background-color: var(--primary-color); color: white; }
</style>

<div class="bg-light py-4" style="min-height: 80vh;">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-dark border-start border-4 border-success ps-3 m-0">Đơn hàng của tôi</h3>
            <div class="d-flex align-items-center gap-2">
                <label class="text-muted small fw-bold">Sắp xếp:</label>
                <select class="form-select form-select-sm" style="width: 150px;" onchange="window.location.href=this.value">
                    <option value="/userorder?status={{ $currentStatus }}&sort=newest" {{ $currentSort == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                    <option value="/userorder?status={{ $currentStatus }}&sort=oldest" {{ $currentSort == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                </select>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4 rounded-3 overflow-hidden">
            <ul class="nav nav-tabs order-tabs d-flex flex-nowrap overflow-auto" id="myTab" role="tablist">
                <li class="nav-item whitespace-nowrap">
                    <a class="nav-link {{ $currentStatus == 'all' ? 'active' : '' }}" href="/userorder?status=all">
                        Tất cả <span class="badge bg-secondary rounded-pill ms-1">{{ $counts['all'] }}</span>
                    </a>
                </li>
                <li class="nav-item whitespace-nowrap">
                    <a class="nav-link {{ $currentStatus == 'pending' ? 'active' : '' }}" href="/userorder?status=pending">
                        Chờ xác nhận <span class="badge {{ $counts['pending'] > 0 ? 'bg-danger' : 'bg-light text-muted border' }} rounded-pill ms-1">{{ $counts['pending'] }}</span>
                    </a>
                </li>
                <li class="nav-item whitespace-nowrap">
                    <a class="nav-link {{ $currentStatus == 'shipping' ? 'active' : '' }}" href="/userorder?status=shipping">
                        Đang vận chuyển <span class="badge {{ $counts['shipping'] > 0 ? 'bg-danger' : 'bg-light text-muted border' }} rounded-pill ms-1">{{ $counts['shipping'] }}</span>
                    </a>
                </li>
                <li class="nav-item whitespace-nowrap">
                    <a class="nav-link {{ $currentStatus == 'completed' ? 'active' : '' }}" href="/userorder?status=completed">
                        Hoàn thành <span class="badge {{ $counts['completed'] > 0 ? 'bg-success' : 'bg-light text-muted border' }} rounded-pill ms-1">{{ $counts['completed'] }}</span>
                    </a>
                </li>
                <li class="nav-item whitespace-nowrap">
                    <a class="nav-link {{ $currentStatus == 'cancelled' ? 'active' : '' }}" href="/userorder?status=cancelled">
                        Đã hủy <span class="badge {{ $counts['cancelled'] > 0 ? 'bg-secondary' : 'bg-light text-muted border' }} rounded-pill ms-1">{{ $counts['cancelled'] }}</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="row g-4">
            @if(empty($orders))
                <div class="col-12 text-center py-5">
                    <img src="https://cdni.iconscout.com/illustration/premium/thumb/empty-cart-2130356-1800917.png" width="150" class="mb-3 opacity-75">
                    <p class="text-muted fs-5">Chưa có đơn hàng nào trong mục này.</p>
                    <a href="/" class="btn btn-brand px-4 rounded-pill">Mua sắm ngay</a>
                </div>
            @else
                @foreach($orders as $order)
                <div class="col-12">
                    <div class="card order-card border-0 shadow-sm rounded-3">
                        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div class="d-flex align-items-center gap-3">
                                <span class="fw-bold text-dark fs-5">#{{ $order['id'] }}</span>
                                <span class="text-muted small border-start ps-3">{{ date('d/m/Y H:i', strtotime($order['created_at'])) }}</span>
                            </div>
                            <div>
                                @if($order['status'] == 'pending')
                                    <span class="badge bg-warning text-dark status-badge">Chờ xác nhận</span>
                                @elseif(in_array($order['status'], ['approved', 'processing']))
                                    <span class="badge bg-info text-white status-badge">Đang xử lý</span>
                                @elseif($order['status'] == 'shipping')
                                    <span class="badge bg-primary text-white status-badge">Đang giao hàng</span>
                                @elseif($order['status'] == 'completed')
                                    <span class="badge bg-success text-white status-badge">Giao thành công</span>
                                @elseif(in_array($order['status'], ['cancelled', 'returns', 'returned']))
                                    <span class="badge bg-secondary text-white status-badge">Đã hủy/Hoàn</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-start mb-2">
                                        <i class="bi bi-geo-alt text-brand me-2 mt-1"></i>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $order['recipient_name'] }} <span class="fw-normal text-muted">({{ $order['recipient_phone'] }})</span></div>
                                            <div class="text-muted small">{{ $order['recipient_address'] }}</div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-credit-card text-brand me-2"></i>
                                        <span class="text-muted small me-2">Thanh toán:</span>
                                        <span class="fw-bold text-uppercase small">{{ $order['payment_method'] }}</span>
                                        @if($order['payment_status'] == 'paid') 
                                            <span class="badge bg-light text-success border ms-2"><i class="bi bi-check-circle-fill me-1"></i>Đã thanh toán</span> 
                                        @else 
                                            <span class="badge bg-light text-secondary border ms-2">Chưa thanh toán</span> 
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4 text-md-end mt-3 mt-md-0 border-start ps-md-4">
                                    <div class="small text-muted mb-1">Tổng tiền thanh toán</div>
                                    <div class="fs-3 fw-bold text-danger">{{ number_format($order['total_amount'], 0, ',', '.') }}đ</div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer bg-white border-top py-3 d-flex justify-content-end gap-2">
                            @if($order['status'] == 'pending')
                                <a href="/userorder/cancelOrder/{{ $order['id'] }}" 
                                   class="btn btn-outline-danger btn-sm rounded-pill px-4"
                                   onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?');">
                                    Hủy đơn
                                </a>
                            @endif
                            <button class="btn btn-brand btn-sm rounded-pill px-4 fw-bold" onclick="viewOrderDetail({{ $order['id'] }})">
                                Xem chi tiết
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach

                @if($totalPages > 1)
                <div class="col-12 mt-4">
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            <li class="page-item {{ $currentPage == 1 ? 'disabled' : '' }}">
                                <a class="page-link" href="/userorder?status={{ $currentStatus }}&sort={{ $currentSort }}&page={{ $currentPage - 1 }}"><i class="bi bi-chevron-left"></i></a>
                            </li>
                            
                            @for($i = 1; $i <= $totalPages; $i++)
                                <li class="page-item {{ $currentPage == $i ? 'active' : '' }}">
                                    <a class="page-link" href="/userorder?status={{ $currentStatus }}&sort={{ $currentSort }}&page={{ $i }}">{{ $i }}</a>
                                </li>
                            @endfor

                            <li class="page-item {{ $currentPage == $totalPages ? 'disabled' : '' }}">
                                <a class="page-link" href="/userorder?status={{ $currentStatus }}&sort={{ $currentSort }}&page={{ $currentPage + 1 }}"><i class="bi bi-chevron-right"></i></a>
                            </li>
                        </ul>
                    </nav>
                </div>
                @endif
            @endif
        </div>
    </div>
</div>

<div class="modal fade" id="orderDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom pb-3">
                <div>
                    <h5 class="modal-title fw-bold text-brand mb-1">Chi tiết đơn hàng <span id="modalOrderId"></span></h5>
                    <span class="text-muted small" id="modalOrderDate"></span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div id="modalLoading" class="text-center py-5">
                    <div class="spinner-border text-brand" role="status"></div>
                    <p class="mt-2 text-muted small">Đang tải thông tin...</p>
                </div>
                
                <div id="modalContent" style="display: none;">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="bg-light text-secondary small text-uppercase">
                                <tr>
                                    <th class="ps-4 py-3">Sản phẩm</th>
                                    <th class="text-center py-3">Đơn giá</th>
                                    <th class="text-center py-3">Số lượng</th>
                                    <th class="text-end pe-4 py-3">Tạm tính</th>
                                </tr>
                            </thead>
                            <tbody id="orderItemsList">
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="bg-light p-4">
                        <div class="row justify-content-end">
                            <div class="col-md-5">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Tổng tiền hàng</span>
                                    <span class="fw-bold" id="modalSubtotal"></span>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="text-muted">Phí vận chuyển</span>
                                    <span class="text-success fw-bold">Miễn phí</span>
                                </div>
                                <div class="border-top pt-3 d-flex justify-content-between align-items-center">
                                    <span class="fw-bold fs-5 text-dark">Thành tiền</span>
                                    <span class="fw-bold fs-4 text-brand" id="modalTotal"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-2 pb-3 px-4">
                <button type="button" class="btn btn-secondary btn-sm px-4 rounded-pill fw-bold" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function viewOrderDetail(orderId) {
        const modalEl = document.getElementById('orderDetailModal');
        const modal = new bootstrap.Modal(modalEl);
        
        document.getElementById('modalOrderId').innerText = '#' + orderId;
        document.getElementById('modalLoading').style.display = 'block';
        document.getElementById('modalContent').style.display = 'none';
        
        modal.show();

        fetch(`/userorder/getOrderDetail?id=${orderId}`)
            .then(response => response.json())
            .then(res => {
                if(res.success) {
                    const items = res.data;
                    let html = '';
                    let subtotal = 0;
                    
                    items.forEach(item => {
                        const img = item.variant_image ? `/${item.variant_image}` : 'https://placehold.co/70x70';
                        const price = parseFloat(item.price);
                        const qty = parseInt(item.quantity);
                        const itemTotal = price * qty;
                        subtotal += itemTotal;

                        html += `
                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <img src="${img}" class="product-thumb me-3 shadow-sm">
                                        <div>
                                            <div class="fw-bold text-dark mb-1">${item.product_name}</div>
                                            <div class="small text-muted bg-light border px-2 py-1 rounded d-inline-block">Biến thể: #${item.product_variant_id}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center text-muted">${new Intl.NumberFormat('vi-VN').format(price)}đ</td>
                                <td class="text-center fw-bold">x${qty}</td>
                                <td class="text-end pe-4 fw-bold text-dark">${new Intl.NumberFormat('vi-VN').format(itemTotal)}đ</td>
                            </tr>
                        `;
                    });

                    document.getElementById('orderItemsList').innerHTML = html;
                    document.getElementById('modalSubtotal').innerText = new Intl.NumberFormat('vi-VN').format(subtotal) + 'đ';
                    document.getElementById('modalTotal').innerText = new Intl.NumberFormat('vi-VN').format(subtotal) + 'đ';
                    
                    document.getElementById('modalLoading').style.display = 'none';
                    document.getElementById('modalContent').style.display = 'block';
                } else {
                    alert('Không thể tải chi tiết đơn hàng.');
                    modal.hide();
                }
            })
            .catch(err => {
                console.error(err);
                alert('Lỗi kết nối.');
                modal.hide();
            });
    }
</script>
@endsection