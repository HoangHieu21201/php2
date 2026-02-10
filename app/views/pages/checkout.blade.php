@extends('layout.userLayout')

@section('content')
<style>
    :root {
        --primary-color: #009981;
    }
    .text-brand { color: var(--primary-color) !important; }
    .btn-brand { 
        background-color: var(--primary-color); 
        border-color: var(--primary-color); 
        color: white; 
        transition: all 0.3s;
    }
    .btn-brand:hover { 
        background-color: #007a67; 
        border-color: #007a67;
        color: white; 
    }
    
    .address-selection-card {
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 15px;
        cursor: pointer;
        transition: all 0.2s;
        background: #fff;
        height: 100%;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    .address-selection-card:hover {
        border-color: var(--primary-color);
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    
    .form-check-input:checked ~ .address-selection-card {
        border-color: var(--primary-color);
        background-color: #f8fffe;
        border-width: 2px;
    }
    
    .checkout-item-img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #dee2e6;
    }

    /* Fix lỗi badge bị che mất */
    .checkout-items {
        padding-top: 15px;
        padding-right: 5px;
    }
</style>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="fw-bold text-dark border-start border-4 border-success ps-3">Thanh toán</h3>
        </div>
    </div>

    <div class="row g-4">
        <!-- Cột trái: Thông tin -->
        <div class="col-lg-7 col-xl-8">
            <form action="/checkout/process" method="POST" id="checkoutForm">
                
                <!-- 1. Địa chỉ -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-geo-alt me-2 text-brand"></i>Thông tin nhận hàng</h5>
                    </div>
                    <div class="card-body p-4">
                        @if (isset($_SESSION['error']))
                            <div class="alert alert-danger alert-dismissible fade show mb-4">
                                {{ $_SESSION['error'] }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                <?php unset($_SESSION['error']); ?>
                            </div>
                        @endif
                        @if (isset($_SESSION['success']))
                            <div class="alert alert-success alert-dismissible fade show mb-4">
                                {{ $_SESSION['success'] }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                <?php unset($_SESSION['success']); ?>
                            </div>
                        @endif

                        <div class="row g-3">
                            <!-- Danh sách địa chỉ có sẵn -->
                            @if(!empty($addresses))
                                @foreach($addresses as $addr)
                                <div class="col-md-6">
                                    <div class="position-relative h-100">
                                        <input class="form-check-input position-absolute top-50 start-50 translate-middle d-none address-radio" 
                                               type="radio" name="address_id" id="addr_{{ $addr['id'] }}" 
                                               value="{{ $addr['id'] }}" 
                                               {{ $addr['is_default'] ? 'checked' : '' }}>
                                        
                                        <label class="address-selection-card d-block" for="addr_{{ $addr['id'] }}">
                                            <div class="d-flex align-items-center mb-2">
                                                <strong class="text-dark me-2">{{ $addr['recipient_name'] }}</strong>
                                                @if($addr['is_default'])
                                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2">Mặc định</span>
                                                @endif
                                            </div>
                                            <div class="mb-1 text-muted small">
                                                <i class="bi bi-telephone me-1"></i> {{ $addr['recipient_phone'] }}
                                            </div>
                                            <div class="text-dark small">
                                                {{ $addr['address'] }}
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            @endif

                            <!-- Tùy chọn nhập địa chỉ mới -->
                            <div class="col-md-6">
                                <div class="position-relative h-100">
                                    <input class="form-check-input position-absolute top-50 start-50 translate-middle d-none address-radio" 
                                           type="radio" name="address_id" id="addr_new" 
                                           value="new"
                                           {{ empty($addresses) ? 'checked' : '' }}>
                                    
                                    <label class="address-selection-card text-center" for="addr_new">
                                        <div class="text-muted">
                                            <i class="bi bi-plus-circle fs-3 mb-2 d-block"></i>
                                            <span class="fw-bold">Giao đến địa chỉ khác</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Form nhập địa chỉ thủ công (API Provinces) -->
                        <div id="newAddressForm" class="mt-4 p-4 bg-light rounded-3 border" style="{{ empty($addresses) ? '' : 'display: none;' }}">
                            <h6 class="fw-bold mb-3 text-brand border-bottom pb-2">Thông tin người nhận mới</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Họ và tên <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="new_recipient_name" placeholder="Ví dụ: Nguyễn Văn A">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Số điện thoại <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="new_recipient_phone" placeholder="Ví dụ: 0912345678">
                                </div>
                                
                                <div class="col-12"><hr class="my-1 opacity-25"></div>

                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Tỉnh / Thành phố <span class="text-danger">*</span></label>
                                    <select class="form-select" id="province">
                                        <option value="" selected>Chọn Tỉnh/Thành</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Quận / Huyện <span class="text-danger">*</span></label>
                                    <select class="form-select" id="district" disabled>
                                        <option value="" selected>Chọn Quận/Huyện</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Phường / Xã <span class="text-danger">*</span></label>
                                    <select class="form-select" id="ward" disabled>
                                        <option value="" selected>Chọn Phường/Xã</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold">Địa chỉ chi tiết (Số nhà, Tên đường) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="specific_address" placeholder="Ví dụ: 123 Đường ABC">
                                    
                                    <!-- Input ẩn lưu địa chỉ full để gửi về server -->
                                    <input type="hidden" name="new_address_detail_full" id="full_address">
                                </div>
                            </div>
                        </div>

                        @if(!empty($addresses))
                        <div class="mt-3 text-end">
                            <a href="/profile" class="text-decoration-none small text-brand"><i class="bi bi-pencil-square me-1"></i> Quản lý sổ địa chỉ</a>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- 2. Phương thức thanh toán -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-credit-card me-2 text-brand"></i>Thanh toán</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="border rounded-3 p-3 d-flex align-items-center bg-light">
                            <input class="form-check-input me-3" type="radio" checked disabled>
                            <div>
                                <strong class="d-block text-dark">Thanh toán khi nhận hàng (COD)</strong>
                                <small class="text-muted">Bạn sẽ thanh toán tiền mặt cho shipper khi nhận được hàng.</small>
                            </div>
                            <i class="bi bi-cash-stack ms-auto fs-3 text-brand"></i>
                        </div>
                    </div>
                </div>

                <!-- 3. Ghi chú -->
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-chat-text me-2 text-brand"></i>Ghi chú</h5>
                    </div>
                    <div class="card-body p-4">
                        <textarea class="form-control" name="note" rows="2" placeholder="Ghi chú cho người bán (Ví dụ: Giao giờ hành chính)"></textarea>
                    </div>
                </div>
            </form>
        </div>

        <!-- Cột phải: Đơn hàng -->
        <div class="col-lg-5 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 position-sticky" style="top: 20px;">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0 text-dark">Đơn hàng</h5>
                </div>
                <div class="card-body p-4">
                    <div class="checkout-items mb-4 custom-scrollbar" style="max-height: 350px; overflow-y: auto;">
                        @foreach($cartItems as $item)
                        <div class="d-flex align-items-center mb-3">
                            <div class="position-relative">
                                <img src="/{{ $item['image'] ?? $item['product_image'] ?? '' }}" class="checkout-item-img" onerror="this.src='https://placehold.co/60x60'">
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary border border-light">
                                    {{ $item['quantity'] }}
                                </span>
                            </div>
                            <div class="ms-3 flex-grow-1 overflow-hidden">
                                <h6 class="mb-0 text-truncate small fw-bold" title="{{ $item['product_name'] }}">{{ $item['product_name'] }}</h6>
                                <div class="text-muted small text-truncate">{{ $item['attribute_values'] }}</div>
                            </div>
                            <div class="ms-2 text-end">
                                <span class="fw-bold text-brand small">{{ number_format($item['final_price'] ?? $item['variant_price'] ?? 0, 0, ',', '.') }}đ</span>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- FORM COUPON -->
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted text-uppercase">Mã giảm giá</label>
                        <form action="/checkout/applyCoupon" method="POST">
                            
                            @if(!empty($coupons))
                            <div class="mb-2">
                                <select class="form-select form-select-sm" onchange="document.getElementById('couponCodeInput').value = this.value">
                                    <option value="">-- Chọn mã ưu đãi --</option>
                                    @foreach($coupons as $c)
                                        <option value="{{ $c['code'] }}">
                                            {{ $c['code'] }} - Giảm {{ $c['type'] == 'percent' ? $c['value'].'%' : number_format($c['value']).'đ' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            <div class="input-group">
                                <input type="text" class="form-control" name="coupon_code" id="couponCodeInput" 
                                       placeholder="Nhập mã coupon" 
                                       value="{{ isset($_SESSION['coupon']) ? $_SESSION['coupon']['code'] : '' }}">
                                <button class="btn btn-outline-primary" type="submit">Áp dụng</button>
                            </div>
                            @if(isset($_SESSION['coupon']))
                                <div class="mt-1 small text-success">
                                    <i class="bi bi-check-circle"></i> Đang dùng mã: <strong>{{ $_SESSION['coupon']['code'] }}</strong>
                                </div>
                            @endif
                        </form>
                    </div>

                    <hr class="border-secondary border-opacity-10">

                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Tạm tính</span>
                        <span class="fw-bold">{{ number_format($subtotal, 0, ',', '.') }}đ</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Giảm giá</span>
                        <span class="text-success">{{ $discount > 0 ? '-' : '' }}{{ number_format($discount, 0, ',', '.') }}đ</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 small">
                        <span class="text-muted">Phí vận chuyển</span>
                        <span class="text-success fw-bold">Miễn phí</span>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center border-top pt-3 mb-4">
                        <span class="fw-bold fs-5 text-dark">Tổng cộng</span>
                        <span class="fw-bold fs-4 text-brand">{{ number_format($total, 0, ',', '.') }}đ</span>
                    </div>

                    <button type="submit" form="checkoutForm" class="btn btn-brand w-100 py-3 rounded-pill fw-bold text-uppercase shadow-sm">
                        Đặt hàng ngay
                    </button>
                    
                    <div class="text-center mt-3">
                        <a href="/cart" class="text-decoration-none small text-muted">
                            <i class="bi bi-arrow-left me-1"></i>Quay lại giỏ hàng
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // --- LOGIC ẨN HIỆN FORM ĐỊA CHỈ ---
        const addressRadios = document.querySelectorAll('.address-radio');
        const newAddressForm = document.getElementById('newAddressForm');

        addressRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'new') {
                    newAddressForm.style.display = 'block';
                    newAddressForm.scrollIntoView({ behavior: 'smooth', block: 'center' });
                } else {
                    newAddressForm.style.display = 'none';
                }
            });
        });

        // --- LOGIC API TỈNH THÀNH (GIỐNG PROFILE) ---
        const provinceSelect = document.getElementById('province');
        const districtSelect = document.getElementById('district');
        const wardSelect = document.getElementById('ward');
        const specificAddressInput = document.getElementById('specific_address');
        const fullAddressInput = document.getElementById('full_address');
        
        const API_URL = 'https://provinces.open-api.vn/api';

        // Load Tỉnh
        axios.get(`${API_URL}/p/`)
            .then(response => {
                response.data.forEach(item => {
                    let option = new Option(item.name, item.code);
                    option.setAttribute('data-name', item.name);
                    provinceSelect.add(option);
                });
            })
            .catch(error => console.error('Lỗi load tỉnh thành:', error));

        // Chọn Tỉnh -> Load Huyện
        provinceSelect.addEventListener('change', function() {
            districtSelect.length = 1; 
            wardSelect.length = 1;
            wardSelect.disabled = true;
            
            if (this.value) {
                districtSelect.disabled = false;
                axios.get(`${API_URL}/p/${this.value}?depth=2`)
                    .then(response => {
                        response.data.districts.forEach(item => {
                            let option = new Option(item.name, item.code);
                            option.setAttribute('data-name', item.name);
                            districtSelect.add(option);
                        });
                    });
            } else {
                districtSelect.disabled = true;
            }
            updateFullAddress();
        });

        // Chọn Huyện -> Load Xã
        districtSelect.addEventListener('change', function() {
            wardSelect.length = 1; 
            
            if (this.value) {
                wardSelect.disabled = false;
                axios.get(`${API_URL}/d/${this.value}?depth=2`)
                    .then(response => {
                        response.data.wards.forEach(item => {
                            let option = new Option(item.name, item.code);
                            option.setAttribute('data-name', item.name);
                            wardSelect.add(option);
                        });
                    });
            } else {
                wardSelect.disabled = true;
            }
            updateFullAddress();
        });

        wardSelect.addEventListener('change', updateFullAddress);
        specificAddressInput.addEventListener('input', updateFullAddress);

        function updateFullAddress() {
            const provinceText = provinceSelect.options[provinceSelect.selectedIndex]?.getAttribute('data-name') || '';
            const districtText = districtSelect.options[districtSelect.selectedIndex]?.getAttribute('data-name') || '';
            const wardText = wardSelect.options[wardSelect.selectedIndex]?.getAttribute('data-name') || '';
            const specificText = specificAddressInput.value.trim();

            let addressParts = [];
            if (specificText) addressParts.push(specificText);
            if (wardText) addressParts.push(wardText);
            if (districtText) addressParts.push(districtText);
            if (provinceText) addressParts.push(provinceText);

            fullAddressInput.value = addressParts.join(', ');
        }
    });
</script>
@endsection