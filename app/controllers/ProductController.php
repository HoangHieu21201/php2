<?php
class ProductController extends Controller
{

    public function index()
    {

        if (empty($_SESSION['user_id'])) {
            header("Location: /auth/login");
            exit;
        }

        $productModel = $this->model('ProductModel');
        $data = $productModel->all();
        $this->view('product/index', ['products' => $data]);
    }

    public function create()
    {
        $categories = $this->model('CategoryModel')->all();
        $brands = $this->model('BrandModel')->all();

        $this->view('product/create', [
            'categories' => $categories,
            'brands' => $brands
        ]);
    }

    public function store()
    {
        $imagePath = '';

        $categories = $this->model('CategoryModel')->all();
        $brands = $this->model('BrandModel')->all();

        if (empty($_POST['name']) || strlen($_POST['name']) < 3 || strlen($_POST['name']) > 255) {
            $mess = "Tên sản phẩm không được để trống, từ 3 đến 255 ký tự";
            $this->view('product/create', [
                'mess' => $mess,
                'categories' => $categories,
                'brands' => $brands
            ]);
            return;
        }

        if (!is_numeric($_POST['price']) || $_POST['price'] < 0) {
            $mess = "Giá bán không hợp lệ!";
            $this->view('product/create', [
                'mess' => $mess,
                'categories' => $categories,
                'brands' => $brands
            ]);
            return;
        }
        if ($_POST['price'] > 999999999) {
            $mess = "Giá bán quá lớn!";
            $this->view('product/create', [
                'mess' => $mess,
                'categories' => $categories,
                'brands' => $brands
            ]);
            return;
        }

        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $targetDir = 'image/product/';
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $fileName = time() . '_' . basename($_FILES['image']['name']);
            $targetFilePath = $targetDir . $fileName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                $imagePath = $targetFilePath;
            }
        }

        $data = [
            'name' => $_POST['name'],
            'price' => $_POST['price'],
            'image' => $imagePath,
            'description' => $_POST['description'],
            'short_description' => $_POST['short_description'],
            'category_id' => $_POST['category_id'],
            'brand_id' => !empty($_POST['brand_id']) ? $_POST['brand_id'] : null,
            'status' => $_POST['status']
        ];
        $mess = "Thêm sản phẩm thành công!";
        $_SESSION['success'] = $mess;
        $this->model('ProductModel')->create($data);
        header("Location: /product");
    }

    public function edit($id)
    {
        $product = $this->model('ProductModel')->find($id);
        $categories = $this->model('CategoryModel')->all();
        $brands = $this->model('BrandModel')->all();

        $this->view('product/edit', [
            'product' => $product,
            'categories' => $categories,
            'brands' => $brands
        ]);
    }

    public function update($id)
    {
        $productModel = $this->model('ProductModel');
        $currentProduct = $productModel->find($id);

        if (!$currentProduct) {
            header("Location: /product");
            exit;
        }

        $imagePath = $currentProduct['image'];

        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $targetDir = 'image/product/';
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $fileName = time() . '_' . basename($_FILES['image']['name']);
            $targetFilePath = $targetDir . $fileName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                if (!empty($imagePath) && file_exists($imagePath)) {
                    unlink($imagePath);
                }
                $imagePath = $targetFilePath;
            }
        }

        $data = [
            'name' => $_POST['name'],
            'price' => $_POST['price'],
            'image' => $imagePath,
            'description' => $_POST['description'],
            'short_description' => $_POST['short_description'],
            'category_id' => $_POST['category_id'],
            'brand_id' => !empty($_POST['brand_id']) ? $_POST['brand_id'] : null,
            'status' => $_POST['status']
        ];
        $mess = "Cập nhật sản phẩm thành công!";
        $_SESSION['success'] = $mess;
        $productModel->update($id, $data);
        header("Location: /product");
    }

    public function delete($id)
    {
        $mess = "Xóa sản phẩm thành công!";
        $_SESSION['success'] = $mess;
        $this->model('ProductModel')->delete($id);
        header("Location: /product");
    }
}
