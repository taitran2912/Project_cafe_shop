<?php
class ContactController extends Controller {
    public function index() {
        // Gọi model
        $branchModel = $this->model('Contact');

        // Lấy dữ liệu từ DB
        $branch = $branchModel->getAllBranch();


        $data = [
            'title' => 'Liên hệ',
            'branch' => $branch
        ];
        $this->view('contact/index', $data);
    }
}
