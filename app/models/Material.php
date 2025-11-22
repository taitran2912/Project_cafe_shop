<?php

class Material extends Model {
    
    // Get all materials
    public function getAllMaterials() {
        $query = "SELECT * FROM Material ORDER BY Name";
        $result = $this->db->query($query);
        $data = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }
    
    // Get material by ID
    public function getMaterialById($id) {
        $stmt = $this->db->prepare("SELECT * FROM Material WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
    // Update material
    public function updateMaterial($id, $name, $unit) {
        $stmt = $this->db->prepare("UPDATE Material SET Name = ?, Unit = ? WHERE ID = ?");
        $stmt->bind_param("ssi", $name, $unit, $id);
        return $stmt->execute();
    }
}
?>
