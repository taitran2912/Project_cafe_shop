<?php
class Digitalmenu extends Model {
    public function store($storeNumber) {
        $stmt = $this->db->prepare("SELECT * FROM Branches WHERE ID = ? AND Status = 'active'");
        $stmt->bind_param("i", $storeNumber); // "i" = integer
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        $stmt->close();
        return $data;
    }
    public function tableByStore($tableNumber) {
        $stmt = $this->db->prepare("SELECT b.ID, b.Name, b.Address, t.No 
                                    FROM Branches b JOIN Table_Coffee t ON t.ID_Brach = b.ID 
                                    WHERE t.ID = ? AND b.Status = 'active';");
        $stmt->bind_param("i", $tableNumber);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        $stmt->close();
        return $data;
    }

    public function product() {
        $stmt = $this->db->prepare("SELECT p.* 
                                    FROM Products p 
                                    WHERE p.Status = 'active';");
        // $stmt->bind_param("i", $storeID);
        // $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        $stmt->close();
        return $data;
    }

    public function categories() {
        $query = "SELECT * FROM Categories WHERE Status = 'active';";
        $result = $this->db->query($query);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

// Lấy món yêu thích dựa trên số điện thoại
    public function getFavoriteByPhone($phone) {
        $query = "SELECT p.Name, p.Price, p.Image, p.Description, SUM(od.Quantity) AS total_ordered 
                    FROM Account acc 
                    JOIN Orders o ON acc.ID = o.ID_Customer 
                    JOIN Order_detail od ON o.ID = od.ID_order 
                    JOIN Product p ON p.ID = od.ID_product 
                    WHERE acc.Phone = ? 
                    AND p.Status = 'active' 
                    GROUP BY p.ID 
                    ORDER BY total_ordered 
                    DESC LIMIT 6"; // Top 5 món yêu thích

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $phone);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function getPopularFavorites() {
        $query = "SELECT p.Name, p.Price, p.Image, p.Description, SUM(od.Quantity) AS total_ordered 
                    FROM Order_detail od 
                    JOIN Product p ON p.ID = od.ID_product 
                    WHERE p.Status = 'active' 
                    GROUP BY p.ID 
                    ORDER BY total_ordered 
                    DESC LIMIT 6"; // Top 6 món phổ biến

        $result = $this->db->query($query);

        $data = [];
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function getNewProducts() {
        $query = "SELECT p.Name, p.Price, p.Image, p.Description, p.Created_At 
                    FROM Product p 
                    WHERE p.Status = 'active' 
                    ORDER BY p.Created_At DESC 
                    LIMIT 6"; // Top 6 món mới nhất

        $result = $this->db->query($query);

        $data = [];
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }   

    public function getCategoryByProductId($Cat){
        $query = "SELECT * FROM Product WHERE ID_category = ? AND Status = 'active';";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $Cat);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function getSimilarProductsByCategory($categoryId, $excludeIds = [], $limit = 6) {
        // Nếu không có ID loại hoặc limit = 0 thì trả về rỗng
        if (!$categoryId || $limit <= 0) return [];

        // Chuẩn bị phần NOT IN (tránh lỗi SQL khi mảng rỗng)
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

        $result = $stmt->get_result();
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }

}   
