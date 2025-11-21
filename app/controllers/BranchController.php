<?php
require_once __DIR__ . '/../core/Controller.php';
class BranchController extends Controller {
    public function index() {
        $branchModel = $this->model('Branch');
        
        $data = [
            'title' => 'admin',
            'branches' => $branchModel->getAllBranches(),
            'pagination' => $branchModel->paginate(),
        ];
        $this->view('admin/manager/QLCN', $data);
    }
    
}
