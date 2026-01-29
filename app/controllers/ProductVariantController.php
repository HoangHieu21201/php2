<?php
class ProductVariantController extends Controller
{
    public function index()
    {

        if (empty($_SESSION['user_id'])) {
            header("Location: /auth/login");
            exit;
        }

        $variantModel = $this->model('ProductVariantModel');

        $filters = [
            'keyword'    => $_GET['keyword'] ?? '',
            'product_id' => $_GET['product_id'] ?? '',
            'color_id'   => $_GET['color_id'] ?? '',
            'size_id'    => $_GET['size_id'] ?? ''
        ];

        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;

        if (method_exists($variantModel, 'getFilteredVariants')) {
            $variants = $variantModel->getFilteredVariants($filters, $page, $limit);
            $totalRecords = $variantModel->countFilteredVariants($filters);
            $totalPages = ceil($totalRecords / $limit);
        } else {
            $variants = $variantModel->all();
            $totalPages = 1;
        }

        $products = $this->model('ProductModel')->all();
        $colors = $this->model('ColorModel')->all();
        $sizes = $this->model('SizeModel')->all();

        $this->view('product/varitant/product_variant/index', [
            'variants'   => $variants,
            'products'   => $products,
            'colors'     => $colors,
            'sizes'      => $sizes,
            'filters'    => $filters,
            'page'       => $page,
            'totalPages' => $totalPages
        ]);
    }

    public function create()
    {
        $products = $this->model('ProductModel')->all();
        $colors = $this->model('ColorModel')->all();
        $sizes = $this->model('SizeModel')->all();

        $this->view('product/varitant/product_variant/create', [
            'products' => $products,
            'colors' => $colors,
            'sizes' => $sizes
        ]);
    }

    public function store()
    {
        $products = $this->model('ProductModel')->all();
        $colors = $this->model('ColorModel')->all();
        $sizes = $this->model('SizeModel')->all();

        $errors = [];
        $old = $_POST;

        if (empty($_POST['product_id'])) {
            $errors['product_id'] = "Vui lòng chọn Sản phẩm gốc.";
        }

        $price = $_POST['price'] ?? '';
        if ($price === '' && !empty($_POST['product_id'])) {
            foreach ($products as $p) {
                if ($p['id'] == $_POST['product_id']) {
                    $price = $p['price'];
                    $old['price'] = $price;
                    break;
                }
            }
        }

        if ($price === '') {
            $errors['price'] = "Vui lòng nhập giá bán.";
        } elseif (!is_numeric($price) || $price < 0) {
            $errors['price'] = "Giá bán phải là số dương.";
        } elseif ($price > 999999999) {
            $errors['price'] = "Giá bán quá lớn.";
        }

        $sale_price = $_POST['sale_price'] ?? 0;
        if ($sale_price !== '' && $sale_price > 0) {
            if (!is_numeric($sale_price) || $sale_price < 0) {
                $errors['sale_price'] = "Giá khuyến mãi phải là số dương.";
            } elseif ((float)$sale_price >= (float)$price) {
                $errors['sale_price'] = "Giá khuyến mãi phải nhỏ hơn giá gốc.";
            }
        }

        $quantity = $_POST['quantity'] ?? '';
        if ($quantity === '') {
            $errors['quantity'] = "Vui lòng nhập số lượng kho.";
        } elseif (!is_numeric($quantity) || $quantity < 0) {
            $errors['quantity'] = "Số lượng phải là số nguyên dương.";
        }

        $skuInput = trim($_POST['sku'] ?? '');

        if (empty($skuInput)) {
            if (empty($errors['product_id'])) {
                $pName = '';
                $cName = '';
                $sName = '';

                foreach ($products as $p) {
                    if ($p['id'] == $_POST['product_id']) {
                        $pName = $p['name'];
                        break;
                    }
                }
                if (!empty($_POST['color_id'])) {
                    foreach ($colors as $c) {
                        if ($c['id'] == $_POST['color_id']) {
                            $cName = $c['name'];
                            break;
                        }
                    }
                }
                if (!empty($_POST['size_id'])) {
                    foreach ($sizes as $s) {
                        if ($s['id'] == $_POST['size_id']) {
                            $sName = $s['name'];
                            break;
                        }
                    }
                }

                $skuString = implode('-', array_filter([$pName, $cName, $sName]));
                $sku = $this->generateSku($skuString);
            } else {
                $sku = '';
            }
        } else {
            $sku = $this->generateSku($skuInput);
        }

        if (empty($sku) && empty($errors['product_id'])) {
            $errors['sku'] = "Không thể tạo mã SKU tự động, vui lòng nhập thủ công.";
        }

        if (!empty($errors)) {
            $this->view('product/varitant/product_variant/create', [
                'errors' => $errors,
                'old' => $old,
                'products' => $products,
                'colors' => $colors,
                'sizes' => $sizes
            ]);
            return;
        }

        $imagePath = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $targetDir = 'image/product_variant/';
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
            'product_id' => $_POST['product_id'],
            'color_id'   => !empty($_POST['color_id']) ? $_POST['color_id'] : null,
            'size_id'    => !empty($_POST['size_id']) ? $_POST['size_id'] : null,
            'sku'        => $sku,
            'quantity'   => (int)$quantity,
            'price'      => (int)$price,
            'sale_price' => (int)$sale_price,
            'image'      => $imagePath,
            'status'     => $_POST['status'] ?? 1
        ];

