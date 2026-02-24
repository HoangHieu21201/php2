@extends('layout.userLayout')

@section('content')

    <div class="container py-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-muted">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="/userproductdetail" class="text-decoration-none text-muted">Sản phẩm</a></li>
                <li class="breadcrumb-item active text-dark fw-bold" aria-current="page">{{ $product['name'] }}</li>
            </ol>
        </nav>

        <div class="row g-5">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm mb-3">
                    <img id="mainImage" src="{{ !empty($product['image']) ? '/' . $product['image'] : 'https://placehold.co/600x600?text=No+Img' }}" 
                         class="card-img-top object-fit-cover" height="500" alt="Product Image">
                </div>
                <div class="d-flex gap-2 overflow-auto">
                    <img src="{{ !empty($product['image']) ? '/' . $product['image'] : 'https://placehold.co/100x100' }}" 
                         class="border rounded cursor-pointer thumbnail-img active" width="80" height="80" 
                         onclick="changeImage(this.src)">
                    
                    @if(!empty($variants))
                        @foreach($variants as $v)
                            @if(!empty($v['image']))
                            <img src="/{{ $v['image'] }}" 
                                 class="border rounded cursor-pointer thumbnail-img" width="80" height="80" 
                                 onclick="changeImage(this.src)"
                                 data-variant-id="{{ $v['id'] }}">
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="col-lg-6">
                <h1 class="fw-bold mb-3">{{ $product['name'] }}</h1>
                
                <div class="mb-3">
                    <span class="badge bg-brand text-white me-2">Còn hàng</span>
                    <span class="text-muted small">Mã SP: #{{ $product['id'] }}</span>
                </div>

                <div class="mb-4">
                    @php 
                        // Sử dụng min_price/max_price đã được tính toán ở Controller
                        $minPrice = $product['min_price'] ?? 0;
                        $maxPrice = $product['max_price'] ?? 0;
                        
                        $displayPrice = 'Liên hệ';
                        
                        if ($minPrice > 0) {
                            if ($minPrice != $maxPrice) {
                                $displayPrice = number_format($minPrice, 0, ',','.') . ' - ' . number_format($maxPrice, 0, ',','.') . 'đ';
                            } else {
                                $displayPrice = number_format($minPrice, 0, ',','.') . 'đ';
                            }
                        }
                    @endphp
                    <h2 class="text-brand fw-bold" id="productPrice">
                        {{ $displayPrice }}
                    </h2>
                </div>

                <p class="text-secondary mb-4">
                    {!! nl2br(htmlspecialchars($product['short_description'] ?? '')) !!}
                </p>

                <form action="/usercart/add" method="POST" id="addToCartForm">
                    <input type="hidden" name="product_id" value="{{ $product['id'] }}">
                    <input type="hidden" name="variant_id" id="selectedVariantId" value="">

                    <div class="mb-4">
                        <label class="fw-bold mb-2">Chọn phân loại:</label>
                        <select class="form-select" id="variantSelect" onchange="updateVariantInfo()">
                            <option value="" data-price="" data-image="">-- Chọn Màu / Size --</option>
                            @if(!empty($variants))
                                @foreach($variants as $v)
                                    <option value="{{ $v['id'] }}" 
                                            data-price="{{ $v['sale_price'] > 0 ? $v['sale_price'] : $v['price'] }}"
                                            data-stock="{{ $v['quantity'] }}"
                                            data-image="{{ !empty($v['image']) ? '/' . $v['image'] : '' }}">
                                            {{ $v['attributes_string'] ?? (($v['color_name'] ?? '') . ' - ' . ($v['size_name'] ?? '')) }} 
                                            ({{ number_format($v['sale_price'] > 0 ? $v['sale_price'] : $v['price'], 0, ',','.') }}đ)
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        <div id="stockAlert" class="form-text text-danger mt-1" style="display:none;">Hết hàng</div>
                    </div>

                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="input-group" style="width: 140px;">
                            <button class="btn btn-outline-secondary" type="button" onclick="adjustQty(-1)">-</button>
                            <input type="number" name="quantity" id="qtyInput" class="form-control text-center" value="1" min="1">
                            <button class="btn btn-outline-secondary" type="button" onclick="adjustQty(1)">+</button>
                        </div>
                        <button type="submit" id="btnAddToCart" class="btn btn-brand px-5 py-2 fw-bold flex-grow-1" disabled>
                            <i class="bi bi-cart-plus me-2"></i> Thêm vào giỏ
                        </button>
                    </div>
                </form>

                <hr class="text-muted my-4">

                <div class="small text-muted">
                    <div class="mb-2"><i class="bi bi-truck me-2"></i> Miễn phí vận chuyển toàn quốc</div>
                    <div class="mb-2"><i class="bi bi-arrow-repeat me-2"></i> Đổi trả miễn phí trong 7 ngày</div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active text-brand fw-bold" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc-pane">Mô tả chi tiết</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link text-secondary" id="review-tab" data-bs-toggle="tab" data-bs-target="#review-pane">Đánh giá</button>
                    </li>
                </ul>
                <div class="tab-content border border-top-0 p-4 bg-white shadow-sm" id="myTabContent">
                    <div class="tab-pane fade show active" id="desc-pane">
                        {!! nl2br(htmlspecialchars($product['description'] ?? 'Đang cập nhật nội dung...')) !!}
                    </div>
                    <div class="tab-pane fade" id="review-pane">
                        <p class="text-muted">Chưa có đánh giá nào cho sản phẩm này.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- SẢN PHẨM LIÊN QUAN -->
        @if(!empty($relatedProducts) && count($relatedProducts) > 0)
        <div class="mt-5">
            <h3 class="fw-bold mb-4 border-start border-4 border-success ps-3">Sản phẩm liên quan</h3>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
                @foreach($relatedProducts as $rp)
                    @php
                        // Xử lý hiển thị giá cho sản phẩm liên quan
                        $rMin = $rp['min_price'] ?? $rp['price'] ?? 0;
                        $rMax = $rp['max_price'] ?? $rp['price'] ?? 0;
                        $rDisplayPrice = 'Liên hệ';
                        if($rMin > 0) {
                            if($rMin < $rMax) {
                                $rDisplayPrice = number_format($rMin, 0, ',', '.') . 'đ - ' . number_format($rMax, 0, ',', '.') . 'đ';
                            } else {
                                $rDisplayPrice = number_format($rMin, 0, ',', '.') . 'đ';
                            }
                        }
                    @endphp
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm product-card">
                            <div class="position-relative overflow-hidden">
                                <a href="/userproductdetail/detail/{{ $rp['id'] }}">
                                    <img src="{{ !empty($rp['image']) ? '/' . $rp['image'] : 'https://placehold.co/300x300' }}" 
                                         class="card-img-top object-fit-cover" height="250" alt="{{ $rp['name'] }}">
                                </a>
                                @if(isset($rp['is_new']) && $rp['is_new'])
                                    <span class="position-absolute top-0 start-0 m-2 badge bg-success">Mới</span>
                                @endif
                            </div>
                            <div class="card-body">
                                <div class="mb-1">
                                    <small class="text-muted text-uppercase" style="font-size: 0.75rem;">
                                        {{ $rp['category_name'] ?? 'Sản phẩm' }}
                                    </small>
                                </div>
                                <h6 class="card-title fw-bold text-truncate">
                                    <a href="/userproductdetail/detail/{{ $rp['id'] }}" class="text-decoration-none text-dark">
                                        {{ $rp['name'] }}
                                    </a>
                                </h6>
                                <div class="mt-2 text-brand fw-bold">
                                    {{ $rDisplayPrice }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>

@endsection

@section('scripts')
<script>
    function changeImage(src) {
        if(src) document.getElementById('mainImage').src = src;
    }

    function adjustQty(amount) {
        const qtyInput = document.getElementById('qtyInput');
        let currentQty = parseInt(qtyInput.value) || 1;
        let newQty = currentQty + amount;
        if (newQty < 1) newQty = 1;
        qtyInput.value = newQty;
    }

    function updateVariantInfo() {
        const select = document.getElementById('variantSelect');
        const selectedOption = select.options[select.selectedIndex];
        const btnAdd = document.getElementById('btnAddToCart');
        const priceDisplay = document.getElementById('productPrice');
        const variantIdInput = document.getElementById('selectedVariantId');
        const stockAlert = document.getElementById('stockAlert');

        const price = selectedOption.getAttribute('data-price');
        const stock = parseInt(selectedOption.getAttribute('data-stock'));
        const image = selectedOption.getAttribute('data-image');
        const variantId = select.value;

        if (price) {
            priceDisplay.innerText = new Intl.NumberFormat('vi-VN').format(price) + 'đ';
        }

        if (image) {
            changeImage(image);
        }

        variantIdInput.value = variantId;

        if (variantId && stock > 0) {
            btnAdd.removeAttribute('disabled');
            stockAlert.style.display = 'none';
            btnAdd.innerHTML = '<i class="bi bi-cart-plus me-2"></i> Thêm vào giỏ';
        } else if (variantId && stock <= 0) {
            btnAdd.setAttribute('disabled', 'disabled');
            stockAlert.style.display = 'block';
            btnAdd.innerHTML = 'Tạm hết hàng';
        } else {
            btnAdd.setAttribute('disabled', 'disabled');
        }
    }
</script>
@endsection