<?php
class Menu extends Model {
    public function getAll() {
        $query = "SELECT * FROM Product";
        $result = $this->db->query($query);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }
}
