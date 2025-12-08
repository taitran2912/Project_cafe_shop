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
    // Enable mysqli exceptions to be thrown for easier debugging
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    // Quick validation
    if (empty($data['items']) || !is_array($data['items'])) {
        return ["success" => false, "message" => "Items missing or invalid"];
    }

    if (empty($data['customerPhone']) && empty($data['phone'])) {
        return ["success" => false, "message" => "Customer phone missing"];
    }

    // Normalize phone field
    $phone = $data['customerPhone'] ?? $data['phone'];

    // Start transaction
    $this->db->begin_transaction();

    try {
        // 1) Get customer ID
        $stmt = $this->db->prepare("
            SELECT CP.ID 
            FROM Customer_Profile CP 
            JOIN Account A ON CP.ID_account = A.ID
            WHERE A.Phone = ?
            LIMIT 1
        ");
        $stmt->bind_param("s", $phone);
        $stmt->execute();
        $res = $stmt->get_result();
        $user = $res->fetch_assoc();
        $stmt->close();

        if (!$user) {
            $this->db->rollback();
            return ["success" => false, "message" => "Customer not found for phone: $phone"];
        }
        $customerID = (int)$user['ID'];

        // 2) Prepare order fields
        $branchID   = isset($data['storeID']) ? (int)$data['storeID'] : 0;
        $tableID    = isset($data['tableNumber']) ? (int)$data['tableNumber'] : 0; // adjust if DB allows NULL
        $usePoints  = isset($data['usePoints']) ? (int)$data['usePoints'] : 0;
        $finalTotal = isset($data['finalTotal']) ? (float)$data['finalTotal'] : 0.0;
        $paymentMethod = $data['method'] ?? 'Cash';
        $today = date("Y-m-d");

        // 3) Insert Orders (bind types: iiissid -> last is double)
        $stmt = $this->db->prepare("
            INSERT INTO Orders
            (ID_Customer, ID_Branch, ID_Table, Status, Shipping_Cost, Payment_status, Method, Note, Date, Points, Total)
            VALUES (?, ?, ?, 'Pending', 0, 'Unpaid', ?, 'Đơn hàng tại quán hoặc mang về', ?, ?, ?)
        ");
        $stmt->bind_param(
            "iiissid",
            $customerID,
            $branchID,
            $tableID,
            $paymentMethod,
            $today,
            $usePoints,
            $finalTotal
        );

        $stmt->execute();
        $orderID = $this->db->insert_id;
        $stmt->close();

        if (!$orderID) {
            throw new Exception("Failed to create order");
        }

        // 4) Insert order details
        $stmtD = $this->db->prepare("
            INSERT INTO Order_detail (ID_order, ID_product, Quantity, Price)
            VALUES (?, ?, ?, ?)
        ");

        foreach ($data['items'] as $item) {
            // Validate each item
            if (!isset($item['id'])) {
                throw new Exception("Item missing ID: " . json_encode($item));
            }
            $productID = (int)$item['id'];
            $quantity = isset($item['quantity']) ? (int)$item['quantity'] : 1;
            $price = isset($item['price']) ? (float)$item['price'] : 0.0;

            $stmtD->bind_param("iiid", $orderID, $productID, $quantity, $price);
            $stmtD->execute();

            // Decrease inventory (best-effort)
            $sqlUpdate = "
                UPDATE Inventory I
                JOIN Product_detail PD ON I.ID_Material = PD.ID_Material
                SET I.Quantity = I.Quantity - (PD.Quantity * ?)
                WHERE PD.ID_Product = ?
                AND I.ID_Branch = ?
            ";
            $uStmt = $this->db->prepare($sqlUpdate);
            $uStmt->bind_param("iii", $quantity, $productID, $branchID);
            $uStmt->execute();
            $uStmt->close();
        }
        $stmtD->close();

        // 5) Deduct points if used
        if ($usePoints > 0) {
            $uP = $this->db->prepare("UPDATE Customer_Profile SET Points = Points - ? WHERE ID = ?");
            $uP->bind_param("ii", $usePoints, $customerID);
            $uP->execute();
            $uP->close();
        }

        // 6) Save coupon usage if any (optional)
        if (!empty($data['couponCode'])) {
            $code = $data['couponCode'];
            $discountAmount = isset($data['discountAmount']) ? (float)$data['discountAmount'] : 0.0;

            $getC = $this->db->prepare("SELECT ID FROM Coupons WHERE Code = ? LIMIT 1");
            $getC->bind_param("s", $code);
            $getC->execute();
            $couponRow = $getC->get_result()->fetch_assoc();
            $getC->close();

            if ($couponRow) {
                $couponID = (int)$couponRow['ID'];
                $insertC = $this->db->prepare("
                    INSERT INTO Coupon_usage (ID_coupon, ID_customer, ID_order, DiscountAmount)
                    VALUES (?, ?, ?, ?)
                ");
                $insertC->bind_param("iiii", $couponID, $customerID, $orderID, (int)$discountAmount);
                $insertC->execute();
                $insertC->close();
            }
        }

        // Commit everything
        $this->db->commit();

        return ["success" => true, "order_id" => $orderID];
    } catch (Throwable $e) {
        // Rollback and return error; also log to file
        $this->db->rollback();
        $err = [
            "success" => false,
            "message" => "Insert order failed",
            "error" => $e->getMessage(),
            "line" => $e->getLine()
        ];
        file_put_contents(__DIR__ . "/../../debug/order_errors.log", date("c") . " " . print_r($err, true) . "\n", FILE_APPEND);
        return $err;
    } finally {
        // restore mysqli reporting? optional
    }
}
}