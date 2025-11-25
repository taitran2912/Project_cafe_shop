<?php
class PaymentController extends Controller {
    public function index() {
        // Gọi model
        $paymentModel = $this->model('Payment');

        // Lấy dữ liệu từ DB
        $userID = $_SESSION['user']['ID'];
        $product = $paymentModel->getAllProductstoCart($userID);
        $defaultAddress = $paymentModel->getDefaultAddress($userID);
        $Address = $paymentModel->getAllAddress($userID);
        $storeLocations = $paymentModel->getStoreLocations();

        $data = [
            'title' => 'Thanh toán',
            'js' => 'payment',
            'css' => 'payment.css',
            'defaultAddress' => $defaultAddress,
            'addresses' => $Address, 
            'product' => $product,
            'storeLocations' => $storeLocations
            // 'userID' => $userID
        ];
        $this->view('payment/index', $data);
    }
}
