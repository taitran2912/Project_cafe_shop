<?php
class Thankyou extends Model {
    public function getOrderById($orderID) {
        $query = "SELECT * FROM Orders WHERE ID = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $orderID);
        $stmt->execute();
        $result = $stmt->get_result();
        $order = $result->fetch_assoc();
        $stmt->close();
        return $order;
    }
}
?>
