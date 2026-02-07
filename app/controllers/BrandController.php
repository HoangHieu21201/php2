<?php
class BrandController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            header("Location: /auth/login");
            exit;
        }
        $brandModel = $this->model('BrandModel');
        $data = $brandModel->all();
        $this->view('brand/index', ['brands' => $data]);
    }

    public function create()
    {
        $this->view('brand/create');
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /brand/create");
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $status = $_POST['status'] ?? 0;

        if (empty($name)) {
            $_SESSION['error'] = "Tên thương hiệu không được để trống!";
            header("Location: /brand/create");
            exit;
        }

        if (strlen($name) > 255) {
            $_SESSION['error'] = "Tên thương hiệu không được vượt quá 255 ký tự!";
            header("Location: /brand/create");
            exit;
        }

        $brandModel = $this->model('BrandModel');
        
        if ($brandModel->findByName($name)) {
            $_SESSION['error'] = "Thương hiệu '$name' đã tồn tại!";
            header("Location: /brand/create");
            exit;
        }

        $imagePath = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
            $fileType = mime_content_type($_FILES['image']['tmp_name']);
            $fileSize = $_FILES['image']['size'];

            if (!in_array($fileType, $allowedTypes)) {
                $_SESSION['error'] = "Chỉ chấp nhận file ảnh (JPG, PNG, WEBP)!";
                header("Location: /brand/create");
                exit;
            }

            // Kiểm tra dung lượng (VD: 2MB)
            if ($fileSize > 2 * 1024 * 1024) {
                $_SESSION['error'] = "File ảnh quá lớn (tối đa 2MB)!";
                header("Location: /brand/create");
                exit;
            }

            $targetDir = 'image/brand/';
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $fileName = time() . '_' . basename($_FILES['image']['name']);
            $targetFilePath = $targetDir . $fileName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                $imagePath = $targetFilePath;
            }
        }

        $slug = !empty($_POST['slug']) ? $this->vn_to_str($_POST['slug']) : $this->vn_to_str($name);

        $data = [
            'name' => $name,
            'slug' => $slug,
            'image' => $imagePath,
            'status' => $status
        ];

        $brandModel->create($data);
        $_SESSION['success'] = "Thêm thương hiệu thành công!";
        header("Location: /brand");
    }

    public function edit($id)
    {
        $brand = $this->model('BrandModel')->find($id);
        if (!$brand) {
            header("Location: /brand");
            exit;
        }
        $this->view('brand/edit', ['brand' => $brand]);
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /brand/edit/$id");
            exit;
        }

        $brandModel = $this->model('BrandModel');
        $currentBrand = $brandModel->find($id);

        if (!$currentBrand) {
            header("Location: /brand");
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $status = $_POST['status'] ?? 0;

        // 1. Validate Tên
        if (empty($name)) {
            $_SESSION['error'] = "Tên thương hiệu không được để trống!";
            header("Location: /brand/edit/$id");
            exit;
        }

        // 2. Validate Trùng tên (trừ chính nó)
        $existingBrand = $brandModel->findByName($name);
        if ($existingBrand && $existingBrand['id'] != $id) {
            $_SESSION['error'] = "Thương hiệu '$name' đã được sử dụng!";
            header("Location: /brand/edit/$id");
            exit;
        }

        // 3. Validate Ảnh
        $imagePath = $currentBrand['image'];
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
            $fileType = mime_content_type($_FILES['image']['tmp_name']);
            
            if (!in_array($fileType, $allowedTypes)) {
                $_SESSION['error'] = "Chỉ chấp nhận file ảnh (JPG, PNG, WEBP)!";
                header("Location: /brand/edit/$id");
                exit;
            }

            $targetDir = 'image/brand/';
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

        $slug = !empty($_POST['slug']) ? $this->vn_to_str($_POST['slug']) : $this->vn_to_str($name);

        $data = [
            'name' => $name,
            'slug' => $slug,
            'image' => $imagePath,
            'status' => $status
        ];

        $brandModel->update($id, $data);
        $_SESSION['success'] = "Cập nhật thương hiệu thành công!";
        header("Location: /brand");
    }

    public function delete($id)
    {
        $brandModel = $this->model('BrandModel');
        $brandModel->delete($id);
        $_SESSION['success'] = "Xóa thương hiệu thành công!";
        header("Location: /brand"); 
    }

    private function vn_to_str($str)
    {
        $unicode = array(
            'a' => 'á|à|ả|ã|ạ|ă|ắ|ằ|ẳ|ẵ|ặ|â|ầ|ấ|ậ|ẩ|ẫ',
            'd' => 'đ',
            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i' => 'í|ì|ỉ|ĩ|ị',
            'o' => 'ó|ò|ỏ|õ|ọ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ',
            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
            'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ằ|Ẳ|Ẵ|Ặ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'D' => 'Đ',
            'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'I' => 'Í|Ì|Ỉ|Ĩ|Ị',
            'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ở|Ỡ|Ợ',
            'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
        );

        $str = trim($str);
        foreach ($unicode as $nonUnicode => $uni) {
            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
        }
        $str = str_replace(' ', '-', $str);
        $str = preg_replace('/[^a-zA-Z0-9\-\_]/', '', $str);
        return strtolower($str);
    }
}