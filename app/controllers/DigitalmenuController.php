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
        $dm = $this->model('Digitalmenu');
        // 1) Lấy danh sách ban đầu
        if (!empty($phone)) {
            $favorites = $dm->getFavoriteByPhone($phone);
            // Nếu khách chưa có lịch sử → lấy món phổ biến
            if (empty($favorites)) {
                $favorites = $dm->getFavoritePopular();
            }
        } else {
            $favorites = $dm->getFavoritePopular();
        }
        // Danh sách ID để chống trùng
        $existingIds = array_column($favorites, 'ID');
        // 2) BỔ SUNG MÓN để đủ 6 → Không trùng sản phẩm
        $favorites = $this->fillRecommendedItems($favorites, $existingIds, $dm, 6);
        echo json_encode($favorites);
    }

    private function fillRecommendedItems($favorites, $existingIds, $dm, $limit = 6) {
        // Nếu đủ số lượng → return ngay
        if (count($favorites) >= $limit) {
            return array_slice($favorites, 0, $limit);
        }
        // 1) Bổ sung món phổ biến
        $popular = $dm->getFavoritePopular();
        foreach ($popular as $p) {
            if (!in_array($p['ID'], $existingIds)) {
                $favorites[] = $p;
                $existingIds[] = $p['ID'];
            }
            if (count($favorites) >= $limit) break;
        }
        // 2) Nếu vẫn thiếu → lấy món cùng danh mục với món đầu tiên
        if (count($favorites) < $limit && !empty($favorites)) {
            $category = $dm->getCategoryByProductId($favorites[0]['ID']);
            if ($category) {
                $similar = $dm->getSimilarProductsByCategory(
                    $category['ID_category'],
                    $existingIds,
                    $limit - count($favorites)
                );
                foreach ($similar as $s) {
                    if (!in_array($s['ID'], $existingIds)) {
                        $favorites[] = $s;
                        $existingIds[] = $s['ID'];
                    }
                    if (count($favorites) >= $limit) break;
                }
            }
        }
        // Trả về đúng số lượng yêu cầu
        return array_slice($favorites, 0, $limit);
    }



}
