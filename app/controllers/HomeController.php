<?php
require_once './config/config.php';
require_once './app/models/Branch.php';

class HomeController {
    public function index() {
        $database = new Database();
        $db = $database->connect();

        $branchModel = new Branch($db);
        $branches = $branchModel->getAllBranches();

        require './app/views/home/index.php';
    }
}
?>