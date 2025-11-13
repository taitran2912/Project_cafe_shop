<?php
class AdminController extends Controller {
    public function index() {
        if (!isset($_SESSION['user']) || empty($_SESSION['user']['ID'])) {
            echo "<script>
                alert('Vui lòng đăng nhập để truy cập!');
                
                window.location.href = '" . BASE_URL . "login_admin';
            </script>";
            exit();
        }

        $role = $_SESSION['user']['Role'];
        $userID = $_SESSION['user']['ID'];

        if (!in_array($role, [2, 1, 3])) {
            echo "<script>
                alert('Bạn không có quyền truy cập trang này!');
                window.location.href = '" . BASE_URL . "login_admin';
            </script>";
            exit();
        }


        $data = [
            'title' => 'admin',
        ];
        $this->view('admin/home/index', $data);
    }
   public function branch() {
        $branch = $this->model('Contact');
        $branches = $branch->getAllBranch();

        $data = [
            'title' => 'Quản lý chi nhánh',
            'branches' => $branches
        ];
        $this->view('admin/home/index', $data);
         
    }   

    public function branch_detail($idBranch) {
        // Hủy session và chuyển hướng về trang đăng nhập
        $detail = $this->model('Branch');
        // $branchDetail = $detail->getBranchById($idBranch);
        
        $data = [
            'title' => 'Chi tiết chi nhánh',
            // 'detail' => $branchDetail
        ];
        $this->view('admin/home/index', $data);
    }

    public function menu() { // từ function menu render ra view thực đơn ở trang index
        $productModel = $this->model('Product');
        $data = [
            'title' => 'Sản phẩm',
            'products' => $productModel->getAllProducts(),
            // 'pagination' => $productModel->paginate()

        ];
        $this->view('admin/home/index', $data);
    }

}
