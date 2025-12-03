<?php
class Thankyou extends Model {
    public function getOrderById($orderID) {
        $query = "SELECT *, Sum(od.Quantity) Quantity FROM Orders o JOIN Order_detail od ON o.ID = od.ID_order WHERE o.ID = ?;";
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
