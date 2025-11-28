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
                <input type="text" id="searchInput" placeholder="Tìm kiếm nhân viên..." value="<?php echo isset($searchQuery) ? htmlspecialchars($searchQuery) : ''; ?>" autocomplete="off">
                <?php if (isset($isSearching) && $isSearching): ?>
                    <button type="button" class="clear-search-btn" id="clearSearchBtn" title="Xóa tìm kiếm">
                        <i class="fas fa-times"></i>
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <?php if (isset($isSearching) && $isSearching): ?>
            <div class="search-info">
                <i class="fas fa-info-circle"></i>
                Tìm thấy <strong><?php echo isset($accounts) ? count($accounts) : 0; ?></strong> nhân viên với từ khóa "<strong><?php echo htmlspecialchars($searchQuery); ?></strong>"
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
            <form id="addAccountForm" method="POST" action="<?= BASE_URL ?>admin/user">
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
            <form id="editAccountForm" method="POST" action="<?= BASE_URL ?>admin/user">
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

    <script>
        // Pass data from PHP to JavaScript
        const accountsDataFromPHP = <?php echo isset($accounts) ? json_encode($accounts) : '[]'; ?>;
        const BASE_URL = '<?= BASE_URL ?>';
        const hasErrorMessage = <?php echo !empty($errorMessage) ? 'true' : 'false'; ?>;
    </script>
    <script src="<?= BASE_URL ?>public/js/admin/user.js"></script>
</body>
</html>
