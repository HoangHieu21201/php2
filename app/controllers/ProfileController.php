<?php
class ProfileController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            header("Location: /auth/login");
            exit;
        }

        $userId = $_SESSION['user_id'];
        $userModel = $this->model('UserModel');
        $user = $userModel->find($userId);

        if (!$user) {
            session_destroy();
            header("Location: /auth/login");
            exit;
        }

        // Lấy danh sách toàn bộ địa chỉ của user
        $addressModel = $this->model('UserAddressModel');
        $addresses = $addressModel->getByUserId($userId);

        $this->view('pages/profile', [
            'user' => $user,
            'addresses' => $addresses, // Truyền danh sách địa chỉ sang view
            'title' => 'Hồ sơ cá nhân'
        ]);
    }

    public function update()
    {
        if (empty($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /profile");
            exit;
        }

        $userId = $_SESSION['user_id'];
        $userModel = $this->model('UserModel');
        $currentUser = $userModel->find($userId);

        // Lấy dữ liệu cơ bản
        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        $errors = [];
        if (empty($name)) $errors['name'] = "Họ tên không được để trống.";

        $passwordToUpdate = '';
        if (!empty($newPassword)) {
            if (strlen($newPassword) < 6) $errors['new_password'] = "Mật khẩu mới phải >= 6 ký tự.";
            if ($newPassword !== $confirmPassword) $errors['confirm_password'] = "Mật khẩu không khớp.";
            if (empty($errors)) $passwordToUpdate = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        if (!empty($errors)) {
            $_SESSION['error'] = "Vui lòng kiểm tra lại thông tin.";
            $_SESSION['errors'] = $errors;
            header("Location: /profile");
            exit;
        }

        $userData = [
            'name' => $name,
            'email' => $currentUser['email'],
            'phone' => $phone,
            'role' => $currentUser['role'],    
            'status' => $currentUser['status'],
        ];

        if (!empty($passwordToUpdate)) $userData['password'] = $passwordToUpdate;

        if ($userModel->update($userId, $userData)) {
            $_SESSION['success'] = "Cập nhật thông tin thành công!";
            $_SESSION['user_name'] = $name;
        } else {
            $_SESSION['error'] = "Lỗi cập nhật.";
        }

        header("Location: /profile");
        exit;
    }

    // --- CÁC HÀM QUẢN LÝ ĐỊA CHỈ ---

    public function addAddress()
    {
        if (empty($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /auth/login");
            exit;
        }

        $userId = $_SESSION['user_id'];
        $address = trim($_POST['address_detail'] ?? '');
        // Ghép địa chỉ từ các select box nếu có (Province, District, Ward) xử lý ở JS trước khi submit vào address_detail
        // Hoặc nhận riêng lẻ và ghép tại đây. Ở đây giả sử form gửi address_detail đã đầy đủ.

        if (empty($address)) {
            $_SESSION['error'] = "Vui lòng nhập địa chỉ chi tiết.";
            header("Location: /profile");
            exit;
        }

        $data = [
            'user_id' => $userId,
            'recipient_name' => $_POST['recipient_name'] ?? '',
            'recipient_phone' => $_POST['recipient_phone'] ?? '',
            'address' => $address,
            'is_default' => isset($_POST['is_default']) ? 1 : 0
        ];

        $addressModel = $this->model('UserAddressModel');
        
        // Nếu chọn mặc định, reset các cái cũ
        if ($data['is_default'] == 1) {
            $addressModel->setDefault($userId, 0);
        }

        if ($addressModel->create($data)) {
            $_SESSION['success'] = "Thêm địa chỉ mới thành công!";
        } else {
            $_SESSION['error'] = "Thêm địa chỉ thất bại.";
        }
        header("Location: /profile");
        exit;
    }

    public function deleteAddress($id)
    {
        if (empty($_SESSION['user_id'])) return;
        
        $this->model('UserAddressModel')->delete($id, $_SESSION['user_id']);
        $_SESSION['success'] = "Đã xóa địa chỉ.";
        header("Location: /profile");
        exit;
    }

    public function setDefaultAddress($id)
    {
        if (empty($_SESSION['user_id'])) return;

        $this->model('UserAddressModel')->setDefault($_SESSION['user_id'], $id);
        $_SESSION['success'] = "Đã đặt làm địa chỉ mặc định.";
        header("Location: /profile");
        exit;
    }
}