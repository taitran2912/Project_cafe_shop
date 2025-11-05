<?php
class Branch extends Model {

    // Lấy danh sách chi nhánh có phân trang
    public function getBranchPaginated($limit, $offset) {
        $query = "SELECT * FROM Branches WHERE Status = 'active' LIMIT $limit OFFSET $offset;";
        $result = $this->db->query($query);
        $data = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }

    // Lấy tổng số chi nhánh (dùng để tính tổng số trang)
    public function getTotalBranch() {
        $query = "SELECT COUNT(*) AS total FROM Branches WHERE Status = 'active';";
        $result = $this->db->query($query);
        $row = $result->fetch_assoc();
        return $row ? (int)$row['total'] : 0;
    }

    // Lấy toàn bộ chi nhánh (nếu không phân trang)
    public function getAllBranch() {
        $query = "SELECT * FROM Branches WHERE Status = 'active';";
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
