<?php
class Table extends Model {
    
   public function getAllTables() {
        $query = "SELECT * FROM Tables";
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