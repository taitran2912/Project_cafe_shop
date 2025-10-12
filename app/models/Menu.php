<?php
class Menu extends Model {
    public function getAll() {
        $query = "SELECT p.ID, p.Name, p.Description, p.Price, p.Image, c.Name as Name_Category FROM Product p join Categories c on p.ID_category = c.ID WHERE p.Status = 'active';";
        $result = $this->db->query($query);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }
    public function getAllCategory() {
        $query = "SELECT * FROM Categories c WHERE c.Status = 'active'";
        $result = $this->db->query($query);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }
}
