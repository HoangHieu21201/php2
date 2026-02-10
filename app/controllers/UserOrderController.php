<?php
class UserOrderController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            header("Location: /auth/login");
            exit;
        }

        $userId = $_SESSION['user_id'];
        $orderModel = $this->model('OrderModel');
        
        $allOrders = $orderModel->getOrdersByUserId($userId);

        $counts = [
            'all' => count($allOrders),
            'pending' => 0,
            'shipping' => 0,
            'completed' => 0,
            'cancelled' => 0
        ];

        foreach ($allOrders as $order) {
            $st = $order['status'];
            if ($st == 'pending') {
                $counts['pending']++;
            } elseif (in_array($st, ['approved', 'processing', 'shipping'])) {
                $counts['shipping']++;
            } elseif ($st == 'completed') {
                $counts['completed']++;
            } elseif (in_array($st, ['cancelled', 'returns', 'returned'])) {
                $counts['cancelled']++;
            }
        }

        $currentStatus = $_GET['status'] ?? 'all';
        $filteredOrders = [];

        if ($currentStatus == 'all') {
            $filteredOrders = $allOrders;
        } else {
            $filteredOrders = array_filter($allOrders, function($order) use ($currentStatus) {
                $st = $order['status'];
                if ($currentStatus == 'pending') return $st == 'pending';
                if ($currentStatus == 'shipping') return in_array($st, ['approved', 'processing', 'shipping']);
                if ($currentStatus == 'completed') return $st == 'completed';
                if ($currentStatus == 'cancelled') return in_array($st, ['cancelled', 'returns', 'returned']);
                return false;
            });
        }

        $sort = $_GET['sort'] ?? 'newest';
        usort($filteredOrders, function($a, $b) use ($sort) {
            $timeA = strtotime($a['created_at']);
            $timeB = strtotime($b['created_at']);
            return ($sort == 'oldest') ? $timeA - $timeB : $timeB - $timeA;
        });

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 5; 
        $totalItems = count($filteredOrders);
        $totalPages = ceil($totalItems / $limit);
        $offset = ($page - 1) * $limit;
        
        $pagedOrders = array_slice($filteredOrders, $offset, $limit);

        $this->view('pages/orders', [
            'orders' => $pagedOrders,
            'counts' => $counts,
            'currentStatus' => $currentStatus,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'currentSort' => $sort,
            'title' => 'Đơn hàng của tôi'
        ]);
    }

    public function getOrderDetail()
    {
        if (empty($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        $orderId = $_GET['id'] ?? 0;
        $orderDetailModel = $this->model('OrderDetailModel');
        $details = $orderDetailModel->getByOrderId($orderId);

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'data' => $details]);
    }

    public function cancelOrder($id)
    {
        if (empty($_SESSION['user_id'])) {
            header("Location: /auth/login");
            exit;
        }

        $orderModel = $this->model('OrderModel');
        $order = $orderModel->find($id);

        if ($order && $order['user_id'] == $_SESSION['user_id'] && $order['status'] == 'pending') {
            $orderModel->updateStatus($id, 'cancelled', $order['payment_status']);
            $_SESSION['success'] = "Đã hủy đơn hàng #$id thành công.";
        } else {
            $_SESSION['error'] = "Không thể hủy đơn hàng này.";
        }

        header("Location: /userorder?status=pending");
        exit;
    }
}