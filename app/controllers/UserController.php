<?php
class UserController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            header("Location: /auth/login");
            exit;
        }

        $userModel = $this->model('UserModel');
        $addressModel = $this->model('UserAddressModel');

        $users = $userModel->all();

        foreach ($users as &$user) {
            $defaultAddress = $addressModel->getDefaultAddress($user['id']);
            $user['default_address'] = $defaultAddress ? $defaultAddress['address'] : 'Chưa có địa chỉ';
        }

        $this->view('user/index', ['users' => $users]);
    }

    public function create()
    {
        if (empty($_SESSION['user_id'])) {
            header("Location: /auth/login");
            exit;
        }
        $this->view('user/create');
    }

    public function store()
    {
        if (empty($_SESSION['user_id'])) {
            header("Location: /auth/login");
            exit;
        }

        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $role = $_POST['role'] ?? '';
        $status = $_POST['status'] ?? '';

        if (empty($name) || empty($email) || empty($password) || empty($phone)) {
            $_SESSION['error'] = "Vui lòng nhập đầy đủ thông tin bắt buộc!";
            header("Location: /user/create");
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Định dạng email không hợp lệ!";
            header("Location: /user/create");
            exit;
        }

        if (strlen($password) < 6) {
            $_SESSION['error'] = "Mật khẩu phải có ít nhất 6 ký tự!";
            header("Location: /user/create");
            exit;
        }

        $userModel = $this->model('UserModel');
        if ($userModel->findByEmail($email)) {
            $_SESSION['error'] = "Email đã tồn tại trong hệ thống!";
            header("Location: /user/create");
            exit;
        }

        $data = [
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'phone' => $phone,
            'role' => $role,
            'status' => $status
        ];
        
        $isCreated = $userModel->create($data);

        if ($isCreated) {
            $_SESSION['success'] = "Thêm người dùng thành công!";
            header("Location: /user");
        } else {
            $_SESSION['error'] = "Thêm thất bại!";
            header("Location: /user/create");
        }
    }

    public function edit($id)
    {
        if (empty($_SESSION['user_id'])) {
            header("Location: /auth/login");
            exit;
        }

        $userModel = $this->model('UserModel');
        $user = $userModel->find($id);

        if (!$user) {
            $_SESSION['error'] = "Người dùng không tồn tại!";
            header("Location: /user");
            exit;
        }

        $addresses = $this->model('UserAddressModel')->getByUserId($id);

        $this->view('user/edit', [
            'user' => $user,
            'addresses' => $addresses
        ]);
    }

    public function update($id)
    {
        if (empty($_SESSION['user_id'])) {
            header("Location: /auth/login");
            exit;
        }

        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $role = $_POST['role'] ?? '';
        $status = $_POST['status'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($name) || empty($email) || empty($phone)) {
            $_SESSION['error'] = "Vui lòng nhập đầy đủ thông tin bắt buộc!";
            header("Location: /user/edit/$id");
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Định dạng email không hợp lệ!";
            header("Location: /user/edit/$id");
            exit;
        }

        $userModel = $this->model('UserModel');
        $currentUser = $userModel->find($id);

        if ($currentUser['email'] !== $email) {
            if ($userModel->findByEmail($email)) {
                $_SESSION['error'] = "Email đã được sử dụng bởi tài khoản khác!";
                header("Location: /user/edit/$id");
                exit;
            }
        }

        $data = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'role' => $role,
            'status' => $status
        ];

        if (!empty($password)) {
            if (strlen($password) < 6) {
                $_SESSION['error'] = "Mật khẩu mới phải có ít nhất 6 ký tự!";
                header("Location: /user/edit/$id");
                exit;
            }
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        } else {
             $data['password'] = null; 
        }

        $userModel->update($id, $data);
        
        $_SESSION['success'] = "Cập nhật người dùng thành công!";
        header("Location: /user/edit/$id");
    }

    public function delete($id)
    {
        if (empty($_SESSION['user_id'])) {
            header("Location: /auth/login");
            exit;
        }

        $this->model('UserModel')->delete($id);
        $_SESSION['success'] = "Xóa người dùng thành công!";
        header("Location: /user");
    }

    public function addAddress($userId)
    {
        if (empty($_SESSION['user_id'])) {
            header("Location: /auth/login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $recipient_name = $_POST['recipient_name'] ?? '';
            $recipient_phone = $_POST['recipient_phone'] ?? '';
            $address_detail = $_POST['address_detail'] ?? '';
            $is_default = isset($_POST['is_default']) ? 1 : 0;

            if (empty($recipient_name) || empty($recipient_phone) || empty($address_detail)) {
                $_SESSION['error'] = "Vui lòng nhập đầy đủ thông tin địa chỉ!";
                header("Location: /user/edit/$userId#address");
                exit;
            }

            $data = [
                'user_id' => $userId,
                'recipient_name' => $recipient_name,
                'recipient_phone' => $recipient_phone,
                'address' => $address_detail,
                'is_default' => $is_default
            ];

            if ($data['is_default'] == 1) {
                 $this->model('UserAddressModel')->setDefault($userId, 0);
            }

            $this->model('UserAddressModel')->create($data);
            
            $_SESSION['success'] = "Đã thêm địa chỉ mới";
            header("Location: /user/edit/$userId#address");
        }
    }

    public function deleteAddress($id, $userId) 
    {
        if (empty($_SESSION['user_id'])) {
            header("Location: /auth/login");
            exit;
        }

        $this->model('UserAddressModel')->delete($id, $userId);
        $_SESSION['success'] = "Đã xóa địa chỉ";
        header("Location: /user/edit/$userId#address");
    }
}