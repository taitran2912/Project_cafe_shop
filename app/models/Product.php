<?php
class Product extends Model
{
    protected $table = 'product';

    public function paginate($page = 1, $limit = 5) {
        // sanitize
        $page = (int)$page;
        $limit = (int)$limit;
        if ($page < 1) $page = 1;
        if ($limit < 1) $limit = 5;

        $totalItems = $this->countProducts(); // dùng trực tiếp $this
        $totalPages = ($totalItems > 0) ? (int)ceil($totalItems / $limit) : 1;
        if ($page > $totalPages) $page = $totalPages;

        $products = $this->getProductsByPage($page, $limit); // dùng trực tiếp $this

        return [
            'products' => $products,
            'totalItems' => $totalItems,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'limit' => $limit
        ];
    }

    /**
     * Lấy tất cả sản phẩm (tối đa 100, sắp xếp mới nhất)
     */
    public function getAllProducts()
    {
        $query = "SELECT ID, ID_category, Name, Price, Description, Status 
                  FROM {$this->table} 
                  ORDER BY ID DESC 
                  LIMIT 100;";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }

        $stmt->close();
        return $products;
    }

    /**
     * Lấy sản phẩm theo trang (phân trang)
     */
    public function getProductsByPage($page = 1, $limit = 5)
    {
        $page = max(1, (int)$page);
        $limit = max(1, (int)$limit);
        $offset = ($page - 1) * $limit;

        $query = "SELECT ID, ID_category, Name, Price, Description, Status 
                  FROM {$this->table} 
                  ORDER BY ID DESC 
                  LIMIT ? OFFSET ?;";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();

        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }

        $stmt->close();
        return $products;
    }

    /**
     * Đếm tổng số sản phẩm
     */
    public function countProducts()
    {
        $query = "SELECT COUNT(*) AS total FROM {$this->table};";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $count = ($row = $result->fetch_assoc()) ? (int)$row['total'] : 0;

        $stmt->close();
        return $count;
    }

    /**
     * Tạo mới sản phẩm
     */
    public function createProduct($data)
    {
        $name = trim($data['Name'] ?? '');
        if (empty($name)) return false;

        $query = "INSERT INTO {$this->table} 
                    (ID_category, Name, Description, Price, Status, Image)
                  VALUES (?, ?, ?, ?, ?, ?);";

        $stmt = $this->db->prepare($query);
        if (!$stmt) return false;

        $id_category = (int)($data['ID_category'] ?? 0);
        $description = trim($data['Description'] ?? '');
        $price = (float)($data['Price'] ?? 0.0);
        $status = trim($data['Status'] ?? 'active');
        $image = trim($data['Image'] ?? '');

        $stmt->bind_param('issdss', $id_category, $name, $description, $price, $status, $image);
        $ok = $stmt->execute();
        $insertId = $ok ? $this->db->insert_id : false;

        $stmt->close();
        return $insertId;
    }

    /**
     * Cập nhật thông tin sản phẩm theo ID
     */
    public function updateProduct($id, $data)
    {
        $id = (int)$id;
        if ($id <= 0 || empty($data)) return false;

        $fields = [];
        $types = '';
        $values = [];

        if (isset($data['ID_category'])) {
            $fields[] = "ID_category = ?";
            $types .= 'i';
            $values[] = (int)$data['ID_category'];
        }
        if (isset($data['Name'])) {
            $fields[] = "Name = ?";
            $types .= 's';
            $values[] = trim($data['Name']);
        }
        if (isset($data['Description'])) {
            $fields[] = "Description = ?";
            $types .= 's';
            $values[] = trim($data['Description']);
        }
        if (isset($data['Price'])) {
            $fields[] = "Price = ?";
            $types .= 'd';
            $values[] = (float)$data['Price'];
        }
        if (isset($data['Status'])) {
            $fields[] = "Status = ?";
            $types .= 's';
            $values[] = trim($data['Status']);
        }
        if (isset($data['Image'])) {
            $fields[] = "Image = ?";
            $types .= 's';
            $values[] = trim($data['Image']);
        }

        if (empty($fields)) return false;

        $query = "UPDATE {$this->table} 
                  SET " . implode(', ', $fields) . " 
                  WHERE ID = ?;";
        $stmt = $this->db->prepare($query);
        if (!$stmt) return false;

        $types .= 'i';
        $values[] = $id;

        $stmt->bind_param($types, ...$values);
        $ok = $stmt->execute();

        $stmt->close();
        return $ok;
    }

    /**
     * Xóa sản phẩm theo ID
     */
    public function deleteProduct($id)
    {
        $id = (int)$id;
        if ($id <= 0) return false;

        $query = "DELETE FROM {$this->table} WHERE ID = ?;";
        $stmt = $this->db->prepare($query);
        if (!$stmt) return false;

        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();

        $stmt->close();
        return $ok;
    }

    /**
     * Đếm số lượng sản phẩm theo từ khóa tìm kiếm
     */
    public function countSearchProducts($q)
    {
        $like = '%' . (string)$q . '%';
        $query = "SELECT COUNT(*) AS total 
                  FROM {$this->table} 
                  WHERE Name LIKE ? OR Description LIKE ?;";

        $stmt = $this->db->prepare($query);
        if (!$stmt) return 0;

        $stmt->bind_param('ss', $like, $like);
        $stmt->execute();
        $result = $stmt->get_result();

        $count = ($row = $result->fetch_assoc()) ? (int)$row['total'] : 0;

        $stmt->close();
        return $count;
    }

    /**
     * Tìm kiếm sản phẩm có phân trang
     */
    public function searchProducts($q, $limit = 5, $offset = 0)
    {
        $like = '%' . (string)$q . '%';
        $limit = max(1, (int)$limit);
        $offset = max(0, (int)$offset);

        $query = "SELECT ID, ID_category, Name, Price, Description, Status, Image 
                  FROM {$this->table} 
                  WHERE Name LIKE ? OR Description LIKE ? 
                  ORDER BY ID DESC 
                  LIMIT ? OFFSET ?;";

        $stmt = $this->db->prepare($query);
        if (!$stmt) return [];

        $stmt->bind_param('ssii', $like, $like, $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();

        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }

        $stmt->close();
        return $products;
    }
}
