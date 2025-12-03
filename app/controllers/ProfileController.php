<?php
session_start();
class ProfileController extends Controller {
    public function index() {
        // Gọi model
        $profileModel = $this->model('Profile');

        $userId = $_SESSION['user']['ID'];

        // Lấy dữ liệu từ DB
        $profile = $profileModel->getProfile($userId); 
        $order = $profileModel->getOrders($userId);


        $data = [
            'title' => 'Hồ sơ',
            'ID'   => $userId,
            'Name' => $profile['Name'],
            'Mail' => $profile['Email'],
            'Phone' => $profile['Phone'],
            'Point' => $profile['Points'],
            'Order' => $order
        ];
        $this->view('profile/index', $data);
    }
}
