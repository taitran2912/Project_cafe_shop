<?php
class Cart extends Model {
    protected $table = 'Cart';

    // ✅ Lấy giỏ hàng
    public function getCartByCustomer($customerId) {
        $query = "SELECT c.ID AS cart_id, cd.ID_Product, cd.Quantity, 
                         p.Name, p.Price, p.Image
                  FROM Cart c
                  JOIN Cart_detail cd ON c.ID = cd.ID_Cart
                  JOIN Product p ON cd.ID_Product = p.ID
                  WHERE c.ID_Customer = ?";

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

    // ✅ Thêm sản phẩm vào giỏ hàng
    public function addToCart($customerId, $productId, $quantity = 1) {
        // Lấy ID giỏ hàng
        $stmt = $this->db->prepare("SELECT ID FROM Cart WHERE ID_Customer = ?");
        $stmt->bind_param("i", $customerId);
        $stmt->execute();
        $result = $stmt->get_result();
        $cart = $result->fetch_assoc();
        $stmt->close();

        if (!$cart) {
            $stmt = $this->db->prepare("INSERT INTO Cart (ID_Customer) VALUES (?)");
            $stmt->bind_param("i", $customerId);
            $stmt->execute();
            $cartId = $this->db->insert_id;
            $stmt->close();
        } else {
            $cartId = $cart['ID'];
        }

        // Kiểm tra sản phẩm
        $stmt = $this->db->prepare("SELECT Quantity FROM Cart_detail WHERE ID_Cart = ? AND ID_Product = ?");
        $stmt->bind_param("ii", $cartId, $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        $item = $result->fetch_assoc();
        $stmt->close();

        if ($item) {
            $stmt = $this->db->prepare("UPDATE Cart_detail SET Quantity = Quantity + ? WHERE ID_Cart = ? AND ID_Product = ?");
            $stmt->bind_param("iii", $quantity, $cartId, $productId);
        } else {
            $stmt = $this->db->prepare("INSERT INTO Cart_detail (ID_Cart, ID_Product, Quantity) VALUES (?, ?, ?)");
            $stmt->bind_param("iii", $cartId, $productId, $quantity);
        }
        $stmt->execute();
        $stmt->close();
        return true;
    }

    // ✅ Cập nhật số lượng
    public function updateQuantity($customerId, $productId, $quantity) {
        $cartId = $this->getCartId($customerId);
        $sql = "UPDATE Cart_detail SET Quantity = ? WHERE ID_Cart = ? AND ID_Product = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iii", $quantity, $cartId, $productId);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    // ✅ Xóa sản phẩm khỏi giỏ hàng
    public function removeItem($customerId, $productId) {
        $cartId = $this->getCartId($customerId);
        $sql = "DELETE FROM Cart_detail WHERE ID_Cart = ? AND ID_Product = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $cartId, $productId);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    // ✅ Lấy ID giỏ hàng
    private function getCartId($customerId) {
        $stmt = $this->db->prepare("SELECT ID FROM Cart WHERE ID_Customer = ?");
        $stmt->bind_param("i", $customerId);
        $stmt->execute();
        $result = $stmt->get_result();
        $cart = $result->fetch_assoc();
        $stmt->close();
        return $cart ? $cart['ID'] : null;
    }
}
