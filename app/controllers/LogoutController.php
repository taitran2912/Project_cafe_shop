<?php
class LogoutController extends Controller {
    public function index() {
        // Bắt đầu session nếu chưa có
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Xoá toàn bộ dữ liệu session
        session_unset();
        session_destroy();

        // Chuyển hướng về trang đăng nhập hoặc trang chủ
        header("Location: " . BASE_URL);
        exit();
    }
}
