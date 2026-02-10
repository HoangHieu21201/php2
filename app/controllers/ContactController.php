<?php
class ContactController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            header("Location: /auth/login");
            exit;
        }

        $contactModel = $this->model('ContactModel');
        $contacts = $contactModel->all();
        $this->view('contact/index', ['contacts' => $contacts]);
    }

    public function create()
    {
        $this->view('contact/create');
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /contact/create");
            exit;
        }

        $full_name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');
        $created_at = date('Y-m-d H:i:s');

        $errors = [];

        if (empty($full_name)) {
            $errors['name'] = 'Họ tên không được để trống.';
        }

        if (empty($email)) {
            $errors['email'] = 'Email không được để trống.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không hợp lệ.';
        }

        if (empty($subject)) {
            $errors['subject'] = 'Tiêu đề không được để trống.';
        }

        if (empty($message)) {
            $errors['message'] = 'Nội dung không được để trống.';
        }

        if (!empty($errors)) {
            $this->view('contact/create', [
                'errors' => $errors,
                'old' => $_POST
            ]);
            return;
        }

        $data = [
            'full_name' => $full_name,
            'email' => $email,
            'phone' => $phone,
            'subject' => $subject,
            'message' => $message,
            'created_at' => $created_at
        ];

        $mess = "Gửi liên hệ thành công!";
        $_SESSION['success'] = $mess;
        $contactModel = $this->model('ContactModel');
        $contactModel->create($data);
        header("Location: /contact");
        exit;
    }

    public function edit($id)
    {
        $contactModel = $this->model('ContactModel');
        $contact = $contactModel->find($id);

        if (!$contact) {
            header("Location: /contact");
            exit;
        }

        $this->view('contact/edit', ['contact' => $contact]);
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /contact/edit/$id");
            exit;
        }

        $contactModel = $this->model('ContactModel');
        $currentContact = $contactModel->find($id);
        
        if (!$currentContact) {
            header("Location: /contact");
            exit;
        }

        $full_name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');

        $errors = [];

        if (empty($full_name)) {
            $errors['name'] = 'Họ tên không được để trống.';
        }

        if (empty($email)) {
            $errors['email'] = 'Email không được để trống.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không hợp lệ.';
        }

        if (empty($subject)) {
            $errors['subject'] = 'Tiêu đề không được để trống.';
        }

        if (empty($message)) {
            $errors['message'] = 'Nội dung không được để trống.';
        }

        if (!empty($errors)) {
            $this->view('contact/edit', [
                'contact' => array_merge($currentContact, $_POST, ['id' => $id]),
                'errors' => $errors
            ]);
            return;
        }

        $data = [
            'full_name' => $full_name,
            'email' => $email,
            'phone' => $phone,
            'subject' => $subject,
            'message' => $message
        ];

        $mess = "Cập nhật liên hệ thành công!";
        $_SESSION['success'] = $mess;
        $contactModel->update($id, $data);

        header("Location: /contact");
        exit;
    }

    public function delete($id)
    {
        $mess = "Xóa liên hệ thành công!";
        $_SESSION['success'] = $mess;
        $contactModel = $this->model('ContactModel');
        $contactModel->delete($id);
        header("Location: /contact");
    }
}