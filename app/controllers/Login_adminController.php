<?php
class Login_adminController extends Controller {
    public function index() {
        $data = [
            'title' => 'login',
        ];
        $this->view('admin/login/index', $data);
    }
    public function auth() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            // Gọi model User
            $loginModel = $this->model('Login_admin');
            $user = $loginModel->checkLogin($email, $password);
            if ($user) {
                // Lưu thông tin người dùng vào session
                $_SESSION['user'] = [
                    'ID' => $user['ID'],
                    'Name' => $user['Name'],
                    'Email' => $user['Email'],              
                    'Role' => $user['Role']
                ];  
                // Chuyển hướng về trang admin dashboard
                header("Location: " . BASE_URL . "admin/menu");
                exit();
            } else {
                echo "<script>alert('Email hoặc mật khẩu không đúng.');</script>";
                // Xử lý lỗi đăng nhập
                $data = [
                    'title' => 'Đăng nhập'
                ];
                $this->view('admin/login/index', $data);
            }
        }
    }
}
