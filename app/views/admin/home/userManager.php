<?php 
// Form submissions are handled in index.php BEFORE HTML output

// Initialize messages
$successMessage = '';
$errorMessage = '';

// Handle success messages from redirect
if (isset($_GET['success'])) {
    switch ($_GET['success']) {
        case 'add':
            $successMessage = 'Thêm nhân viên thành công!';
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

// Get accounts - either from data passed by controller or fetch directly
if (!isset($accounts)) {
    $accountModel = new Account();
    $allAccounts = $accountModel->getAllAccounts();
} else {
    $allAccounts = $accounts;
}

// Filter accounts if searching
if ($isSearching) {
    $accounts = array_filter($allAccounts, function($account) use ($searchQuery) {
        $searchLower = strtolower($searchQuery);
        return stripos($account['Name'], $searchLower) !== false ||
               stripos($account['Phone'], $searchLower) !== false ||
               stripos($account['ID'], $searchLower) !== false;
    });
    $accounts = array_values($accounts); // Re-index array
} else {
    $accounts = $allAccounts;
}

?>

    <!-- Content Area -->
    <div class="content">
        <!-- Success/Error Messages -->
        <?php if (!empty($successMessage)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo htmlspecialchars($successMessage); ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <!-- Content Header -->
        <div class="content-header">
            <button class="btn btn-primary" id="btnAddAccount">
                <i class="fas fa-plus"></i>
                Thêm nhân viên
            </button>
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Tìm kiếm nhân viên..." value="<?php echo htmlspecialchars($searchQuery); ?>" autocomplete="off">
                <?php if ($isSearching): ?>
                    <button type="button" class="clear-search-btn" id="clearSearchBtn" title="Xóa tìm kiếm">
                        <i class="fas fa-times"></i>
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($isSearching): ?>
            <div class="search-info">
                <i class="fas fa-info-circle"></i>
                Tìm thấy <strong><?php echo count($accounts); ?></strong> nhân viên với từ khóa "<strong><?php echo htmlspecialchars($searchQuery); ?></strong>"
            </div>
        <?php endif; ?>

        <!-- Table Container -->
        <div class="table-container">
            <table class="data-table" id="accountTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Họ tên</th>
                        <th>Số điện thoại</th>
                        <th>Vai trò</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody id="accountBody">
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pagination" id="pagination"></div>
    </div>

    <!-- Modal Thêm Nhân Viên -->
    <div id="addAccountModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-user-plus"></i> Thêm Nhân Viên Mới</h2>
                <button class="close-btn" id="closeModal">&times;</button>
            </div>
            <form id="addAccountForm" method="POST" action="">
                <input type="hidden" name="action" value="add_account">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="accountName">
                            <i class="fas fa-user"></i> Họ tên <span class="required">*</span>
                        </label>
                        <input type="text" id="accountName" name="name" placeholder="Nhập họ tên" required>
                    </div>

                    <div class="form-group">
                        <label for="accountPhone">
                            <i class="fas fa-phone"></i> Số điện thoại <span class="required">*</span>
                        </label>
                        <input type="tel" id="accountPhone" name="phone" placeholder="Nhập số điện thoại" required>
                    </div>

                    <div class="form-group">
                        <label for="accountPassword">
                            <i class="fas fa-lock"></i> Mật khẩu <span class="required">*</span>
                        </label>
                        <input type="password" id="accountPassword" name="password" placeholder="Nhập mật khẩu" required>
                    </div>

                    <div class="form-group">
                        <label for="accountRole">
                            <i class="fas fa-user-tag"></i> Vai trò <span class="required">*</span>
                        </label>
                        <select id="accountRole" name="role" required>
                            <option value="1">Admin</option>
                            <option value="2" selected>Nhân viên</option>
                            <option value="3">Quản lý</option>
                        </select>
                    </div>

                    <div class="form-group full-width">
                        <label for="accountStatus">
                            <i class="fas fa-toggle-on"></i> Trạng thái <span class="required">*</span>
                        </label>
                        <select id="accountStatus" name="status" required>
                            <option value="active">Đang hoạt động</option>
                            <option value="inactive">Tạm dừng</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="cancelBtn">
                        <i class="fas fa-times"></i> Hủy
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Lưu nhân viên
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Xem Chi Tiết -->
    <div id="viewAccountModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-info-circle"></i> Chi Tiết Nhân Viên</h2>
                <button class="close-btn" id="closeViewModal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="detail-grid">
                    <div class="detail-item">
                        <label><i class="fas fa-hashtag"></i> ID:</label>
                        <span id="view_id"></span>
                    </div>
                    <div class="detail-item">
                        <label><i class="fas fa-user"></i> Họ tên:</label>
                        <span id="view_name"></span>
                    </div>
                    <div class="detail-item">
                        <label><i class="fas fa-phone"></i> Số điện thoại:</label>
                        <span id="view_phone"></span>
                    </div>
                    <div class="detail-item">
                        <label><i class="fas fa-user-tag"></i> Vai trò:</label>
                        <span id="view_role"></span>
                    </div>
                    <div class="detail-item full-width">
                        <label><i class="fas fa-toggle-on"></i> Trạng thái:</label>
                        <span id="view_status"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="closeViewBtn">
                    <i class="fas fa-times"></i> Đóng
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Sửa Nhân Viên -->
    <div id="editAccountModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-edit"></i> Sửa Nhân Viên</h2>
                <button class="close-btn" id="closeEditModal">&times;</button>
            </div>
            <form id="editAccountForm" method="POST" action="">
                <input type="hidden" name="action" value="edit_account">
                <input type="hidden" name="account_id" id="edit_account_id">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="editAccountName">
                            <i class="fas fa-user"></i> Họ tên <span class="required">*</span>
                        </label>
                        <input type="text" id="editAccountName" name="name" placeholder="Nhập họ tên" required>
                    </div>

                    <div class="form-group">
                        <label for="editAccountPhone">
                            <i class="fas fa-phone"></i> Số điện thoại <span class="required">*</span>
                        </label>
                        <input type="tel" id="editAccountPhone" name="phone" placeholder="Nhập số điện thoại" required>
                    </div>

                    <div class="form-group">
                        <label for="editAccountPassword">
                            <i class="fas fa-lock"></i> Mật khẩu mới (để trống nếu không đổi)
                        </label>
                        <input type="password" id="editAccountPassword" name="password" placeholder="Nhập mật khẩu mới">
                    </div>

                    <div class="form-group">
                        <label for="editAccountRole">
                            <i class="fas fa-user-tag"></i> Vai trò <span class="required">*</span>
                        </label>
                        <select id="editAccountRole" name="role" required>
                            <option value="1">Admin</option>
                            <option value="2">Nhân viên</option>
                            <option value="3">Quản lý</option>
                        </select>
                    </div>

                    <div class="form-group full-width">
                        <label for="editAccountStatus">
                            <i class="fas fa-toggle-on"></i> Trạng thái <span class="required">*</span>
                        </label>
                        <select id="editAccountStatus" name="status" required>
                            <option value="active">Đang hoạt động</option>
                            <option value="inactive">Tạm dừng</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="cancelEditBtn">
                        <i class="fas fa-times"></i> Hủy
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Cập nhật
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Xác Nhận Xóa -->
    <div id="deleteConfirmModal" class="modal">
        <div class="modal-content modal-small">
            <div class="modal-header modal-header-danger">
                <h2><i class="fas fa-exclamation-triangle"></i> Xác Nhận Xóa</h2>
                <button class="close-btn" id="closeDeleteModal">&times;</button>
            </div>
            <div class="modal-body">
                <p class="delete-message">
                    Bạn có chắc chắn muốn xóa nhân viên <strong id="delete_account_name"></strong>?
                </p>
                <p class="delete-warning">
                    <i class="fas fa-info-circle"></i> Hành động này không thể hoàn tác!
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="cancelDeleteBtn">
                    <i class="fas fa-times"></i> Hủy
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="fas fa-trash"></i> Xóa
                </button>
            </div>
        </div>
    </div>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
        }

        .content {
            padding: 30px;
            margin-left: 0;
            background-color: #f8f9fa;
            min-height: 100vh;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 15px;
            animation: slideDown 0.4s ease;
        }

        .alert-success {
            background-color: #d4edda;
            border-left: 4px solid #28a745;
            color: #155724;
        }

        .alert-error {
            background-color: #f8d7da;
            border-left: 4px solid #dc3545;
            color: #721c24;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
            }
            to {
                opacity: 0;
            }
        }

        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            gap: 20px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #d7a86e 0%, #c49856 100%);
            color: #3d2817;
            box-shadow: 0 4px 12px rgba(215, 168, 110, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(215, 168, 110, 0.4);
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .search-box {
            position: relative;
            flex: 0 0 400px;
            max-width: 400px;
        }

        .search-box input {
            width: 100%;
            padding: 12px 45px 12px 45px;
            border: 2px solid #e0e0e0;
            border-radius: 25px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .search-box input:focus {
            outline: none;
            border-color: #d7a86e;
            box-shadow: 0 0 0 3px rgba(215, 168, 110, 0.1);
        }

        .search-box i.fa-search {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        .clear-search-btn {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #999;
            cursor: pointer;
            padding: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .clear-search-btn:hover {
            color: #d7a86e;
        }

        .search-info {
            background-color: #e8f4fd;
            border-left: 4px solid #0288d1;
            padding: 12px 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #01579b;
        }

        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            overflow: hidden;
            margin-bottom: 20px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table thead {
            background: linear-gradient(135deg, #3d2817 0%, #5d3d24 100%);
            color: white;
        }

        .data-table th {
            padding: 16px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .data-table tbody tr {
            border-bottom: 1px solid #f0f0f0;
            transition: background-color 0.2s ease;
        }

        .data-table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .data-table td {
            padding: 14px 16px;
            font-size: 14px;
            color: #333;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .status-active {
            background-color: #d4edda;
            color: #155724;
        }

        .status-inactive {
            background-color: #f8d7da;
            color: #721c24;
        }

        .role-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .role-admin {
            background-color: #fff3cd;
            color: #856404;
        }

        .role-manager {
            background-color: #cfe2ff;
            color: #084298;
        }

        .role-staff {
            background-color: #e2e3e5;
            color: #41464b;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-action {
            padding: 8px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .btn-view {
            background-color: #17a2b8;
            color: white;
        }

        .btn-view:hover {
            background-color: #138496;
            transform: translateY(-2px);
        }

        .btn-edit {
            background-color: #ffc107;
            color: #3d2817;
        }

        .btn-edit:hover {
            background-color: #e0a800;
            transform: translateY(-2px);
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
        }

        .btn-delete:hover {
            background-color: #c82333;
            transform: translateY(-2px);
        }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .page-btn {
            padding: 10px 16px;
            border: 2px solid #d7a86e;
            background-color: white;
            color: #3d2817;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            min-width: 45px;
        }

        .page-btn:hover:not(:disabled) {
            background-color: #d7a86e;
            color: white;
            transform: translateY(-2px);
        }

        .page-btn:disabled {
            cursor: not-allowed;
            opacity: 0.6;
        }

        .page-info {
            margin-left: 15px;
            color: #666;
            font-size: 14px;
            font-weight: 500;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.6);
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal-content {
            background-color: white;
            margin: 3% auto;
            padding: 0;
            border-radius: 16px;
            width: 90%;
            max-width: 700px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            animation: slideDown 0.4s ease;
        }

        .modal-small {
            max-width: 500px;
        }

        .modal-header {
            background: linear-gradient(135deg, #d7a86e 0%, #c49856 100%);
            color: #3d2817;
            padding: 20px 25px;
            border-radius: 16px 16px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header-danger {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
        }

        .modal-header h2 {
            font-size: 22px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 32px;
            cursor: pointer;
            color: inherit;
            line-height: 1;
            padding: 0;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s ease;
        }

        .close-btn:hover {
            transform: scale(1.2);
        }

        .modal-body {
            padding: 25px;
        }

        .modal-footer {
            padding: 20px 25px;
            border-top: 1px solid #e9ecef;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group.full-width {
            grid-column: span 2;
        }

        .form-group label {
            font-weight: 600;
            margin-bottom: 8px;
            color: #3d2817;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .required {
            color: #dc3545;
        }

        .form-group input,
        .form-group select {
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #d7a86e;
            box-shadow: 0 0 0 3px rgba(215, 168, 110, 0.1);
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .detail-item.full-width {
            grid-column: span 2;
        }

        .detail-item label {
            font-weight: 600;
            color: #666;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .detail-item span {
            font-size: 15px;
            color: #333;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 6px;
        }

        .delete-message {
            font-size: 16px;
            color: #333;
            margin-bottom: 15px;
            text-align: center;
        }

        .delete-warning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 12px;
            border-radius: 6px;
            color: #856404;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        @media (max-width: 768px) {
            .content {
                padding: 15px;
            }

            .content-header {
                flex-direction: column;
                align-items: stretch;
            }

            .search-box {
                flex: 1;
                max-width: 100%;
            }

            .form-grid,
            .detail-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full-width,
            .detail-item.full-width {
                grid-column: span 1;
            }

            .modal-content {
                width: 95%;
                margin: 10% auto;
            }

            .data-table {
                font-size: 12px;
            }

            .data-table th,
            .data-table td {
                padding: 10px 8px;
            }
        }
    </style>

    <script>
        // Account data from PHP
        const accountsData = <?php echo json_encode($accounts); ?>;
        
        // Role names mapping
        const roleNames = {
            '1': 'Admin',
            '2': 'Nhân viên',
            '3': 'Quản lý'
        };

        // Pagination settings
        let currentPage = 1;
        const itemsPerPage = 5;
        let totalPages = Math.ceil(accountsData.length / itemsPerPage);

        // Display accounts for current page
        function displayAccounts(page) {
            const tbody = document.getElementById('accountBody');
            const start = (page - 1) * itemsPerPage;
            const end = start + itemsPerPage;
            const pageAccounts = accountsData.slice(start, end);

            if (pageAccounts.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:20px;">Không có nhân viên nào</td></tr>';
                return;
            }

            tbody.innerHTML = '';
            pageAccounts.forEach(account => {
                const row = document.createElement('tr');
                const roleName = roleNames[account.Role] || 'Nhân viên';
                const roleClass = account.Role == 1 ? 'role-admin' : (account.Role == 3 ? 'role-manager' : 'role-staff');
                const statusClass = account.Status === 'active' ? 'status-active' : 'status-inactive';
                const statusText = account.Status === 'active' ? 'Đang hoạt động' : 'Tạm dừng';

                row.innerHTML = `
                    <td>${account.ID}</td>
                    <td>${escapeHtml(account.Name)}</td>
                    <td>${escapeHtml(account.Phone)}</td>
                    <td><span class="role-badge ${roleClass}">${roleName}</span></td>
                    <td><span class="status-badge ${statusClass}">${statusText}</span></td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-action btn-view" title="Xem chi tiết" 
                                data-id="${account.ID}"
                                data-name="${escapeHtml(account.Name)}"
                                data-phone="${escapeHtml(account.Phone)}"
                                data-role="${account.Role}"
                                data-status="${account.Status}">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn-action btn-edit" title="Sửa"
                                data-id="${account.ID}"
                                data-name="${escapeHtml(account.Name)}"
                                data-phone="${escapeHtml(account.Phone)}"
                                data-role="${account.Role}"
                                data-status="${account.Status}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-action btn-delete" title="Xóa"
                                data-id="${account.ID}"
                                data-name="${escapeHtml(account.Name)}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                `;
                tbody.appendChild(row);
            });

            attachEventListeners();
        }

        // Display pagination controls
        function displayPagination() {
            const pagination = document.getElementById('pagination');
            pagination.innerHTML = '';

            if (totalPages === 0) return;

            // Previous button
            const prevBtn = document.createElement('button');
            prevBtn.className = 'page-btn';
            prevBtn.innerHTML = '<i class="fas fa-angle-left"></i>';
            prevBtn.disabled = currentPage === 1;
            prevBtn.onclick = () => {
                if (currentPage > 1) {
                    currentPage--;
                    displayAccounts(currentPage);
                    displayPagination();
                }
            };
            pagination.appendChild(prevBtn);

            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                const pageBtn = document.createElement('button');
                pageBtn.className = 'page-btn';
                pageBtn.textContent = i;
                if (i === currentPage) {
                    pageBtn.disabled = true;
                    pageBtn.style.cssText = 'background-color:#d7a86e;border-color:#d7a86e;color:#3d2817;font-weight:600;';
                }
                pageBtn.onclick = () => {
                    currentPage = i;
                    displayAccounts(currentPage);
                    displayPagination();
                };
                pagination.appendChild(pageBtn);
            }

            // Next button
            const nextBtn = document.createElement('button');
            nextBtn.className = 'page-btn';
            nextBtn.innerHTML = '<i class="fas fa-angle-right"></i>';
            nextBtn.disabled = currentPage === totalPages;
            nextBtn.onclick = () => {
                if (currentPage < totalPages) {
                    currentPage++;
                    displayAccounts(currentPage);
                    displayPagination();
                }
            };
            pagination.appendChild(nextBtn);

            // Page info
            const pageInfo = document.createElement('span');
            pageInfo.className = 'page-info';
            pageInfo.textContent = totalPages > 0 
                ? `Trang ${currentPage}/${totalPages} — Tổng: ${accountsData.length} mục`
                : 'Không có nhân viên';
            pagination.appendChild(pageInfo);
        }

        // Escape HTML
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Attach event listeners to buttons
        function attachEventListeners() {
            // View buttons
            document.querySelectorAll('.btn-view').forEach(btn => {
                btn.onclick = function() {
                    const role = this.dataset.role;
                    document.getElementById('view_id').textContent = this.dataset.id;
                    document.getElementById('view_name').textContent = this.dataset.name;
                    document.getElementById('view_phone').textContent = this.dataset.phone;
                    document.getElementById('view_role').innerHTML = `<span class="role-badge ${role == 1 ? 'role-admin' : (role == 3 ? 'role-manager' : 'role-staff')}">${roleNames[role] || 'Nhân viên'}</span>`;
                    
                    const statusSpan = document.getElementById('view_status');
                    if (this.dataset.status === 'active') {
                        statusSpan.innerHTML = '<span class="status-badge status-active">Đang hoạt động</span>';
                    } else {
                        statusSpan.innerHTML = '<span class="status-badge status-inactive">Tạm dừng</span>';
                    }

                    viewModal.style.display = 'block';
                    document.body.style.overflow = 'hidden';
                };
            });

            // Edit buttons
            document.querySelectorAll('.btn-edit').forEach(btn => {
                btn.onclick = function() {
                    document.getElementById('edit_account_id').value = this.dataset.id;
                    document.getElementById('editAccountName').value = this.dataset.name;
                    document.getElementById('editAccountPhone').value = this.dataset.phone;
                    document.getElementById('editAccountRole').value = this.dataset.role;
                    document.getElementById('editAccountStatus').value = this.dataset.status;

                    editModal.style.display = 'block';
                    document.body.style.overflow = 'hidden';
                };
            });

            // Delete buttons
            document.querySelectorAll('.btn-delete').forEach(btn => {
                btn.onclick = function() {
                    deleteAccountId = this.dataset.id;
                    document.getElementById('delete_account_name').textContent = this.dataset.name;
                    deleteModal.style.display = 'block';
                    document.body.style.overflow = 'hidden';
                };
            });
        }

        // Modal elements
        const modal = document.getElementById('addAccountModal');
        const viewModal = document.getElementById('viewAccountModal');
        const editModal = document.getElementById('editAccountModal');
        const deleteModal = document.getElementById('deleteConfirmModal');
        let deleteAccountId = null;

        // Add account button
        document.getElementById('btnAddAccount').onclick = () => {
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        };

        // Close modals
        function closeModal() {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
            document.getElementById('addAccountForm').reset();
        }

        function closeViewModal() {
            viewModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        function closeEditModal() {
            editModal.style.display = 'none';
            document.body.style.overflow = 'auto';
            document.getElementById('editAccountForm').reset();
        }

        function closeDeleteModal() {
            deleteModal.style.display = 'none';
            document.body.style.overflow = 'auto';
            deleteAccountId = null;
        }

        document.getElementById('closeModal').onclick = closeModal;
        document.getElementById('cancelBtn').onclick = closeModal;
        document.getElementById('closeViewModal').onclick = closeViewModal;
        document.getElementById('closeViewBtn').onclick = closeViewModal;
        document.getElementById('closeEditModal').onclick = closeEditModal;
        document.getElementById('cancelEditBtn').onclick = closeEditModal;
        document.getElementById('closeDeleteModal').onclick = closeDeleteModal;
        document.getElementById('cancelDeleteBtn').onclick = closeDeleteModal;

        // Confirm delete
        document.getElementById('confirmDeleteBtn').onclick = function() {
            if (deleteAccountId) {
                const currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('action', 'delete');
                currentUrl.searchParams.set('id', deleteAccountId);
                window.location.href = currentUrl.toString();
            }
        };

        // Close modal when clicking outside
        window.onclick = (e) => {
            if (e.target === modal) closeModal();
            if (e.target === viewModal) closeViewModal();
            if (e.target === editModal) closeEditModal();
            if (e.target === deleteModal) closeDeleteModal();
        };

        // Form validation
        document.getElementById('addAccountForm').onsubmit = (e) => {
            const name = document.getElementById('accountName').value.trim();
            const phone = document.getElementById('accountPhone').value.trim();
            const password = document.getElementById('accountPassword').value.trim();

            if (!name) {
                e.preventDefault();
                alert('Vui lòng nhập họ tên!');
                return false;
            }
            if (!phone) {
                e.preventDefault();
                alert('Vui lòng nhập số điện thoại!');
                return false;
            }
            if (!password) {
                e.preventDefault();
                alert('Vui lòng nhập mật khẩu!');
                return false;
            }
            return true;
        };

        document.getElementById('editAccountForm').onsubmit = (e) => {
            const name = document.getElementById('editAccountName').value.trim();
            const phone = document.getElementById('editAccountPhone').value.trim();

            if (!name) {
                e.preventDefault();
                alert('Vui lòng nhập họ tên!');
                return false;
            }
            if (!phone) {
                e.preventDefault();
                alert('Vui lòng nhập số điện thoại!');
                return false;
            }
            return true;
        };

        // Search functionality (page reload)
        const searchInput = document.getElementById('searchInput');
        const clearSearchBtn = document.getElementById('clearSearchBtn');

        searchInput.onkeypress = (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                const query = searchInput.value.trim();
                window.location.href = query ? `?search=${encodeURIComponent(query)}` : window.location.pathname;
            }
        };

        if (clearSearchBtn) {
            clearSearchBtn.onclick = () => {
                window.location.href = window.location.pathname;
            };
        }

        // Auto-hide alerts
        document.addEventListener('DOMContentLoaded', () => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.animation = 'fadeOut 0.5s ease';
                    setTimeout(() => alert.style.display = 'none', 500);
                }, 5000);
            });

            // Keep modal open if error
            <?php if (!empty($errorMessage)): ?>
                modal.style.display = 'block';
                document.body.style.overflow = 'hidden';
            <?php endif; ?>
        });

        // Initialize on page load
        displayAccounts(currentPage);
        displayPagination();
    </script>
</body>
</html>
