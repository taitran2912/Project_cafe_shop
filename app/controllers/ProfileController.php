<?php
session_start();
class ProfileController extends Controller {
    public function index() {
        // Gọi model
        $profileModel = $this->model('Profile');

        $userId = $_SESSION['user']['ID'];

        // Lấy dữ liệu từ DB
        $profile = $profileModel->getProfile($userId);
        $addresses = $profileModel->getAddresses($userId);
        $orders = $profileModel->getOrders($userId);

        // Nếu có đơn hàng, lấy chi tiết cho từng đơn
        if (!empty($orders) && is_array($orders)) {
            foreach ($orders as $idx => $o) {
                // Giả sử mỗi đơn có khóa ID hoặc OrderID — thử theo thứ tự 'ID' rồi 'OrderID'
                $orderId = null;
                if (isset($o['ID'])) {
                    $orderId = $o['ID'];
                } elseif (isset($o['OrderID'])) {
                    $orderId = $o['OrderID'];
                }

                // Nếu tìm thấy ID, lấy chi tiết đơn từ model
                if ($orderId !== null) {
                    // Tên hàm lấy chi tiết đơn trong model có thể là getOrderDetails
                    // Nếu model dùng tên khác, chỉnh lại ở đây tương ứng.
                    $details = $profileModel->getOrderDetails($orderId);
                    $orders[$idx]['Details'] = $details;
                } else {
                    $orders[$idx]['Details'] = [];
                }
            }
        }

        $data = [
            'title' => 'Hồ sơ',
            'ID'   => $userId,
            'Name' => $profile['Name'],
            'Mail' => $profile['Email'],
            'Phone' => $profile['Phone'],
            'Point' => $profile['Points'],
            'Status' => $profile['Status'],
            'Address' => $addresses,
            'Order' => $orders
        ];
        $this->view('profile/index', $data);
    }

    public function addAddress() {
        $input = json_decode(file_get_contents('php://input'), true);

        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'Bạn chưa đăng nhập']);
            return;
        }

        $address = trim($input['address'] ?? '');
        $isDefault = isset($input['isDefault']) ? intval($input['isDefault']) : 0;
        $latitude = 10.8455;
        $longitude = 106.6663;

        if (!$address) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng nhập địa chỉ']);
            return;
        }

        // Gọi model Profile (thay vì Address)
        $success = $this->model('Profile')->addAddress($userId, $address, $latitude, $longitude, $isDefault);

        if ($success) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không thể thêm địa chỉ']);
        }
    }

}
