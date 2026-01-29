<?php
class UserCartController extends Controller
{
    public function index()
    {
        
        if (empty($_SESSION['user_id'])) {
            header("Location: /auth/login");
            exit;
        }

        $userId = $_SESSION['user_id'];
        $cartModel = $this->model('CartModel');
        $couponModel = $this->model('CouponModel');

        $_SESSION['cart_count'] = $cartModel->countCart($userId);

        $cartItems = $cartModel->getCartByUserId($userId);

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $price = $item['variant_sale_price'] > 0 ? $item['variant_sale_price'] : ($item['variant_price'] > 0 ? $item['variant_price'] : $item['product_price']);
            $subtotal += $price * $item['quantity'];
        }

        $allCoupons = $couponModel->all();
        $availableCoupons = [];
        $now = date('Y-m-d H:i:s');

        foreach ($allCoupons as $c) {
            if (
                $c['status'] == 1 &&
                ($c['start_date'] == null || $c['start_date'] <= $now) &&
                ($c['end_date'] == null || $c['end_date'] >= $now)
            ) {
                $availableCoupons[] = $c;
            }
        }

        // 4. Xử lý Mã giảm giá đang áp dụng
        $discount = 0;
        $couponCode = '';

        if (isset($_SESSION['applied_coupon'])) {
            $coupon = $_SESSION['applied_coupon'];
            $couponCode = $coupon['code'];

            // Kiểm tra lại điều kiện đơn hàng tối thiểu
            if ($subtotal >= $coupon['min_order_value']) {
                if ($coupon['type'] == 'percent') {
                    $discount = $subtotal * ($coupon['value'] / 100);
                } else {
                    $discount = $coupon['value']; // Giảm cố định
                }

                if ($discount > $subtotal) {
                    $discount = $subtotal;
                }
            } else {
                unset($_SESSION['applied_coupon']);
                $couponCode = '';
                $_SESSION['error'] = "Mã giảm giá đã tự động hủy do giá trị đơn hàng thay đổi.";
            }
        }

        $total = $subtotal - $discount;

        $this->view('pages/cart', [
            'cartItems'        => $cartItems,
            'subtotal'         => $subtotal,
            'discount'         => $discount,
            'total'            => $total,
            'couponCode'       => $couponCode,
            'availableCoupons' => $availableCoupons,
            'title'            => 'Giỏ hàng của bạn'
        ]);
    }

    public function applyCoupon()
    {
        if (empty($_SESSION['user_id'])) {
            header("Location: /auth/login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /usercart");
            exit;
        }

        $code = strtoupper(trim($_POST['coupon_code'] ?? ''));

        if (empty($code)) {
            $_SESSION['error'] = "Vui lòng chọn mã giảm giá.";
            header("Location: /usercart");
            exit;
        }

        $couponModel = $this->model('CouponModel');
        $coupon = $couponModel->findByCode($code);

        if (!$coupon) {
            $_SESSION['error'] = "Mã giảm giá không tồn tại.";
            header("Location: /usercart");
            exit;
        }

        if ($coupon['status'] == 0) {
            $_SESSION['error'] = "Mã giảm giá đang bị tạm khóa.";
            header("Location: /usercart");
            exit;
        }

        $now = date('Y-m-d H:i:s');
        if (!empty($coupon['start_date']) && $coupon['start_date'] > $now) {
            $_SESSION['error'] = "Mã giảm giá chưa đến thời gian áp dụng.";
            header("Location: /usercart");
            exit;
        }
        if (!empty($coupon['end_date']) && $coupon['end_date'] < $now) {
            $_SESSION['error'] = "Mã giảm giá đã hết hạn.";
            header("Location: /usercart");
            exit;
        }

        if ($coupon['max_usage'] !== null && $coupon['max_usage'] > 0 && $coupon['times_used'] >= $coupon['max_usage']) {
            $_SESSION['error'] = "Mã giảm giá đã hết lượt sử dụng.";
            header("Location: /usercart");
            exit;
        }

        $userId = $_SESSION['user_id'];
        $cartModel = $this->model('CartModel');
        $cartItems = $cartModel->getCartByUserId($userId);
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $price = $item['variant_sale_price'] > 0 ? $item['variant_sale_price'] : ($item['variant_price'] > 0 ? $item['variant_price'] : $item['product_price']);
            $subtotal += $price * $item['quantity'];
        }

        if ($subtotal < $coupon['min_order_value']) {
            $_SESSION['error'] = "Đơn hàng phải từ " . number_format($coupon['min_order_value'], 0, ',', '.') . "đ để dùng mã này.";
            header("Location: /usercart");
            exit;
        }

        $_SESSION['applied_coupon'] = $coupon;
        $_SESSION['success'] = "Áp dụng mã giảm giá thành công!";
        header("Location: /usercart");
    }

    public function removeCoupon()
    {
        if (isset($_SESSION['applied_coupon'])) {
            unset($_SESSION['applied_coupon']);
            $_SESSION['success'] = "Đã gỡ bỏ mã giảm giá.";
        }
        header("Location: /usercart");
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /");
            exit;
        }

        if (empty($_SESSION['user_id'])) {
            $_SESSION['error'] = "Vui lòng đăng nhập để mua hàng!";
            header("Location: /auth/login");
            exit;
        }
        

        $userId = $_SESSION['user_id'];
        $variantId = $_POST['variant_id'] ?? null;
        $quantity = (int)($_POST['quantity'] ?? 1);

        if (!$variantId || $quantity < 1) {
            header("Location: /");
            exit;
        }

        $cartModel = $this->model('CartModel');
        $existingItem = $cartModel->findCartItem($userId, $variantId);

        if ($existingItem) {
            $newQuantity = $existingItem['quantity'] + $quantity;
            $cartModel->updateQuantity($existingItem['id'], $newQuantity);
        } else {
            $cartModel->add([
                ':user_id' => $userId,
                ':variant_id' => $variantId,
                ':quantity' => $quantity
            ]);
        }

        $_SESSION['cart_count'] = $cartModel->countCart($userId);
        header("Location: /usercart");
        exit;
    }

    public function update()
    {
        if (empty($_SESSION['user_id'])) exit;

        $userId = $_SESSION['user_id'];
        $cartId = $_POST['cart_id'] ?? null;
        $quantity = (int)($_POST['quantity'] ?? 1);

        $cartModel = $this->model('CartModel');

        if ($cartId && $quantity > 0) {
            $cartModel->updateQuantity($cartId, $quantity);
        }

        $_SESSION['cart_count'] = $cartModel->countCart($userId);
        header("Location: /usercart");
    }

    public function remove($id)
    {
        if (empty($_SESSION['user_id'])) {
            header("Location: /auth/login");
            exit;
        }

        $userId = $_SESSION['user_id'];
        $cartModel = $this->model('CartModel');
        $cartModel->remove($id, $userId);

        $_SESSION['cart_count'] = $cartModel->countCart($userId);
        header("Location: /usercart");
    }
}
