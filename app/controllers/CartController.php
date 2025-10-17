<?php
class CartController extends Controller {
    public function index() {
        // Kiểm tra session đăng nhập
        session_start();
        if (!isset($_SESSION['user']['ID'])) {
            header("Location: " . BASE_URL . "login");
            exit();
        }

        $customerID = $_SESSION['user']['ID'];

        // Gọi model
        // $cartModel = $this->model('Cart');

        // Lấy giỏ hàng từ database
        // $cartItems = $cartModel->getCartItems($customerID);

        $data = [
            'title' => 'Giỏ hàng',
            'cartItems' => $cartItems
        ];

        $this->view('cart/index', $data);
    }

    // API trả JSON để JS load (nếu muốn dùng fetch AJAX)
    public function getCartData($customerID = null) {
        header('Content-Type: application/json');

        session_start();
        if (!$customerID) {
            $customerID = $_SESSION['user']['ID'] ?? null;
        }

        if (!$customerID) {
            echo json_encode(['error' => 'Chưa đăng nhập']);
            return;
        }

        $cartModel = $this->model('Cart');
        $cartItems = $cartModel->getCartItems($customerID);
        echo json_encode($cartItems);
    }
}
?>
