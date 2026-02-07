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
        $variantModel = $this->model('ProductVariantModel');
        
        $products = $productModel->all();
        $keyword = $_GET['keyword'] ?? '';

        if (!empty($keyword)) { 
            $products = array_filter($products, function($p) use ($keyword) {
                return stripos($p['name'], $keyword) !== false;
            });
        }

        foreach ($products as &$p) {
            $variants = [];
            if (method_exists($variantModel, 'getByProductId')) {
                $variants = $variantModel->getByProductId($p['id']);
            } else {
                $allVariants = $variantModel->all();
                $variants = array_filter($allVariants, function($v) use ($p) {
                    return $v['product_id'] == $p['id'];
                });
            }

            $p['variant_count'] = count($variants);
            
            $activeCount = 0;
            foreach ($variants as $v) {
                if (isset($v['status']) && $v['status'] == 1) {
                    $activeCount++;
                }
            }
            $p['active_variant_count'] = $activeCount;
        }

        $this->view('product/index', ['products' => $products, 'filters' => ['keyword' => $keyword]]);
    }

    public function quickUpdateStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? null;
        $status = $input['status'] ?? null;

        if (!$id || !isset($status)) {
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
            exit;
        }

        $productModel = $this->model('ProductModel');
        $variantModel = $this->model('ProductVariantModel');

        if ($status == 1) {
            $variants = [];
            if (method_exists($variantModel, 'getByProductId')) {
                $variants = $variantModel->getByProductId($id);
            } else {
                $all = $variantModel->all();
                $variants = array_filter($all, function($v) use ($id) { return $v['product_id'] == $id; });
            }

            $hasActiveVariant = false;
            foreach ($variants as $v) {
                if ($v['status'] == 1) {
                    $hasActiveVariant = true;
                    break;
                }
            }

            if (!$hasActiveVariant) {
                echo json_encode([
                    'success' => false, 
                    'message' => 'Sản phẩm chưa có biến thể hoạt động nào! Vui lòng cấu hình biến thể trước khi hiển thị.'
                ]);
                exit;
            }
        }

        if ($productModel->updateStatus($id, $status)) {
            echo json_encode(['success' => true, 'message' => 'Cập nhật trạng thái thành công!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Lỗi Database']);
        }
        exit;
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
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /product/create");
            exit;
        }

        $imagePath = '';
        $name = trim($_POST['name'] ?? '');

        if (empty($name) || strlen($name) < 3 || strlen($name) > 255) {
            $_SESSION['error'] = "Tên sản phẩm không được để trống, từ 3 đến 255 ký tự.";
            $_SESSION['old'] = $_POST;
            header("Location: /product/create");
            exit;
        }

        if (empty($_POST['category_id'])) {
            $_SESSION['error'] = "Vui lòng chọn danh mục sản phẩm.";
            $_SESSION['old'] = $_POST;
            header("Location: /product/create");
            exit;
        }

        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $targetDir = 'image/product/';
            if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);

            $fileName = time() . '_' . basename($_FILES['image']['name']);
            $targetFilePath = $targetDir . $fileName;

            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
            $fileType = mime_content_type($_FILES['image']['tmp_name']);
            
            if (in_array($fileType, $allowedTypes)) {
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                    $imagePath = $targetFilePath;
                }
            } else {
                $_SESSION['error'] = "Định dạng ảnh không hợp lệ.";
                header("Location: /product/create");
                exit;
            }
        }

        $data = [
            'name' => $name,
            'image' => $imagePath,
            'short_description' => $_POST['short_description'] ?? '',
            'category_id' => $_POST['category_id'],
            'brand_id' => !empty($_POST['brand_id']) ? $_POST['brand_id'] : null,
            'status' => 0 
        ];

        $this->model('ProductModel')->create($data);
        
        if(isset($_SESSION['old'])) unset($_SESSION['old']);

        $_SESSION['success'] = "Thêm sản phẩm thành công! Hãy cấu hình biến thể.";
        header("Location: /product");
        exit;
    }

    public function edit($id)
    {
        $product = $this->model('ProductModel')->find($id);
        
        if (!$product) {
            $_SESSION['error'] = "Sản phẩm không tồn tại.";
            header("Location: /product");
            exit;
        }

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
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /product");
            exit;
        }

        $productModel = $this->model('ProductModel');
        $currentProduct = $productModel->find($id);

        if (!$currentProduct) {
            $_SESSION['error'] = "Sản phẩm không tồn tại.";
            header("Location: /product");
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        if (empty($name)) {
            $_SESSION['error'] = "Tên sản phẩm không được để trống.";
            header("Location: /product/edit/$id");
            exit;
        }

        $imagePath = $currentProduct['image'];
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $targetDir = 'image/product/';
            $fileName = time() . '_' . basename($_FILES['image']['name']);
            $targetFilePath = $targetDir . $fileName;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                if (!empty($imagePath) && file_exists($imagePath)) unlink($imagePath);
                $imagePath = $targetFilePath;
            }
        }

        $data = [
            'name' => $name,
            'image' => $imagePath,
            'short_description' => $_POST['short_description'] ?? '',
            'category_id' => $_POST['category_id'],
            'brand_id' => !empty($_POST['brand_id']) ? $_POST['brand_id'] : null,
            'status' => $currentProduct['status'] 
        ];

        $productModel->update($id, $data);
        $_SESSION['success'] = "Cập nhật sản phẩm thành công!";
        header("Location: /product");
        exit;
    }

    public function delete($id)
    {
        $this->model('ProductModel')->delete($id);
        $_SESSION['success'] = "Xóa sản phẩm thành công!";
        header("Location: /product");
    }
}