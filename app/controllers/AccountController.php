<?php
require_once __DIR__ . '/../core/Controller.php';
class AccountController extends Controller {

    public function index() {
        $accountModel = $this->model('Account');
        $data = [
            'title' => 'Tài khoản',
            'accounts' => $accountModel->getAllAccounts(),
            'pagination' => $accountModel->paginate()
        ];
        $this->view('admin/manager/PQNV', $data);
    }

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

        $name = isset($data['Name']) ? trim($data['Name']) : '';
        $password = isset($data['Password']) ? $data['Password'] : '';
        $phone = isset($data['Phone']) ? trim($data['Phone']) : '';
        $role = isset($data['Role']) ? $data['Role'] : 3; // default to staff (3)
        // normalize role: if string like 'admin' convert to numeric code; if numeric keep
        if (!is_numeric($role)) {
            $r = strtolower(trim($role));
            $roleMap = ['admin' => 1, 'manager' => 2, 'staff' => 3];
            $role = isset($roleMap[$r]) ? $roleMap[$r] : 3;
        } else {
            $role = (int)$role;
            if ($role < 1 || $role > 3) $role = 3;
        }
        $status = isset($data['Status']) ? trim($data['Status']) : 'active';

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
        if (isset($data['Name'])) $payload['Name'] = trim($data['Name']);
        if (isset($data['Password']) && $data['Password'] !== '') {
            $payload['Password'] = password_hash($data['Password'], PASSWORD_DEFAULT);
        }
        if (isset($data['Phone'])) $payload['Phone'] = trim($data['Phone']);
        if (isset($data['Role'])) {
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
        if (isset($data['Status'])) $payload['Status'] = trim($data['Status']);

        return $accountModel->updateAccount($id, $payload);
    }

    public function delete($id) {
        $accountModel = $this->model('Account');
        $id = (int)$id;
        if ($id <= 0) return false;
        return $accountModel->deleteAccount($id);
    }
}

?>