<?php
class Table extends Model {
    
   public function getAllTables() {
        $query = "SELECT t.ID, t.ID_Brach, t.No, t.Status, b.Name, b.Address 
                    FROM Table_Coffee t 
                    JOIN Branches b ON t.ID_Brach = b.ID ";
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