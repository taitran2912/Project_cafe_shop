<?php
class Login_adminController extends Controller {
    public function index() {
        $data = [
            'title' => 'login',
        ];
        $this->view('admin/login/index', $data);
    }
}
