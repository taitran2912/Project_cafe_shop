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
                $_SESSION['user'] = [
                    'ID' => $user['ID'],
                    'Name' => $user['Name'],
                    'Email' => $user['Email'],
                    'Role' => $user['Role']
                ];


                // Chuyển hướng về trang chủ hoặc trang dashboard
                // $_SESSION['user'] = $user;
                // echo "<script>alert('ID = {$user['ID']}'); window.location.href='" . BASE_URL . "';</script>";
                header("Location: " . BASE_URL);
                exit();


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
