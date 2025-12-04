<?php
session_start();
class ProfileController extends Controller {
    public function index() {
        // Gọi model
        $profileModel = $this->model('Profile');

        $userId = $_SESSION['user']['ID'];

        // Lấy dữ liệu từ DB
        $profile = $profileModel->getProfile($userId);
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
            'Order' => $orders
        ];
        $this->view('profile/index', $data);
    }
}
