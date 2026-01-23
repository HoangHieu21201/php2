<?php
class AuthController extends Controller {

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index() {
        $authModel = $this->model('UserModel');
        $data = $authModel->all();
        $this->view('auth/index', ['users' => $data]);
        header("Location: /auth/login");
    }

    public function register() {
        $this->view('auth/register'); 
    }

    public function storeRegister() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /auth/register");
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = $_POST['phone'] ?? '';
        $address = $_POST['address'] ?? '';
        $password = trim($_POST['password']) ?? '';
        $confirmPassword = trim($_POST['confirm_password']) ?? '';

        $errors = [];

        if (empty($name)) {
            $errors['name'] = 'Vui lòng nhập họ tên đầy đủ!';
        }

        if (empty($email)) {
            $errors['email'] = 'Vui lòng nhập email!';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không hợp lệ!';
        }

        if (empty($password)) {
            $errors['password'] = 'Vui lòng nhập mật khẩu!';
        } elseif (strlen($password) < 6) {
            $errors['password'] = 'Mật khẩu phải có ít nhất 6 ký tự!';
        }

        if ($password !== $confirmPassword) {
            $errors['confirm_password'] = 'Mật khẩu xác nhận không khớp!';
        }
        if (empty($_POST['terms'])) {
            $errors['terms'] = 'Bạn phải đồng ý với các điều khoản dịch vụ!';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST; 
            header("Location: /auth/register");
            exit;
        }

        $userModel = $this->model('UserModel');

        if ($userModel->findByEmail($email)) {
            $_SESSION['errors']['email'] = 'Email này đã được sử dụng!';
            $_SESSION['old'] = $_POST;
            header("Location: /auth/register");
            exit;
        }

        $data = [
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'phone' => $phone,           
            'address' => $address,         
            'role' => 0,    
            'status' => 1            
        ];

        if ($userModel->create($data)) {
            $_SESSION['success'] = 'Đăng ký thành công! Vui lòng đăng nhập.';
            unset($_SESSION['errors']);
            unset($_SESSION['old']);
            header("Location: /auth/login");
            $_POST = [];
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại.';
            header("Location: /auth/register");
        }
    }

    public function login() {
        $this->view('auth/login');
    }
    
    public function handleLogin()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $userModel = $this->model('UserModel');
        $user = $userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            header("Location: /");
        } else {
            $_SESSION['error'] = 'Email hoặc mật khẩu không đúng!';
            header("Location: /auth/login");
        }
    }

    public function logout()
    {
        session_destroy();
        header("Location: /auth/login");
    }
}
