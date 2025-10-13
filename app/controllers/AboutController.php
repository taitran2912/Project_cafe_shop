<?php
class AboutController extends Controller {
    public function index() {
        // Gọi model
        // $productModel = $this->model('About');

        // Lấy dữ liệu từ DB
        // $products = $productModel->get_Best_Selling_Products();


        $data = [
            'title' => 'Giới thiệu',
            
        ];
        $this->view('about/index', $data);
    }
}
