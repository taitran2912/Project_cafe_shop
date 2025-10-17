<?php
class CartController extends Controller {
    public function index() {
        // Gọi model
        $cartModel = $this->model('Cart');

        // Lấy dữ liệu từ DB
        // $products = $productModel->get_Best_Selling_Products();


        $data = [
            'title' => 'Giỏ hàng',
            // 'products' => $products
        ];
        $this->view('cart/index', $data);
    }
}
