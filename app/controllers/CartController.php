<?php
// $customerId = $_SESSION['user']['ID']; // tạm thời cố định người dùng
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

class CartController extends Controller {
    public function index() {
        $cartModel = $this->model('Cart');
        // giả sử khách hàng đang đăng nhập có ID = 1

        // $cartItems = $cartModel->getCartByCustomer($customerId);

        $data = [
            'title' => 'Giỏ hàng',
            'css' => 'cart.css'
            // 'cartItems' => $cartItems
        ];

        $this->view('cart/index', $data);
    }

    // ✅ API lấy dữ liệu giỏ hàng (cho AJAX)
   public function getCart($customerId) {
    $cartModel = $this->model('Cart');
     // tạm thời cố định người dùng

    $cartItems = $cartModel->getCartByCustomer($customerId);

    // Trả về JSON thuần
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($cartItems, JSON_UNESCAPED_UNICODE);
    exit; // đảm bảo không in thêm gì khác
}


    // AJAX thêm sản phẩm
    public function add() {
        $cartModel = $this->model('Cart');
        $customerId = $_POST['customer_id'];
        $productId = $_POST['product_id'];
        $quantity = $_POST['quantity'] ?? 1;

        $success = $cartModel->addToCart($customerId, $productId, $quantity);
        echo json_encode(['success' => $success]);
    }

    // AJAX cập nhật số lượng
    public function update() {
        $cartModel = $this->model('Cart');
        $customerId = $_POST['customer_id'];
        $productId = $_POST['product_id'];
        $quantity = $_POST['quantity'];

        $success = $cartModel->updateQuantity($customerId, $productId, $quantity);
        echo json_encode(['success' => $success]);
    }

    // AJAX xoá sản phẩm
    public function delete() {
        $cartModel = $this->model('Cart');
        $customerId = $_POST['customer_id'];
        $productId = $_POST['product_id'];

        $success = $cartModel->removeItem($customerId, $productId);
        echo json_encode(['success' => $success]);
    }
}
