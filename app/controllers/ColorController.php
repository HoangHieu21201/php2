<?php
class ColorController extends Controller
{

    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            header("Location: /auth/login");
            exit;
        }

        $ColorModel = $this->model('ColorModel');
        $data = $ColorModel->all();
        $this->view('product/varitant/color/index', ['colors' => $data]);
    }

    public function create()
    {
        $this->view('product/varitant/color/create');
    }

    public function store()
    {
        $data = [
            'name' => $_POST['name']
        ];
        $mess = "Thêm màu sắc thành công!";
        $_SESSION['success'] = $mess;
        $this->model('ColorModel')->create($data);
        header("Location: /product/varitant/color/index");
    }

    public function edit($id)
    {
        $color = $this->model('ColorModel')->find($id);
        $this->view('product/varitant/color/edit', ['color' => $color]);
    }

    public function update($id)
    {
        $data = [
            'name' => $_POST['name']
        ];
        $mess = "Cập nhật màu sắc thành công!";
        $_SESSION['success'] = $mess;
        $this->model('ColorModel')->update($id, $data);
        header("Location:/color");
    }

    public function delete($id)
    {
        $mess = "Xóa màu sắc thành công!";
        $_SESSION['success'] = $mess;
        $this->model('ColorModel')->delete($id);
        header("Location:/color");
    }
}
