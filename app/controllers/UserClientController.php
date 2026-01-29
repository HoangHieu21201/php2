<?php 
class UserClientController extends Controller {
    public function index() {
        $product = $this->model('ProductModel');
        $data = $product->all();
        $title = "Trang chủ";
        $this->view('pages/index', ['title' => $title, 'products' => $data]);
    }
}