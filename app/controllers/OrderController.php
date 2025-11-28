<?php

class OrderController extends Controller {
    
    public function index() {
        // This will load the orders.php view through index.php
        // The view will handle displaying orders
    }
    
    // ==================== ADMIN ORDER MANAGEMENT ====================
    
    public function adminIndex() {
        $this->checkAuth();
        
        // Handle AJAX request for pending count
        // if (isset($_GET['action']) && $_GET['action'] === 'get_pending_count') {
        //     $this->getPendingCount();
        // }
        
        // // Handle confirm order
        // if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'confirm_order') {
        //     $this->confirm();
        // }
        
        // // Handle AJAX update item status
        // if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_item_status') {
        //     $this->updateItemStatus();
        // }
        
        // Initialize messages
        // $successMessage = '';
        // $errorMessage = '';

        // // Handle success messages from redirect
        // if (isset($_GET['success'])) {
        //     switch ($_GET['success']) {
        //         case 'confirm':
        //             $successMessage = 'Xác nhận đơn hàng thành công!';
        //             break;
        //         case 'update_item':
        //             $successMessage = 'Cập nhật trạng thái món thành công!';
        //             break;
        //     }
        // }

        // // Handle error messages
        // if (isset($_GET['error'])) {
        //     switch ($_GET['error']) {
        //         case 'confirm':
        //             $errorMessage = 'Xác nhận đơn hàng thất bại!';
        //             break;
        //         case 'update_item':
        //             $errorMessage = 'Cập nhật trạng thái món thất bại!';
        //             break;
        //     }
        // }
        
        // Load orders
        $orderModel = $this->model('Order');
        $orders = $orderModel->getPendingOrders();

        // Load order details và tính tổng cho mỗi đơn
        foreach ($orders as &$order) {
            $order['details'] = $orderModel->getOrderDetails($order['ID']);

            // Tính tổng tiền sản phẩm
            $totalProduct = 0;
            foreach ($order['details'] as $item) {
                $totalProduct += $item['Quantity'] * $item['Price'];
            }

            // Cộng thêm phí vận chuyển
            $order['Total'] = $totalProduct + $order['Shipping_Cost'];
        }
        unset($order); // break reference

        $pendingCount = count(array_filter($orders, function($order) {
            return strtolower($order['Status']) === 'pending';
        }));

        $data = [
            'title' => 'Quản lý đơn hàng',
            'action' => 'orders',
            'orders' => $orders,
            'pendingCount' => $pendingCount
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

    public function confirm() {
        $orderId = (int)($_POST['order_id'] ?? 0);
        if ($orderId > 0) {
            $orderModel = $this->model('Order');
            if ($orderModel->confirmOrder($orderId)) {
                header('Location: ' . BASE_URL . 'admin/orders?success=confirm');
                exit;
            }
        }
        header('Location: ' . BASE_URL . 'admin/orders?error=confirm');
        exit;
    }

    public function updateItemStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item_id']) && isset($_POST['status'])) {
            $itemId = (int)$_POST['item_id'];
            $status = $_POST['status'];
            
            // Validate status
            $validStatuses = ['pending', 'preparing', 'completed', 'served'];
            if (!in_array($status, $validStatuses)) {
                echo json_encode(['success' => false, 'message' => 'Invalid status']);
                exit;
            }
            
            // For now, return success (we'll need to add item status tracking to DB later)
            echo json_encode(['success' => true]);
            exit;
        }
        
        echo json_encode(['success' => false]);
        exit;
    }

    public function getPendingCount() {
        $orderModel = $this->model('Order');
        $count = $orderModel->getPendingCount();
        header('Content-Type: application/json');
        echo json_encode(['count' => $count]);
        exit;
    }
}
?>
