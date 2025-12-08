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
        header('Content-Type: application/json; charset=utf-8');

        // Nhận dữ liệu từ client
        $code  = $_GET['code']  ?? $_POST['code']  ?? '';
        $phone = $_GET['phone'] ?? $_POST['phone'] ?? '';

        // Kiểm tra thiếu mã
        if (empty($code)) {
            echo json_encode([
                'success' => false,
                'message' => 'Thiếu mã giảm giá!'
            ]);
            return;
        }

        // Tải model
        $checkoutModel = $this->model('Checkout');

        // Gọi hàm lấy coupon
        $coupon = $checkoutModel->getCouponByCode($code, $phone);

        // Không tồn tại / hết hạn / đã dùng
        if (!$coupon) {
            echo json_encode([
                'success' => false,
                'message' => 'Mã giảm giá không hợp lệ, đã hết hạn hoặc bạn đã sử dụng!'
            ]);
            return;
        }

        // Thành công
        echo json_encode([
            'success' => true,
            'percent' => (int)$coupon['Discount_value'],  // phần trăm giảm
            'message' => 'Áp dụng mã thành công!'
        ]);
    }

    public function save() {
        header('Content-Type: application/json; charset=utf-8');

        $input = json_decode(file_get_contents("php://input"), true);
        // // Chỉ chấp nhận POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(["success" => false, "message" => "Invalid method"]);
            return;
        }

        // Model Checkout
        

        // Lấy JSON từ body request
        $input = json_decode(file_get_contents("php://input"), true);

        if (!$input) {
            echo json_encode(["success" => false, "message" => "Invalid JSON data"]);
            return;
        }

        // Kiểm tra dữ liệu chính
        if (empty($input['items']) || empty($input['finalTotal'])) {
            echo json_encode(["success" => false, "message" => "Missing order data"]);
            return;
        }

        // // --- LẤY DỮ LIỆU ---
        $customerPhone   = $input['customerPhone'] ?? null;
        $storeId         = $input['storeID'] ?? 0;
        $tableNumber     = $input['tableNumber'] ?? null;

        $items           = $input['items'];
        $usePoints       = $input['usePoints'] ?? 0;
        $couponCode      = $input['couponCode'] ?? null;
        $discountAmount  = $input['discountAmount'] ?? 0;
        $finalTotal      = $input['finalTotal'];

        // --- LƯU ĐƠN HÀNG ---
        $checkoutModel = $this->model('Checkout');
        $orderId = $checkoutModel->saveOrder([
            "customerPhone" => $customerPhone,
            "storeID"       => $storeId,
            "tableNumber"   => $tableNumber,
            "usePoints"     => $usePoints,
            "total"         => $finalTotal
        ]);

        if (!$orderId) {
            echo json_encode(["success" => false, "message" => "Cannot save order"]);
            return;
        }

        // // // --- LƯU CHI TIẾT ---
        // // foreach ($items as $item) {
        // //     $checkoutModel->saveOrderItem($orderId, $item);
        // // }

        // // // --- TRỪ ĐIỂM (NẾU CÓ) ---
        // // if ($customerPhone && $usePoints > 0) {
        // //     $checkoutModel->subtractPoints($customerPhone, $usePoints);
        // // }

        // // // --- LƯU MÃ GIẢM GIÁ ---
        // // if ($couponCode) {
        // //     $checkoutModel->applyCoupon($customerPhone, $couponCode);
        // // }

        // // --- TRẢ KẾT QUẢ ---
        echo json_encode([
            "success" => true,
            "orderID" => $orderId,
            "message" => "Đặt hàng thành công"
        ]);
        exit();


    // echo json_encode([
    //     "debug" => true,
    //     "received_raw" => $input,
    //     "parsed" => [
    //         "customerPhone" => $input["customerPhone"] ?? null,
    //         "storeID"       => $input["storeID"] ?? null,
    //         "tableNumber"   => $input["tableNumber"] ?? null,
    //         "items"         => $input["items"] ?? null,
    //         "usePoints"     => $input["usePoints"] ?? null,
    //         "couponCode"    => $input["couponCode"] ?? null,
    //         "discountAmount"=> $input["discountAmount"] ?? null,
    //         "finalTotal"    => $input["finalTotal"] ?? null
    //     ]
    // ]);

        return;
    }



}
