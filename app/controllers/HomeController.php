<?php
class HomeController extends Controller {
    public function index() {
        $data = ['title' => 'Trang chủ', 'message' => 'Chào mừng bạn đến với website!'];
        $this->view('home/index', $data);
    }
}
?>