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
            'role' => $role,
            'userID' => $userID
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
    public function inventory() {
        $inventory = $this->model('Inventory');
        $inventories = $inventory->getAllInventory();

        $data = [
            'title' => 'Quản lý kho',
            'inventories' => $inventories
        ];
        $this->view('admin/home/index', $data);
         
    }   

    public function store() {
        $name    = $_POST['name'] ?? '';
        $address = $_POST['address'] ?? '';
        $phone   = $_POST['phone'] ?? '';
        $status  = $_POST['status'] ?? 'active';

        $branchModel = $this->model('Branch');
        $result = $branchModel->addBranch($name, $address, $phone, $status);

        header('Content-Type: application/json');

        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Thêm chi nhánh thành công!'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Thêm chi nhánh thất bại! Vui lòng thử lại.'
            ]);
        }
        exit;
    }
    public function update() {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];
        $status = $_POST['status'];

        $branchModel = $this->model('Branch');

        $ok = $branchModel->updateBranch($id, $name, $address, $phone, $status);

        echo json_encode([
            "success" => $ok,
            "message" => $ok ? "Cập nhật thành công!" : "Cập nhật thất bại!"
        ]);
    }

    public function delete() {
        $id = $_POST['id'];

        $branchModel = $this->model('Branch');
        $ok = $branchModel->deleteBranch($id);

        echo json_encode([
            "success" => $ok,
            "message" => $ok ? "Xóa thành công!" : "Xóa thất bại!"
        ]);
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

    public function user() {
        $userModel = $this->model('Account');
        $data = [
            'title' => 'Người dùng',
            'users' => $userModel->getAllAccounts(),
        ];
        $this->view('admin/home/index', $data);
    }
    public function coupon() {
        $couponModel = $this->model('Coupon');
        $data = [
            'title' => 'Khuyến mãi',
            'coupons' => $couponModel->getAllCoupons(),
        ];
        $this->view('admin/home/index', $data);
    }

}
