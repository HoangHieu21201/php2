<?php
class ProductController extends Controller {

    // 1. Danh sách
    public function index() {
        $productModel = $this->model('ProductModel');
        $data = $productModel->all();
        $this->view('product/index', ['products' => $data]);
    }

    // 2. Form Thêm
    public function create() {
        $categories = $this->model('CategoryModel')->all();

        $this->view('product/create', [
            'categories' => $categories,
            'brands' => [] 
        ]);
    }

    // 3. Xử lý Lưu
    public function store() {
        $imagePath = ''; 
        // validate dữ liệu hợp lệ không để trống khống số âm
        if (empty($_POST['name']) || strlen($_POST['name']) < 3 || strlen($_POST['name']) > 255) {
            $mess = "Tên sản phẩm không được để trống, khống ít hơn 3 hoặc nhiều hơn 255 ký tự";
            $categories = $this->model('CategoryModel')->all();
            $this->view('product/create', [
                'mess' => $mess,
                'categories' => $categories,
                'brands' => [] 
            ]);
            return;
        }
        // validate số lượng giá bán
        if (!is_numeric($_POST['price']) || $_POST['price'] < 0) {
            $mess = "Giá bán không hợp lệ!";
            $categories = $this->model('CategoryModel')->all();
            $this->view('product/create', [
                'mess' => $mess,
                'categories' => $categories,
                'brands' => [] 
            ]);
            return;
        }
        // giá không quá lớn 
        if ($_POST['price'] > 999999999) {
            $mess = "Giá bán không hợp lệ!";
            $categories = $this->model('CategoryModel')->all();
            $this->view('product/create', [
                'mess' => $mess,
                'categories' => $categories,
                'brands' => [] 
            ]);
            return;
        }
        // giá bán không được lướn hơn giá gốc 
        if (!empty($_POST['sale_price']) && $_POST['sale_price'] > $_POST['price']) {
            $mess = "Giá bán không được lớn hơn giá gốc!";
            $categories = $this->model('CategoryModel')->all();
            $this->view('product/create', [
                'mess' => $mess,
                'categories' => $categories,
                'brands' => [] 
            ]);
            return;
        }

        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            
            $targetDir = 'image/product/';
            
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            // Tạo tên file
            $fileName = time() . '_' . basename($_FILES['image']['name']);
            $targetFilePath = $targetDir . $fileName;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                $imagePath = $targetFilePath; 
            }
        }

        $data = [
            'name' => $_POST['name'],
            'price' => $_POST['price'],
            'sale_price' => $_POST['sale_price'] ?? 0,
            'quantity' => $_POST['quantity'] ?? 0,
            'image' => $imagePath,
            'description' => $_POST['description'],
            'short_description' => $_POST['short_description'],
            'category_id' => $_POST['category_id'],
            'brand_id' => !empty($_POST['brand_id']) ? $_POST['brand_id'] : null,
            'status' => $_POST['status']
        ];

        $this->model('ProductModel')->create($data);
        header("Location: /product");
    }

    // 4. Form Sửa
    public function edit($id) {
        $product = $this->model('ProductModel')->find($id);
        $categories = $this->model('CategoryModel')->all();

        $this->view('product/edit', [
            'product' => $product,
            'categories' => $categories,
            'brands' => [] 
        ]);
    }

    // 5. Xử lý Cập nhật
    public function update($id) {
        $productModel = $this->model('ProductModel');
        
        $currentProduct = $productModel->find($id);
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
            'sale_price' => $_POST['sale_price'] ?? 0,
            'quantity' => $_POST['quantity'] ?? 0,
            'image' => $imagePath,
            'description' => $_POST['description'],
            'short_description' => $_POST['short_description'],
            'category_id' => $_POST['category_id'],
            'brand_id' => !empty($_POST['brand_id']) ? $_POST['brand_id'] : null,
            'status' => $_POST['status']
        ];

        $productModel->update($id, $data);
        header("Location: /product");
    }

    public function delete($id) {
        $this->model('ProductModel')->delete($id);
        header("Location: /product");
    }
}