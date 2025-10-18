<?php
class PaymentController extends Controller {
    public function index() {
        // Gọi model
        $branchModel = $this->model('Payment');

        // Lấy dữ liệu từ DB


        $data = [
            'title' => 'Thanh toán',
            'js' => 'payment',
            'css' => 'payment.css'
        ];
        $this->view('payment/index', $data);
    }
}
