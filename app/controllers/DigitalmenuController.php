<?php
class DigitalmenuController extends Controller {

    /* ======================================
        HIỂN THỊ MENU THEO SỐ BÀN
    ====================================== */
    public function table($tableNumber) {
        $dm = $this->model('Digitalmenu');

        $store = $dm->tableByStore($tableNumber);

        if (empty($store)) {
            die("Table not found or inactive.");
        }

        $data = [
            'title' => 'Digital Menu',
            'tableNumber' => $tableNumber,
            'storeID' => $store[0]['ID'],
            'storeName' => $store[0]['Name'],
            'storeAddress' => $store[0]['Address'],
            'categories' => $dm->categories(),
            'products' => $dm->product()
        ];

        $this->view('digitalmenu/index', $data);
    }

    /* ======================================
        HIỂN THỊ MENU THEO ID CHI NHÁNH
    ====================================== */
    public function store($storeNumber) {
        $dm = $this->model('Digitalmenu');

        $store = $dm->store($storeNumber);

        if (empty($store)) {
            die("Store not found or inactive.");
        }

        $data = [
            'title' => 'Digital Menu',
            'storeID' => $store[0]['ID'],
            'storeName' => $store[0]['Name'],
            'storeAddress' => $store[0]['Address'],
            'categories' => $dm->categories(),
            'products' => $dm->product()
        ];

        $this->view('digitalmenu/index', $data);
    }

    /* ======================================
        API: MÓN YÊU THÍCH / PHỔ BIẾN / TƯƠNG TỰ
    ====================================== */
    public function favorite() {
        header('Content-Type: application/json');

        $phone = $_GET['phone'] ?? '';
        $dm = $this->model('Digitalmenu');

        // 1. Lấy món yêu thích theo số điện thoại
        if (!empty($phone)) {
            $favorites = $dm->getFavoriteByPhone($phone);

            // Nếu không có lịch sử của khách → lấy món phổ biến
            if (empty($favorites)) {
                $favorites = $dm->getPopularFavorites();
            }

        } else {
            // Không nhập số điện thoại → lấy món phổ biến
            $favorites = $dm->getPopularFavorites();
        }

        // 2. Danh sách ID hiện tại để tránh trùng
        $existingIds = array_column($favorites, 'ID');

        // 3. Bổ sung món (phổ biến + tương tự) tới đủ số lượng
        $favorites = $this->addMoreItems($favorites, $existingIds, $dm, 6);

        echo json_encode($favorites);
    }

    /* ======================================
        BỔ SUNG MÓN ĐỂ ĐỦ SỐ LƯỢNG (KHÔNG TRÙNG)
    ====================================== */
    private function addMoreItems($items, $existingIds, $dm, $limit) {

        // Nếu đã đủ
        if (count($items) >= $limit) {
            return array_slice($items, 0, $limit);
        }

        /* === 1. Bổ sung món phổ biến === */
        $popular = $dm->getPopularFavorites();

        foreach ($popular as $p) {
            if (!in_array($p['ID'], $existingIds)) {
                $items[] = $p;
                $existingIds[] = $p['ID'];
            }
            if (count($items) >= $limit) break;
        }

        /* === 2. Nếu vẫn thiếu → lấy món tương tự theo category === */
        if (count($items) < $limit && !empty($items)) {

            $cat = $dm->getCategoryByProductId($items[0]['ID']);

            if (!empty($cat)) {
                $similar = $dm->getSimilarProductsByCategory(
                    $cat['ID_category'],
                    $existingIds,
                    $limit - count($items)
                );

                foreach ($similar as $s) {
                    if (!in_array($s['ID'], $existingIds)) {
                        $items[] = $s;
                        $existingIds[] = $s['ID'];
                    }
                    if (count($items) >= $limit) break;
                }
            }
        }

        return array_slice($items, 0, $limit);
    }
}
