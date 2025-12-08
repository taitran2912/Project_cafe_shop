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
        return $points;
    }

    public function getCouponByCode($code, $phone) {
        try {
            $sql = "
                SELECT c.*
                FROM Coupons c
                WHERE c.Code = ?
                AND c.Status = 'active'
                AND c.Quantity > 0
                AND CURDATE() BETWEEN c.Start AND c.End
                AND NOT EXISTS (
                        SELECT 1
                        FROM Coupon_usage cu
                        WHERE cu.ID_coupon = c.ID
                        AND cu.ID_customer = (
                                SELECT cp.ID 
                                FROM Customer_Profile cp 
                                JOIN Account a ON cp.ID_account = a.ID 
                                WHERE a.Phone = ?
                                LIMIT 1
                        )
                )
                LIMIT 1
            ";

            // Chuẩn bị
            $stmt = $this->db->prepare($sql);
            if (!$stmt) {
                die("Prepare failed: " . $this->db->error);
            }

            // Gắn biến
            $stmt->bind_param("ss", $code, $phone);

            // Thực thi
            if (!$stmt->execute()) {
                die("Execute failed: " . $stmt->error);
            }

            // Lấy kết quả
            $result = $stmt->get_result();
            $coupon = $result->fetch_assoc();

            $stmt->close();

            return $coupon ?: false;

        } catch (Exception $e) {
            error_log("SQL ERROR getCouponByCode: " . $e->getMessage());
            return false;
        }
    }

//digital menu
    public function saveOrder($data) {
        // Lấy user ID từ bảng Account theo số điện thoại
        $stmt = $this->conn->prepare("SELECT ID FROM Account WHERE Phone = ?");
        $stmt->execute([$data["customerPhone"]]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Nếu không tìm thấy user → đặt ID = NULL
        $userID = $user ? $user["ID"] : null;


        $sql = "INSERT INTO Orders(ID_Customer, ID_Branch, ID_Table, Status, Address, Shipping_Cost, Payment_status, Method, Note, Date, Points, Total) 
        VALUES (?,?,?, Ordered, Null, 0, Unpaid, Cash, Null, Now(),?,?)";
        // INSERT INTO Coupon_usage(ID_coupon, ID_customer, ID_order, Used_at) VALUES ('[value-1]','[value-2]','[value-3]','[value-4]','[value-5]')

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            $userID,
            $data["storeID"],
            $data["tableNumber"],
            $data["usePoints"],
            // $data["couponCode"],
            $data["total"]
        ]);

        return $this->conn->lastInsertId();
    }

    // Lưu từng sản phẩm
    public function saveOrderItem($orderId, $item) {
        $sql = "INSERT INTO order_items (order_id, product_id, product_name, price, quantity, total_price)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            $orderId,
            $item["id"],
            $item["name"],
            $item["price"],
            $item["quantity"],
            $item["price"] * $item["quantity"]
        ]);
    }

    // Trừ điểm khách
    public function subtractPoints($phone, $points) {
        $sql = "UPDATE customers SET points = points - ? WHERE phone = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$points, $phone]);
    }

    // Lưu việc dùng mã giảm giá
    public function applyCoupon($phone, $code) {
        $sql = "INSERT INTO used_coupons (phone, code, used_at) VALUES (?, ?, NOW())";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$phone, $code]);
    }



}