<?php
require_once __DIR__ . '/../core/Controller.php';
class AccountController extends Controller {

    public function getAllAccounts() {
        $accountModel = $this->model('Account');
        return $accountModel->getAllAccounts();
    }

    public function getAccountsByPage($page = 1, $limit = 5) {
        $accountModel = $this->model('Account');
        return $accountModel->getAccountsByPage($page, $limit);
    }

    public function getAccountCount() {
        $accountModel = $this->model('Account');
        return $accountModel->countAccounts();
    }

    // Controller-level helper that proxies to model paginate (supports optional search)
    public function paginate($page = 1, $limit = 5, $search = null) {
        $accountModel = $this->model('Account');
        return $accountModel->paginate($page, $limit, $search);
    }

    public function store($data) {
        $accountModel = $this->model('Account');

        // Map lowercase field names from HTML form to uppercase for model
        $name = isset($data['name']) ? trim($data['name']) : (isset($data['Name']) ? trim($data['Name']) : '');
        $password = isset($data['password']) ? $data['password'] : (isset($data['Password']) ? $data['Password'] : '');
        $phone = isset($data['phone']) ? trim($data['phone']) : (isset($data['Phone']) ? trim($data['Phone']) : '');
        $role = isset($data['role']) ? $data['role'] : (isset($data['Role']) ? $data['Role'] : 3);
        $status = isset($data['status']) ? trim($data['status']) : (isset($data['Status']) ? trim($data['Status']) : 'active');
        
        // normalize role: if string like 'admin' convert to numeric code; if numeric keep
        if (!is_numeric($role)) {
            $r = strtolower(trim($role));
            $roleMap = ['admin' => 1, 'manager' => 2, 'staff' => 3];
            $role = isset($roleMap[$r]) ? $roleMap[$r] : 3;
        } else {
            $role = (int)$role;
            if ($role < 1 || $role > 3) $role = 3;
        }

        if ($name === '' || $password === '') {
            return false; // required fields
        }

        // Hash password
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $payload = [
            'Name' => $name,
            'Password' => $hashed,
            'Phone' => $phone,
            'Role' => $role,
            'Status' => $status
        ];

        return $accountModel->createAccount($payload);
    }

    public function update($id, $data) {
        $accountModel = $this->model('Account');

        $id = (int)$id;
        if ($id <= 0) return false;

        $payload = [];
        
        // Map lowercase field names from HTML form to uppercase for model
        if (isset($data['name'])) $payload['Name'] = trim($data['name']);
        elseif (isset($data['Name'])) $payload['Name'] = trim($data['Name']);
        
        if (isset($data['password']) && $data['password'] !== '') {
            $payload['Password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } elseif (isset($data['Password']) && $data['Password'] !== '') {
            $payload['Password'] = password_hash($data['Password'], PASSWORD_DEFAULT);
        }
        
        if (isset($data['phone'])) $payload['Phone'] = trim($data['phone']);
        elseif (isset($data['Phone'])) $payload['Phone'] = trim($data['Phone']);
        
        if (isset($data['role'])) {
            $r = $data['role'];
            if (!is_numeric($r)) {
                $rr = strtolower(trim($r));
                $roleMap = ['admin' => 1, 'manager' => 2, 'staff' => 3];
                $r = isset($roleMap[$rr]) ? $roleMap[$rr] : 3;
            } else {
                $r = (int)$r;
                if ($r < 1 || $r > 3) $r = 3;
            }
            $payload['Role'] = $r;
        } elseif (isset($data['Role'])) {
            $r = $data['Role'];
            if (!is_numeric($r)) {
                $rr = strtolower(trim($r));
                $roleMap = ['admin' => 1, 'manager' => 2, 'staff' => 3];
                $r = isset($roleMap[$rr]) ? $roleMap[$rr] : 3;
            } else {
                $r = (int)$r;
                if ($r < 1 || $r > 3) $r = 3;
            }
            $payload['Role'] = $r;
        }
        
        if (isset($data['status'])) $payload['Status'] = trim($data['status']);
        elseif (isset($data['Status'])) $payload['Status'] = trim($data['Status']);

        return $accountModel->updateAccount($id, $payload);
    }

    public function delete($id) {
        $accountModel = $this->model('Account');
        $id = (int)$id;
        if ($id <= 0) return false;
        return $accountModel->deleteAccount($id);
    }

    public function adminIndex() {
        // Initialize messages
        $successMessage = '';
        $errorMessage = '';

        // Handle success messages from redirect
        if (isset($_GET['success'])) {
            switch ($_GET['success']) {
                case 'add':
                    $successMessage = 'Thêm nhân viên mới thành công!';
                    break;
                case 'edit':
                    $successMessage = 'Cập nhật nhân viên thành công!';
                    break;
                case 'delete':
                    $successMessage = 'Xóa nhân viên thành công!';
                    break;
            }
        }

        // Handle search
        $searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
        $isSearching = !empty($searchQuery);

        // Get all accounts
        $accountModel = $this->model('Account');
        $allAccounts = $accountModel->getAllAccounts();

        // Filter accounts if searching
        $accounts = $allAccounts;
        if ($isSearching) {
            $accounts = array_filter($allAccounts, function($account) use ($searchQuery) {
                $searchLower = mb_strtolower($searchQuery, 'UTF-8');
                return mb_stripos($account['Name'], $searchLower) !== false 
                    || mb_stripos($account['Phone'], $searchLower) !== false 
                    || mb_stripos($account['ID'], $searchLower) !== false;
            });
            $accounts = array_values($accounts); // Re-index array
        }

        // Prepare data for view
        $data = [
            'accounts' => $accounts,
            'successMessage' => $successMessage,
            'errorMessage' => $errorMessage,
            'searchQuery' => $searchQuery,
            'isSearching' => $isSearching
        ];

        // Load view
        $this->view('admin/home/userManager', $data);
    }
}

?>