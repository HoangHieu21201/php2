<?php 
class UserClientController extends Controller {
    public function index() {
        $productModel = $this->model('ProductModel');
        $products = $productModel->getAllWithPriceRange();
        $title = "Trang chủ";
        
        $this->view('pages/index', [
            'title' => $title, 
            'products' => $products
        ]);
    }
}