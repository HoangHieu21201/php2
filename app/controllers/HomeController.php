<?php
class HomeController extends Controller
{
    public function index()
    {

        if (empty($_SESSION['user_id'])) {
            header("Location: /auth/login");
            exit;
        }

        $product = $this->model('ProductModel');
        $data = $product->all();
        $title = "Trang chủ";
        $this->view('home/index', ['title' => $title, 'products' => $data]);
    }
}
