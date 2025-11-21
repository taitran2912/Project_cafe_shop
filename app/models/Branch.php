<?php

class Branch extends Model {
    // Lấy toàn bộ chi nhánh (nếu không phân trang)
    public function getAllBranch() {
        // Return all branches newest-first
        $query = "SELECT * FROM Branches ORDER BY ID DESC;";
        $result = $this->db->query($query);
        $data = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }
    // Thêm chi nhánh mới
    public function addBranch($name, $address, $phone, $status) {

        $sql = "INSERT INTO Branches (Name, Address, Phone, Status, Latitude, Longitude)
                VALUES (?, ?, ?, ?, 0, 0)";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            die("SQL prepare error: " . $this->db->error);
        }

        $stmt->bind_param("ssss", $name, $address, $phone, $status);

        if (!$stmt->execute()) {
            die("SQL execute error: " . $stmt->error);
        }

        $stmt->close();
        return true;
    }

    public function updateBranch($id, $name, $address, $phone, $status) {
        $stmt = $this->db->prepare("UPDATE Branches SET Name=?, Address=?, Phone=?, Status=? WHERE ID=?");
        $stmt->bind_param("ssssi", $name, $address, $phone, $status, $id);
        return $stmt->execute();
    }

    public function deleteBranch($id) {
        $stmt = $this->db->prepare("DELETE FROM Branches WHERE ID=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }



}
