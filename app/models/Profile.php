<?php
class Profile extends Model {
    public function getProfile($userId) {
        $stmt = $this->db->prepare("SELECT * FROM Account WHERE ID = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getOrders($userId) {
        $stmt = $this->db->prepare("SELECT * FROM Orders WHERE ID_Customer = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
        return $orders;
    }
}
