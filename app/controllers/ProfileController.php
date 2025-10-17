<?php
class ProfileController extends Controller {
    public function index() {
        // Gọi model
        $productModel = $this->model('Profile');

        // Lấy dữ liệu từ DB
        // $products = $productModel->get_Best_Selling_Products();


        $data = [
            'title' => 'Hồ sơ',
            // 'products' => $products
        ];
        $this->view('profile/index', $data);
    }
}
