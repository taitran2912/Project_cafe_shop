<?php
class AdminController extends Controller {
    public function index() {
        $userID = $_SESSION['user']['ID'];
        if (!in_array($role, [2, 3])) {
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
