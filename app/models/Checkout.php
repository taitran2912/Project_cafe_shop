<?php
class Checkout extends Model {
    public function createOrder($userID, $addressId, $storeId, $shippingFee) {
        $query = "INSERT INTO Orders (ID_Customer, ID_Address, ID_Store, Shipping_Fee, Order_Date, Status)
                  VALUES (?, ?, ?, ?, NOW(), 'Pending')";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("iiid", $userID, $addressId, $storeId, $shippingFee);
        $stmt->execute();
        $orderId = $this->db->insert_id;
        $stmt->close();

        return $orderId;
    }

    public function getCartItems($customerId) {
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

    function generateSepayQR($config, $amount, $content) {
        $url = sprintf(
            "https://img.vietqr.io/image/%s-%s-%s.png?amount=%s&addInfo=%s&accountName=%s",
            $config['bank_code'],
            $config['account_number'],
            $config['template'],
            $amount,
            urlencode($content),
            urlencode($config['account_name'])
        );
        return $url;
    }
}