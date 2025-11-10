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

    public function paginate($page = 1, $limit = 5) {
        $branchModel = $this->model('Branch');

        // sanitize
        $page = (int)$page;
        $limit = (int)$limit;
        if ($page < 1) $page = 1;
        if ($limit < 1) $limit = 5;

        $totalItems = $branchModel->countBranches();
        $totalPages = ($totalItems > 0) ? (int)ceil($totalItems / $limit) : 1;
        if ($page > $totalPages) $page = $totalPages;

        $branches = $branchModel->getBranchesByPage($page, $limit);

        return [
            'branches' => $branches,
            'totalItems' => $totalItems,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'limit' => $limit
        ];
    }

    /**
     * Search branches by query string with pagination (used for AJAX realtime search)
     * Returns array with same structure as paginate()
     */
    public function search($q = '', $page = 1, $limit = 5) {
        $branchModel = $this->model('Branch');

        // sanitize
        $page = (int)$page;
        $limit = (int)$limit;
        if ($page < 1) $page = 1;
        if ($limit < 1) $limit = 5;

        $totalItems = $branchModel->countSearchBranches($q);
        $totalPages = ($totalItems > 0) ? (int)ceil($totalItems / $limit) : 1;
        if ($page > $totalPages) $page = $totalPages;

        $offset = ($page - 1) * $limit;
        $branches = $branchModel->searchBranches($q, $limit, $offset);

        return [
            'branches' => $branches,
            'totalItems' => $totalItems,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'limit' => $limit
        ];
    }

    /**
     * Store a new branch. $data should contain Name, Address, Phone, Status
     * Returns inserted ID on success or false on failure.
     */
    public function store($data) {
        $branchModel = $this->model('Branch');
        $name = isset($data['Name']) ? trim($data['Name']) : '';
        $address = isset($data['Address']) ? trim($data['Address']) : '';
        $phone = isset($data['Phone']) ? trim($data['Phone']) : '';
        $status = isset($data['Status']) ? trim($data['Status']) : 'active';

        if ($name === '') return false; // require name

        $payload = [
            'Name' => $name,
            'Address' => $address,
            'Phone' => $phone,
            'Status' => $status
        ];
        return $branchModel->createBranch($payload);
    }

    /**
     * Update an existing branch by ID.
     */
    public function update($id, $data) {
        $branchModel = $this->model('Branch');
        $id = (int)$id;
        if ($id <= 0) return false;

        $payload = [];
        if (isset($data['Name'])) $payload['Name'] = trim($data['Name']);
        if (isset($data['Address'])) $payload['Address'] = trim($data['Address']);
        if (isset($data['Phone'])) $payload['Phone'] = trim($data['Phone']);
        if (isset($data['Status'])) $payload['Status'] = trim($data['Status']);

        if (count($payload) === 0) return false;
        return $branchModel->updateBranch($id, $payload);
    }

    /**
     * Delete a branch by ID.
     */
    public function delete($id) {
        $branchModel = $this->model('Branch');
        $id = (int)$id;
        if ($id <= 0) return false;
        return $branchModel->deleteBranch($id);
    }
}