        try {
            $this->model('ProductVariantModel')->create($data);
            header("Location: /productvariant");
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                if ($imagePath && file_exists($imagePath)) {
                    unlink($imagePath);
                }
                $errors['product_id'] = "Biến thể này (Sản phẩm + Màu + Size) đã tồn tại.";
                $this->view('product/varitant/product_variant/create', [
                    'errors' => $errors,
                    'old' => $old,
                    'products' => $products,
                    'colors' => $colors,
                    'sizes' => $sizes,
                    'mess' => "Lỗi: Biến thể đã tồn tại!"
                ]);
            } else {
                throw $e;
            }
        }
    }

    public function edit($id)
    {
        $variant = $this->model('ProductVariantModel')->find($id);

        if (!$variant) {
            header("Location: /productvariant");
            exit;
        }

        $products = $this->model('ProductModel')->all();
        $colors = $this->model('ColorModel')->all();
        $sizes = $this->model('SizeModel')->all();

        $this->view('product/varitant/product_variant/edit', [
            'variant' => $variant,
            'products' => $products,
            'colors' => $colors,
            'sizes' => $sizes
        ]);
    }

    public function update($id)
    {
        $variantModel = $this->model('ProductVariantModel');
        $currentVariant = $variantModel->find($id);

        if (!$currentVariant) {
            header("Location: /productvariant");
            exit;
        }

        $products = $this->model('ProductModel')->all();
        $colors = $this->model('ColorModel')->all();
        $sizes = $this->model('SizeModel')->all();

        $errors = [];
        $old = $_POST;

        if (empty($_POST['product_id'])) {
            $errors['product_id'] = "Vui lòng chọn Sản phẩm.";
        }

        $price = $_POST['price'] ?? '';
        if ($price === '' && !empty($_POST['product_id'])) {
            foreach ($products as $p) {
                if ($p['id'] == $_POST['product_id']) {
                    $price = $p['price'];
                    break;
                }
            }
        }

        if ($price === '') {
            $errors['price'] = "Vui lòng nhập giá bán.";
        } elseif (!is_numeric($price) || $price < 0) {
            $errors['price'] = "Giá bán không hợp lệ.";
        }

        $sale_price = $_POST['sale_price'] ?? 0;
        if ($sale_price !== '' && $sale_price > 0) {
            if ((float)$sale_price > (float)$price) {
                $errors['sale_price'] = "Giá khuyến mãi không lớn hơn giá gốc.";
            }
        } elseif ($sale_price === '' || $sale_price == 0) {
            $sale_price = $price;
        }

        $quantity = $_POST['quantity'] ?? '';
        if ($quantity === '') {
            $errors['quantity'] = "Vui lòng nhập số lượng.";
        } elseif (!is_numeric($quantity) || $quantity < 0) {
            $errors['quantity'] = "Số lượng không hợp lệ.";
        }

        $skuInput = trim($_POST['sku'] ?? '');
        if (empty($skuInput)) {
            if (empty($errors['product_id'])) {
                $pName = '';
                $cName = '';
                $sName = '';
                foreach ($products as $p) {
                    if ($p['id'] == $_POST['product_id']) {
                        $pName = $p['name'];
                        break;
                    }
                }
                if (!empty($_POST['color_id'])) foreach ($colors as $c) {
                    if ($c['id'] == $_POST['color_id']) {
                        $cName = $c['name'];
                        break;
                    }
                }
                if (!empty($_POST['size_id'])) foreach ($sizes as $s) {
                    if ($s['id'] == $_POST['size_id']) {
                        $sName = $s['name'];
                        break;
                    }
                }

                $skuString = implode('-', array_filter([$pName, $cName, $sName]));
                $sku = $this->generateSku($skuString);
            } else {
                $sku = $currentVariant['sku'];
            }
        } else {
            $sku = $this->generateSku($skuInput);
        }

        if (!empty($errors)) {
            $this->view('product/varitant/product_variant/edit', [
                'errors' => $errors,
                'variant' => array_merge($currentVariant, $_POST),
                'products' => $products,
                'colors' => $colors,
                'sizes' => $sizes
            ]);
            return;
        }

        $imagePath = $currentVariant['image'];
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $targetDir = 'image/product_variant/';
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
            'product_id' => $_POST['product_id'],
            'color_id'   => !empty($_POST['color_id']) ? $_POST['color_id'] : null,
            'size_id'    => !empty($_POST['size_id']) ? $_POST['size_id'] : null,
            'sku'        => $sku,
            'quantity'   => (int)$quantity,
            'price'      => (int)$price,
            'sale_price' => (int)$sale_price,
            'image'      => $imagePath,
            'status'     => $_POST['status'] ?? 1
        ];

        try {
            $variantModel->update($id, $data);
            header("Location: /productvariant");
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $errors['product_id'] = "Biến thể này (Sản phẩm + Màu + Size) đã tồn tại.";
                $this->view('product/varitant/product_variant/edit', [
                    'errors' => $errors,
                    'variant' => array_merge($currentVariant, $_POST),
                    'products' => $products,
                    'colors' => $colors,
                    'sizes' => $sizes,
                    'mess' => "Lỗi: Biến thể đã tồn tại!"
                ]);
            } else {
                throw $e;
            }
        }
    }

    public function delete($id)
    {
        $mess = "Xóa biến thể sản phẩm thành công!";
        $_SESSION['success'] = $mess;
        $this->model('ProductVariantModel')->delete($id);
        header("Location: /productvariant");
    }

    private function generateSku($string)
    {
        $string = $this->removeAccents($string);
        $string = str_replace(' ', '-', $string);
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);
        $string = preg_replace('/-+/', '-', $string);
        $string = trim($string, '-');

        return strtoupper($string);
    }

    private function removeAccents($str)
    {
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
        $str = preg_replace("/(đ)/", 'd', $str);
        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
        $str = preg_replace("/(Đ)/", 'D', $str);
        return $str;
    }
}
