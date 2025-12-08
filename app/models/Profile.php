<?php
class Profile extends Model {
    public function getProfile($userId) {
        $stmt = $this->db->prepare("SELECT a.*, c.Points FROM Account a JOIN Customer_Profile c ON a.ID = c.ID_account WHERE a.ID = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getOrders($userId) {
        $stmt = $this->db->prepare("SELECT * FROM Orders WHERE ID_Customer = ? AND != 'Pending'");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
        return $orders;
    }

    public function getOrderDetails($orderId) {
        $stmt = $this->db->prepare("SELECT od.*, p.Name, p.Image 
                                    FROM Order_detail od JOIN Product p 
                                    ON od.ID_product = p.ID 
                                    WHERE od.ID_order = ?");
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();

        $details = [];
        while ($row = $result->fetch_assoc()) {
            $details[] = $row;
        }
        return $details;
    }

    public function getAddresses($userId) {
        $stmt = $this->db->prepare("SELECT * FROM Address WHERE ID_Customer = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $addresses = [];
        while ($row = $result->fetch_assoc()) {
            $addresses[] = $row;
        }
        return $addresses;
    }   
}
