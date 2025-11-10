<?php
require_once __DIR__ . '/../core/Model.php';

/**
 * Product Model - CRUD, pagination, and search for products
 */
class Product extends Model
{
    protected $table = 'product';

    /**
     * Execute query and return all rows
     */
    private function fetchAll($query)
    {
        $result = $this->db->query($query);
        $data = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    /**
     * Get all products (max 100, newest first)
     */
    public function getAllProducts()
    {
        return $this->fetchAll(
            "SELECT ID, ID_category, Name, Price, Description, Status 
             FROM {$this->table} ORDER BY ID DESC LIMIT 100"
        );
    }

    /**
     * Get products by page
     */
    public function getProductsByPage($page = 1, $limit = 5)
    {
        $page = max(1, (int)$page);
        $limit = max(1, (int)$limit);
        $offset = ($page - 1) * $limit;

        return $this->fetchAll(
            "SELECT ID, ID_category, Name, Price, Description, Status 
             FROM {$this->table} ORDER BY ID DESC LIMIT {$limit} OFFSET {$offset}"
        );
    }

    /**
     * Count total products
     */
    public function countProducts()
    {
        $result = $this->db->query("SELECT COUNT(*) AS cnt FROM {$this->table}");
        return $result ? (int)($result->fetch_assoc()['cnt'] ?? 0) : 0;
    }

    /**
     * Create new product
     */
    public function createProduct($data)
    {
        $name = trim($data['Name'] ?? '');
        if (empty($name)) return false;

        $stmt = $this->db->prepare(
            "INSERT INTO {$this->table} (ID_category, Name, Description, Price, Status, Image) 
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        if (!$stmt) return false;

        $id_category = (int)($data['ID_category'] ?? 0) ?: null;
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
     * Update product by ID
     */
    public function updateProduct($id, $data)
    {
        $id = (int)$id;
        if ($id <= 0 || empty($data)) return false;

        $fields = [];
        $types = [];
        $values = [];

        if (isset($data['ID_category'])) {
            $fields[] = 'ID_category = ?';
            $types[] = 'i';
            $values[] = (int)$data['ID_category'];
        }
        if (isset($data['Name'])) {
            $fields[] = 'Name = ?';
            $types[] = 's';
            $values[] = $data['Name'];
        }
        if (isset($data['Description'])) {
            $fields[] = 'Description = ?';
            $types[] = 's';
            $values[] = $data['Description'];
        }
        if (isset($data['Price'])) {
            $fields[] = 'Price = ?';
            $types[] = 'd';
            $values[] = (float)$data['Price'];
        }
        if (isset($data['Status'])) {
            $fields[] = 'Status = ?';
            $types[] = 's';
            $values[] = $data['Status'];
        }
        if (isset($data['Image'])) {
            $fields[] = 'Image = ?';
            $types[] = 's';
            $values[] = $data['Image'];
        }

        if (empty($fields)) return false;

        $stmt = $this->db->prepare(
            "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE ID = ?"
        );
        if (!$stmt) return false;

        $types[] = 'i';
        $values[] = $id;
        
        $stmt->bind_param(implode('', $types), ...$values);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    /**
     * Delete product by ID
     */
    public function deleteProduct($id)
    {
        $id = (int)$id;
        if ($id <= 0) return false;

        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE ID = ?");
        if (!$stmt) return false;
        
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    /**
     * Count search results
     */
    public function countSearchProducts($q)
    {
        $like = '%' . (string)$q . '%';
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) AS total FROM {$this->table} 
             WHERE Name LIKE ? OR Description LIKE ?"
        );
        if (!$stmt) return 0;

        $stmt->bind_param('ss', $like, $like);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return (int)($row['total'] ?? 0);
    }

    /**
     * Search products with pagination
     */
    public function searchProducts($q, $limit = 5, $offset = 0)
    {
        $like = '%' . (string)$q . '%';
        $limit = max(1, (int)$limit);
        $offset = max(0, (int)$offset);

        $stmt = $this->db->prepare(
            "SELECT ID, ID_category, Name, Price, Description, Status, Image 
             FROM {$this->table} 
             WHERE Name LIKE ? OR Description LIKE ? 
             ORDER BY ID DESC LIMIT ? OFFSET ?"
        );
        if (!$stmt) return [];

        $stmt->bind_param('ssii', $like, $like, $limit, $offset);
        $stmt->execute();
        
        $data = [];
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $stmt->close();
        return $data;
    }
}
