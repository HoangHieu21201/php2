<?php
class UserProductDetailController extends Controller
{
    public function index()
    {
        $productModel = $this->model('ProductModel');
        // Sử dụng getAllWithPriceRange để lấy sản phẩm kèm giá min/max cho trang danh sách
        if (method_exists($productModel, 'getAllWithPriceRange')) {
            $products = $productModel->getAllWithPriceRange();
        } else {
            $products = $productModel->all();
        }

        $this->view('pages/index', [
            'products' => $products,
            'title'    => 'Danh sách sản phẩm'
        ]);
    }

    public function detail($id)
    {
        if (empty($id)) {
            header("Location: /userproductdetail");
            exit;
        }

        $productModel = $this->model('ProductModel');
        $product = $productModel->find($id);

        if (!$product) {
            header("Location: /userproductdetail");
            exit;
        }

        $variantModel = $this->model('ProductVariantModel');

        if (method_exists($variantModel, 'getFilteredVariants')) {
            $variants = $variantModel->getFilteredVariants(['product_id' => $id], 1, 100);
        } else {
            $allVariants = $variantModel->all();
            $variants = array_filter($allVariants, function ($v) use ($id) {
                return $v['product_id'] == $id;
            });
        }

        // --- Logic tính toán giá Min/Max từ biến thể ---
        $minPrice = 0;
        $maxPrice = 0;
        $prices = [];

        if (!empty($variants)) {
            foreach ($variants as $v) {
                // Ưu tiên giá sale nếu có, nếu không lấy giá thường
                $p = ($v['sale_price'] > 0) ? $v['sale_price'] : $v['price'];
                $prices[] = $p;
            }
        }

        if (!empty($prices)) {
            $minPrice = min($prices);
            $maxPrice = max($prices);
        }

        // Gán giá trị vào mảng product để View sử dụng, tránh lỗi Undefined index
        $product['min_price'] = $minPrice;
        $product['max_price'] = $maxPrice;
        
        // Gán giá mặc định (0) nếu không có biến thể để View không lỗi khi gọi $product['price'] cũ
        $product['price'] = 0; 

        $this->view('pages/detail', [
            'product'  => $product,
            'variants' => $variants,
            'title'    => $product['name'] . ' - Chi tiết sản phẩm'
        ]);
    }
}