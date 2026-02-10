<?php
class OrderController extends Controller
{
    // Hiển thị danh sách đơn hàng
    public function index()
    {
        if (empty($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
            header("Location: /auth/login");
            exit;
        }

        $orderModel = $this->model('OrderModel');
        $allOrders = $orderModel->getAll();

        // 1. Thống kê
        $stats = [
            'all' => count($allOrders),
            'pending' => 0, 'approved' => 0, 'shipping' => 0,
            'completed' => 0, 'cancelled' => 0, 'returns' => 0,
            'revenue' => 0
        ];

        foreach ($allOrders as $order) {
            $st = $order['status'];
            if (isset($stats[$st])) $stats[$st]++;
            if ($st == 'completed') $stats['revenue'] += $order['total_amount'];
        }

        // 2. Lọc danh sách theo Status
        $currentStatus = $_GET['status'] ?? 'all';
        $filteredOrders = [];

        if ($currentStatus == 'all') {
            $filteredOrders = $allOrders;
        } else {
            $filteredOrders = array_filter($allOrders, function($order) use ($currentStatus) {
                return $order['status'] == $currentStatus;
            });
        }

        // 3. Phân trang (Pagination)
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10; // Số đơn hàng mỗi trang
        $totalItems = count($filteredOrders);
        $totalPages = ceil($totalItems / $limit);
        
        // Đảm bảo page hợp lệ
        if ($page < 1) $page = 1;
        if ($page > $totalPages && $totalPages > 0) $page = $totalPages;

        $offset = ($page - 1) * $limit;
        $pagedOrders = array_slice($filteredOrders, $offset, $limit);

        $this->view('order/index', [
            'orders' => $pagedOrders, // Chỉ truyền dữ liệu của trang hiện tại
            'stats' => $stats,
            'currentStatus' => $currentStatus,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ]);
    }

    public function quickUpdate($id)
    {
        if (empty($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
            header("Location: /auth/login");
            exit;
        }

        $status = $_GET['status'] ?? '';
        $redirectTab = $_GET['tab'] ?? 'all';

        if ($status) {
            $orderModel = $this->model('OrderModel');
            $currentOrder = $orderModel->find($id);
            $payment_status = $currentOrder['payment_status'];

            if ($status == 'completed') {
                $payment_status = 'paid';
            }

            $orderModel->updateStatus($id, $status, $payment_status);
            $_SESSION['success'] = "Đã cập nhật trạng thái đơn #$id";
        }

        header("Location: /order?status=" . $redirectTab);
        exit;
    }

    public function updatePayment($id)
    {
        if (empty($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
            header("Location: /auth/login");
            exit;
        }

        $payment_status = $_GET['payment_status'] ?? '';
        $redirectTab = $_GET['tab'] ?? 'all';

        if (in_array($payment_status, ['paid', 'unpaid'])) {
            $orderModel = $this->model('OrderModel');
            $order = $orderModel->find($id);
            
            if ($order['status'] == 'completed' && $payment_status == 'unpaid') {
                $_SESSION['error'] = "Đơn hàng đã hoàn thành bắt buộc phải đã thanh toán.";
            } else {
                $orderModel->updateStatus($id, $order['status'], $payment_status);
                $_SESSION['success'] = "Đã cập nhật thanh toán đơn #$id";
            }
        }

        header("Location: /order?status=" . $redirectTab);
        exit;
    }

    public function detail($id)
    {
        if (empty($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
            header("Location: /auth/login");
            exit;
        }

        $order = $this->model('OrderModel')->find($id);
        if (!$order) {
            header("Location: /order");
            exit;
        }
        $details = $this->model('OrderDetailModel')->getByOrderId($id);

        $this->view('order/detail', ['order' => $order, 'details' => $details]);
    }

    public function updateStatus($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $status = $_POST['status'];
            $payment_status = $_POST['payment_status'];
            
            if ($status == 'completed' && $payment_status != 'paid') {
                $_SESSION['error'] = "Không thể hoàn thành đơn hàng khi CHƯA thanh toán!";
                header("Location: /order/detail/$id");
                exit;
            }

            $this->model('OrderModel')->updateStatus($id, $status, $payment_status);
            $_SESSION['success'] = "Cập nhật thành công!";
            header("Location: /order/detail/$id");
        }
    }
    
    public function print($id) {
        $order = $this->model('OrderModel')->find($id);
        $details = $this->model('OrderDetailModel')->getByOrderId($id);
        require_once './app/views/order/print.php';
    }
}