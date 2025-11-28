<?php
class ProductController extends Controller {
    // public function getAllProducts() {
    //     $productModel = $this->model('Product');
    //     return $productModel->getAllProducts();
    // }

    // public function getProductsByPage($page = 1, $limit = 5) {
    //     $productModel = $this->model('Product');
    //     return $productModel->getProductsByPage($page, $limit);
    // }
    
    // public function getProductCount() {
    //     $productModel = $this->model('Product');
    //     return $productModel->countProducts();
    // }
    
    
    /**
     * Store a new product
     */
    public function store($data) {
        $productModel = $this->model('Product');
        $payload = [];
        $payload['ID_category'] = isset($data['ID_category']) ? (int)$data['ID_category'] : null;
        $payload['Name'] = isset($data['Name']) ? trim($data['Name']) : '';
        $payload['Description'] = isset($data['Description']) ? trim($data['Description']) : '';
        $payload['Price'] = isset($data['Price']) ? (float)$data['Price'] : 0;
        $payload['Status'] = isset($data['Status']) ? trim($data['Status']) : 'active';
        $payload['Image'] = isset($data['Image']) ? trim($data['Image']) : '';

        if ($payload['Name'] === '') return false;
        return $productModel->createProduct($payload);
    }

    /**
     * Update product
     */
    public function update($id, $data) {
        $productModel = $this->model('Product');
        $id = (int)$id;
        if ($id <= 0) return false;
        $payload = [];
        if (isset($data['ID_category'])) $payload['ID_category'] = (int)$data['ID_category'];
        if (isset($data['Name'])) $payload['Name'] = trim($data['Name']);
        if (isset($data['Description'])) $payload['Description'] = trim($data['Description']);
        if (isset($data['Price'])) $payload['Price'] = (float)$data['Price'];
        if (isset($data['Status'])) $payload['Status'] = trim($data['Status']);
        if (isset($data['Image'])) $payload['Image'] = trim($data['Image']);
        if (count($payload) === 0) return false;
        return $productModel->updateProduct($id, $payload);
    }

    /**
     * Delete product
     */
    public function delete($id) {
        $productModel = $this->model('Product');
        $id = (int)$id;
        if ($id <= 0) return false;
        return $productModel->deleteProduct($id);
    }

    /**
     * Paginate products
     */
    public function paginate($page = 1, $limit = 5) {
        $productModel = $this->model('Product');
        $page = (int)$page; if ($page < 1) $page = 1;
        $limit = (int)$limit; if ($limit < 1) $limit = 5;
        $totalItems = $productModel->countProducts();
        $totalPages = ($totalItems > 0) ? (int)ceil($totalItems / $limit) : 1;
        if ($page > $totalPages) $page = $totalPages;
        $products = $productModel->getProductsByPage($page, $limit);
        return [
            'products' => $products,
            'totalItems' => $totalItems,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'limit' => $limit
        ];
    }

    /**
     * Search products with pagination for AJAX
     */
    public function search($q = '', $page = 1, $limit = 5) {
        $productModel = $this->model('Product');
        $page = (int)$page; if ($page < 1) $page = 1;
        $limit = (int)$limit; if ($limit < 1) $limit = 5;
        $totalItems = $productModel->countSearchProducts($q);
        $totalPages = ($totalItems > 0) ? (int)ceil($totalItems / $limit) : 1;
        if ($page > $totalPages) $page = $totalPages;
        $offset = ($page - 1) * $limit;
        $products = $productModel->searchProducts($q, $limit, $offset);
        return [
            'products' => $products,
            'totalItems' => $totalItems,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'limit' => $limit
        ];
    }
    
    // ==================== ADMIN MENU MANAGEMENT ====================
    public function adminIndex() {
        $this->checkAuth();

        // --- Handle Actions ---
        // $this->handleAdminRequests();

        // // --- Messages ---
        // $successMessage = $this->getSuccessMessage();
        // $errorMessage   = $this->getErrorMessage();

        // --- Render View ---
        $data = [
            'title'           => 'Quản lý sản phẩm'
            // 'successMessage'  => $successMessage,
            // 'errorMessage'    => $errorMessage,
        ];

        $this->view('admin/home/index', $data);
    }

    private function handleAdminRequests(){
        // Handle POST actions
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            $this->handleAdminAction();
            return;
        }

        // Handle GET delete
        if (isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'delete') {
            $this->adminDelete((int) $_GET['id']);
            return;
        }
    }

    private function getSuccessMessage(){
        if (!isset($_GET['success'])) return '';

        return match($_GET['success']) {
            'add'    => 'Thêm sản phẩm mới thành công!',
            'edit'   => 'Cập nhật sản phẩm thành công!',
            'delete' => 'Xóa sản phẩm thành công!',
            default  => ''
        };
    }

    private function getErrorMessage(){
        return $_GET['error'] ?? '';
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
        
        if ($action === 'add_product') {
            $this->adminCreate();
        } elseif ($action === 'edit_product') {
            $this->adminUpdate();
        }
    }
    
    public function adminCreate() {
        $productData = [
            'ID_category' => (int)($_POST['category'] ?? 0),
            'Name' => trim($_POST['name'] ?? ''),
            'Description' => trim($_POST['description'] ?? ''),
            'Price' => (float)($_POST['price'] ?? 0),
            'Status' => trim($_POST['status'] ?? 'available'),
            'Image' => ''
        ];
        
        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/image/products/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (in_array($fileExtension, $allowedExtensions)) {
                $newFileName = uniqid('product_') . '.' . $fileExtension;
                $uploadPath = $uploadDir . $newFileName;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                    $productData['Image'] = 'public/image/products/' . $newFileName;
                }
            }
        }
        
        if (!empty($productData['Name']) && $productData['ID_category'] > 0 && $productData['Price'] > 0) {
            $productModel = $this->model('Product');
            if ($productModel->createProduct($productData)) {
                header('Location: ' . BASE_URL . 'admin/menu?success=add');
                exit;
            }
        }
        header('Location: ' . BASE_URL . 'admin/menu?error=add');
        exit;
    }
    
    public function adminUpdate() {
        $productId = (int)($_POST['product_id'] ?? 0);
        if ($productId > 0) {
            $productData = [
                'ID_category' => (int)($_POST['category'] ?? 0),
                'Name' => trim($_POST['name'] ?? ''),
                'Description' => trim($_POST['description'] ?? ''),
                'Price' => (float)($_POST['price'] ?? 0),
                'Status' => trim($_POST['status'] ?? 'available')
            ];
            
            // Handle image upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../../public/image/products/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                if (in_array($fileExtension, $allowedExtensions)) {
                    $newFileName = uniqid('product_') . '.' . $fileExtension;
                    $uploadPath = $uploadDir . $newFileName;
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                        $productData['Image'] = 'public/image/products/' . $newFileName;
                    }
                }
            }
            
            if (!empty($productData['Name']) && $productData['Price'] > 0) {
                $productModel = $this->model('Product');
                if ($productModel->updateProduct($productId, $productData)) {
                    header('Location: ' . BASE_URL . 'admin/menu?success=edit');
                    exit;
                }
            }
        }
        header('Location: ' . BASE_URL . 'admin/menu?error=edit');
        exit;
    }
    
    public function adminDelete($productId) {
        if ($productId > 0) {
            $productModel = $this->model('Product');
            if ($productModel->deleteProduct($productId)) {
                header('Location: ' . BASE_URL . 'admin/menu?success=delete');
                exit;
            }
        }
        header('Location: ' . BASE_URL . 'admin/menu?error=delete');
        exit;
    }
}

?>