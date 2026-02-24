<?php
class UserClientController extends Controller
{
    public function index()
    {
        // 1. Khởi tạo Model
        $productModel = $this->model('ProductModel');
        $variantModel = $this->model('ProductVariantModel');

        // 2. Lấy tham số từ URL
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 12; // Số sản phẩm trên 1 trang

        // 3. Lấy dữ liệu
        // Kiểm tra xem Model có hàm tối ưu không, nếu không dùng all()
        if (method_exists($productModel, 'getAllWithPriceRange')) {
            $allProducts = $productModel->getAllWithPriceRange();
        } else {
            $allProducts = $productModel->all();
        }
        
        // Fallback nếu null
        $allProducts = $allProducts ?? [];

        // 4. Lọc theo từ khóa (Search)
        if (!empty($keyword)) {
            $filteredProducts = array_filter($allProducts, function($p) use ($keyword) {
                return mb_stripos($p['name'], $keyword) !== false;
            });
        } else {
            $filteredProducts = $allProducts;
        }

        // 5. Tính toán giá (Nếu chưa có min_price/max_price từ SQL thì tính bằng PHP)
        foreach ($filteredProducts as &$p) {
            if (!isset($p['min_price'])) {
                $variants = [];
                // Lấy biến thể để tính giá
                if (method_exists($variantModel, 'getByProductId')) {
                    $variants = $variantModel->getByProductId($p['id']);
                } else {
                    $allVariants = $variantModel->all() ?? [];
                    $variants = array_filter($allVariants, function($v) use ($p) {
                        return $v['product_id'] == $p['id'];
                    });
                }
                
                $prices = [];
                foreach ($variants as $v) {
                    $price = ($v['sale_price'] > 0 && $v['sale_price'] < $v['price']) ? $v['sale_price'] : $v['price'];
                    $prices[] = $price;
                }

                if (!empty($prices)) {
                    $p['min_price'] = min($prices);
                    $p['max_price'] = max($prices);
                } else {
                    $p['min_price'] = 0;
                    $p['max_price'] = 0;
                }
            }
        }
        unset($p);

        // 6. Sắp xếp (Sort)
        if ($sort == 'price_asc') {
            usort($filteredProducts, function($a, $b) {
                return $a['min_price'] - $b['min_price'];
            });
        } elseif ($sort == 'price_desc') {
            usort($filteredProducts, function($a, $b) {
                return $b['min_price'] - $a['min_price'];
            });
        } else {
            // Mặc định: Mới nhất (ID giảm dần)
            usort($filteredProducts, function($a, $b) {
                return $b['id'] - $a['id'];
            });
        }

        // 7. Phân trang (Pagination)
        $totalItems = count($filteredProducts);
        $totalPages = ceil($totalItems / $limit);
        
        if ($page < 1) $page = 1;
        if ($page > $totalPages && $totalPages > 0) $page = $totalPages;

        $offset = ($page - 1) * $limit;
        $pagedProducts = array_slice($filteredProducts, $offset, $limit);

        $this->view('pages/index', [
            'products' => $pagedProducts,
            'title' => 'Trang chủ - MyShop',
            'keyword' => $keyword,
            'currentSort' => $sort,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ]);
    }
}