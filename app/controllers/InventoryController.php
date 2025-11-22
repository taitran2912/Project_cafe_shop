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
    
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $inventoryModel = $this->model('Inventory');
            
            $id_material = (int)($_POST['id_material'] ?? 0);
            $id_branch = (int)($_POST['id_branch'] ?? 0);
            $quantity = (int)($_POST['quantity'] ?? 0);
            
            if ($id_material > 0 && $id_branch > 0) {
                if ($inventoryModel->createInventory($id_material, $id_branch, $quantity)) {
                    header('Location: /Project_cafe_shop/admin/inventory?success=add');
                    exit;
                }
            }
        }
        
        // If POST failed or GET request, redirect to index
        header('Location: /Project_cafe_shop/admin/inventory');
        exit;
    }
    
    public function edit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $inventoryModel = $this->model('Inventory');
            $itemId = (int)($_POST['item_id'] ?? 0);
            
            if ($itemId > 0) {
                $id_material = (int)($_POST['id_material'] ?? 0);
                $id_branch = (int)($_POST['id_branch'] ?? 0);
                $quantity = (int)($_POST['quantity'] ?? 0);
                
                if ($id_material > 0 && $id_branch > 0) {
                    if ($inventoryModel->updateInventory($itemId, $id_material, $id_branch, $quantity)) {
                        header('Location: /Project_cafe_shop/admin/inventory?success=edit');
                        exit;
                    }
                }
            }
        }
        
        header('Location: /Project_cafe_shop/admin/inventory');
        exit;
    }
    
    public function delete() {
        if (isset($_GET['id'])) {
            $inventoryModel = $this->model('Inventory');
            $itemId = (int)$_GET['id'];
            
            if ($itemId > 0) {
                if ($inventoryModel->deleteInventory($itemId)) {
                    header('Location: /Project_cafe_shop/admin/inventory?success=delete');
                    exit;
                }
            }
        }
        
        header('Location: /Project_cafe_shop/admin/inventory');
        exit;
    }
}
?>
