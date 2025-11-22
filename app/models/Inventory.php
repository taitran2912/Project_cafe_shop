<?php

class Inventory extends Model {
    
    // Get all inventory items with material and branch info
    public function getAllInventory() {
        $query = "SELECT 
                    i.ID,
                    i.ID_Material,
                    i.ID_Branch,
                    m.Name as MaterialName,
                    m.Unit,
                    i.Quantity,
                    b.Name as BranchName,
                    b.ID as BranchID
                  FROM Inventory i
                  INNER JOIN Material m ON i.ID_Material = m.ID
                  INNER JOIN Branches b ON i.ID_Branch = b.ID
                  ORDER BY b.ID, m.Name";
        
        $result = $this->db->query($query);
        $data = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }
    
    // Get inventory by ID
    public function getInventoryById($id) {
        $stmt = $this->db->prepare("SELECT 
                    i.ID,
                    i.ID_Material,
                    i.ID_Branch,
                    i.Quantity,
                    m.Name as MaterialName,
                    m.Unit,
                    b.Name as BranchName
                  FROM Inventory i
                  INNER JOIN Material m ON i.ID_Material = m.ID
                  INNER JOIN Branches b ON i.ID_Branch = b.ID
                  WHERE i.ID = ?");
        
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }
    
    // Create new inventory item
    public function createInventory($id_material, $id_branch, $quantity) {
        $stmt = $this->db->prepare("INSERT INTO Inventory (ID_Material, ID_Branch, Quantity) 
                                     VALUES (?, ?, ?)");
        
        if (!$stmt) {
            die("SQL prepare error: " . $this->db->error);
        }

        $stmt->bind_param("iii", $id_material, $id_branch, $quantity);

        if (!$stmt->execute()) {
            die("SQL execute error: " . $stmt->error);
        }

        $stmt->close();
        return true;
    }
    
    // Update inventory
    public function updateInventory($id, $id_material, $id_branch, $quantity) {
        $stmt = $this->db->prepare("UPDATE Inventory 
                                     SET ID_Material = ?, ID_Branch = ?, Quantity = ?
                                     WHERE ID = ?");
        $stmt->bind_param("iiii", $id_material, $id_branch, $quantity, $id);
        return $stmt->execute();
    }
    
    // Delete inventory
    public function deleteInventory($id) {
        $stmt = $this->db->prepare("DELETE FROM Inventory WHERE ID = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
    // Get all materials for dropdown
    public function getAllMaterials() {
        $query = "SELECT ID, Name, Unit FROM Material ORDER BY Name";
        $result = $this->db->query($query);
        $data = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }
    
    // Get all branches for dropdown
    public function getAllBranches() {
        $query = "SELECT ID, Name FROM Branches WHERE Status = 'active' ORDER BY Name";
        $result = $this->db->query($query);
        $data = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }
    
    // Get inventory stats
    public function getInventoryStats() {
        $query = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN Quantity >= 50 THEN 1 ELSE 0 END) as good,
                    SUM(CASE WHEN Quantity > 0 AND Quantity < 50 THEN 1 ELSE 0 END) as low,
                    SUM(CASE WHEN Quantity = 0 THEN 1 ELSE 0 END) as out_of_stock
                  FROM Inventory";
        
        $result = $this->db->query($query);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return [
            'total' => 0,
            'good' => 0,
            'low' => 0,
            'out_of_stock' => 0
        ];
    }
}
?>
