<?php
class Payment extends Model {
    // Lấy tất cả sản phẩm trong giỏ hàng của khách hàng
    public function getAllProductstoCart($customerId) {
       $query = "SELECT p.Name Name, p.Price Price, cd.Quantity Quantity 
                    FROM Cart c 
                    JOIN Cart_detail cd on c.ID = cd.ID_Cart 
                    JOIN Product p on cd.ID_Product = p.ID 
                    WHERE c.ID_Customer = ? 
                    AND p.Status ='active';";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $customerId);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        $stmt->close();
        return $data;
    }

    public function getDefaultAddress($customerId) {
        $query = "SELECT ad.Address, ac.Name, ac.Phone, ad.ID, ad.Latitude as lat, ad.Longitude as lng
                    FROM Address ad 
                    JOIN Account ac 
                    ON ad.ID_Customer = ac.ID 
                    WHERE ad.ID_Customer = ? 
                    AND ad.AddressDefault = 1 
                    LIMIT 1;";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $customerId);
        $stmt->execute();
        $result = $stmt->get_result();

        $row = $result->fetch_assoc(); // Lấy 1 dòng duy nhất

        $stmt->close();

        if ($row) {
            return [
                'ID' => $row['ID'],
                'Name' => $row['Name'],
                'Phone' => $row['Phone'],
                'Address' => $row['Address'],
                'lat' => $row['lat'],
                'lng' => $row['lng']
            ];
        } else {
            return null;
        }
    }

    public function getStoreLocations() {
        // Giả sử có bảng Store với các cột ID, Name, Latitude, Longitude
        $query = "SELECT b.ID AS BranchID, b.Name AS BranchName, b.Address, b.Latitude, b.Longitude 
                    FROM Branches b WHERE NOT EXISTS ( 
                        SELECT 1 FROM Cart_detail cd 
                        JOIN Cart c ON cd.ID_Cart = c.ID 
                        WHERE c.ID_Customer = 1 AND NOT EXISTS ( 
                            SELECT 1 FROM Inventory i JOIN Product_detail pd ON pd.ID_material = i.ID_Material 
                            WHERE i.ID_Branch = b.ID 
                            AND pd.ID_product = cd.ID_Product 
                            AND i.Quantity >= pd.Quantity * cd.Quantity 
                        ) 
                    );
                ";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        $stores = [];
        while ($row = $result->fetch_assoc()) {
            $stores[] = [
                'ID' => $row['BranchID'],
                'Name' => $row['Name'],
                'Address' => $row['Address'],
                'lat' => $row['Latitude'],
                'lng' => $row['Longitude']
            ];
        }

        $stmt->close();
        return $stores; // Trả về mảng các cửa hàng
    }

    public function getAllAddress($customerId) {
        $query = "SELECT ad.Address, ac.Name, ac.Phone, ad.ID, ad.AddressDefault, ad.Latitude as lat, ad.Longitude as lng
                    FROM Address ad 
                    JOIN Account ac 
                    ON ad.ID_Customer = ac.ID 
                    WHERE ad.ID_Customer = ?;";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $customerId);
        $stmt->execute();
        $result = $stmt->get_result();

        $addresses = [];
        while ($row = $result->fetch_assoc()) {
            $addresses[] = [
                'ID' => $row['ID'],
                'Name' => $row['Name'],
                'Phone' => $row['Phone'],
                'Address' => $row['Address'],
                'is_default' => $row['AddressDefault'],
                'lat' => $row['lat'],
                'lng' => $row['lng']
            ];
        }

        $stmt->close();
        return $addresses; // Trả về mảng nhiều địa chỉ
    }    
}   