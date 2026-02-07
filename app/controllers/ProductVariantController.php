<?php
class ProductVariantController extends Controller
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

        $this->view('product/attribute-variant/index', [
            'products' => $products,
            'filters' => ['keyword' => $keyword]
        ]);
    }

    public function create()
    {
        $productId = $_GET['product_id'] ?? null;
        if (!$productId) {
            $_SESSION['error'] = "Vui lòng chọn sản phẩm cần cấu hình!";
            header("Location: /productvariant");
            exit;
        }

        $productModel = $this->model('ProductModel');
        $product = $productModel->find($productId);

        if (!$product) {
            $_SESSION['error'] = "Sản phẩm không tồn tại.";
            header("Location: /productvariant");
            exit;
        }

        $attributeModel = $this->model('AttributeModel');
        $attrValueModel = $this->model('AttributeValueModel');
        
        $attributes = $attributeModel->all();
        foreach ($attributes as &$attr) {
            $attr['values'] = $attrValueModel->getByAttributeId($attr['id']);
        }

        $variantsData = [];

        if (isset($_SESSION['old_variants'])) {
            $variantsData = $_SESSION['old_variants'];
            unset($_SESSION['old_variants']);
        } else {
            $variantModel = $this->model('ProductVariantModel');
            $variantAttrModel = $this->model('VariantAttributeValueModel');
            
            if (method_exists($variantModel, 'getByProductId')) {
                $dbVariants = $variantModel->getByProductId($productId);
                
                foreach ($dbVariants as $v) {
                    $attrs = $variantAttrModel->getAttributesByVariantId($v['id']);
                    $attrMap = [];
                    foreach ($attrs as $a) {
                        $attrMap[$a['attribute_id']] = $a['attribute_value_id'];
                    }

                    $variantsData[] = [
                        'id' => $v['id'],
                        'sku' => $v['sku'],
                        'price' => $v['price'],
                        'sale_price' => $v['sale_price'],
                        'quantity' => $v['quantity'],
                        'image' => $v['image'],
                        'status' => $v['status'],
                        'attributes' => $attrMap
                    ];
                }
            }
        }

        $this->view('product/attribute-variant/create', [
            'product' => $product,
            'attributes' => $attributes,
            'variantsData' => $variantsData
        ]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /productvariant");
            exit;
        }

        $productId = $_POST['product_id'] ?? null;
        $variantsInput = $_POST['variants'] ?? [];

        if (empty($productId)) {
            $_SESSION['error'] = "Dữ liệu không hợp lệ!";
            header("Location: /productvariant");
            exit;
        }

        $productModel = $this->model('ProductModel');
        $productInfo = $productModel->find($productId);
        $productSlug = $this->createSlug($productInfo['name'] ?? 'product');

        $variantModel = $this->model('ProductVariantModel');
        $variantAttrModel = $this->model('VariantAttributeValueModel');

        $dbVariants = $variantModel->getByProductId($productId);
        $dbSignatures = [];
        foreach ($dbVariants as $dv) {
            $attrs = $variantAttrModel->getAttributesByVariantId($dv['id']);
            $vals = array_column($attrs, 'attribute_value_id');
            sort($vals);
            $dbSignatures[implode('-', $vals)] = $dv['id'];
        }

        $countNew = 0;
        $countUpdate = 0;
        $errors = [];
        $hasError = false;

        foreach ($variantsInput as $key => $vData) {
            $rowNum = $key + 1;
            
            $hasAttr = !empty($vData['attributes']) && count(array_filter($vData['attributes'])) > 0;
            if (empty($vData['price']) && $vData['price'] !== '0' && empty($vData['sku']) && !$hasAttr) continue;

            if ((int)($vData['price'] ?? 0) < 0 || (int)($vData['quantity'] ?? 0) < 0) {
                $hasError = true;
                $errors[] = "Dòng #$rowNum: Giá và số lượng phải lớn hơn hoặc bằng 0.";
            }

            if ((int)($vData['sale_price'] ?? 0) > (int)($vData['price'] ?? 0)) {
                $hasError = true;
                $errors[] = "Dòng #$rowNum: Giá khuyến mãi không được lớn hơn giá gốc.";
            }

            $currentSignature = '';
            if (!empty($vData['attributes'])) {
                $currentValues = array_filter($vData['attributes'], function($v) { return $v && $v !== 'NEW'; });
                sort($currentValues);
                $currentSignature = implode('-', $currentValues);
            }
            
            $variantId = !empty($vData['id']) ? $vData['id'] : null;

            if ($currentSignature && isset($dbSignatures[$currentSignature])) {
                $foundId = $dbSignatures[$currentSignature];
                if (!$variantId || ($variantId && $foundId != $variantId)) {
                    $hasError = true;
                    $errors[] = "Dòng #$rowNum: Bộ thuộc tính này đã tồn tại.";
                }
            }
        }

        if ($hasError) {
            $_SESSION['error'] = implode("<br>", $errors);
            $_SESSION['old_variants'] = $variantsInput;
            header("Location: /productvariant/create?product_id=" . $productId);
            exit;
        }

        foreach ($variantsInput as $key => $vData) {
            $hasAttr = !empty($vData['attributes']) && count(array_filter($vData['attributes'])) > 0;
            if (empty($vData['price']) && $vData['price'] !== '0' && empty($vData['sku']) && !$hasAttr) continue;

            $imagePath = $vData['current_image'] ?? '';
            
            if (!empty($_FILES['variants']['name'][$key]['image'])) {
                $fileError = $_FILES['variants']['error'][$key]['image'];
                if ($fileError == 0) {
                    $targetDir = 'image/product_variant/';
                    if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);
                    
                    $ext = pathinfo($_FILES['variants']['name'][$key]['image'], PATHINFO_EXTENSION);
                    $newFileName = $productSlug . '-var-' . time() . '-' . $key . '.' . $ext;
                    $targetFilePath = $targetDir . $newFileName;
                    
                    if (move_uploaded_file($_FILES['variants']['tmp_name'][$key]['image'], $targetFilePath)) {
                        $imagePath = $targetFilePath;
                    }
                }
            }

            if (empty($vData['sku'])) {
                $randomCode = strtoupper(substr(md5(uniqid(rand(), true)), 0, 5));
                $sku = strtoupper($productSlug . '-' . $randomCode);
            } else {
                $sku = strtoupper(trim($vData['sku']));
            }

            $dbData = [
                'product_id' => $productId,
                'sku' => $sku,
                'price' => (int)($vData['price'] ?? 0),
                'sale_price' => (int)($vData['sale_price'] ?? 0),
                'quantity' => (int)($vData['quantity'] ?? 0),
                'image' => $imagePath,
                'description' => '',
                'status' => (int)($vData['status'] ?? 1)
            ];

            try {
                $variantId = !empty($vData['id']) ? $vData['id'] : null;
                $targetId = null;

                if ($variantId) {
                    $variantModel->update($variantId, $dbData);
                    $variantAttrModel->removeByVariantId($variantId);
                    $targetId = $variantId;
                    $countUpdate++;
                } else {
                    $targetId = $variantModel->create($dbData);
                    $countNew++;
                }

                if ($targetId && !empty($vData['attributes'])) {
                    foreach ($vData['attributes'] as $attrId => $valId) {
                        if ($valId && $valId !== 'NEW') {
                            $variantAttrModel->add($targetId, $valId);
                        }
                    }
                }

            } catch (Exception $e) {
                $_SESSION['error'] = "Lỗi dòng " . ($key+1) . ": " . $e->getMessage();
                $_SESSION['old_variants'] = $variantsInput;
                header("Location: /productvariant/create?product_id=" . $productId);
                exit;
            }
        }

        $_SESSION['success'] = "Đã cập nhật thành công ($countUpdate sửa, $countNew thêm)!";
        if(isset($_SESSION['old_variants'])) unset($_SESSION['old_variants']);
        
        header("Location: /productvariant/create?product_id=" . $productId);
        exit;
    }

    public function delete($id)
    {
        $variantModel = $this->model('ProductVariantModel');
        $variant = $variantModel->find($id);
        
        $redirectUrl = "/productvariant";
        if ($variant) {
            $redirectUrl = "/productvariant/create?product_id=" . $variant['product_id'];
            if (!empty($variant['image']) && file_exists($variant['image'])) {
                unlink($variant['image']);
            }
            $variantModel->delete($id);
            $_SESSION['success'] = "Đã xóa biến thể!";
        } else {
            $_SESSION['error'] = "Biến thể không tồn tại!";
        }
        
        header("Location: " . $redirectUrl);
        exit;
    }

    private function createSlug($str) {
        $str = trim(mb_strtolower($str));
        $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
        $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
        $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
        $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
        $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
        $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
        $str = preg_replace('/(đ)/', 'd', $str);
        $str = preg_replace('/[^a-z0-9-\s]/', '', $str);
        $str = preg_replace('/([\s]+)/', '-', $str);
        return $str;
    }
}