<?php
class SizeController extends Controller
{

    public function index()
    {

        if (empty($_SESSION['user_id'])) {
            header("Location: /auth/login");
            exit;
        }

        $userModel = $this->model('SizeModel');
        $data = $userModel->all();
        $this->view('product/varitant/size/index', ['sizes' => $data]);
    }

    public function create()
    {
        $this->view('product/varitant/size/create');
    }

    public function store()
    {
        $data = [
            'name' => $_POST['name']
        ];
        $mess = "Thêm kích thước thành công!";
        $_SESSION['success'] = $mess;
        $this->model('SizeModel')->create($data);
        header("Location: /size");
    }

    public function edit($id)
    {
        $size = $this->model('SizeModel')->find($id);
        $this->view('product/varitant/size/edit', ['size' => $size]);
    }

    public function update($id)
    {
        $data = [
            'name' => $_POST['name']
        ];
        $mess = "Cập nhật kích thước thành công!";
        $_SESSION['success'] = $mess;
        $this->model('SizeModel')->update($id, $data);
        header("Location:/size");
    }

    public function delete($id)
    {
        $mess = "Xóa kích thước thành công!";
        $_SESSION['success'] = $mess;
        $this->model('SizeModel')->delete($id);
        header("Location:/size");
    }
}
