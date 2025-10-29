<?php
class AdminController extends Controller {
    public function index() {


        $data = [
            'title' => 'admin',
        ];
        $this->view('admin/home/index', $data);
    }
}
