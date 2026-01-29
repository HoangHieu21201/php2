<?php
class UserProductDetailController extends Controller
{
    public function index()
    {
        $productModel = $this->model('ProductModel');
        $products = $productModel->all();

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

        $this->view('pages/detail', [
            'product'  => $product,
            'variants' => $variants,
            'title'    => $product['name'] . ' - Chi tiết sản phẩm'
        ]);
    }
}
