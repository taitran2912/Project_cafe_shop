<?php
class DigitalmenuController extends Controller {

    /** =============================
     *  HIỆN MENU THEO MÃ BÀN
     *  ============================= */
    public function table($tableNumber) {
        $digitalmenuModel = $this->model('Digitalmenu');

        // Lấy store từ mã bàn
        $store = $digitalmenuModel->tableByStore($tableNumber);
        if (empty($store)) {
            die("Table not found or inactive.");
        }
        $recommended = $digitalmenuModel->getRecommended();
        $new = $digitalmenuModel->getNewProducts();

        $storeInfo = $store[0];

        // Lấy categories + products của toàn hệ thống (nếu không truyền ID)
        $data = [
            'title'        => 'Digital Menu',
            'tableNumber'  => $tableNumber,
            'recommended'  => $recommended,
            'newItems'     => $new,    
            'storeID'      => $storeInfo['ID'],
            'storeName'    => $storeInfo['Name'],
            'storeAddress' => $storeInfo['Address'],
            'categories'   => $digitalmenuModel->categories(),
            'products'     => $digitalmenuModel->product()
        ];

        $this->view('digitalmenu/index', $data);
    }

    /** =============================
     *  HIỆN MENU THEO CHI NHÁNH (STORE)
     *  ============================= */
    public function store($storeNumber) {
        $digitalmenuModel = $this->model('Digitalmenu');

        $store = $digitalmenuModel->store($storeNumber);
        if (empty($store)) {
            die("Store not found or inactive.");
        }

        $recommended = $digitalmenuModel->getRecommended();
        $new = $digitalmenuModel->getNewProducts();

        $storeInfo = $store[0];
        $storeID   = $storeInfo['ID'];

        // Lấy categories + products theo store
        $data = [
            'title'        => 'Digital Menu',
            'storeID'      => $storeID,
            'recommended'  => $recommended,
            'newItems'     => $new,  
            'storeName'    => $storeInfo['Name'],
            'storeAddress' => $storeInfo['Address'],
            'categories'   => $digitalmenuModel->categories($storeID),
            'products'     => $digitalmenuModel->product($storeID)
        ];

        $this->view('digitalmenu/index', $data);
    }

    /** =============================
     *  MÓN YÊU THÍCH (THEO PHONE)
     *  ============================= */
    public function favorite() {
        header('Content-Type: application/json');

        $phone = $_GET['phone'] ?? '';
        $digitalmenu = $this->model('Digitalmenu');

        // Mặc định lấy món phổ biến
        $favorites = $digitalmenu->getFavoritePopular();

        // Nếu có SĐT → thử lấy món đã gọi
        if (!empty($phone)) {
            $userFav = $digitalmenu->getFavoriteByPhone($phone);

            // Kiểm tra hợp lệ: phải là array & không rỗng
            if (is_array($userFav) && !empty($userFav)) {
                $favorites = $userFav;
            }
        }

        echo json_encode($favorites);
    }

    /** =============================
     *  MÓN PHỔ BIẾN
     *  ============================= */
    public function popular() {
        header('Content-Type: application/json');

        $popular = $this->model('Digitalmenu')->getPopularFavorites();
        echo json_encode($popular);
    }
}
