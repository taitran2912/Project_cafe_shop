<?php
class Contact extends Model {
    public function getAllBranch() {
        $query = "SELECT * FROM Branches WHERE Status = 'active';";
        $result = $this->db->query($query);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }
}
