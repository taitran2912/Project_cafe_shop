<?php
class Cart extends Model {
    public function getCartByCustomer($customerId) {
        $sql = "SELECT c.id AS cart_id, cd.id_product, cd.quantity, 
                       p.name, p.price, p.image
                FROM cart c
                JOIN cart_detail cd ON c.id = cd.id_cart
                JOIN products p ON cd.id_product = p.id
                WHERE c.id_customer = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $customerId);
        $stmt->execute();
        $result = $stmt->get_result();

        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = [
                'id' => $row['id_product'],
                'name' => $row['name'],
                'price' => (int)$row['price'],
                'image' => $row['image'],
                'quantity' => (int)$row['quantity'],
            ];
        }

        return $items;
    }
}
