<?php
class Menu extends Model {
    public function getAll() {
        $query = "SELECT p.ID, p.Name, p.Description, p.Price, p.Image, c.Name as Name_Category, c.ID as CategoryID FROM Product p join Categories c on p.ID_category = c.ID WHERE p.Status = 'active';";
        $result = $this->db->query($query);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function getAllCategory() {
        $query = "SELECT * FROM Categories c WHERE c.Status = 'active'";
        $result = $this->db->query($query);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function getOpenCart($idCustomer) {
        $query = "SELECT * FROM Cart WHERE ID_Customer = ?";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $idCustomer);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        $stmt->close();
        return $data;
    }

    public function createCart($idCustomer) {
        $query = "INSERT INTO Cart(ID_Customer) VALUES (?)";
    
        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            throw new Exception("Lỗi prepare statement: " . $this->db->error);
        }

        $stmt->bind_param("i", $idCustomer);
        if (!$stmt->execute()) {
            throw new Exception("Lỗi khi tạo giỏ hàng: " . $stmt->error);
        }

        // Lấy ID giỏ hàng vừa tạo
        $cartId = $stmt->insert_id;

        $stmt->close();
        return $cartId;
    }

    public function addItem($cartId, $productId, $quantity) {
        // Kiểm tra xem sản phẩm đã có trong giỏ chưa
        $query = "SELECT * FROM Cart_detail WHERE ID_Cart = ? AND ID_Product = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $cartId, $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        $item = $result->fetch_assoc();
        $stmt->close();

        if ($item) {
            // Nếu có rồi → cập nhật số lượng
            $query = "UPDATE Cart_detail SET Quantity = Quantity + ? WHERE ID_Cart = ? AND ID_Product = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("iii", $quantity, $cartId, $productId);
            $stmt->execute();
            $stmt->close();
        } else {
            // Nếu chưa có → thêm mới
            $query = "INSERT INTO Cart_detail (ID_Cart, ID_Product, Quantity) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("iii", $cartId, $productId, $quantity);
            $stmt->execute();
            $stmt->close();
        }
    }

    public function getAllSuggestions(){
        $query = "WITH TopCategories AS (
                    SELECT p.ID_category
                    FROM Order_detail od
                    JOIN Product p ON od.ID_product = p.ID
                    GROUP BY p.ID_category
                    ORDER BY SUM(od.Quantity) DESC
                    LIMIT 3
                    ),
                    ProductRank AS (
                    SELECT 
                        p.ID,
                        p.Image,
                        p.Name,
                        p.Price,
                        p.ID_category,
                        SUM(od.Quantity) AS TotalSold,
                        ROW_NUMBER() OVER (
                            PARTITION BY p.ID_category
                            ORDER BY SUM(od.Quantity) DESC
                        ) AS rn
                    FROM Order_detail od
                    JOIN Product p ON od.ID_product = p.ID
                    WHERE p.ID_category IN (SELECT ID_category FROM TopCategories)
                    GROUP BY p.ID, p.Image, p.Name, p.Price, p.ID_category
                    )
                    SELECT *
                    FROM ProductRank
                    WHERE rn <= 4
                    ORDER BY ID_category, TotalSold DESC;
                "; // Top 6 món được đề xuất

        $result = $this->db->query($query);

        $data = [];
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function getAllSuggestionsForUser($user){
        $query = "WITH TopCategories AS (
                    SELECT p.ID_category
                    FROM Order_detail od
                    JOIN Product p ON od.ID_product = p.ID
                    GROUP BY p.ID_category
                    ORDER BY SUM(od.Quantity) DESC
                    LIMIT 3
                    ),
                    ProductRank AS (
                    SELECT 
                        p.ID,
                        p.Image,
                        p.Name,
                        p.Price,
                        p.ID_category,
                        SUM(od.Quantity) AS TotalSold,
                        ROW_NUMBER() OVER (
                            PARTITION BY p.ID_category
                            ORDER BY SUM(od.Quantity) DESC
                        ) AS rn
                    FROM Order_detail od
                    JOIN Product p ON od.ID_product = p.ID
                    WHERE p.ID_category IN (SELECT ID_category FROM TopCategories)
                    GROUP BY p.ID, p.Image, p.Name, p.Price, p.ID_category
                    )
                    SELECT *
                    FROM ProductRank
                    WHERE rn <= 4
                    ORDER BY ID_category, TotalSold DESC;
                "; // Top 6 món được đề xuất

        $result = $this->db->query($query);

        $data = [];
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }
}
