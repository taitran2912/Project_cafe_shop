<?php

class CouponController extends Controller {
    
    /**
     * Frontend coupon page (if needed)
     */
    public function index() {
        // Redirect to home or show coupon list for customers
        header('Location: ' . BASE_URL);
        exit;
    }
    
    public function adminIndex() {
        $this->checkAuth();
        
        // Handle form submissions
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            $this->handleAction();
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
                    $successMessage = 'Thêm khuyến mãi thành công!';
                    break;
                case 'edit':
                    $successMessage = 'Cập nhật khuyến mãi thành công!';
                    break;
                case 'delete':
                    $successMessage = 'Xóa khuyến mãi thành công!';
                    break;
            }
        }
        
        if (isset($_GET['error'])) {
            $errorMessage = 'Có lỗi xảy ra: ' . htmlspecialchars($_GET['error']);
        }
        
        // Xử lý search
        $searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
        $isSearching = !empty($searchQuery);
        
        // Load coupons từ Model
        $couponModel = $this->model('Coupon');
        $allCoupons = $couponModel->getAllCoupons();
        
        // Filter coupons nếu đang search
        if ($isSearching) {
            $coupons = array_filter($allCoupons, function($coupon) use ($searchQuery) {
                $searchLower = strtolower($searchQuery);
                return stripos($coupon['Code'], $searchLower) !== false ||
                       stripos($coupon['Description'], $searchLower) !== false ||
                       stripos($coupon['Discount_value'], $searchLower) !== false;
            });
            $coupons = array_values($coupons);
        } else {
            $coupons = $allCoupons;
        }
        
        $data = [
            'title' => 'Quản lý khuyến mãi',
            'action' => 'coupon',
            'coupons' => $coupons,
            'successMessage' => $successMessage,
            'errorMessage' => $errorMessage,
            'searchQuery' => $searchQuery,
            'isSearching' => $isSearching
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
    
    private function handleAction() {
        $action = $_POST['action'];
        
        if ($action === 'add_coupon') {
            $this->create();
        } elseif ($action === 'edit_coupon') {
            $this->update();
        }
    }
    
    public function create() {
        // Map form field names to model field names
        $couponData = [
            'Code' => trim($_POST['code'] ?? ''),
            'Description' => trim($_POST['description'] ?? ''),
            'Value' => trim($_POST['value'] ?? ''), // Model expects 'Value'
            'StartDate' => trim($_POST['start_date'] ?? ''), // Model expects 'StartDate'
            'EndDate' => trim($_POST['end_date'] ?? ''), // Model expects 'EndDate'
            'Status' => trim($_POST['status'] ?? 'active'),
            'Quantity' => (int)($_POST['quantity'] ?? 0)
        ];
        
        if (!empty($couponData['Code'])) {
            $couponModel = $this->model('Coupon');
            $result = $couponModel->createCoupon($couponData);
            if ($result) {
                header('Location: ' . BASE_URL . 'admin/coupon?success=add');
                exit;
            }
        }
        header('Location: ' . BASE_URL . 'admin/coupon?error=add');
        exit;
    }
    
    public function update() {
        $couponId = (int)($_POST['coupon_id'] ?? 0);
        if ($couponId > 0) {
            // Map form field names to model field names
            $couponData = [
                'Code' => trim($_POST['code'] ?? ''),
                'Description' => trim($_POST['description'] ?? ''),
                'Value' => trim($_POST['value'] ?? ''), // Model expects 'Value'
                'StartDate' => trim($_POST['start_date'] ?? ''), // Model expects 'StartDate'
                'EndDate' => trim($_POST['end_date'] ?? ''), // Model expects 'EndDate'
                'Status' => trim($_POST['status'] ?? 'active'),
                'Quantity' => (int)($_POST['quantity'] ?? 0)
            ];
            
            if (!empty($couponData['Code'])) {
                $couponModel = $this->model('Coupon');
                $result = $couponModel->updateCoupon($couponId, $couponData);
                if ($result) {
                    header('Location: ' . BASE_URL . 'admin/coupon?success=edit');
                    exit;
                } else {
                    // Debug: Check if update actually failed
                    error_log("Coupon update failed for ID: $couponId");
                }
            }
        }
        header('Location: ' . BASE_URL . 'admin/coupon?error=edit');
        exit;
    }
    
    public function delete($couponId) {
        if ($couponId > 0) {
            $couponModel = $this->model('Coupon');
            if ($couponModel->deleteCoupon($couponId)) {
                header('Location: ' . BASE_URL . 'admin/coupon?success=delete');
                exit;
            }
        }
        header('Location: ' . BASE_URL . 'admin/coupon?error=delete');
        exit;
    }
}
