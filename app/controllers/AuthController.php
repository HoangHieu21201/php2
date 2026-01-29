<?php

if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
}

use Google\Client;
use Google\Service\Oauth2;

class AuthController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index()
    {
        $authModel = $this->model('UserModel');
        $data = $authModel->all();
        $this->view('auth/index', ['users' => $data]);
        header("Location: /auth/login");
    }

    public function register()
    {
        $this->view('auth/register');
    }

    public function storeRegister()
    {
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

    public function login()
    {
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
            $_SESSION['user_name'] = $user['name'];
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

    public function forgotPassword()
    {
        $this->view('auth/forgot-password');
    }

    public function sendResetLink()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /auth/forgotPassword");
            exit;
        }

        $email = trim($_POST['email'] ?? '');

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Vui lòng nhập email hợp lệ!';
            header("Location: /auth/forgotPassword");
            exit;
        }

        $userModel = $this->model('UserModel');
        $user = $userModel->findByEmail($email);

        if (!$user) {
            $_SESSION['error'] = 'Email không tồn tại trong hệ thống!';
            header("Location: /auth/forgotPassword");
            exit;
        }

        $token = bin2hex(random_bytes(32));

        $resetModel = $this->model('PasswordReset');
        $resetModel->deleteByEmail($email);

        $resetData = [
            'email' => $email,
            'token' => $token,
            'created_at' => date('Y-m-d H:i:s')
        ];
        $resetModel->create($resetData);

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $domainName = $_SERVER['HTTP_HOST'];

        $link = $protocol . $domainName . "/auth/resetPassword?token=" . $token . "&email=" . urlencode($email);

        $subject = "Yêu cầu đặt lại mật khẩu";
        $body = "
            <h3>Yêu cầu đặt lại mật khẩu</h3>
            <p>Chào bạn,</p>
            <p>Chúng tôi nhận được yêu cầu đặt lại mật khẩu cho tài khoản liên kết với email này.</p>
            <p>Vui lòng nhấn vào link sau để đặt lại mật khẩu:</p>
            <p><a href='$link' style='padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;'>Đặt lại mật khẩu</a></p>
            <p>Hoặc copy đường dẫn này: $link</p>
            <p>Link này có hiệu lực trong 30 phút.</p>
        ";

        require_once __DIR__ . '/../core/MailService.php';

        if (MailService::send($email, $subject, $body)) {
            $_SESSION['success'] = 'Link đặt lại mật khẩu đã được gửi vào email của bạn.';
            header("Location: /auth/forgotPassword");
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi gửi email.';
            header("Location: /auth/forgotPassword");
        }
    }

    public function resetPassword()
    {
        $token = $_GET['token'] ?? '';
        $email = $_GET['email'] ?? '';

        if (empty($token) || empty($email)) {
            $_SESSION['error'] = 'Đường dẫn không hợp lệ!';
            header("Location: /auth/login");
            exit;
        }

        $resetModel = $this->model('PasswordReset');
        $resetInfo = $resetModel->findByEmailAndToken($email, $token);

        if (!$resetInfo) {
            $_SESSION['error'] = 'Token không hợp lệ hoặc đã hết hạn!';
            header("Location: /auth/login");
            exit;
        }

        $this->view('auth/reset-password', ['token' => $token, 'email' => $email]);
    }

    public function storeResetPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /auth/login");
            exit;
        }

        $token = $_POST['token'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $redirectBack = "/auth/resetPassword?token=$token&email=$email";

        if (empty($password) || strlen($password) < 6) {
            $_SESSION['error'] = 'Mật khẩu mới phải có ít nhất 6 ký tự!';
            header("Location: " . $redirectBack);
            exit;
        }

        if ($password !== $confirmPassword) {
            $_SESSION['error'] = 'Xác nhận mật khẩu không khớp!';
            header("Location: " . $redirectBack);
            exit;
        }

        $resetModel = $this->model('PasswordReset');
        $resetInfo = $resetModel->findByEmailAndToken($email, $token);

        if (!$resetInfo) {
            $_SESSION['error'] = 'Yêu cầu không hợp lệ!';
            header("Location: /auth/login");
            exit;
        }

        $createdAt = strtotime($resetInfo['created_at']);
        if (time() - $createdAt > 1800) {
            $_SESSION['error'] = 'Link đặt lại mật khẩu đã hết hạn.';
            header("Location: /auth/forgotPassword");
            exit;
        }

        $userModel = $this->model('UserModel');
        $user = $userModel->findByEmail($email);

        if ($user) {
            $updateData = [
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'phone' => $user['phone'],
                'address' => $user['address'],
                'role' => $user['role'],
                'status' => $user['status']
            ];

            if ($userModel->update($user['id'], $updateData)) {
                $resetModel->deleteByEmail($email);
                $_SESSION['success'] = 'Đổi mật khẩu thành công! Vui lòng đăng nhập.';
                header("Location: /auth/login");
            } else {
                $_SESSION['error'] = 'Lỗi cập nhật mật khẩu.';
                header("Location: " . $redirectBack);
            }
        }
    }


    private function getGoogleClient()
    {
        $client = new Client();
        $client->setClientId($_ENV['SETCLIENTID']);
        $client->setClientSecret($_ENV['SETCLIENTSECRET']);

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $domainName = $_SERVER['HTTP_HOST'];
        $client->setRedirectUri($protocol . $domainName . '/auth/googleCallback');

        $client->addScope('email');
        $client->addScope('profile');

        return $client;
    }

    public function loginWithGoogle()
    {
        $client = $this->getGoogleClient();
        $authUrl = $client->createAuthUrl();
        header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
        exit;
    }

    public function googleCallback()
    {
        $client = $this->getGoogleClient();

        if (isset($_GET['code'])) {
            $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

            if (!isset($token['error'])) {
                $client->setAccessToken($token['access_token']);

                $google_oauth = new Oauth2($client);
                $google_account_info = $google_oauth->userinfo->get();

                $email = $google_account_info->email;
                $name = $google_account_info->name;
                $googleId = $google_account_info->id;

                $userModel = $this->model('UserModel');

                $user = $userModel->findByGoogleId($googleId);

                if (!$user) {
                    $user = $userModel->findByEmail($email);
                    if ($user) {
                        $userModel->updateGoogleId($user['id'], $googleId);
                    }
                }

                if (!$user) {
                    $randomPassword = bin2hex(random_bytes(8));
                    $newUser = [
                        'name' => $name,
                        'email' => $email,
                        'password' => password_hash($randomPassword, PASSWORD_DEFAULT),
                        'role' => 0,
                        'status' => 1,
                        'google_id' => $googleId
                    ];
                    $userModel->create($newUser);
                    $user = $userModel->findByEmail($email);
                }

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_name'] = $user['name'];

                header("Location: /");
                exit;
            } else {
                $_SESSION['error'] = 'Lỗi xác thực Google!';
                header("Location: /auth/login");
                exit;
            }
        } else {
            header("Location: /auth/login");
            exit;
        }
    }
}
