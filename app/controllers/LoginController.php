<?php
class LoginController extends Controller {
    public function index() {
        $data = ['title' => 'Đăng nhập'];
        $this->view('login/index', $data);
    }

    public function auth() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            // Gọi model User
            $loginModel = $this->model('Login');
            $user = $loginModel->checkLogin($email, $password);
            if ($user) {
                // Lưu thông tin người dùng vào session
                $_SESSION['user'] = $user;
                // Chuyển hướng về trang chủ hoặc trang dashboard
                // $_SESSION['user'] = $user;
                echo "<script>alert('Đăng nhập thành công!'); window.location.href = '" . BASE_URL . "home';</script>";
            } else {
                // Xử lý lỗi đăng nhập
                $data = [
                    'title' => 'Đăng nhập',
                    'error' => 'Email hoặc mật khẩu không đúng.'
                ];
                $this->view('login/index', $data);
            }
        }   
    }
}
