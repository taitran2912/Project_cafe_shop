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

   public function insertOrder($data) {

        // -----------------------------
        // 1. Lấy Customer ID theo số điện thoại
        // -----------------------------
        $phone = $data["customerPhone"];
        
        $sql = $this->db->prepare("
            SELECT CP.ID 
            FROM Customer_Profile CP 
            JOIN Account A ON CP.ID_account = A.ID
            WHERE A.Phone = ?
        ");
        $sql->bind_param("s", $phone);
        $sql->execute();
        $user = $sql->get_result()->fetch_assoc();

        if (!$user)
            return ["success" => false, "message" => "Customer not found"];

        $customerID = $user["ID"];


        // -----------------------------
        // 2. Insert ORDER
        // -----------------------------
        $branchID   = $data["storeID"];
        $tableID    = $data["tableNumber"] ?? NULL;

        $usePoints  = $data["usePoints"] ?? 0;
        $finalTotal = $data["finalTotal"] ?? 0;

        $paymentMethod = $data["method"] ?? "Cash";
        $today = date("Y-m-d");

        $stmt = $this->db->prepare("
            INSERT INTO Orders 
            (ID_Customer, ID_Branch, ID_Table, Status, Shipping_Cost, Payment_status, Method, Note, Date, Points, Total)
            VALUES (?, ?, ?, 'Pending', 0, 'Unpaid', ?, 'Đơn hàng tại quán hoặc mang về', ?, ?, ?)
        ");

        $stmt->bind_param(
            "iiissid",
            $customerID,    // i
            $branchID,      // i
            $tableID,       // i
            $paymentMethod, // s
            $today,         // s
            $usePoints,     // i
            $finalTotal     // d (decimal)
        );

        if (!$stmt->execute()) {
            return [
                "success" => false,
                "message" => "Order insert failed: " . $stmt->error
            ];
        }


        $orderID = $this->db->insert_id;


        // -----------------------------
        // 3. Insert ORDER DETAIL
        // -----------------------------
        foreach ($data["items"] as $item) {

            if (!isset($item["id"])) {
                return ["success" => false, "message" => "Item missing ID"];
            }

            $productID = (int)$item["id"];
            $quantity  = (int)$item["quantity"];
            $price     = (float)$item["price"];

            $stmtD = $this->db->prepare("
                INSERT INTO Order_detail (ID_order, ID_product, Quantity, Price)
                VALUES (?, ?, ?, ?)
            ");
            $stmtD->bind_param("iiid", $orderID, $productID, $quantity, $price);

            if (!$stmtD->execute()) {
                return ["success" => false, "message" => "Order detail failed: " . $stmtD->error];
            }

            // TRỪ TỒN KHO
            $this->db->query("
                UPDATE Inventory I
                JOIN Product_detail PD ON I.ID_Material = PD.ID_Material
                SET I.Quantity = I.Quantity - (PD.Quantity * $quantity)
                WHERE PD.ID_Product = $productID
                AND I.ID_Branch = $branchID
            ");
        }


        // -----------------------------
        // 4. Trừ điểm
        // -----------------------------
        if ($usePoints > 0) {
            $uP = $this->db->prepare("
                UPDATE Customer_Profile SET Points = Points - ? WHERE ID = ?
            ");
            $uP->bind_param("ii", $usePoints, $customerID);
            $uP->execute();
        }

        return ["success" => true, "order_id" => $orderID];
    }



}