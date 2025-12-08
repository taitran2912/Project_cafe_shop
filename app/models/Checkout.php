<?php
class Checkout extends Model {

    public function getAddressById($addressId) {
        $query = "SELECT * FROM Address WHERE ID = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $addressId);
        $stmt->execute();
        $result = $stmt->get_result();
        $address = $result->fetch_assoc();
        $stmt->close();
        return $address;
    }

    public function createOrder($userID, $address, $storeId, $shippingFee, $total, $note) {
        $query = "
            INSERT INTO Orders(ID_Customer, ID_Branch, Address, Status, Date, Shipping_Cost, Payment_status, Note, Total)
            VALUES (?, ?, ?, 'Pending', NOW(), ?, 'Unpaid', ?, ?)
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("issdsd", $userID, $storeId, $address, $shippingFee, $note, $total);
        $stmt->execute();

        $orderId = $this->db->insert_id;

        $stmt->close();
        return $orderId;
    }

    public function getCartItems($customerId) {
        $query = "SELECT p.ID ID, p.Name Name, p.Price Price, cd.Quantity Quantity 
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
    
    public function addOrderDetail($orderId, $product, $quantity, $price) {
        $query = "
            INSERT INTO Order_detail (ID_order, ID_product, Quantity, Price)
            VALUES (?, ?, ?, ?)
        ";

        $stmt = $this->db->prepare($query);

        if (!$stmt) {
            die("Prepare failed: " . $this->db->error);
        }

        $stmt->bind_param("iiid", $orderId, $product, $quantity, $price);

        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }

        $stmt->close();
    }

    public function deleteOrderById($orderID) {
        $orderID = (int)$orderID;
        $query = "
            DELETE FROM Order_detail WHERE ID_order = ?
        ";
        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            die("Prepare failed: " . $this->db->error);
        }
        $stmt->bind_param("i", $orderID);
        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }
        $stmt->close();
    }

    public function deleteOrder($orderID) {
        $orderID = (int)$orderID;
        $query = "
            DELETE FROM Orders WHERE ID = ?
        ";
        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            die("Prepare failed: " . $this->db->error);
        }
        $stmt->bind_param("i", $orderID);
        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }
        $stmt->close();
    }

    public function updateOrderTotal($createOrder, $finalTotal) {
        $query = "
            UPDATE Orders SET Total = ? WHERE ID = ?
        ";
        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            die("Prepare failed: " . $this->db->error);
        }
        $stmt->bind_param("di", $finalTotal, $createOrder);
        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }
        $stmt->close();
    }

    public function getOrderStatus($orderID){
        $sql = "SELECT Payment_status FROM Orders WHERE ID = ?";
        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            return "DB_Error";
        }

        $stmt->bind_param("i", $orderID);

        if (!$stmt->execute()) {
            return "Execute_Error";
        }

        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return "NotFound";
        }

        $row = $result->fetch_assoc();
        return $row['Payment_status'];  // dạng Pending / Unpaid / Paid / Completed
    }

    public function getPointsByPhone($phone) {
        $sql = "
            SELECT c.Points 
            FROM Customer_Profile c 
            JOIN Account a ON c.ID_account = a.ID 
            WHERE a.Phone = ? 
            LIMIT 1
        ";

        // Prepare
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $this->db->error);
        }

        // Bind parameter
        $stmt->bind_param("s", $phone);

        // Execute
        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }

        // Bind result
        $stmt->bind_result($points);
        $stmt->fetch();

        $stmt->close();

        // Nếu không có dữ liệu → trả về 0
        return $points !== null ? (int)$points : 0;
    }


}