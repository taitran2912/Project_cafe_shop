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


    public function addToCart($customerId, $productId, $quantity = 1) {
        // Kiểm tra giỏ hàng đã tồn tại chưa
        $query = "SELECT ID FROM Cart WHERE ID_Customer = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $customerId);
        $stmt->execute();
        $result = $stmt->get_result();
        $cart = $result->fetch_assoc();
        $stmt->close();

        // Nếu chưa có thì tạo mới giỏ hàng
        if (!$cart) {
            $query = "INSERT INTO Cart (ID_Customer) VALUES (?)";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $customerId);
            $stmt->execute();
            $cartId = $this->db->insert_id;
            $stmt->close();
        } else {
            $cartId = $cart['ID'];
        }

        // Kiểm tra sản phẩm đã có trong giỏ chưa
        $query = "SELECT Quantity FROM Cart_detail WHERE ID_Cart = ? AND ID_Product = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $cartId, $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        $item = $result->fetch_assoc();
        $stmt->close();

        if ($item) {
            // Nếu đã có, tăng số lượng
            $query = "UPDATE Cart_detail 
                      SET Quantity = Quantity + ? 
                      WHERE ID_Cart = ? AND ID_Product = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("iii", $quantity, $cartId, $productId);
        } else {
            // Nếu chưa có, thêm mới
            $query = "INSERT INTO Cart_detail (ID_Cart, ID_Product, Quantity) 
                      VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("iii", $cartId, $productId, $quantity);
        }

        $stmt->execute();
        $stmt->close();
        return true;
    }

    // ✅ Cập nhật số lượng sản phẩm trong giỏ hàng
    public function updateQuantity($customerId, $productId, $quantity) {
        $cartId = $this->getCartId($customerId);
        if (!$cartId) return false;

        $query = "UPDATE Cart_detail 
                  SET Quantity = ? 
                  WHERE ID_Cart = ? AND ID_Product = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("iii", $quantity, $cartId, $productId);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    // ✅ Xóa sản phẩm khỏi giỏ hàng
    public function removeItem($customerId, $productId) {
        $cartId = $this->getCartId($customerId);
        if (!$cartId) return false;

        $query = "DELETE FROM Cart_detail 
                  WHERE ID_Cart = ? AND ID_Product = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $cartId, $productId);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    // ✅ Lấy ID giỏ hàng của khách hàng
    private function getCartId($customerId) {
        $query = "SELECT ID FROM Cart WHERE ID_Customer = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $customerId);
        $stmt->execute();
        $result = $stmt->get_result();
        $cart = $result->fetch_assoc();
        $stmt->close();

        return $cart ? $cart['ID'] : null;
    }

}
