<?php
class CouponController extends Controller
{
    public function index()
    {
        $couponModel = $this->model('CouponModel');
        $coupons = $couponModel->all();
        $this->view('coupon/index', ['coupons' => $coupons]);
    }

    public function edit($id)
    {
        $couponModel = $this->model('CouponModel');
        $coupon = $couponModel->find($id);
        if (!$coupon) {
            header("Location: /coupon");
            exit;
        }
        $this->view('coupon/edit', ['coupon' => $coupon]);
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /coupon/edit/$id");
            exit;
        }

        $data = [
            'code' => $_POST['code'] ?? '',
            'end_date' => $_POST['end_date'] ?? '',
            'max_usage' => $_POST['max_usage'] ?? 0,
            'min_order_value' => $_POST['min_order_value'] ?? 0,
            'start_date' => $_POST['start_date'] ?? '',
            'times_used' => $_POST['times_used'] ?? 0,
            'type' => $_POST['type'] ?? '',
            'value' => $_POST['value'] ?? 0,
            'status' => isset($_POST['status']) ? 1 : 0,
        ];

        $couponModel = $this->model('CouponModel');
        $couponModel->update($id, $data);

        header("Location: /coupon");
    }
}   