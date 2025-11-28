<?php if (isset($successMessage) && $successMessage): ?>
<div class="alert alert-success">
    <i class="fas fa-check-circle"></i>
    <?= htmlspecialchars($successMessage) ?>
</div>
<?php endif; ?>

<?php if (isset($errorMessage) && $errorMessage): ?>
<div class="alert alert-error">
    <i class="fas fa-exclamation-circle"></i>
    <?= htmlspecialchars($errorMessage) ?>
</div>
<?php endif; ?>

<div class="content-header">
          <button class="btn btn-primary" id="addBranchBtn">
            <i class="fas fa-plus"></i>
            Thêm chi nhánh
          </button>
          <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Tìm kiếm chi nhánh...">
          </div>
        </div>

        <div class="table-container">
          <table class="data-table" id="branchTable">
            <thead>
              <tr>
                <th>ID</th>
                <th>Tên chi nhánh</th>
                <th>Địa chỉ</th>
                <th>Số điện thoại</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
              </tr>
            </thead>
            <tbody id="branchBody">

              <?php if (!empty($branches)): ?>
                <?php foreach ($branches as $branch): ?>
                  <tr>
                    <td><?= htmlspecialchars($branch['ID']) ?></td>
                    <td><?= htmlspecialchars($branch['Name']) ?></td>
                    <td><?= htmlspecialchars($branch['Address']) ?></td>
                    <td><?= htmlspecialchars($branch['Phone']) ?></td>
                    <td>
                      <span class="status-badge 
                        <?= $branch['Status'] === 'active' ? 'status-active' : 'status-inactive' ?>">
                        <?= $branch['Status'] === 'active' ? 'Đang hoạt động' : 'Ngưng hoạt động' ?>
                      </span>
                    </td>
                    <td>
                      <div class="action-buttons">
                        <button class="btn-action btn-view" title="Xem chi tiết" data-id="<?= $branch['ID'] ?>">
                          <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn-action btn-edit" title="Sửa" data-id="<?= $branch['ID'] ?>">
                          <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-action btn-delete" title="Xóa" data-id="<?= $branch['ID'] ?>">
                          <i class="fas fa-trash"></i>
                        </button>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="6" style="text-align:center;">Không có chi nhánh nào</td>
                </tr>
              <?php endif; ?>

            </tbody>
          </table>
        </div>

        <div id="pagination" style="margin-top: 20px; text-align:center;"></div>

<!-- Modal Thêm Chi Nhánh -->
<div id="addBranchModal" class="modal">
  <form id="addBranchForm" method="POST" action="<?= BASE_URL ?>admin/branch">
    <div class="modal-content">
      <div class="modal-header">
        <h2><i class="fas fa-plus-circle"></i> Thêm chi nhánh mới</h2>
        <button type="button" class="close-btn close">&times;</button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="action" value="add_branch">
        <div class="form-grid">
          <div class="form-group full-width">
            <label for="branchName"><i class="fas fa-store"></i> Tên chi nhánh <span class="required">*</span></label>
            <input type="text" id="branchName" name="name" required placeholder="Nhập tên chi nhánh">
          </div>
          <div class="form-group full-width">
            <label for="branchAddress"><i class="fas fa-map-marker-alt"></i> Địa chỉ <span class="required">*</span></label>
            <textarea id="branchAddress" name="address" required placeholder="Nhập địa chỉ chi nhánh" rows="3"></textarea>
          </div>
          <div class="form-group">
            <label for="branchPhone"><i class="fas fa-phone"></i> Số điện thoại <span class="required">*</span></label>
            <input type="tel" id="branchPhone" name="phone" required placeholder="Nhập số điện thoại (10-11 số)" pattern="[0-9]{10,11}">
          </div>
          <div class="form-group">
            <label for="branchStatus"><i class="fas fa-toggle-on"></i> Trạng thái</label>
            <select id="branchStatus" name="status">
              <option value="active">Đang hoạt động</option>
              <option value="inactive">Ngưng hoạt động</option>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer form-actions">
        <button type="button" class="btn btn-secondary" id="cancelBtn"><i class="fas fa-times"></i> Hủy</button>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Thêm chi nhánh</button>
      </div>
    </div>
  </form>
