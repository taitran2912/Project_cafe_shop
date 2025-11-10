<?php
require_once __DIR__ . '/../../config/config.php';
class Model {
    protected $db;

    public function __construct() {
        $this->db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->db->connect_error) {
            die("Kết nối CSDL thất bại: " . $this->db->connect_error);
        }
    }
}
?>