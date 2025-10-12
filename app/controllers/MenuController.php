<?php
class MenuController extends Controller {
    public function index() {
        // Gọi model
        $productModel = $this->model('Menu');

        // Lấy dữ liệu từ DB
        $categoris = $productModel->getAllCategory();
        $products = $productModel->getAll();



        $data = [
            'title' => 'Thực đơn',
            'categoris' => $categoris,
            'products' => $products,
            'js' => 'menu'
        ];
        $this->view('menu/index', $data);
    }
}