</div>

<!-- Modal Sửa Chi Nhánh -->
<div id="editBranchModal" class="modal">
  <form id="editBranchForm" method="POST" action="<?= BASE_URL ?>admin/branch">
    <div class="modal-content">
      <div class="modal-header">
        <h2><i class="fas fa-edit"></i> Sửa chi nhánh</h2>
        <button type="button" class="close-btn edit-close">&times;</button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="action" value="edit_branch">
        <input type="hidden" id="editBranchID" name="branch_id">
        <div class="form-grid">
          <div class="form-group full-width">
            <label><i class="fas fa-store"></i> Tên chi nhánh <span class="required">*</span></label>
            <input type="text" id="editBranchName" name="name" required>
          </div>
          <div class="form-group full-width">
            <label><i class="fas fa-map-marker-alt"></i> Địa chỉ <span class="required">*</span></label>
            <textarea id="editBranchAddress" name="address" required rows="3"></textarea>
          </div>
          <div class="form-group">
            <label><i class="fas fa-phone"></i> Số điện thoại <span class="required">*</span></label>
            <input type="tel" id="editBranchPhone" name="phone" required>
          </div>
          <div class="form-group">
            <label><i class="fas fa-toggle-on"></i> Trạng thái</label>
            <select id="editBranchStatus" name="status">
              <option value="active">Đang hoạt động</option>
              <option value="inactive">Ngưng hoạt động</option>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer form-actions">
        <button type="button" class="btn btn-secondary edit-cancel"><i class="fas fa-times"></i> Hủy</button>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Cập nhật</button>
      </div>
    </div>
  </form>
</div>

<!-- Modal Xem Chi Tiết -->
<div id="viewBranchModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h2><i class="fas fa-info-circle"></i> Chi Tiết Chi Nhánh</h2>
      <button type="button" class="close-btn view-close">&times;</button>
    </div>
    <div class="modal-body">
      <div class="detail-grid">
        <div class="detail-item">
          <label><i class="fas fa-hashtag"></i> ID:</label>
          <span id="view_id"></span>
        </div>
        <div class="detail-item">
          <label><i class="fas fa-store"></i> Tên chi nhánh:</label>
          <span id="view_name"></span>
        </div>
        <div class="detail-item full-width">
          <label><i class="fas fa-map-marker-alt"></i> Địa chỉ:</label>
          <span id="view_address"></span>
        </div>
        <div class="detail-item">
          <label><i class="fas fa-phone"></i> Số điện thoại:</label>
          <span id="view_phone"></span>
        </div>
        <div class="detail-item">
          <label><i class="fas fa-toggle-on"></i> Trạng thái:</label>
          <span id="view_status"></span>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary view-cancel"><i class="fas fa-times"></i> Đóng</button>
    </div>
  </div>
</div>

<!-- Modal Xóa Chi Nhánh -->
<div id="deleteBranchModal" class="modal">
  <div class="modal-content modal-small">
    <div class="modal-header modal-header-danger">
      <h2><i class="fas fa-exclamation-triangle"></i> Xác nhận xóa</h2>
      <button type="button" class="close-btn delete-close">&times;</button>
    </div>
    <div class="modal-body">
      <p class="delete-message">Bạn có chắc muốn xóa chi nhánh này không?</p>
      <div class="delete-warning">
        <i class="fas fa-exclamation-triangle"></i>
        <span>Hành động này không thể hoàn tác.</span>
      </div>
    </div>
    <div class="modal-footer form-actions">
      <button type="button" class="btn btn-secondary" id="cancelDeleteBtn"><i class="fas fa-times"></i> Hủy</button>
      <button type="button" class="btn btn-danger" id="confirmDeleteBtn"><i class="fas fa-trash"></i> Xóa</button>
    </div>
  </div>
</div>

<script>
// Truyền data từ PHP sang JavaScript
const branches = <?= json_encode($branches, JSON_UNESCAPED_UNICODE); ?>;
const BASE_URL = '<?= BASE_URL ?>';
</script>
<script src="<?= BASE_URL ?>public/js/admin/branch.js"></script>
