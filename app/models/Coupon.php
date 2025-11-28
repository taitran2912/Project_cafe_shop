<?php
require_once __DIR__ . '/../core/Model.php';

class Coupon extends Model {
    protected $table = 'coupons';

    /**
     * Get all coupons from database
     */
    public function getAllCoupons() {
        $query = 'SELECT * FROM Coupons';
        $result = $this->db->query($query);

        $data = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    /**
     * Create a new coupon
     * Expected fields: Code, Description, Value, StartDate, EndDate, Status, Quantity
     */
    public function createCoupon($data) {
        $code = isset($data['Code']) ? $data['Code'] : null;
        $description = isset($data['Description']) ? $data['Description'] : null;
        $value = isset($data['Value']) ? $data['Value'] : null;
        $startDate = isset($data['StartDate']) ? $data['StartDate'] : null;
        $endDate = isset($data['EndDate']) ? $data['EndDate'] : null;
        $status = isset($data['Status']) ? $data['Status'] : 'active';
        $quantity = isset($data['Quantity']) ? (int)$data['Quantity'] : 0;

        $sql = "INSERT INTO `" . $this->table . "` (Code, Description, Discount_value, Start, End, Status, Quantity) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param('ssssssi', $code, $description, $value, $startDate, $endDate, $status, $quantity);
        $ok = $stmt->execute();
        
        if ($ok) {
            $insertId = $this->db->insert_id;
            $stmt->close();
            return $insertId;
        }
        
        $stmt->close();
        return false;
    }

    /**
     * Update coupon by ID
     */
    public function updateCoupon($id, $data) {
        $id = (int)$id;
        if ($id <= 0) return false;

        $fields = [];
        $types = '';
        $values = [];

        if (isset($data['Code'])) {
            $fields[] = 'Code = ?';
            $types .= 's';
            $values[] = $data['Code'];
        }
        if (isset($data['Description'])) {
            $fields[] = 'Description = ?';
            $types .= 's';
            $values[] = $data['Description'];
        }
        if (isset($data['Value'])) {
            $fields[] = 'Discount_value = ?';
            $types .= 's';
            $values[] = $data['Value'];
        }
        if (isset($data['StartDate'])) {
            $fields[] = 'Start = ?';
            $types .= 's';
            $values[] = $data['StartDate'];
        }
        if (isset($data['EndDate'])) {
            $fields[] = 'End = ?';
            $types .= 's';
            $values[] = $data['EndDate'];
        }
        if (isset($data['Status'])) {
            $fields[] = 'Status = ?';
            $types .= 's';
            $values[] = $data['Status'];
        }
        if (isset($data['Quantity'])) {
            $fields[] = 'Quantity = ?';
            $types .= 'i';
            $values[] = (int)$data['Quantity'];
        }

        if (count($fields) === 0) return false;

        $sql = "UPDATE `" . $this->table . "` SET " . implode(', ', $fields) . " WHERE ID = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return false;

        $types .= 'i';
        $values[] = $id;

        $bindNames = [];
        $bindNames[] = $types;
        for ($k = 0; $k < count($values); $k++) {
            $bindName = 'bind' . $k;
            $$bindName = $values[$k];
            $bindNames[] = &$$bindName;
        }

        call_user_func_array([$stmt, 'bind_param'], $bindNames);

        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    /**
     * Delete coupon by ID
     */
    public function deleteCoupon($id) {
        $id = (int)$id;
        if ($id <= 0) return false;

        $sql = "DELETE FROM `" . $this->table . "` WHERE ID = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    /**
     * Get coupon by ID
     */
    public function getCouponById($id) {
        $id = (int)$id;
        $sql = "SELECT * FROM `" . $this->table . "` WHERE ID = ?";
        $stmt = $this->db->prepare($sql);
        
        if (!$stmt) return null;
        
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            $data = $result->fetch_assoc();
            $stmt->close();
            return $data;
        }
        
        $stmt->close();
        return null;
    }
}
?>
