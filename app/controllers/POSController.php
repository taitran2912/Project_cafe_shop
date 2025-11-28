<?php
session_start();
$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;

class POSController extends Controller {

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
    
    public function adminIndex() {
        $this->checkAuth();
        $POSModel = $this->model('POS');

        // $store = $POSModel->inforStore($user);


        // if (empty($store)) {
        //     die("Table not found or inactive.");
        // }

        // $storeID = $store[0]['ID'];
        // $storeName = $store[0]['Name'];
        // $storeAddress = $store[0]['Address'];

        // $table = $POSModel->tableByStore($storeID);

        // Lấy sản phẩm và category của store
        $categories = $POSModel->categories();
        $products = $POSModel->product();

        $data = [
            'title' => 'POS',
            'tableNumber' => $tableNumber,
            'storeID' => $storeID,
            'storeName' => $storeName,
            'storeAddress' => $storeAddress,
            'categories' => $categories,
            'products' => $products
        ];

        $this->view('admin/home/index', $data);
    }
}