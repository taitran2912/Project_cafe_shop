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
        // $stmt = $this->db->prepare("SELECT p.* 
        //                             FROM Products p 
        //                             WHERE p.Status = 'active';");
        // // $stmt->bind_param("i", $storeID);
        // // $stmt->execute();
        // $result = $stmt->get_result();

        // $data = [];
        // while ($row = $result->fetch_assoc()) {
        //     $data[] = $row;
        // }

        // $stmt->close();
        // return $data;

        $query = "SELECT * FROM Product;";
        $result = $this->db->query($query);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
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

    public function getRecommended() {
        $query = "WITH TopCategories 
                    AS ( SELECT p.ID_category 
                    FROM Order_detail od JOIN Product p ON od.ID_product = p.ID 
                    GROUP BY p.ID_category ORDER BY SUM(od.Quantity) DESC LIMIT 3 ), 
                    ProductSales AS ( SELECT p.ID, p.Name, p.Price, p.ID_category, SUM(od.Quantity) AS TotalSold, ROW_NUMBER() 
                    OVER (PARTITION BY p.ID_category ORDER BY SUM(od.Quantity) DESC) AS rn 
                    FROM Order_detail od JOIN Product p ON od.ID_product = p.ID WHERE p.ID_category 
                    IN (SELECT ID_category FROM TopCategories) GROUP BY p.ID, p.Name, p.Price, p.ID_category ) 
                    SELECT * FROM ProductSales WHERE rn <= 6;
                "; // Top 6 món được đề xuất

        $result = $this->db->query($query);

        $data = [];
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }



}   
