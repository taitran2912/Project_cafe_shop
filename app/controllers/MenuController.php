<?php
class MenuController extends Controller {
    public function index() {
        // Gọi model
        $productModel = $this->model('Menu');

        // Lấy dữ liệu từ DB
        $products = $productModel->getAll();


        $data = [
            'title' => 'Thực đơn',
            'products' => $products,
            'js' => 'menu'
        ];
        $this->view('menu/index', $data);
    }
}
