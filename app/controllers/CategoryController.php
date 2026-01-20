<?php
class CategoryController extends Controller
{

    public function index()
    {
        $categoryModel = $this->model('CategoryModel');
        $data = $categoryModel->all();
        $title = "Quản lý danh mục";
        $this->view('category/index', ['title' => $title, 'categories' => $data]);
    }

    public function create()
    {
        $this->view('category/create');
    }

    public function store()
    {
        if (empty($_POST['name']) || strlen($_POST['name']) < 3 || strlen($_POST['name']) > 255) {
            $mess = "Tên danh mục không được để trống, khống ít hơn 3 hoặc nhiều hơn 255 ký tự";
            $this->view('category/create', ['mess' => $mess]);
            return;
        }

        $categoryModel = $this->model('CategoryModel');

        $data = [
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'status' => $_POST['status']
        ];

        $categoryModel->create($data);

        header("Location: /category");
    }

    public function edit($id)
    {

        $categoryModel = $this->model('CategoryModel');
        $category = $categoryModel->find($id);

        $this->view('category/edit', ['category' => $category]);
    }

    public function update($id)
    {
        if (empty($_POST['name']) || strlen($_POST['name']) < 3 || strlen($_POST['name']) > 255) {
            $mess = "Tên danh mục không hợp lệ!";
            $categoryModel = $this->model('CategoryModel');
            $category = $categoryModel->find($id);
            $this->view('category/edit', ['category' => $category, 'mess' => $mess]);
            return;
        }

        $categoryModel = $this->model('CategoryModel');

        $data = [
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'status' => $_POST['status']
        ];

        $categoryModel->update($id, $data);

        header("Location: /category");
    }

    public function delete($id)
    {
        $categoryModel = $this->model('CategoryModel');
        $categoryModel->delete($id);

        header("Location: /category");
    }
}
