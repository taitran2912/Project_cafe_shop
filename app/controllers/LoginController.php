<?php
class LoginController extends Controller {
    public function index() {
        // Gọi model
        // $productModel = $this->model('About');

        // Lấy dữ liệu từ DB
        // $products = $productModel->get_Best_Selling_Products();


        $data = [
            'title' => 'Đăng nhập',
            
        ];
        $this->view('login/index', $data);
    }
}
