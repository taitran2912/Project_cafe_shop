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
                'account_number' => '0384902203',
                'account_name' => 'TranTanTai',
                'bank_code' => 'MBBank',
                'template' => 'compact',
            ];

            $checkoutModel = $this->model('Checkout');
            $address = $checkoutModel->getAddressById($addressId);

            $cart = $checkoutModel->getCartItems($userID);
            $createOrder = $checkoutModel->createOrder($userID, $address['Address'], $storeId, $shippingFee, 0, $orderCode);
            
            $subtotal = 0;
            if (!empty($cart)){
                foreach ($cart as $item):
                    $totalItem = $item['Price'] * $item['Quantity'];
                    $subtotal += $totalItem;
                    $checkoutModel->addOrderDetail($createOrder, $item['ID'], $item['Quantity'],  $item['Price']);
                endforeach;
            }
            $finalTotal = $subtotal + $shippingFee;

            $qrCodeUrl = $checkoutModel->generateSepayQR($sepayConfig, $finalTotal, $orderCode);

            $checkoutModel->updateOrderTotal($createOrder, $finalTotal);


            // Truyền dữ liệu sang view hiển thị tóm tắt
            $data = [
                'title' => 'Xác nhận đơn hàng',
                
                'address_id' => $addressId,
                'store_id' => $storeId,
                'shipping_fee' => $shippingFee,
                'bank' => $sepayConfig,
                'OrderCode' => $orderCode,
                'orderID' => $createOrder,
                'finalTotal' => $finalTotal,
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
    // AJAX xoá toàn bộ giỏ hàng
    public function clear() {
        // Lấy orderID từ POST
        $orderID = isset($_POST['orderID']) ? (int)$_POST['orderID'] : 0;

        if ($orderID > 0) {
            $checkoutModel = $this->model('Checkout');
            $checkoutModel->deleteOrderById($orderID);
            $checkoutModel->deleteOrder($orderID);

            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'OrderID không hợp lệ']);
        }
    }

    public function checkStatus(){

        $orderID = isset($_POST['orderID']) ? (int)$_POST['orderID'] : 0;
        if ($orderID > 0) {
            $checkoutModel = $this->model('Checkout');
            $status = $checkoutModel->getOrderStatus($orderID);

            echo json_encode(['status' => $status]);
            exit;
        }

        echo json_encode(['status' => 'error', 'message' => 'OrderID không hợp lệ']);
    }

    public function guest(){
        $data = [
            'title' => 'Thanh toán'
        ];

        $this->view('checkout/guest', $data);
    }

    public function points() {
        header('Content-Type: application/json');

        $phone = $_GET['phone'] ?? '';

        if (empty($phone)) {
            echo json_encode([
                'success' => false,
                'message' => 'Thiếu số điện thoại'
            ]);
            return;
        }

        $checkoutModel = $this->model('Checkout');

        $points = $checkoutModel->getPointsByPhone($phone);

        echo json_encode([
            'success' => true,
            'phone' => $phone,
            'points' => $points
        ]);
    }

    public function coupon() {
        header('Content-Type: application/json');

        // Nhận mã từ GET hoặc POST
        $code = $_GET['code'] ?? $_POST['code'] ?? '';

        if (empty($code)) {
            echo json_encode([
                'success' => false,
                'message' => 'Thiếu mã giảm giá!'
            ]);
            return;
        }

        // Gọi model
        $checkoutModel = $this->model('Checkout');
        $coupon = $checkoutModel->getCouponByCode($code);

        // Không tồn tại hoặc hết hạn
        if (!$coupon) {
            echo json_encode([
                'success' => false,
                'message' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn!'
            ]);
            return;
        }

        // Thành công
        echo json_encode([
            'success' => true,
            'percent' => (int)$coupon['Percent'],
            'message' => 'Áp dụng mã thành công!'
        ]);
    }

}
