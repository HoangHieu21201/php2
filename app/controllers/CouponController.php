<?php
class CouponController extends Controller
{
    public function index()
    {
        $couponModel = $this->model('CouponModel');
        $coupons = $couponModel->all();
        $this->view('coupon/index', ['coupons' => $coupons]);
    }

    public function create()
    {
        $this->view('coupon/create');
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /coupon/create");
            exit;
        }

        $code = strtoupper(trim($_POST['code'] ?? ''));
        $type = $_POST['type'] ?? 'fixed';
        $value = $_POST['value'] ?? 0;
        $min_order_value = $_POST['min_order_value'] ?? 0;
        $max_usage = $_POST['max_usage'] ?? '';
        $start_date = $_POST['start_date'] ?? '';
        $end_date = $_POST['end_date'] ?? '';
        
        // SỬA LỖI Ở ĐÂY: Lấy đúng giá trị từ select option thay vì kiểm tra isset
        $status = (int)($_POST['status'] ?? 0);

        $errors = [];

        if (empty($code)) {
            $errors['code'] = 'Mã giảm giá không được để trống.';
        } elseif (!preg_match('/^[A-Z0-9]+$/', $code)) {
            $errors['code'] = 'Mã chỉ được chứa chữ cái in hoa và số, không có ký tự đặc biệt.';
        } else {
            $couponModel = $this->model('CouponModel');
            if ($couponModel->findByCode($code)) {
                $errors['code'] = 'Mã giảm giá này đã tồn tại.';
            }
        }

        if (!in_array($type, ['fixed', 'percent'])) {
            $errors['type'] = 'Loại giảm giá không hợp lệ.';
        }

        if (!filter_var($value, FILTER_VALIDATE_INT) || $value <= 0) {
            $errors['value'] = 'Giá trị giảm phải là số nguyên dương.';
        } else {
            if ($type == 'percent' && $value > 100) {
                $errors['value'] = 'Giảm theo phần trăm không được vượt quá 100%.';
            }
            if ($type == 'fixed' && $value > 1000000000) {
                $errors['value'] = 'Giá trị giảm tiền mặt quá lớn, vui lòng kiểm tra lại.';
            }
        }

        if (!filter_var($min_order_value, FILTER_VALIDATE_INT) || $min_order_value < 0) {
            $errors['min_order_value'] = 'Đơn hàng tối thiểu phải là số nguyên không âm.';
        }

        $max_usage_val = null;
        if ($max_usage !== '') {
            if (!filter_var($max_usage, FILTER_VALIDATE_INT) || $max_usage <= 0) {
                $errors['max_usage'] = 'Lượt dùng tối đa phải là số nguyên dương.';
            } else {
                $max_usage_val = (int)$max_usage;
            }
        }

        $start_time = empty($start_date) ? date('Y-m-d H:i:s') : $start_date;
        $end_time = empty($end_date) ? null : $end_date;

        if ($end_time && strtotime($end_time) <= strtotime($start_time)) {
            $errors['end_date'] = 'Ngày kết thúc phải lớn hơn ngày bắt đầu.';
        }

        if (!empty($errors)) {
            $this->view('coupon/create', [
                'errors' => $errors,
                'old' => $_POST
            ]);
            return;
        }

        $data = [
            'code' => $code,
            'type' => $type,
            'value' => (int)$value,
            'min_order_value' => (int)$min_order_value,
            'max_usage' => $max_usage_val,
            'times_used' => 0,
            'start_date' => $start_time,
            'end_date' => $end_time,
            'status' => $status
        ];

        $couponModel = $this->model('CouponModel');
        $couponModel->create($data);

        header("Location: /coupon");
        exit;
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

        $couponModel = $this->model('CouponModel');
        $currentCoupon = $couponModel->find($id);
        
        if (!$currentCoupon) {
            header("Location: /coupon");
            exit;
        }

        $code = strtoupper(trim($_POST['code'] ?? ''));
        $type = $_POST['type'] ?? 'fixed';
        $value = $_POST['value'] ?? 0;
        $min_order_value = $_POST['min_order_value'] ?? 0;
        $max_usage = $_POST['max_usage'] ?? '';
        $start_date = $_POST['start_date'] ?? '';
        $end_date = $_POST['end_date'] ?? '';
        $status = (int)($_POST['status'] ?? 0);

        $errors = [];

        if (empty($code)) {
            $errors['code'] = 'Mã giảm giá không được để trống.';
        } elseif (!preg_match('/^[A-Z0-9]+$/', $code)) {
            $errors['code'] = 'Mã chỉ được chứa chữ cái in hoa và số.';
        } else {
            $existingCoupon = $couponModel->findByCode($code);
            if ($existingCoupon && $existingCoupon['id'] != $id) {
                $errors['code'] = 'Mã giảm giá này đã tồn tại.';
            }
        }

        if (!in_array($type, ['fixed', 'percent'])) {
            $errors['type'] = 'Loại giảm giá không hợp lệ.';
        }

        if (!filter_var($value, FILTER_VALIDATE_INT) || $value <= 0) {
            $errors['value'] = 'Giá trị giảm phải là số nguyên dương.';
        } else {
            if ($type == 'percent' && $value > 100) {
                $errors['value'] = 'Giảm theo phần trăm không được vượt quá 100%.';
            }
            if ($type == 'fixed' && $value > 1000000000) {
                $errors['value'] = 'Giá trị giảm tiền mặt quá lớn.';
            }
        }

        if (!filter_var($min_order_value, FILTER_VALIDATE_INT) || $min_order_value < 0) {
            $errors['min_order_value'] = 'Đơn hàng tối thiểu phải là số nguyên không âm.';
        }

        $max_usage_val = null;
        if ($max_usage !== '') {
            if (!filter_var($max_usage, FILTER_VALIDATE_INT) || $max_usage <= 0) {
                $errors['max_usage'] = 'Lượt dùng tối đa phải là số nguyên dương.';
            } else {
                $max_usage_val = (int)$max_usage;
            }
        }

        $start_time = empty($start_date) ? date('Y-m-d H:i:s') : $start_date;
        $end_time = empty($end_date) ? null : $end_date;

        if ($end_time && strtotime($end_time) <= strtotime($start_time)) {
            $errors['end_date'] = 'Ngày kết thúc phải lớn hơn ngày bắt đầu.';
        }

        if (!empty($errors)) {
            $this->view('coupon/edit', [
                'coupon' => array_merge($currentCoupon, $_POST, ['id' => $id]),
                'errors' => $errors
            ]);
            return;
        }

        $data = [
            'code' => $code,
            'type' => $type,
            'value' => (int)$value,
            'min_order_value' => (int)$min_order_value,
            'max_usage' => $max_usage_val,
            'start_date' => $start_time,
            'end_date' => $end_time,
            'status' => $status
        ];

        $couponModel->update($id, $data);

        header("Location: /coupon");
        exit;
    }

    public function delete($id)
    {
        $couponModel = $this->model('CouponModel');
        $couponModel->delete($id);
        header("Location: /coupon");
    }
}