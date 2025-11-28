<?php
class TableController extends Controller {

    private function checkAuth() {
        if (!isset($_SESSION['user']) || empty($_SESSION['user']['ID'])) {
            echo "<script>
                alert('Vui lòng đăng nhập để truy cập!');
                window.location.href = '" . BASE_URL . "login_admin';
            </script>";
            exit();
        }

        $role = $_SESSION['user']['Role'];
        if (!in_array($role, [0, 1, 2, 3])) {
            echo "<script>
                alert('Bạn không có quyền truy cập trang này!');
                window.location.href = '" . BASE_URL . "login_admin';
            </script>";
            exit();
        }
    }
    
    public function adminIndex() {
        $this->checkAuth();
        
        // Load tables
        $tableModel = $this->model('Table');
        $tables = $tableModel->getAllTables();
        
        $data = [
            'title' => 'Quản lý bàn',
            'tables' => $tables
        ];
        // Pass data to the view
        $this->view('admin/home/index', $data);
    }
}