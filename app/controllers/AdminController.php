<?php
class AdminController extends Controller {
    public function index() {
        if (!isset($_SESSION['user']) || empty($_SESSION['user']['ID'])) {
            echo "<script>
                alert('Vui lòng đăng nhập để truy cập!');
                window.location.href = 'login_admin';
            </script>";
            exit();
        }

        $role = $_SESSION['user']['Role'];
        $userID = $_SESSION['user']['ID'];

        if (!in_array($role, [2, 1])) {
            echo "<script>
                alert('Bạn không có quyền truy cập trang này!');
                window.location.href = 'login_admin';
            </script>";
            exit();
        }


        $data = [
            'title' => 'admin',
        ];
        $this->view('admin/home/index', $data);
    }
}
