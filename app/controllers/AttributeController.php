<?php
class AttributeController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            header("Location: /auth/login");
            exit;
        }

        $attributeModel = $this->model('AttributeModel');
        $data = $attributeModel->all();
        
        $this->view('product/attribute-variant/attribute_index', ['attributes' => $data]);
    }

    public function create()
    {
        $this->view('product/attribute-variant/attribute_create');
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /attribute");
            exit;
        }

        $name = trim($_POST['name'] ?? '');

        if (empty($name)) {
            $_SESSION['error'] = "Tên thuộc tính không được để trống!";
            $this->redirectBack();
            exit;
        }

        if (strlen($name) < 2 || strlen($name) > 50) {
            $_SESSION['error'] = "Tên thuộc tính phải từ 2 đến 50 ký tự!";
            $this->redirectBack();
            exit;
        }

        $attributeModel = $this->model('AttributeModel');

        if ($attributeModel->findByName($name)) {
            $_SESSION['error'] = "Thuộc tính '$name' đã tồn tại!";
            $this->redirectBack();
            exit;
        }

        $data = ['name' => $name];
        $attributeModel->create($data);

        $_SESSION['success'] = "Thêm thuộc tính thành công!";
        
        $this->redirectBack();
        
    }

    public function edit($id)
    {
        $attributeModel = $this->model('AttributeModel');
        $attribute = $attributeModel->find($id);

        if (!$attribute) {
            $_SESSION['error'] = "Thuộc tính không tồn tại!";
            header("Location: /attribute");
            exit;
        }

        $this->view('product/attribute-variant/attribute_edit', ['attribute' => $attribute]);
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /attribute");
            exit;
        }

        $name = trim($_POST['name'] ?? '');

        if (empty($name)) {
            $_SESSION['error'] = "Tên thuộc tính không được để trống!";
            $this->redirectBack();
            exit;
        }

        if (strlen($name) < 2 || strlen($name) > 50) {
            $_SESSION['error'] = "Tên thuộc tính phải từ 2 đến 50 ký tự!";
            $this->redirectBack();
            exit;
        }

        $attributeModel = $this->model('AttributeModel');
        $existingAttr = $attributeModel->findByName($name);

        if ($existingAttr && $existingAttr['id'] != $id) {
            $_SESSION['error'] = "Thuộc tính '$name' đã được sử dụng!";
            $this->redirectBack();
            exit;
        }

        $data = ['name' => $name];
        $attributeModel->update($id, $data);

        $_SESSION['success'] = "Cập nhật thuộc tính thành công!";
        $this->redirectBack();
    }

    public function delete($id)
    {
        $attributeModel = $this->model('AttributeModel');
        $attributeModel->delete($id);
        
        $_SESSION['success'] = "Xóa thuộc tính thành công!";
        $this->redirectBack();
    }

    private function redirectBack() {
        if (isset($_SERVER['HTTP_REFERER'])) {
            header("Location: " . $_SERVER['HTTP_REFERER']);
        } else {
            header("Location: /attribute");
        }
    }
}