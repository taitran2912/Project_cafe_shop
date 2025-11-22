<?php

class OrderController extends Controller {
    
    public function index() {
        // This will load the orders.php view through index.php
        // The view will handle displaying orders
    }

    public function confirm() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
            $orderId = (int)$_POST['order_id'];
            
            $orderModel = $this->model('Order');
            
            if ($orderModel->confirmOrder($orderId)) {
                header('Location: /Project_cafe_shop/admin/orders?success=confirm');
                exit;
            } else {
                header('Location: /Project_cafe_shop/admin/orders?error=confirm');
                exit;
            }
        }
        
        header('Location: /Project_cafe_shop/admin/orders');
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
        
        echo json_encode(['count' => $count]);
        exit;
    }
}
?>
