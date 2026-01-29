@extends('layout.userLayout')

@section('content')

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-brand m-0">Giỏ hàng của bạn</h2>
        <span class="badge bg-light text-dark border"><?= count($cartItems) ?> sản phẩm</span>
    </div>

    <!-- Hiển thị thông báo lỗi/thành công từ Session -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= $_SESSION['error'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> <?= $_SESSION['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (empty($cartItems)): ?>
        <div class="text-center py-5">
            <img src="https://cdni.iconscout.com/illustration/premium/thumb/empty-cart-2130356-1800917.png" alt="Empty Cart" width="200" class="mb-3">
            <h4>Giỏ hàng trống!</h4>
            <p class="text-muted">Bạn chưa thêm sản phẩm nào vào giỏ hàng.</p>
            <a href="/" class="btn btn-brand px-4 py-2 mt-2">Tiếp tục mua sắm</a>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <!-- Danh sách sản phẩm -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4" style="width: 40%">Sản phẩm</th>
                                    <th class="text-center">Đơn giá</th>
                                    <th class="text-center" style="width: 15%">Số lượng</th>
                                    <th class="text-end pe-4">Thành tiền</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cartItems as $item): ?>
                                    <?php 
                                        $price = $item['variant_sale_price'] > 0 ? $item['variant_sale_price'] : ($item['variant_price'] > 0 ? $item['variant_price'] : $item['product_price']);
                                        $itemSubtotal = $price * $item['quantity'];
                                        $image = !empty($item['variant_image']) ? $item['variant_image'] : ($item['product_image'] ?? 'https://placehold.co/100x100');
                                    ?>
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <a href="/userproductdetail/detail/<?= $item['product_id'] ?>">
                                                    <img src="/<?= $image ?>" class="rounded border me-3" width="70" height="70" style="object-fit: cover;">
                                                </a>
                                                <div>
                                                    <a href="/userproductdetail/detail/<?= $item['product_id'] ?>" class="text-decoration-none text-dark fw-bold d-block mb-1">
                                                        <?= htmlspecialchars($item['product_name']) ?>
                                                    </a>
                                                    <small class="text-muted">
                                                        <?= htmlspecialchars($item['color_name'] ?? '-') ?> / <?= htmlspecialchars($item['size_name'] ?? '-') ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="fw-semibold"><?= number_format($price, 0, ',', '.') ?>đ</span>
                                            <?php if($item['variant_sale_price'] > 0 && $item['variant_sale_price'] < $item['variant_price']): ?>
                                                <br><small class="text-decoration-line-through text-muted"><?= number_format($item['variant_price'], 0, ',', '.') ?>đ</small>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <form action="/usercart/update" method="POST" class="d-flex justify-content-center">
                                                <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
                                                <div class="input-group input-group-sm" style="width: 100px;">
                                                    <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" class="form-control text-center" onchange="this.form.submit()">
                                                </div>
                                            </form>
                                        </td>
                                        <td class="text-end pe-4 fw-bold text-brand">
                                            <?= number_format($itemSubtotal, 0, ',', '.') ?>đ
                                        </td>
                                        <td class="text-end pe-3">
                                            <a href="/usercart/remove/<?= $item['cart_id'] ?>" class="text-danger" onclick="return confirm('Xóa sản phẩm này?');" title="Xóa">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tổng đơn hàng & Mã giảm giá -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm p-4 sticky-top" style="top: 20px;">
                    <h5 class="fw-bold mb-4">Tổng quan đơn hàng</h5>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Tạm tính:</span>
                        <span class="fw-bold"><?= number_format($subtotal, 0, ',', '.') ?>đ</span>
                    </div>

                    <!-- Mã giảm giá -->
                    <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                        <span class="text-muted">Giảm giá:</span>
                        <span class="text-success fw-bold">
                            -<?= number_format($discount, 0, ',', '.') ?>đ
                        </span>
                    </div>

                    <!-- Form nhập mã (DROPDOWN) -->
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Mã ưu đãi</label>
                        <?php if (!empty($couponCode)): ?>
                            <div class="alert alert-success d-flex justify-content-between align-items-center py-2 px-3 mb-0">
                                <span><i class="bi bi-tag-fill me-1"></i> <?= htmlspecialchars($couponCode) ?></span>
                                <a href="/usercart/removeCoupon" class="text-success" title="Gỡ bỏ"><i class="bi bi-x-lg"></i></a>
                            </div>
                        <?php else: ?>
                            <form action="/usercart/applyCoupon" method="POST">
                                <div class="input-group">
                                    <select name="coupon_code" class="form-select text-secondary" style="font-size: 0.9rem;">
                                        <option value="" selected disabled>-- Chọn mã giảm giá --</option>
                                        <?php if(!empty($availableCoupons)): ?>
                                            <?php foreach($availableCoupons as $cp): ?>
                                                <option value="<?= $cp['code'] ?>">
                                                    <?= $cp['code'] ?> (Giảm <?= $cp['type'] == 'percent' ? $cp['value'].'%' : number_format($cp['value'], 0, ',','.').'đ' ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <option value="" disabled>Không có mã nào</option>
                                        <?php endif; ?>
                                    </select>
                                    <button class="btn btn-brand" type="submit">Áp dụng</button>
                                </div>
                                {{-- <div class="form-text small fst-italic mt-1">
                                    <i class="bi bi-info-circle"></i> Chọn mã phù hợp với giá trị đơn hàng.
                                </div> --}}
                            </form>
                        <?php endif; ?>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-4 pt-2 border-top">
                        <span class="fw-bold fs-5">Tổng cộng:</span>
                        <span class="fw-bold fs-4 text-brand"><?= number_format($total, 0, ',', '.') ?>đ</span>
                    </div>

                    <a href="#" class="btn btn-brand w-100 py-2 fw-bold text-uppercase mb-2">
                        Tiến hành thanh toán
                    </a>
                    <a href="/userclient" class="btn btn-outline-secondary w-100 py-2">
                        <i class="bi bi-arrow-left me-1"></i> Tiếp tục mua hàng
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

@endsection