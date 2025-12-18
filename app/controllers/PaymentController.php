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
        $storeLocations = $paymentModel->getStoreLocations($userID);

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

    public function thankyou($orderID)
    {
        $thankyouModel = $this->model('Thankyou');
        $order = $thankyouModel->getOrderById($orderID);
        $data = [
            'title' => 'Cảm ơn bạn đã đặt hàng!',
            'orderID' => $orderID,
            'orderCode' => $order['Note'],
            'Quantity' => $order['Quantity'],
            'css' => 'thankyou.css',
            'Date' => $order['Date'],
            'Total' => $order['Total'],
            'Shipping_Cost' => $order['Shipping_Cost']
            // 'order' => $order
        ];  

        // gọi view
        $this->view("thankyou/index", $data);
    }
}
