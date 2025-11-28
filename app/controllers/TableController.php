<?php
class TableController extends Controller {
    
    public function adminIndex() {
        $this->checkAuth();
        
        // Load tables
        // $tableModel = $this->model('Table');
        // $tables = $tableModel->getAllTables();
        
        $data = [
            'title' => 'Quản lý bàn'
            // 'tables' => $tables
        ];
        // Pass data to the view
        $this->view('admin/home/index', $data);
    }
}