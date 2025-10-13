<?php

class MenuController extends Controller {
    public function index() {
        $productModel = $this->model('Menu');

        $categories = $productModel->getAllCategory();
        $products = $productModel->getAll();

        $data = [
            'title' => 'Thực đơn',
            'categories' => $categories,
            'products' => $products,
            'js' => 'menu'
        ];
        $this->view('menu/index', $data);
    }
    
    public function addToCart() {
    session_start();

        $idCustomer = 1;
        $idProduct = $_POST['id_product'];
        $quantity = $_POST['quantity'];

        // ✅ Gọi model trước khi dùng
        $productModel = $this->model('Menu');

        // Lấy hoặc tạo giỏ hàng
        $cart = $productModel->getOpenCart($idCustomer);
        if (!$cart) {
            $cartId = $productModel->createCart($idCustomer);
        } else {
            // nếu getOpenCart trả mảng, lấy phần tử đầu
            $cartId = is_array($cart[0]) ? $cart[0]['ID'] : $cart['ID'];
        }

        // Thêm item vào giỏ hàng
        $productModel->addItem($cartId, $idProduct, $quantity);

        // ✅ Trả về JSON đúng định dạng
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Đã thêm vào giỏ hàng']);
    }
    

}

$action = $_GET['action'] ?? '';
$controller = new MenuController();

switch ($action) {
    case 'addToCart':
        $controller->addToCart();
        break;
    default:
        $controller->index();
        break;
    }
