<?php
class CheckoutController extends Controller {
    public function submit() {
        // Kiểm tra POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Lấy dữ liệu từ form
            $addressId   = $_POST['address_id'];
            $storeId     = $_POST['store_id'];
            $shippingFee = $_POST['shipping_fee'];
            $userID = $_SESSION['user']['ID'];

            // Tính toán đơn hàng
            $orderCode = "ORD" . time() . rand(1000, 9999);


            $sepayConfig = [
                'account_number' => '91902203843',
                'account_name' => 'TranTanTai',
                'bank_code' => 'TPBank',
                'template' => 'compact',
            ];


            $checkoutModel = $this->model('Checkout');
            $cart = $checkoutModel->getCartItems($userID);
            
            $subtotal = 0;
            if (!empty($cart)){
                foreach ($cart as $item):
                    $totalItem = $item['Price'] * $item['Quantity'];
                    $subtotal += $totalItem;
                endforeach;
            }
            $finalTotal = $subtotal + $shippingFee;

            $qrCodeUrl = $checkoutModel->generateSepayQR($sepayConfig, $finalTotal, $orderCode);


            // Truyền dữ liệu sang view hiển thị tóm tắt
            $data = [
                'title' => 'Xác nhận đơn hàng',
                // 'css' => 'checkout.css',
                'address_id' => $addressId,
                'store_id' => $storeId,
                'shipping_fee' => $shippingFee,
                'bank' => $sepayConfig,
                'OrderCode' => $orderCode,
                'userID' => $userID,
                'QR' => $qrCodeUrl,
                'cart' => $cart
            ];

            $this->view('checkout/index', $data);

        } else {
            // Nếu truy cập trực tiếp không qua POST
            header('Location: payment');
            exit;
        }
    }
}
