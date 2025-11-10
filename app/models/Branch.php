<?php
require_once __DIR__ . '/../core/Model.php';
class Branch extends Model {

    // Lấy danh sách chi nhánh có phân trang
    public function getBranchPaginated($limit, $offset) {
        // Order by ID DESC so newest branches appear first (newly added shows on page 1)
        $query = "SELECT * FROM Branches LIMIT $limit OFFSET $offset;";
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
        $query = "SELECT COUNT(*) AS total FROM Branches;";
        $result = $this->db->query($query);
        $row = $result->fetch_assoc();
        return $row ? (int)$row['total'] : 0;
    }

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

    /**
     * Count branches matching a search query (for pagination)
     */
    public function countSearchBranches($q) {
        $like = '%' . $q . '%';
        $sql = "SELECT COUNT(*) AS total FROM Branches WHERE (Name LIKE ? OR Address LIKE ? OR Phone LIKE ?)";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return 0;
        $stmt->bind_param('sss', $like, $like, $like);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res ? $res->fetch_assoc() : null;
        $stmt->close();
        return $row ? (int)$row['total'] : 0;
    }

    /**
     * Search branches by q (Name/Address/Phone) with pagination
     */
    public function searchBranches($q, $limit = 5, $offset = 0) {
        $like = '%' . $q . '%';
        $limit = (int)$limit;
        $offset = (int)$offset;
        $sql = "SELECT * FROM Branches WHERE (Name LIKE ? OR Address LIKE ? OR Phone LIKE ?) LIMIT ? OFFSET ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return [];
        // bind params (sssii)
        $stmt->bind_param('sssii', $like, $like, $like, $limit, $offset);
        $stmt->execute();
        $res = $stmt->get_result();
        $data = [];
        if ($res && $res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $data[] = $row;
            }
        }
        $stmt->close();
        return $data;
    }

    // Wrapper expected by controller: countBranches()
    public function countBranches() {
        return $this->getTotalBranch();
    }

    // Wrapper expected by controller: getBranchesByPage($page, $limit)
    public function getBranchesByPage($page = 1, $limit = 5) {
        $page = (int)$page;
        $limit = (int)$limit;
        if ($page < 1) $page = 1;
        $offset = ($page - 1) * $limit;
        return $this->getBranchPaginated($limit, $offset);
    }

    /**
     * Create a new branch record.
     * $data expects keys: Name, Address, Phone, Status
     * Returns inserted ID or false on failure.
     */
    public function createBranch($data) {
        $name = isset($data['Name']) ? $data['Name'] : null;
        $address = isset($data['Address']) ? $data['Address'] : null;
        $phone = isset($data['Phone']) ? $data['Phone'] : null;
        $status = isset($data['Status']) ? $data['Status'] : 'active';

    $sql = "INSERT INTO `Branches` (Name, Address, Phone, Status) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return false;
        $stmt->bind_param('ssss', $name, $address, $phone, $status);
        $ok = $stmt->execute();
        if ($ok) {
            $id = $this->db->insert_id;
            $stmt->close();
            return $id;
        }
        $stmt->close();
        return false;
    }

    /**
     * Update a branch by ID. $data may include Name, Address, Phone, Status
     * Returns true on success, false on failure.
     */
    public function updateBranch($id, $data) {
        $id = (int)$id;
        if ($id <= 0) return false;

        $fields = [];
        $types = '';
        $values = [];

        if (isset($data['Name'])) {
            $fields[] = 'Name = ?';
            $types .= 's';
            $values[] = $data['Name'];
        }
        if (isset($data['Address'])) {
            $fields[] = 'Address = ?';
            $types .= 's';
            $values[] = $data['Address'];
        }
        if (isset($data['Phone'])) {
            $fields[] = 'Phone = ?';
            $types .= 's';
            $values[] = $data['Phone'];
        }
        if (isset($data['Status'])) {
            $fields[] = 'Status = ?';
            $types .= 's';
            $values[] = $data['Status'];
        }

        if (count($fields) === 0) return false;

        $sql = "UPDATE `Branches` SET " . implode(', ', $fields) . " WHERE ID = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return false;

        $types .= 'i';
        $values[] = $id;

        // bind params dynamically
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
     * Delete a branch by ID.
     */
    public function deleteBranch($id) {
        $id = (int)$id;
        if ($id <= 0) return false;
        $sql = "DELETE FROM `Branches` WHERE ID = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return false;
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}
