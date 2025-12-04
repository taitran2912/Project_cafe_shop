<?php
class DigitalmenuController extends Controller {
    public function table($tableNumber) {
        $digitalmenuModel = $this->model('Digitalmenu');

        // Lấy thông tin store theo table
        $store = $digitalmenuModel->tableByStore($tableNumber);

        if (empty($store)) {
            die("Table not found or inactive.");
        }

        $storeID = $store[0]['ID'];
        $storeName = $store[0]['Name'];
        $storeAddress = $store[0]['Address'];

        // Lấy sản phẩm và category của store
        $categories = $digitalmenuModel->categories();
        $products = $digitalmenuModel->product();

        $data = [
            'title' => 'Digital Menu',
            'tableNumber' => $tableNumber,
            'storeID' => $storeID,
            'storeName' => $storeName,
            'storeAddress' => $storeAddress,
            'categories' => $categories,
            'products' => $products
        ];

        $this->view('digitalmenu/index', $data);
    }

    public function store($storeNumber) {
        $digitalmenuModel = $this->model('Digitalmenu');

        $store = $digitalmenuModel->store($storeNumber);

        if (empty($store)) {
            die("Store not found or inactive.");
        }

        $storeID = $store[0]['ID'];
        $storeName = $store[0]['Name'];
        $storeAddress = $store[0]['Address'];

        $categories = $digitalmenuModel->categories($storeID);
        $products = $digitalmenuModel->product($storeID);

        $data = [
            'title' => 'Digital Menu',
            'storeID' => $storeID,
            'storeName' => $storeName,
            'storeAddress' => $storeAddress,
            'categories' => $categories,
            'products' => $products
        ];

        $this->view('digitalmenu/index', $data);
    }
    
    // Lấy món yêu thích dựa trên số điện thoại hoặc yêu thích phổ biến
    public function favorite() {
        header('Content-Type: application/json');

        $phone = $_GET['phone'] ?? '';

        $digitalmenu = $this->model('Digitalmenu');

        // Nếu có số điện thoại → lấy theo khách
        if (!empty($phone)) {
            $favorites = $digitalmenu->getFavoriteByPhone($phone);

            // Nếu khách chưa từng gọi món → fallback sang phổ biến
            if (empty($favorites)) {
                $favorites = $digitalmenu->getFavoritePopular();
            }

            echo json_encode($favorites);
            return;
        }

        // Không nhập số điện thoại → lấy top món phổ biến
        $popular = $digitalmenu->getFavoritePopular();
        echo json_encode($popular);
    }

    public function FavoritePopular(){
        header('Content-Type: application/json');

        $digitalmenu = $this->model('Digitalmenu');

        $popular = $digitalmenu->getFavoritePopular();
        echo json_encode($popular);
    }


}
