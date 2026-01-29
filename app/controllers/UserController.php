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
        $data = $userModel->all();
        $this->view('user/index', ['users' => $data]);
    }

    public function create()
    {
        $this->view('user/create');
    }

    public function store()
    {
        if (empty($_POST['email']) || empty($_POST['password'])) {
            header("Location: /user/create");
            exit;
        }

        $data = [
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'phone' => $_POST['phone'],
            'address' => $_POST['address'],
            'role' => $_POST['role'],
            'status' => $_POST['status']
        ];
        $mess = "Thêm người dùng thành công!";
        $_SESSION['success'] = $mess;
        $this->model('UserModel')->create($data);
        header("Location: /user");
    }

    public function edit($id)
    {
        $user = $this->model('UserModel')->find($id);
        $this->view('user/edit', ['user' => $user]);
    }

    public function update($id)
    {
        $data = [
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'phone' => $_POST['phone'],
            'address' => $_POST['address'],
            'role' => $_POST['role'],
            'status' => $_POST['status']
        ];

        if (!empty($_POST['password'])) {
            $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        } else {
            $data['password'] = null;
        }
        $mess = "Cập nhật người dùng thành công!";
        $_SESSION['success'] = $mess;
        $this->model('UserModel')->update($id, $data);
        header("Location: /user");
    }

    public function delete($id)
    {
        $mess = "Xóa người dùng thành công!";
        $_SESSION['success'] = $mess;
        $this->model('UserModel')->delete($id);
        header("Location: /user");
    }
}
