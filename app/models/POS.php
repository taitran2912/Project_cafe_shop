<?php
class POS extends Model {
    public function inforStore($user) {
        $stmt = $this->db->prepare("SELECT b.ID ID, b.Name Name, b.Address Address
                                    FROM Branches b JOIN Staff s ON b.ID = s.ID_brand JOIN Account a on s.ID_account = a.ID 
                                    WHERE a.ID = ? AND b.Status = 'active';");
        $stmt->bind_param("i", $user); // "i" = integer
        $stmt->execute();
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

    public function tableByStore($storeID) {
        $stmt = $this->db->prepare("SELECT t.* 
                                    FROM Table_Coffee t 
                                    WHERE t.ID_Brach = ?;");
        $stmt->bind_param("i", $storeID);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        $stmt->close();
        return $data;
    }
}