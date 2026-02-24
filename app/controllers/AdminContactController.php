<?php
class AdminContactController extends Controller
{
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if ($email === 'admin@gmail.com' && $password === '123456') {
                $_SESSION['admin_id'] = 1;
                $_SESSION['admin_name'] = 'Administrator';
                header("Location: /admin/contacts");
                exit;
            } else {
                $error = "Email hoặc mật khẩu không đúng";
                $this->view('admin/auth/login', ['error' => $error]);
            }
        } else {
            if (!empty($_SESSION['admin_id'])) {
                header("Location: /admin/contacts");
                exit;
            }
            $this->view('admin/auth/login');
        }
    }

    public function index()
    {
        if (empty($_SESSION['admin_id'])) {
            header("Location: /admin/login");
            exit;
        }

        $contactModel = $this->model('ContactModel');
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        
        $search = isset($_GET['q']) ? trim($_GET['q']) : '';
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $totalRecords = $contactModel->countAll($search);
        $totalPages = ceil($totalRecords / $limit);
        
        $contacts = $contactModel->getAll($limit, $offset, $search);

        $this->view('admin/contact/index', [
            'contacts' => $contacts,
            'totalRecords' => $totalRecords,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'search' => $search
        ]);
    }

    public function delete()
    {
        if (empty($_SESSION['admin_id'])) {
            header("Location: /admin/login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 0;
            $page = $_GET['page'] ?? 1;
            $search = $_GET['q'] ?? '';

            if ($id > 0) {
                $contactModel = $this->model('ContactModel');
                $contactModel->delete($id);
                $_SESSION['success'] = "Đã xóa liên hệ thành công.";
            }
            
            $redirectUrl = "/admin/contacts?page=$page";
            if (!empty($search)) {
                $redirectUrl .= "&q=" . urlencode($search);
            }
            
            header("Location: $redirectUrl");
            exit;
        }
        
        header("Location: /admin/contacts");
    }

    public function logout() {
        unset($_SESSION['admin_id']);
        unset($_SESSION['admin_name']);
        header("Location: /admin/login");
    }
}