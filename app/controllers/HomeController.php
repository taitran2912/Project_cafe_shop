<?php
class HomeController extends Controller {
    public function index() {
        // Gọi model
        $productModel = $this->model('Product');

        // Lấy dữ liệu từ DB
        $products = $productModel->get_Best_Selling_Products();


        $data = [
            'title' => 'Trang chủ',
            'products' => $products
        ];
        $this->view('home/index', $data);
    }
}
