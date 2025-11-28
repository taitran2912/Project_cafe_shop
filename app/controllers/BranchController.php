<?php
require_once __DIR__ . '/../core/Controller.php';
class BranchController extends Controller {
    public function index() {
        $branchModel = $this->model('Branch');
        
        $data = [
            'title' => 'admin',
            'branches' => $branchModel->getAllBranches(),
            'pagination' => $branchModel->paginate(),
        ];
        $this->view('admin/manager/QLCN', $data);
    }
    
    // ==================== ADMIN BRANCH MANAGEMENT ====================
    
    public function adminIndex() {
        $this->checkAuth();
        
        // Handle form submissions
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            $this->handleAdminAction();
        }
        
        // Handle delete via GET
        if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
            $this->delete((int)$_GET['id']);
        }
        
        // Xử lý success/error messages
        $successMessage = '';
        $errorMessage = '';
        
        if (isset($_GET['success'])) {
            switch ($_GET['success']) {
                case 'add':
                    $successMessage = 'Thêm chi nhánh thành công!';
                    break;
                case 'edit':
                    $successMessage = 'Cập nhật chi nhánh thành công!';
                    break;
                case 'delete':
                    $successMessage = 'Xóa chi nhánh thành công!';
                    break;
            }
        }
        
        if (isset($_GET['error'])) {
            $errorMessage = 'Có lỗi xảy ra: ' . htmlspecialchars($_GET['error']);
        }
        
        // Load branches từ Model
        $branchModel = $this->model('Branch');
        $branches = $branchModel->getAllBranch();
        
        $data = [
            'title' => 'Quản lý chi nhánh',
            'action' => 'branch',
            'branches' => $branches,
            'successMessage' => $successMessage,
            'errorMessage' => $errorMessage
        ];
        $this->view('admin/home/index', $data);
    }
    
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
    
    private function handleAdminAction() {
        $action = $_POST['action'];
        
        if ($action === 'add_branch') {
            $this->create();
        } elseif ($action === 'edit_branch') {
            $this->update();
        }
    }
    
    public function create() {
        $name = trim($_POST['name'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $status = trim($_POST['status'] ?? 'active');
        
        if (!empty($name) && !empty($address) && !empty($phone)) {
            $branchModel = $this->model('Branch');
            if ($branchModel->addBranch($name, $address, $phone, $status)) {
                header('Location: ' . BASE_URL . 'admin/branch?success=add');
                exit;
            }
        }
        header('Location: ' . BASE_URL . 'admin/branch?error=add');
        exit;
    }
    
    public function update() {
        $branchId = (int)($_POST['branch_id'] ?? 0);
        if ($branchId > 0) {
            $name = trim($_POST['name'] ?? '');
            $address = trim($_POST['address'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $status = trim($_POST['status'] ?? 'active');
            
            if (!empty($name) && !empty($address) && !empty($phone)) {
                $branchModel = $this->model('Branch');
                if ($branchModel->updateBranch($branchId, $name, $address, $phone, $status)) {
                    header('Location: ' . BASE_URL . 'admin/branch?success=edit');
                    exit;
                }
            }
        }
        header('Location: ' . BASE_URL . 'admin/branch?error=edit');
        exit;
    }
    
    public function delete($branchId) {
        if ($branchId > 0) {
            $branchModel = $this->model('Branch');
            if ($branchModel->deleteBranch($branchId)) {
                header('Location: ' . BASE_URL . 'admin/branch?success=delete');
                exit;
            }
        }
        header('Location: ' . BASE_URL . 'admin/branch?error=delete');
        exit;
    }
}

