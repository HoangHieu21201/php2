<?php
class HomeController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            header("Location: /auth/login");
            exit;
        }

        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] != 1) {
            header("Location: /");
            exit;
        }

        $orderModel = $this->model('OrderModel');
        $productModel = $this->model('ProductModel');

        $filter = $_GET['filter'] ?? 'day';
        $chartData = [];
        $chartLabel = "Doanh thu 7 ngày gần nhất";

        switch ($filter) {
            case 'month':
                $chartData = $orderModel->getMonthlyRevenue(12);
                $chartLabel = "Doanh thu 12 tháng gần nhất";
                break;
            case 'year':
                $chartData = $orderModel->getYearlyRevenue(5);
                $chartLabel = "Doanh thu các năm qua";
                break;
            case '30days':
                $chartData = $orderModel->getDailyRevenue(30);
                $chartLabel = "Doanh thu 30 ngày gần nhất";
                break;
            default:
                $chartData = $orderModel->getDailyRevenue(7);
                $chartLabel = "Doanh thu 7 ngày gần nhất";
                break;
        }

        $revenueStats = $orderModel->getRevenueSummary();
        $products = $productModel->all();

        $this->view('home/index', [
            'title' => "Dashboard Quản trị",
            'products' => $products,
            'revenueStats' => $revenueStats,
            'chartData' => $chartData,
            'chartLabel' => $chartLabel,
            'currentFilter' => $filter
        ]);
    }
}