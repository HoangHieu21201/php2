<?php
class UserProductController extends Controller
{
    public function index()
    {
        $productModel = $this->model('ProductModel');
        $products = $productModel->getAllWithPriceRange();

        $this->view('pages/index', [
            'products' => $products,
            'title' => 'Trang chủ - MyShop'
        ]);
    }
}