<?php
class Order extends Model {
    // Get all pending and confirmed orders with customer and branch information
    public function getPendingOrders() {
        $query = "SELECT 
                    o.ID,
                    o.ID_Customer,
                    o.ID_Branch,
                    o.Status,
                    o.Address,
                    a.Name as CustomerName,
                    a.Phone as CustomerPhone,
                    b.Name as BranchName, 
                    o.Shipping_Cost
                  FROM Orders o
                  LEFT JOIN Customer_Profile cp ON o.ID_Customer = cp.ID
                  LEFT JOIN Account a ON cp.ID_account = a.ID
                  LEFT JOIN Branches b ON o.ID_Branch = b.ID
                  WHERE o.Status IN ('Ordered', 'Confirmed')
                  ORDER BY o.ID DESC";
        
        $result = $this->db->query($query);
        $data = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }

    // Get order details (items) for a specific order
    public function getOrderDetails($orderId) {
        $query = "SELECT 
                    od.ID,
                    od.ID_order,
                    od.ID_product,
                    od.Quantity,
                    od.Price,
                    p.Name as ProductName,
                    p.Image as ProductImage
                  FROM Order_detail od
                  JOIN Product p ON od.ID_product = p.ID
                  WHERE od.ID_order = ?
                  ORDER BY od.ID";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $data = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        
        $stmt->close();
        return $data;
    }

    // Confirm order - update status to confirmed
    public function confirmOrder($orderId) {
        $stmt = $this->db->prepare("UPDATE Orders SET Status = 'confirmed' WHERE ID = ?");
        $stmt->bind_param("i", $orderId);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // Get pending orders count for notification badge
    public function getPendingCount() {
        $query = "SELECT COUNT(*) as count FROM Orders WHERE Status = 'pending'";
        $result = $this->db->query($query);
        
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return (int)$row['count'];
        }
        
        return 0;
    }

    // Get order by ID
    public function getOrderById($orderId) {
        $query = "SELECT 
                    o.*,
                    a.Name as CustomerName,
                    a.Phone as CustomerPhone,
                    b.Name as BranchName
                  FROM Orders o
                  LEFT JOIN Customer_Profile cp ON o.ID_Customer = cp.ID
                  LEFT JOIN Account a ON cp.ID_account = a.ID
                  LEFT JOIN Branches b ON o.ID_Branch = b.ID
                  WHERE o.ID = ?";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $data = null;
        if ($result && $result->num_rows > 0) {
            $data = $result->fetch_assoc();
        }
        
        $stmt->close();
        return $data;
    }

    // Update order status
    public function updateOrderStatus($orderId, $status) {
        $stmt = $this->db->prepare("UPDATE Orders SET Status = ? WHERE ID = ?");
        $stmt->bind_param("si", $status, $orderId);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // Get all orders (for reporting/history)
    public function getAllOrders() {
        $query = "SELECT 
                    o.ID,
                    o.ID_Customer,
                    o.ID_Branch,
                    o.Status,
                    o.Time,
                    o.Address,
                    o.Total,
                    a.Name as CustomerName,
                    a.Phone as CustomerPhone,
                    b.Name as BranchName
                  FROM Orders o
                  LEFT JOIN Customer_Profile cp ON o.ID_Customer = cp.ID
                  LEFT JOIN Account a ON cp.ID_account = a.ID
                  LEFT JOIN Branches b ON o.ID_Branch = b.ID
                  ORDER BY o.Time DESC, o.ID DESC";
        
        $result = $this->db->query($query);
        $data = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }
}
?>
