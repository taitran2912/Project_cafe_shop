<?php
class Branch {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllBranches() {
        $query = "SELECT * FROM Branches";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>