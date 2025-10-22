<?php
class PaymentController extends Controller {
    public function index() {
        // Gọi model
        $paymentModel = $this->model('Payment');

        // Lấy dữ liệu từ DB
        $userID = $_SESSION['user']['ID'];
        $product = $paymentModel->getAllProductstoCart($userID);

        $data = [
            'title' => 'Thanh toán',
            'js' => 'payment',
            'css' => 'payment.css',
            'product' => $product
            // 'userID' => $userID
        ];
        $this->view('payment/index', $data);
    }
}
