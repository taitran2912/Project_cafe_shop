<?php
require_once __DIR__ . '/../core/Model.php';
class Account extends Model {
    protected $table = 'account';

    public function getAllAccounts() {
        $query = 'SELECT *
                FROM ' . $this->table
                . ' LIMIT 100';
        $result = $this->db->query($query);

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }
    public function getAccountsByPage($page = 1, $limit = 5) {
        $offset = ($page - 1) * $limit;
        $query = 'SELECT *
                FROM ' . $this->table
                . ' LIMIT ' . $offset . ', ' . $limit;
        $result = $this->db->query($query);

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }

    /**
     * Return total number of accounts in the table.
     * Used for computing total pages in pagination.
     */
    public function countAccounts() {
        $query = 'SELECT COUNT(*) AS cnt FROM ' . $this->table;
        $result = $this->db->query($query);
        if ($result) {
            $row = $result->fetch_assoc();
            return isset($row['cnt']) ? (int)$row['cnt'] : 0;
        }
        return 0;
    }

    /**
     * Paginate accounts at model level.
     * Returns array: accounts, totalItems, totalPages, currentPage, limit
     */
    public function paginate($page = 1, $limit = 5, $search = null) {
        $page = (int)$page;
        $limit = (int)$limit;
        if ($page < 1) $page = 1;
        if ($limit < 1) $limit = 5;

        // compute total items with optional search
        $totalItems = 0;
        if ($search !== null && $search !== '') {
            // use prepared statement for count with LIKE
            $like = '%' . $search . '%';
            $sql = 'SELECT COUNT(*) AS cnt FROM ' . $this->table . ' WHERE Name LIKE ? OR Phone LIKE ?';
            $stmt = $this->db->prepare($sql);
            if ($stmt) {
                $stmt->bind_param('ss', $like, $like);
                $stmt->execute();
                $res = $stmt->get_result();
                if ($res) {
                    $row = $res->fetch_assoc();
                    $totalItems = isset($row['cnt']) ? (int)$row['cnt'] : 0;
                }
                $stmt->close();
            }
        } else {
            $totalItems = $this->countAccounts();
        }
        $totalPages = ($totalItems > 0) ? (int)ceil($totalItems / $limit) : 1;
        if ($page > $totalPages) $page = $totalPages;

        $offset = ($page - 1) * $limit;
        $data = [];
        if ($search !== null && $search !== '') {
            $like = '%' . $search . '%';
            $sql = 'SELECT ID, Name, Password, Phone, Role, Status FROM ' . $this->table . ' WHERE Name LIKE ? OR Phone LIKE ? LIMIT ?, ?';
            $stmt = $this->db->prepare($sql);
            if ($stmt) {
                // bind: ssii (like, like, offset, limit)
                $stmt->bind_param('ssii', $like, $like, $offset, $limit);
                $stmt->execute();
                $res = $stmt->get_result();
                if ($res) {
                    while ($row = $res->fetch_assoc()) {
                        $data[] = $row;
                    }
                }
                $stmt->close();
            }
        } else {
            $sql = 'SELECT ID, Name, Password, Phone, Role, Status FROM ' . $this->table . ' LIMIT ?, ?';
            $stmt = $this->db->prepare($sql);
            if ($stmt) {
                $stmt->bind_param('ii', $offset, $limit);
                $stmt->execute();
                $res = $stmt->get_result();
                if ($res) {
                    while ($row = $res->fetch_assoc()) {
                        $data[] = $row;
                    }
                }
                $stmt->close();
            }
        }

        return [
            'accounts' => $data,
            'totalItems' => $totalItems,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'limit' => $limit
        ];
    }

    /**
     * Create a new account.
     * $data should contain: Name, Password (already hashed), Phone, Role, Status
     * Returns inserted ID on success or false on failure.
     */
    public function createAccount($data) {
        // Use prepared statement to avoid SQL injection and ensure proper escaping
        $name = isset($data['Name']) ? $data['Name'] : null;
        $password = isset($data['Password']) ? $data['Password'] : null;
        $phone = isset($data['Phone']) ? $data['Phone'] : null;
        // Role stored as numeric code in DB: 1=admin,2=manager,3=staff
        $role = isset($data['Role']) ? (int)$data['Role'] : 3;
        $status = isset($data['Status']) ? $data['Status'] : null;

        $sql = "INSERT INTO `" . $this->table . "` (Name, Password, Phone, Role, Status) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            return false;
        }

        // bind parameters: s = string, i = integer
        $stmt->bind_param('sssis', $name, $password, $phone, $role, $status);
        $ok = $stmt->execute();
        if ($ok) {
            $insertId = $this->db->insert_id;
            $stmt->close();
            return $insertId;
        }
        $stmt->close();
        return false;
        }

        /**
         * Update an account by ID. $data may include Name, Password (already hashed), Phone, Role, Status
         * If Password is null or empty, it will not be updated.
         */
        public function updateAccount($id, $data) {
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
            if (isset($data['Password']) && $data['Password'] !== '') {
                $fields[] = 'Password = ?';
                $types .= 's';
                $values[] = $data['Password'];
            }
            if (isset($data['Phone'])) {
                $fields[] = 'Phone = ?';
                $types .= 's';
                $values[] = $data['Phone'];
            }
            if (isset($data['Role'])) {
                $fields[] = 'Role = ?';
                // Role stored as integer in DB
                if (is_numeric($data['Role'])) {
                    $types .= 'i';
                    $values[] = (int)$data['Role'];
                } else {
                    $map = ['admin' => 1, 'manager' => 2, 'staff' => 3];
                    $r = strtolower(trim($data['Role']));
                    $types .= 'i';
                    $values[] = isset($map[$r]) ? $map[$r] : 3;
                }
            }
            if (isset($data['Status'])) {
                $fields[] = 'Status = ?';
                $types .= 's';
                $values[] = $data['Status'];
            }

            if (count($fields) === 0) return false; // nothing to update

            $sql = "UPDATE `" . $this->table . "` SET " . implode(', ', $fields) . " WHERE ID = ?";
            $stmt = $this->db->prepare($sql);
            if (!$stmt) return false;

            // bind params: types + i for id
            $types .= 'i';
            $values[] = (int)$id;

            // php requires variables for bind_param
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
         * Delete an account by ID.
         * Returns true on success, false on failure.
         */
        public function deleteAccount($id) {
            $id = (int)$id;
            if ($id <= 0) return false;

            $sql = "DELETE FROM `" . $this->table . "` WHERE ID = ?";
            $stmt = $this->db->prepare($sql);
            if (!$stmt) return false;

            $stmt->bind_param('i', $id);
            $ok = $stmt->execute();
            $stmt->close();
            return $ok;
        }

    }

    ?>