<?php 
// Form submissions are handled in index.php BEFORE HTML output

// Initialize messages
$successMessage = '';
$errorMessage = '';

// Handle success messages from redirect
if (isset($_GET['success'])) {
    switch ($_GET['success']) {
        case 'add':
            $successMessage = 'Thêm khuyến mãi thành công!';
            break;
        case 'edit':
            $successMessage = 'Cập nhật khuyến mãi thành công!';
            break;
        case 'delete':
            $successMessage = 'Xóa khuyến mãi thành công!';
            break;
    }
}

// Handle search
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
$isSearching = !empty($searchQuery);

// Get coupons
if (!isset($coupons)) {
    $couponModel = new Coupon();
    $allcoupons = $couponModel->getAllCoupons();
} else {
    $allcoupons = $coupons;
}

// Filter coupons if searching
if ($isSearching) {
    $coupons = array_filter($allcoupons, function($coupon) use ($searchQuery) {
        $searchLower = strtolower($searchQuery);
        return stripos($coupon['Code'], $searchLower) !== false ||
               stripos($coupon['Description'], $searchLower) !== false ||
               stripos($coupon['Discount_value'], $searchLower) !== false;
    });
    $coupons = array_values($coupons);
} else {
    $coupons = $allcoupons;
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
            <button class="btn btn-primary" id="btnAddcoupon">
                <i class="fas fa-plus"></i>
                Thêm khuyến mãi
            </button>
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Tìm kiếm khuyến mãi..." value="<?php echo htmlspecialchars($searchQuery); ?>" autocomplete="off">
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
                Tìm thấy <strong><?php echo count($coupons); ?></strong> khuyến mãi với từ khóa "<strong><?php echo htmlspecialchars($searchQuery); ?></strong>"
            </div>
        <?php endif; ?>

        <!-- Table Container -->
        <div class="table-container">
            <table class="data-table" id="couponTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Mã KM</th>
                        <th>Mô tả</th>
                        <th>Giá trị</th>
                        <th>Ngày bắt đầu</th>
                        <th>Ngày kết thúc</th>
                        <th>Số lượng</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody id="couponBody">
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pagination" id="pagination"></div>
    </div>

    <!-- Modal Thêm Khuyến Mãi -->
    <div id="addcouponModal" class="modal">
        <div class="modal-content">
            <form id="addcouponForm" method="POST" action="">
                <div class="modal-header">
                    <h2><i class="fas fa-ticket-alt"></i> Thêm Khuyến Mãi Mới</h2>
                    <button type="button" class="close-btn" id="closeModal">&times;</button>
                </div>
                <input type="hidden" name="action" value="add_coupon">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="couponCode">
                            <i class="fas fa-barcode"></i> Mã khuyến mãi <span class="required">*</span>
                        </label>
                        <input type="text" id="couponCode" name="code" placeholder="Nhập mã khuyến mãi" required>
                    </div>

                    <div class="form-group">
                        <label for="couponValue">
                            <i class="fas fa-percentage"></i> Giá trị <span class="required">*</span>
                        </label>
                        <input type="text" id="couponValue" name="value" placeholder="Ví dụ: 10%, 20000đ" required>
                    </div>

                    <div class="form-group full-width">
                        <label for="couponDescription">
                            <i class="fas fa-align-left"></i> Mô tả <span class="required">*</span>
                        </label>
                        <textarea id="couponDescription" name="description" placeholder="Nhập mô tả khuyến mãi" rows="3" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="couponStartDate">
                            <i class="fas fa-calendar-alt"></i> Ngày bắt đầu <span class="required">*</span>
                        </label>
                        <input type="date" id="couponStartDate" name="start_date" required>
                    </div>

                    <div class="form-group">
                        <label for="couponEndDate">
                            <i class="fas fa-calendar-check"></i> Ngày kết thúc <span class="required">*</span>
                        </label>
                        <input type="date" id="couponEndDate" name="end_date" required>
                    </div>

                    <div class="form-group">
                        <label for="couponQuantity">
                            <i class="fas fa-box"></i> Số lượng <span class="required">*</span>
                        </label>
                        <input type="number" id="couponQuantity" name="quantity" placeholder="Nhập số lượng" min="0" value="0" required>
                    </div>

                    <div class="form-group">
                        <label for="couponStatus">
                            <i class="fas fa-toggle-on"></i> Trạng thái <span class="required">*</span>
                        </label>
                        <select id="couponStatus" name="status" required>
                            <option value="active">Đang hoạt động</option>
                            <option value="inactive">Chưa kích hoạt</option>
                            <option value="expired">Đã hết hạn</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="cancelBtn">
                        <i class="fas fa-times"></i> Hủy
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Lưu khuyến mãi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Xem Chi Tiết -->
    <div id="viewcouponModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-info-circle"></i> Chi Tiết Khuyến Mãi</h2>
                <button type="button" class="close-btn" id="closeViewModal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="detail-grid">
                    <div class="detail-item">
                        <label><i class="fas fa-hashtag"></i> ID:</label>
                        <span id="view_id"></span>
                    </div>
                    <div class="detail-item">
                        <label><i class="fas fa-barcode"></i> Mã KM:</label>
                        <span id="view_code"></span>
                    </div>
                    <div class="detail-item full-width">
                        <label><i class="fas fa-align-left"></i> Mô tả:</label>
                        <span id="view_description"></span>
                    </div>
                    <div class="detail-item">
                        <label><i class="fas fa-percentage"></i> Giá trị:</label>
                        <span id="view_value"></span>
                    </div>
                    <div class="detail-item">
                        <label><i class="fas fa-box"></i> Số lượng:</label>
                        <span id="view_quantity"></span>
                    </div>
                    <div class="detail-item">
                        <label><i class="fas fa-calendar-alt"></i> Ngày bắt đầu:</label>
                        <span id="view_start_date"></span>
                    </div>
                    <div class="detail-item">
                        <label><i class="fas fa-calendar-check"></i> Ngày kết thúc:</label>
                        <span id="view_end_date"></span>
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

    <!-- Modal Sửa Khuyến Mãi -->
    <div id="editcouponModal" class="modal">
        <div class="modal-content">
            <form id="editcouponForm" method="POST" action="">
                <div class="modal-header">
                    <h2><i class="fas fa-edit"></i> Sửa Khuyến Mãi</h2>
                    <button type="button" class="close-btn" id="closeEditModal">&times;</button>
                </div>
                <input type="hidden" name="action" value="edit_coupon">
                <input type="hidden" name="coupon_id" id="edit_coupon_id">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="editcouponCode">
                            <i class="fas fa-barcode"></i> Mã khuyến mãi <span class="required">*</span>
                        </label>
                        <input type="text" id="editcouponCode" name="code" placeholder="Nhập mã khuyến mãi" required>
                    </div>

                    <div class="form-group">
                        <label for="editcouponValue">
                            <i class="fas fa-percentage"></i> Giá trị <span class="required">*</span>
                        </label>
                        <input type="text" id="editcouponValue" name="value" placeholder="Ví dụ: 10%, 20000đ" required>
                    </div>

                    <div class="form-group full-width">
                        <label for="editcouponDescription">
                            <i class="fas fa-align-left"></i> Mô tả <span class="required">*</span>
                        </label>
                        <textarea id="editcouponDescription" name="description" placeholder="Nhập mô tả khuyến mãi" rows="3" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="editcouponStartDate">
                            <i class="fas fa-calendar-alt"></i> Ngày bắt đầu <span class="required">*</span>
                        </label>
                        <input type="date" id="editcouponStartDate" name="start_date" required>
                    </div>

                    <div class="form-group">
                        <label for="editcouponEndDate">
                            <i class="fas fa-calendar-check"></i> Ngày kết thúc <span class="required">*</span>
                        </label>
                        <input type="date" id="editcouponEndDate" name="end_date" required>
                    </div>

                    <div class="form-group">
                        <label for="editcouponQuantity">
                            <i class="fas fa-box"></i> Số lượng <span class="required">*</span>
                        </label>
                        <input type="number" id="editcouponQuantity" name="quantity" placeholder="Nhập số lượng" min="0" required>
                    </div>

                    <div class="form-group">
                        <label for="editcouponStatus">
                            <i class="fas fa-toggle-on"></i> Trạng thái <span class="required">*</span>
                        </label>
                        <select id="editcouponStatus" name="status" required>
                            <option value="active">Đang hoạt động</option>
                            <option value="inactive">Chưa kích hoạt</option>
                            <option value="expired">Đã hết hạn</option>
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
                <button type="button" class="close-btn" id="closeDeleteModal">&times;</button>
            </div>
            <div class="modal-body">
                <p class="delete-message">
                    Bạn có chắc chắn muốn xóa khuyến mãi <strong id="delete_coupon_code"></strong>?
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
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: #f5f5f5;
        }

        .content {
            padding: 30px;
            margin-left: 0;
            background-color: #f8f9fa;
            min-height: 100vh;
        }

        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 15px;
            font-weight: 500;
            animation: slideDown 0.3s ease-out;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border-left: 4px solid #28a745;
            color: #155724;
        }

        .alert-error {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            border-left: 4px solid #dc3545;
            color: #721c24;
        }

        .alert i {
            font-size: 20px;
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
            border-radius: 10px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            letter-spacing: 0.3px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #d4a574 0%, #c49254 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(212, 165, 116, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #c49254 0%, #b38043 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(212, 165, 116, 0.4);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(108, 117, 125, 0.2);
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, #5a6268 0%, #495057 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(108, 117, 125, 0.3);
        }

        .btn-danger {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(220, 53, 69, 0.4);
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
            background: white;
        }

        .search-box input:focus {
            outline: none;
            border-color: #d4a574;
            box-shadow: 0 0 0 4px rgba(212, 165, 116, 0.15);
            background: #fefdfb;
        }

        .search-box i.fa-search {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #d4a574;
            font-size: 16px;
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
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .clear-search-btn:hover {
            color: #dc3545;
            background: rgba(220, 53, 69, 0.1);
        }

        .search-info {
            background: linear-gradient(135deg, #e8f4fd 0%, #d4ebf8 100%);
            border-left: 4px solid #0288d1;
            padding: 14px 20px;
            margin-bottom: 24px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: #01579b;
            box-shadow: 0 2px 8px rgba(2, 136, 209, 0.1);
        }

        .search-info i {
            font-size: 18px;
        }

        .table-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
            overflow: hidden;
            margin-bottom: 24px;
            border: 1px solid rgba(0,0,0,0.05);
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table thead {
            background: linear-gradient(135deg, #3e2723 0%, #5d4037 100%);
            color: white;
        }

        .data-table th {
            padding: 18px 16px;
            text-align: left;
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .data-table tbody tr {
            border-bottom: 1px solid #f0f0f0;
            transition: all 0.3s ease;
        }

        .data-table tbody tr:hover {
            background: linear-gradient(135deg, #fef9f3 0%, #fef5e7 100%);
            transform: scale(1.005);
            box-shadow: 0 2px 8px rgba(212, 165, 116, 0.1);
        }

        .data-table td {
            padding: 16px;
            font-size: 14px;
            color: #333;
        }

        .status-badge {
            padding: 7px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            display: inline-block;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .status-active {
            background: linear-gradient(135deg, #70ad47 0%, #5a9335 100%);
            color: white;
        }

        .status-inactive {
            background: linear-gradient(135deg, #ffd966 0%, #ffcc33 100%);
            color: #3e2723;
        }

        .status-expired {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-action {
            padding: 10px 14px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-view {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
        }

        .btn-view:hover {
            background: linear-gradient(135deg, #138496 0%, #117a8b 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(23, 162, 184, 0.3);
        }

        .btn-edit {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
            color: white;
        }

        .btn-edit:hover {
            background: linear-gradient(135deg, #e0a800 0%, #d39e00 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
        }

        .btn-delete {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
        }

        .btn-delete:hover {
            background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
        }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 24px;
            flex-wrap: wrap;
        }

        .page-btn {
            padding: 12px 18px;
            border: 2px solid #d4a574;
            background: white;
            color: #3e2723;
            border-radius: 10px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            min-width: 48px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .page-btn:hover:not(:disabled) {
            background: linear-gradient(135deg, #d4a574 0%, #c49254 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(212, 165, 116, 0.3);
        }

        .page-btn:disabled {
            cursor: not-allowed;
            opacity: 0.5;
            background: #e0e0e0;
            border-color: #e0e0e0;
            color: #999;
        }

        .page-info {
            margin-left: 15px;
            color: #3e2723;
            font-size: 14px;
            font-weight: 600;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow-y: auto;
            overflow-x: hidden;
            background-color: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(2px);
        }

        .modal.show {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-content {
            background-color: white;
            margin: 2% auto;
            padding: 0;
            border-radius: 16px;
            width: 85%;
            max-width: 750px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
            animation: slideDown 0.4s ease;
            border: 1px solid rgba(0, 0, 0, 0.1);
            max-height: 90vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .modal-small {
            max-width: 500px;
        }

        .modal-header {
            background: linear-gradient(135deg, #d4a574 0%, #c49254 100%);
            color: white;
            padding: 20px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
        }

        .modal-header-danger {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
        }

        .modal-header h2 {
            font-size: 20px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
            letter-spacing: 0.3px;
            margin: 0;
        }

        .modal-header h2 i {
            font-size: 20px;
        }

        .close-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: white;
            line-height: 1;
            padding: 0;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .close-btn:hover {
            transform: rotate(90deg);
            background: rgba(255, 255, 255, 0.3);
        }

        .modal-body {
            padding: 24px;
            overflow-y: auto;
            flex: 1;
        }

        form .modal-body {
            padding: 0;
        }

        .modal-footer {
            padding: 16px 24px;
            border-top: 2px solid #f0f0f0;
            background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%);
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            flex-shrink: 0;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px;
            padding: 24px;
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
            color: #3e2723;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .form-group label i {
            color: #d4a574;
            font-size: 14px;
        }

        .required {
            color: #dc3545;
            font-weight: 700;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 11px 14px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            font-family: inherit;
            background: white;
            width: 100%;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #d4a574;
            box-shadow: 0 0 0 3px rgba(212, 165, 116, 0.15);
            background: #fefdfb;
        }

        .form-group input[type="date"] {
            cursor: pointer;
        }

        .form-group input[type="date"]::-webkit-calendar-picker-indicator {
            cursor: pointer;
            filter: opacity(0.6);
        }

        .form-group input[type="date"]::-webkit-calendar-picker-indicator:hover {
            filter: opacity(1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        .form-group select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23333' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            padding-right: 40px;
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
            font-weight: 700;
            color: #666;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .detail-item label i {
            color: #d4a574;
            font-size: 16px;
        }

        .detail-item span {
            font-size: 15px;
            color: #333;
            padding: 14px 16px;
            background: linear-gradient(135deg, #f8f9fa 0%, #f0f0f0 100%);
            border-radius: 10px;
            border: 1px solid #e0e0e0;
            font-weight: 500;
        }

        .delete-message {
            font-size: 17px;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 500;
            line-height: 1.6;
        }

        .delete-message strong {
            color: #dc3545;
            font-weight: 700;
        }

        .delete-warning {
            background: linear-gradient(135deg, #fff3cd 0%, #ffe8a1 100%);
            border-left: 4px solid #ffc107;
            padding: 14px 16px;
            border-radius: 10px;
            color: #856404;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(255, 193, 7, 0.1);
        }

        .delete-warning i {
            font-size: 18px;
        }

        /* Responsive */
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
                margin: 5% auto;
            }

            .modal-header h2 {
                font-size: 18px;
            }

            .modal-body {
                padding: 20px;
            }

            .modal-footer {
                padding: 15px 20px;
            }

            .data-table {
                font-size: 12px;
            }

            .data-table th,
            .data-table td {
                padding: 10px 8px;
            }

            .action-buttons {
                flex-direction: column;
                gap: 6px;
            }

            .btn-action {
                width: 100%;
            }
        }

        /* Fix for body scroll when modal is open */
        body.modal-open {
            overflow: hidden;
        }
    </style>

    <script>
        // coupon data from PHP
        const couponsData = <?php echo json_encode($coupons); ?>;
        
        // Pagination settings
        let currentPage = 1;
        const itemsPerPage = 5;
        let totalPages = Math.ceil(couponsData.length / itemsPerPage);

        // Format date to dd/mm/yyyy or display time if it's time format
        function formatDate(dateString) {
            if (!dateString) return '';
            // If it's a time format (HH:MM:SS), just return it
            if (dateString.match(/^\d{2}:\d{2}:\d{2}$/)) {
                return dateString;
            }
            // Otherwise try to parse as date
            const date = new Date(dateString);
            if (isNaN(date.getTime())) {
                return dateString; // Return original if invalid
            }
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();
            return `${day}/${month}/${year}`;
        }

        // Display coupons for current page
        function displaycoupons(page) {
            const tbody = document.getElementById('couponBody');
            const start = (page - 1) * itemsPerPage;
            const end = start + itemsPerPage;
            const pagecoupons = couponsData.slice(start, end);

            if (pagecoupons.length === 0) {
                tbody.innerHTML = '<tr><td colspan="9" style="text-align:center;padding:20px;">Không có khuyến mãi nào</td></tr>';
                return;
            }

            tbody.innerHTML = '';
            pagecoupons.forEach(coupon => {
                const row = document.createElement('tr');
                
                let statusClass = 'status-active';
                let statusText = 'Đang hoạt động';
                if (coupon.Status === 'inactive') {
                    statusClass = 'status-inactive';
                    statusText = 'Chưa kích hoạt';
                } else if (coupon.Status === 'expired') {
                    statusClass = 'status-expired';
                    statusText = 'Đã hết hạn';
                }

                row.innerHTML = `
                    <td>${coupon.ID}</td>
                    <td>${escapeHtml(coupon.Code)}</td>
                    <td>${escapeHtml(coupon.Description)}</td>
                    <td>${escapeHtml(coupon.Discount_value)}</td>
                    <td>${formatDate(coupon.StartDate)}</td>
                    <td>${formatDate(coupon.EndDate)}</td>
                    <td>${coupon.Quantity}</td>
                    <td><span class="status-badge ${statusClass}">${statusText}</span></td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-action btn-view" title="Xem chi tiết" 
                                data-id="${coupon.ID}"
                                data-code="${escapeHtml(coupon.Code)}"
                                data-description="${escapeHtml(coupon.Description)}"
                                data-value="${escapeHtml(coupon.Discount_value)}"
                                data-start="${coupon.StartDate}"
                                data-end="${coupon.EndDate}"
                                data-quantity="${coupon.Quantity}"
                                data-status="${coupon.Status}">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn-action btn-edit" title="Sửa"
                                data-id="${coupon.ID}"
                                data-code="${escapeHtml(coupon.Code)}"
                                data-description="${escapeHtml(coupon.Description)}"
                                data-value="${escapeHtml(coupon.Discount_value)}"
                                data-start="${coupon.StartDate}"
                                data-end="${coupon.EndDate}"
                                data-quantity="${coupon.Quantity}"
                                data-status="${coupon.Status}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-action btn-delete" title="Xóa"
                                data-id="${coupon.ID}"
                                data-code="${escapeHtml(coupon.Code)}">
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
                    displaycoupons(currentPage);
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
                    displaycoupons(currentPage);
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
                    displaycoupons(currentPage);
                    displayPagination();
                }
            };
            pagination.appendChild(nextBtn);

            // Page info
            const pageInfo = document.createElement('span');
            pageInfo.className = 'page-info';
            pageInfo.textContent = totalPages > 0 
                ? `Trang ${currentPage}/${totalPages} — Tổng: ${couponsData.length} mục`
                : 'Không có khuyến mãi';
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
                    document.getElementById('view_id').textContent = this.dataset.id;
                    document.getElementById('view_code').textContent = this.dataset.code;
                    document.getElementById('view_description').textContent = this.dataset.description;
                    document.getElementById('view_value').textContent = this.dataset.value;
                    document.getElementById('view_quantity').textContent = this.dataset.quantity;
                    document.getElementById('view_start_date').textContent = formatDate(this.dataset.start);
                    document.getElementById('view_end_date').textContent = formatDate(this.dataset.end);
                    
                    const statusSpan = document.getElementById('view_status');
                    let statusClass = 'status-active';
                    let statusText = 'Đang hoạt động';
                    if (this.dataset.status === 'inactive') {
                        statusClass = 'status-inactive';
                        statusText = 'Chưa kích hoạt';
                    } else if (this.dataset.status === 'expired') {
                        statusClass = 'status-expired';
                        statusText = 'Đã hết hạn';
                    }
                    statusSpan.innerHTML = `<span class="status-badge ${statusClass}">${statusText}</span>`;

                    openModal(viewModal);
                };
            });

            // Edit buttons
            document.querySelectorAll('.btn-edit').forEach(btn => {
                btn.onclick = function() {
                    document.getElementById('edit_coupon_id').value = this.dataset.id;
                    document.getElementById('editcouponCode').value = this.dataset.code;
                    document.getElementById('editcouponDescription').value = this.dataset.description;
                    document.getElementById('editcouponValue').value = this.dataset.value;
                    document.getElementById('editcouponStartDate').value = this.dataset.start;
                    document.getElementById('editcouponEndDate').value = this.dataset.end;
                    document.getElementById('editcouponQuantity').value = this.dataset.quantity;
                    document.getElementById('editcouponStatus').value = this.dataset.status;

                    openModal(editModal);
                };
            });

            // Delete buttons
            document.querySelectorAll('.btn-delete').forEach(btn => {
                btn.onclick = function() {
                    deletecouponId = this.dataset.id;
                    document.getElementById('delete_coupon_code').textContent = this.dataset.code;
                    openModal(deleteModal);
                };
            });
        }

        // Modal elements
        const modal = document.getElementById('addcouponModal');
        const viewModal = document.getElementById('viewcouponModal');
        const editModal = document.getElementById('editcouponModal');
        const deleteModal = document.getElementById('deleteConfirmModal');
        let deletecouponId = null;

        // Helper functions for modal
        function openModal(modalElement) {
            modalElement.style.display = 'block';
            modalElement.classList.add('show');
            document.body.classList.add('modal-open');
            document.body.style.overflow = 'hidden';
        }

        function closeModalElement(modalElement) {
            modalElement.style.display = 'none';
            modalElement.classList.remove('show');
            document.body.classList.remove('modal-open');
            document.body.style.overflow = 'auto';
        }

        // Add coupon button
        document.getElementById('btnAddcoupon').onclick = () => {
            openModal(modal);
        };

        // Close modals
        function closeModal() {
            closeModalElement(modal);
            document.getElementById('addcouponForm').reset();
        }

        function closeViewModal() {
            closeModalElement(viewModal);
        }

        function closeEditModal() {
            closeModalElement(editModal);
            document.getElementById('editcouponForm').reset();
        }

        function closeDeleteModal() {
            closeModalElement(deleteModal);
            deletecouponId = null;
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
            if (deletecouponId) {
                const currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('action', 'delete');
                currentUrl.searchParams.set('id', deletecouponId);
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
        document.getElementById('addcouponForm').onsubmit = (e) => {
            const code = document.getElementById('couponCode').value.trim();
            const description = document.getElementById('couponDescription').value.trim();
            const value = document.getElementById('couponValue').value.trim();
            const startDate = document.getElementById('couponStartDate').value;
            const endDate = document.getElementById('couponEndDate').value;

            if (!code) {
                e.preventDefault();
                alert('Vui lòng nhập mã khuyến mãi!');
                return false;
            }
            if (!description) {
                e.preventDefault();
                alert('Vui lòng nhập mô tả!');
                return false;
            }
            if (!value) {
                e.preventDefault();
                alert('Vui lòng nhập giá trị khuyến mãi!');
                return false;
            }
            if (!startDate || !endDate) {
                e.preventDefault();
                alert('Vui lòng chọn ngày bắt đầu và kết thúc!');
                return false;
            }
            if (new Date(startDate) > new Date(endDate)) {
                e.preventDefault();
                alert('Ngày bắt đầu phải nhỏ hơn ngày kết thúc!');
                return false;
            }
            return true;
        };

        document.getElementById('editcouponForm').onsubmit = (e) => {
            const code = document.getElementById('editcouponCode').value.trim();
            const description = document.getElementById('editcouponDescription').value.trim();
            const value = document.getElementById('editcouponValue').value.trim();
            const startDate = document.getElementById('editcouponStartDate').value;
            const endDate = document.getElementById('editcouponEndDate').value;

            if (!code) {
                e.preventDefault();
                alert('Vui lòng nhập mã khuyến mãi!');
                return false;
            }
            if (!description) {
                e.preventDefault();
                alert('Vui lòng nhập mô tả!');
                return false;
            }
            if (!value) {
                e.preventDefault();
                alert('Vui lòng nhập giá trị khuyến mãi!');
                return false;
            }
            if (new Date(startDate) > new Date(endDate)) {
                e.preventDefault();
                alert('Ngày bắt đầu phải nhỏ hơn ngày kết thúc!');
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
                openModal(modal);
            <?php endif; ?>
        });

        // Add keyboard support (ESC to close)
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                if (modal.style.display === 'block') closeModal();
                if (viewModal.style.display === 'block') closeViewModal();
                if (editModal.style.display === 'block') closeEditModal();
                if (deleteModal.style.display === 'block') closeDeleteModal();
            }
        });

        // Initialize on page load
        displaycoupons(currentPage);
        displayPagination();
    </script>
</body>
</html>
