<?php
class Digitalmenu extends Model {

    /* ================================
        LẤY THÔNG TIN CHI NHÁNH
    ================================= */
    public function store($storeNumber) {
        $stmt = $this->db->prepare("
            SELECT * FROM Branches 
            WHERE ID = ? AND Status = 'active'
        ");
        $stmt->bind_param("i", $storeNumber);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function tableByStore($tableNumber) {
        $stmt = $this->db->prepare("
            SELECT b.ID, b.Name, b.Address, t.No 
            FROM Branches b 
            JOIN Table_Coffee t ON t.ID_Brach = b.ID 
            WHERE t.ID = ? AND b.Status = 'active'
        ");
        $stmt->bind_param("i", $tableNumber);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /* ================================
        LẤY DANH SÁCH SẢN PHẨM
    ================================= */
    public function product() {
        $query = "SELECT * FROM Product WHERE Status = 'active'";
        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /* ================================
        LẤY DANH SÁCH DANH MỤC
    ================================= */
    public function categories() {
        $query = "SELECT * FROM Categories WHERE Status = 'active'";
        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /* ================================
        MÓN YÊU THÍCH THEO SỐ ĐIỆN THOẠI
    ================================= */
    public function getFavoriteByPhone($phone) {
        $query = "
            SELECT p.ID, p.Name, p.Price, p.Image, p.Description, 
                   SUM(od.Quantity) AS total_ordered 
            FROM Account acc 
            JOIN Orders o ON acc.ID = o.ID_Customer 
            JOIN Order_Detail od ON o.ID = od.ID_order 
            JOIN Product p ON p.ID = od.ID_product 
            WHERE acc.Phone = ? AND p.Status = 'active'
            GROUP BY p.ID
            ORDER BY total_ordered DESC 
            LIMIT 6
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $phone);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /* ================================
        TOP MÓN PHỔ BIẾN
    ================================= */
    public function getPopularFavorites() {
        $query = "
            SELECT p.ID, p.Name, p.Price, p.Image, p.Description, 
                   SUM(od.Quantity) AS total_ordered 
            FROM Order_Detail od 
            JOIN Product p ON p.ID = od.ID_product 
            WHERE p.Status = 'active'
            GROUP BY p.ID 
            ORDER BY total_ordered DESC 
            LIMIT 6
        ";

        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /* ================================
        MÓN MỚI
    ================================= */
    public function getNewProducts() {
        $query = "
            SELECT ID, Name, Price, Image, Description, Created_At 
            FROM Product 
            WHERE Status = 'active'
            ORDER BY Created_At DESC 
            LIMIT 6
        ";
        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /* ================================
        LẤY CATEGORY ID TỪ PRODUCT ID
    ================================= */
    public function getCategoryByProductId($productId) {
        $stmt = $this->db->prepare("
            SELECT ID_category 
            FROM Product 
            WHERE ID = ? AND Status = 'active'
            LIMIT 1
        ");
        $stmt->bind_param("i", $productId);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    /* ================================
        MÓN CÙNG DANH MỤC
    ================================= */
    public function getSimilarProductsByCategory($categoryId, $excludeIds = [], $limit = 6) {

        if (!$categoryId || $limit <= 0) return [];

        $notIn = "";
        if (!empty($excludeIds)) {
            $safeIds = implode(",", array_map('intval', $excludeIds));
            $notIn = "AND ID NOT IN ($safeIds)";
        }

        $sql = "
            SELECT ID, ID_category, Name, Description, Price, Image
            FROM Product
            WHERE Status = 'active'
              AND ID_category = ?
              $notIn
            ORDER BY RAND()
            LIMIT ?
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $categoryId, $limit);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
