<?php
class CheckoutController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            header("Location: /auth/login");
            exit;
        }

        $userId = $_SESSION['user_id'];
        
        $cartModel = $this->model('CartModel');
        $cartItems = $cartModel->getCartByUserId($userId);

        if (empty($cartItems)) {
            $_SESSION['error'] = "Giỏ hàng trống.";
            header("Location: /cart");
            exit;
        }

        $addressModel = $this->model('UserAddressModel');
        $addresses = $addressModel->getByUserId($userId);

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $price = $item['final_price'] ?? $item['variant_price'] ?? 0;
            $subtotal += $price * $item['quantity'];
        }

        // --- LOGIC LẤY COUPON KHẢ DỤNG ---
        $couponModel = $this->model('CouponModel');
        $allCoupons = $couponModel->all();
        $availableCoupons = [];
        $now = date('Y-m-d H:i:s');

        foreach ($allCoupons as $c) {
            // Điều kiện coupon hợp lệ:
            // 1. Status = 1 (Hoạt động)
            // 2. Ngày bắt đầu <= Hiện tại
            // 3. Ngày kết thúc >= Hiện tại (hoặc null)
            // 4. Giá trị đơn hàng tối thiểu <= Subtotal
            // 5. Chưa hết lượt dùng (nếu có giới hạn)
            if ($c['status'] == 1 && 
                $c['start_date'] <= $now && 
                ($c['end_date'] == null || $c['end_date'] >= $now) &&
                $c['min_order_value'] <= $subtotal
            ) {
                if ($c['max_usage'] > 0 && $c['times_used'] >= $c['max_usage']) {
                    continue; // Bỏ qua nếu hết lượt
                }
                $availableCoupons[] = $c;
            }
        }

        $discount = 0;
        if (isset($_SESSION['coupon'])) {
            $coupon = $_SESSION['coupon'];
            if ($coupon['type'] == 'percent') {
                $discount = $subtotal * ($coupon['value'] / 100);
            } else {
                $discount = $coupon['value'];
            }
        }

        $total = max(0, $subtotal - $discount);

        $this->view('pages/checkout', [
            'cartItems' => $cartItems,
            'addresses' => $addresses,
            'coupons' => $availableCoupons, // Truyền danh sách coupon sang view
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $total,
            'title' => 'Thanh toán'
        ]);
    }

    public function applyCoupon()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /checkout");
            exit;
        }

        $code = $_POST['coupon_code'] ?? '';
        
        if (empty($code)) {
            if (isset($_SESSION['coupon'])) unset($_SESSION['coupon']);
            header("Location: /checkout");
            exit;
        }

        $couponModel = $this->model('CouponModel');
        $coupon = $couponModel->findByCode($code);
        $now = date('Y-m-d H:i:s');

        if (!$coupon) {
            $_SESSION['error'] = "Mã giảm giá không tồn tại.";
        } elseif ($coupon['status'] != 1) {
            $_SESSION['error'] = "Mã giảm giá đã ngưng hoạt động.";
        } elseif ($coupon['start_date'] > $now) {
            $_SESSION['error'] = "Mã giảm giá chưa đến đợt áp dụng.";
        } elseif ($coupon['end_date'] && $coupon['end_date'] < $now) {
            $_SESSION['error'] = "Mã giảm giá đã hết hạn.";
        } elseif ($coupon['max_usage'] > 0 && $coupon['times_used'] >= $coupon['max_usage']) {
            $_SESSION['error'] = "Mã giảm giá đã hết lượt sử dụng.";
        } else {
            $userId = $_SESSION['user_id'];
            $cartModel = $this->model('CartModel');
            $cartItems = $cartModel->getCartByUserId($userId);
            $subtotal = 0;
            foreach ($cartItems as $item) {
                $price = $item['final_price'] ?? $item['variant_price'] ?? 0;
                $subtotal += $price * $item['quantity'];
            }

            if ($subtotal < $coupon['min_order_value']) {
                $_SESSION['error'] = "Đơn hàng phải từ " . number_format($coupon['min_order_value']) . "đ để sử dụng mã này.";
            } else {
                $_SESSION['coupon'] = $coupon;
                $_SESSION['success'] = "Áp dụng mã giảm giá thành công!";
            }
        }

        header("Location: /checkout");
        exit;
    }

    public function process()
    {
        if (empty($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /");
            exit;
        }

        $userId = $_SESSION['user_id'];
        $addressId = $_POST['address_id'] ?? null;
        $note = $_POST['note'] ?? '';
        
        $recipient_name = "";
        $recipient_phone = "";
        $recipient_address = "";

        if ($addressId === 'new') {
            $recipient_name = $_POST['new_recipient_name'] ?? '';
            $recipient_phone = $_POST['new_recipient_phone'] ?? '';
            $recipient_address = $_POST['new_address_detail_full'] ?? ''; 

            if (empty($recipient_name) || empty($recipient_phone) || empty($recipient_address)) {
                $_SESSION['error'] = "Vui lòng nhập đầy đủ thông tin người nhận.";
                header("Location: /checkout");
                exit;
            }


        } else {
            if (empty($addressId)) {
                $_SESSION['error'] = "Vui lòng chọn địa chỉ giao hàng.";
                header("Location: /checkout");
                exit;
            }

            $addressModel = $this->model('UserAddressModel');
            $addressInfo = $addressModel->find($addressId);

            if (!$addressInfo || $addressInfo['user_id'] != $userId) {
                $_SESSION['error'] = "Địa chỉ không hợp lệ.";
                header("Location: /checkout");
                exit;
            }

            $recipient_name = $addressInfo['recipient_name'];
            $recipient_phone = $addressInfo['recipient_phone'];
            $recipient_address = $addressInfo['address'];
        }

        $cartModel = $this->model('CartModel');
        $cartItems = $cartModel->getCartByUserId($userId);

        if (empty($cartItems)) {
            header("Location: /cart");
            exit;
        }

        $variantModel = $this->model('ProductVariantModel');
        
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $price = $item['final_price'] ?? $item['variant_price'] ?? 0;
            $subtotal += $price * $item['quantity'];
        }

        $discount = 0;
        if (isset($_SESSION['coupon'])) {
            $coupon = $_SESSION['coupon'];
            if ($coupon['type'] == 'percent') {
                $discount = $subtotal * ($coupon['value'] / 100);
            } else {
                $discount = $coupon['value'];
            }
        }
        $total = max(0, $subtotal - $discount);

        $orderModel = $this->model('OrderModel');
        $orderDetailModel = $this->model('OrderDetailModel');

        if (!method_exists($orderDetailModel, 'create')) {
            die("LỖI CRITICAL: File app/models/OrderDetailModel.php chưa có hàm create().");
        }

        try {
            $orderData = [
                'user_id' => $userId,
                'recipient_name' => $recipient_name,
                'recipient_phone' => $recipient_phone,
                'recipient_address' => $recipient_address,
                'subtotal' => $subtotal,
                'discount_amount' => $discount,
                'total_amount' => $total,
                'payment_method' => 'cod',
                'payment_status' => 'unpaid',
                'status' => 'pending',
                'note' => $note
            ];

            $orderId = $orderModel->create($orderData);

            if (!$orderId) {
                throw new Exception("Lỗi hệ thống: Không thể tạo đơn hàng.");
            }

            foreach ($cartItems as $item) {
                $price = $item['final_price'] ?? $item['variant_price'] ?? 0;
                
                $detailData = [
                    'order_id' => $orderId,
                    'product_variant_id' => $item['product_variant_id'],
                    'quantity' => $item['quantity'],
                    'price' => $price,
                    'total_price' => $price * $item['quantity']
                ];
                
                $orderDetailModel->create($detailData);

                $isDecreased = $variantModel->decreaseStock($item['product_variant_id'], $item['quantity']);
                if (!$isDecreased) {
                    throw new Exception("Sản phẩm " . ($item['product_name'] ?? 'SP') . " vừa hết hàng.");
                }
            }

            $cartModel->clearCart($userId);
            if (isset($_SESSION['coupon'])) {
                unset($_SESSION['coupon']);
            }

            header("Location: /checkout/success");
            exit;

        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header("Location: /checkout");
            exit;
        }
    }

    public function success()
    {
        $this->view('pages/order_success', ['title' => 'Đặt hàng thành công']);
    }
}