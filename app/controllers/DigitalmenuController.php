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
        $categories = $digitalmenuModel->categories($storeID);
        $products = $digitalmenuModel->product($storeID);

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
    
// Lấy món yêu thích dựa trên số điện thoại
    public function favorite() {
        $phone = $_GET['phone'] ?? '';

        if(empty($phone)) {
            echo json_encode([]);
            return;
        }

        $digitalmenuModel = $this->model('Digitalmenu');
        $favorites = $digitalmenuModel->getFavoriteByPhone($phone);

        echo json_encode($favorites);
    }

}
