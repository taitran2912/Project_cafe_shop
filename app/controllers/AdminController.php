<?php
class AdminController extends Controller {
    
    /**
     * Check authentication for all admin actions
     */
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

    public function dashboard() {
        $data = [
            'title' => 'Dashboard'
        ];
        $this->view('admin/home/index', $data);
    }

    /**
     * Admin dashboard - default redirects to menu
     */
    public function index() {
        $this->checkAuth();
        
        // Redirect to menu by default
        header('Location: ' . BASE_URL . 'admin/dashboard');
        exit;
    }
    
    /**
     * Route to Product admin (menu management)
     */
    public function menu() {
        require_once __DIR__ . '/ProductController.php';
        $productController = new ProductController();
        $productController->adminIndex();
    }
    
    /**
     * Route to Account admin (user management)
     */
    public function user() {
        $this->checkAuth();
        
        // Handle form submissions
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            $this->handleUserAction();
        }
        
        // Handle delete via GET
        if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
            $this->deleteAccount((int)$_GET['id']);
        }
        
        // Load accounts
        $accountModel = $this->model('Account');
        $accounts = $accountModel->getAllAccounts();
        
        $data = [
            'title' => 'Quản lý nhân viên',
            'action' => 'user',
            'accounts' => $accounts
        ];
        $this->view('admin/home/index', $data);
    }
    
    private function handleUserAction() {
        require_once __DIR__ . '/AccountController.php';
        $accountController = new AccountController();
        $action = $_POST['action'];
        
        if ($action === 'add_account') {
            $result = $accountController->store($_POST);
            if ($result) {
                header('Location: ' . BASE_URL . 'admin/user?success=add');
            } else {
                header('Location: ' . BASE_URL . 'admin/user?error=add');
            }
            exit;
        } elseif ($action === 'edit_account') {
            $accountId = (int)($_POST['account_id'] ?? 0);
            $result = $accountController->update($accountId, $_POST);
            if ($result) {
                header('Location: ' . BASE_URL . 'admin/user?success=edit');
            } else {
                header('Location: ' . BASE_URL . 'admin/user?error=edit');
            }
            exit;
        }
    }
    
    private function deleteAccount($accountId) {
        require_once __DIR__ . '/AccountController.php';
        $accountController = new AccountController();
        if ($accountController->delete($accountId)) {
            header('Location: ' . BASE_URL . 'admin/user?success=delete');
        } else {
            header('Location: ' . BASE_URL . 'admin/user?error=delete');
        }
        exit;
    }
    
    /**
     * Route to Coupon admin
     */
    public function coupon() {
        require_once __DIR__ . '/CouponController.php';
        $couponController = new CouponController();
        $couponController->adminIndex();
    }
    
    /**
     * Route to Branch admin
     */
    public function branch() {
        require_once __DIR__ . '/BranchController.php';
        $branchController = new BranchController();
        $branchController->adminIndex();
    }
    
    /**
     * Route to Inventory admin
     */
    public function inventory() {
        require_once __DIR__ . '/InventoryController.php';
        $inventoryController = new InventoryController();
        $inventoryController->adminIndex();
    }
    
    /**
     * Route to Order admin
     */
    public function orders() {
        require_once __DIR__ . '/OrderController.php';
        $orderController = new OrderController();
        $orderController->adminIndex();
    }
}
