<?php
class AdminController extends Controller {
    public function index() {
        $userID = $_SESSION['user']['ID'];
        if (!isset($userID) || $_SESSION['user']['Role'] != 'admin') {
            header('Location: login_admin');
            exit();
        }

        $data = [
            'title' => 'admin',
        ];
        $this->view('admin/home/index', $data);
    }
}
