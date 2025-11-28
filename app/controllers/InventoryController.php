<?php
require_once __DIR__ . '/../core/Controller.php';

class InventoryController extends Controller {
    
    public function index() {
        // Load Inventory model
        $inventoryModel = $this->model('Inventory');
        
        // Get all inventory data
        $items = $inventoryModel->getAllInventory();
        $materials = $inventoryModel->getAllMaterials();
        $branches = $inventoryModel->getAllBranches();
        $stats = $inventoryModel->getInventoryStats();
        
        // Prepare data for view
        $data = [
            'title' => 'Quản lý kho',
            'items' => $items,
            'materials' => $materials,
            'branches' => $branches,
            'stats' => $stats
        ];
        
        // Load view
        $this->view('admin/home/inventory', $data);
    }
    
    // ==================== ADMIN INVENTORY MANAGEMENT ====================
    
    public function adminIndex() {
        $this->checkAuth();
        
        // Handle form submissions
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            $this->handleAdminAction();
        }
        
        // Handle delete via GET
        if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
            $this->delete();
        }
        
        // Load inventory model
        $inventoryModel = $this->model('Inventory');
        
        // Get all necessary data
        $items = $inventoryModel->getAllInventory();
        $materials = $inventoryModel->getAllMaterials();
        $branches = $inventoryModel->getAllBranches();
        $stats = $inventoryModel->getInventoryStats();
        
        // Debug: Log data counts
        error_log("Inventory items count: " . count($items));
        error_log("Materials count: " . count($materials));
        error_log("Branches count: " . count($branches));
        error_log("Stats: " . print_r($stats, true));
        
        // Handle success/error messages
        $successMessage = '';
        $errorMessage = '';
        
        if (isset($_GET['success'])) {
            switch ($_GET['success']) {
                case 'add':
                    $successMessage = 'Thêm nguyên liệu vào kho thành công!';
                    break;
                case 'edit':
                    $successMessage = 'Cập nhật thông tin kho thành công!';
                    break;
                case 'delete':
                    $successMessage = 'Xóa nguyên liệu khỏi kho thành công!';
                    break;
            }
        }
        
        if (isset($_GET['error'])) {
            switch ($_GET['error']) {
                case 'add':
                    $errorMessage = 'Không thể thêm nguyên liệu vào kho!';
                    break;
                case 'edit':
                    $errorMessage = 'Không thể cập nhật thông tin kho!';
                    break;
                case 'delete':
                    $errorMessage = 'Không thể xóa nguyên liệu khỏi kho!';
                    break;
            }
        }
        
        $data = [
            'title' => 'Quản lý kho',
            'action' => 'inventory',
            'items' => $items,
            'materials' => $materials,
            'branches' => $branches,
            'stats' => $stats,
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
        if (!in_array($role, [1, 2, 3])) {
            echo "<script>
                alert('Bạn không có quyền truy cập trang này!');
                window.location.href = '" . BASE_URL . "login_admin';
            </script>";
            exit();
        }
    }
    
    private function handleAdminAction() {
        $action = $_POST['action'];
        
        if ($action === 'add_item') {
            $this->add();
        } elseif ($action === 'edit_item') {
            $this->edit();
        }
    }
    
    public function add() {
        $id_material = (int)($_POST['id_material'] ?? 0);
        $id_branch = (int)($_POST['id_branch'] ?? 0);
        $quantity = (int)($_POST['quantity'] ?? 0);
        
        if ($id_material > 0 && $id_branch > 0) {
            $inventoryModel = $this->model('Inventory');
            if ($inventoryModel->createInventory($id_material, $id_branch, $quantity)) {
                header('Location: ' . BASE_URL . 'admin/inventory?success=add');
                exit;
            }
        }
        header('Location: ' . BASE_URL . 'admin/inventory?error=add');
        exit;
    }
    
    public function edit() {
        $itemId = (int)($_POST['item_id'] ?? 0);
        if ($itemId > 0) {
            $id_material = (int)($_POST['id_material'] ?? 0);
            $id_branch = (int)($_POST['id_branch'] ?? 0);
            $quantity = (int)($_POST['quantity'] ?? 0);
            
            if ($id_material > 0 && $id_branch > 0) {
                $inventoryModel = $this->model('Inventory');
                if ($inventoryModel->updateInventory($itemId, $id_material, $id_branch, $quantity)) {
                    header('Location: ' . BASE_URL . 'admin/inventory?success=edit');
                    exit;
                }
            }
        }
        header('Location: ' . BASE_URL . 'admin/inventory?error=edit');
        exit;
    }
    
    public function delete() {
        $itemId = (int)($_GET['id'] ?? 0);
        if ($itemId > 0) {
            $inventoryModel = $this->model('Inventory');
            if ($inventoryModel->deleteInventory($itemId)) {
                header('Location: ' . BASE_URL . 'admin/inventory?success=delete');
                exit;
            }
        }
        header('Location: ' . BASE_URL . 'admin/inventory?error=delete');
        exit;
    }
}
?>
