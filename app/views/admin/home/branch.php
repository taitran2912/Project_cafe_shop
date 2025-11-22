<?php 
// Form submissions are handled in index.php BEFORE HTML output

// Initialize messages
$successMessage = '';
$errorMessage = '';

// Handle success messages from redirect
if (isset($_GET['success'])) {
    switch ($_GET['success']) {
        case 'add':
            $successMessage = 'Thêm chi nhánh thành công!';
            break;
        case 'edit':
            $successMessage = 'Cập nhật chi nhánh thành công!';
            break;
        case 'delete':
            $successMessage = 'Xóa chi nhánh thành công!';
            break;
    }
}

// Get all branches
$branchModel = new Branch();
$branches = $branchModel->getAllBranch();
?>

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
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 14px;
        font-size: 14.5px;
        font-weight: 500;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        animation: slideDown 0.4s ease;
    }

    .alert-success {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        border-left: 5px solid #28a745;
        color: #155724;
    }

    .alert-success i {
        font-size: 22px;
        color: #28a745;
    }

    .alert-error, .alert-danger {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        border-left: 5px solid #dc3545;
        color: #721c24;
    }

    .alert-error i, .alert-danger i {
        font-size: 22px;
        color: #dc3545;
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

    .content-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        gap: 20px;
    }

    .btn {
        padding: 12px 26px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-size: 15px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
        position: relative;
        overflow: hidden;
    }

    .btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .btn:hover::before {
        width: 300px;
        height: 300px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #d7a86e 0%, #c49856 100%);
        color: #3d2817;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #c49856 0%, #b38846 100%);
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(215, 168, 110, 0.4);
    }

    .btn-secondary {
        background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
        color: white;
    }

    .btn-secondary:hover {
        background: linear-gradient(135deg, #5a6268 0%, #495057 100%);
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(108, 117, 125, 0.4);
    }

    .btn-danger {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
    }

    .btn-danger:hover {
        background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(220, 53, 69, 0.4);
    }

    .search-box {
        position: relative;
        flex: 0 0 420px;
        max-width: 420px;
    }

    .search-box input {
        width: 100%;
        padding: 13px 48px 13px 48px;
        border: 2px solid #e0e0e0;
        border-radius: 30px;
        font-size: 14.5px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: white;
    }

    .search-box input:focus {
        outline: none;
        border-color: transparent;
        background: linear-gradient(white, white) padding-box,
                    linear-gradient(135deg, #d7a86e 0%, #c49856 100%) border-box;
        box-shadow: 0 4px 16px rgba(215, 168, 110, 0.25);
        transform: translateY(-2px);
    }

    .search-box i.fa-search {
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: #d7a86e;
        font-size: 16px;
        transition: color 0.3s ease;
    }

    .search-box input:focus ~ i.fa-search {
        color: #c49856;
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
        background: linear-gradient(135deg, #3e2723 0%, #5d4037 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .data-table th {
        padding: 18px;
        text-align: left;
        font-weight: 700;
        font-size: 13.5px;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }

    .data-table tbody tr {
        border-bottom: 1px solid #f0f0f0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .data-table tbody tr:hover {
        background: linear-gradient(135deg, #fff9f5 0%, #fff5ed 100%);
        transform: scale(1.005);
        box-shadow: 0 2px 8px rgba(215, 168, 110, 0.15);
    }

    .data-table td {
        padding: 16px 18px;
        font-size: 14.5px;
        color: #333;
    }

    .status-badge {
        padding: 7px 14px;
        border-radius: 25px;
        font-size: 11.5px;
        font-weight: 700;
        display: inline-block;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .status-active {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        color: #155724;
    }

    .status-inactive {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        color: #721c24;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
    }

    .btn-action {
        padding: 9px 14px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        font-weight: 500;
    }

    .btn-view {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
    }

    .btn-view:hover {
        background: linear-gradient(135deg, #138496 0%, #117a8b 100%);
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(23, 162, 184, 0.4);
    }

    .btn-edit {
        background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
        color: #3d2817;
    }

    .btn-edit:hover {
        background: linear-gradient(135deg, #e0a800 0%, #d39e00 100%);
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(255, 193, 7, 0.4);
    }

    .btn-delete {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
    }

    .btn-delete:hover {
        background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
    }

    .page-btn {
        padding: 11px 18px;
        border: 2px solid #d7a86e;
        background-color: white;
        color: #3d2817;
        border-radius: 10px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        min-width: 48px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        margin: 0 4px;
    }

    .page-btn:hover {
        background: linear-gradient(135deg, #d7a86e 0%, #c49856 100%);
        color: #3d2817;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(215, 168, 110, 0.35);
        border-color: transparent;
    }

    .page-btn.active {
        background: linear-gradient(135deg, #d7a86e 0%, #c49856 100%);
        border-color: transparent;
        color: #3d2817;
        font-weight: 700;
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
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.65);
        backdrop-filter: blur(4px);
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideDownModal {
        from {
            opacity: 0;
            transform: translateY(-40px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modal-content {
        background-color: white;
        margin: 3% auto;
        padding: 0;
        border-radius: 16px;
        width: 90%;
        max-width: 700px;
        max-height: 90vh;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        animation: slideDownModal 0.4s ease;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .modal-small {
        max-width: 500px;
    }

    .modal-header {
        background: linear-gradient(135deg, #d7a86e 0%, #c49856 100%);
        color: #3d2817;
        padding: 20px 24px;
        border-radius: 16px 16px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-shrink: 0;
    }

    .modal-header-danger {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
    }

    .modal-header h2 {
        font-size: 20px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .modal-header h2 i {
        font-size: 22px;
    }

    .close-btn, .close {
        background: none;
        border: none;
        font-size: 28px;
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
        border-radius: 8px;
    }

    .close-btn:hover, .close:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: rotate(90deg);
    }

    .modal-body {
        padding: 24px;
        overflow-y: auto;
        flex: 1;
    }

    .modal-footer, .form-actions {
        padding: 16px 24px;
        border-top: 1px solid #e9ecef;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        flex-shrink: 0;
        background-color: #f8f9fa;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 18px;
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
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .form-group label i {
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
        width: 100%;
    }

    .form-group textarea {
        min-height: 80px;
        resize: vertical;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #d7a86e;
        box-shadow: 0 0 0 4px rgba(215, 168, 110, 0.15);
        transform: translateY(-1px);
    }

    .form-group select {
        appearance: none;
        background: white url('data:image/svg+xml;charset=UTF-8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%23d7a86e"><path d="M7 10l5 5 5-5z"/></svg>') no-repeat right 12px center;
        background-size: 20px;
        padding-right: 40px;
        cursor: pointer;
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
        background: linear-gradient(135deg, #fff3cd 0%, #ffe69c 100%);
        border-left: 5px solid #ffc107;
        padding: 14px;
        border-radius: 8px;
        color: #856404;
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 500;
        box-shadow: 0 2px 8px rgba(255, 193, 7, 0.2);
    }

    .delete-warning i {
        font-size: 20px;
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

        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-group.full-width {
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

<?php if ($successMessage): ?>
<div class="alert alert-success">
    <i class="fas fa-check-circle"></i>
    <?= htmlspecialchars($successMessage) ?>
</div>
<?php endif; ?>

<?php if ($errorMessage): ?>
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
  <form id="addBranchForm" method="POST">
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
  <form id="editBranchForm">
    <div class="modal-content">
      <div class="modal-header">
        <h2><i class="fas fa-edit"></i> Sửa chi nhánh</h2>
        <button type="button" class="close-btn edit-close">&times;</button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="editBranchID" name="id">
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
      <button type="button" class="close-btn" onclick="document.getElementById('deleteBranchModal').style.display='none'">&times;</button>
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
// Lấy dữ liệu chi nhánh từ PHP
const branches = <?= json_encode($branches, JSON_UNESCAPED_UNICODE); ?>;

// Cấu hình
const rowsPerPage = 5;
let currentPage = 1;

function displayBranches(page) {
    const tableBody = document.getElementById('branchBody');
    tableBody.innerHTML = '';

    const start = (page - 1) * rowsPerPage;
    const end = start + rowsPerPage;
    const pageData = branches.slice(start, end);

    pageData.forEach(branch => {
        const row = `
            <tr>
                <td>${branch.ID}</td>
                <td>${branch.Name}</td>
                <td>${branch.Address}</td>
                <td>${branch.Phone || ''}</td>
                <td>
                    <span class="status-badge ${branch.Status === 'active' ? 'status-active' : 'status-inactive'}">
                        ${branch.Status === 'active' ? 'Đang hoạt động' : 'Ngưng hoạt động'}
                    </span>
                </td>
                <td>
                    <div class="action-buttons">
                        <button class="btn-action btn-view" title="Xem chi tiết" data-id="${branch.ID}"><i class="fas fa-eye"></i></button>
                        <button class="btn-action btn-edit" title="Sửa" data-id="${branch.ID}"><i class="fas fa-edit"></i></button>
                        <button class="btn-action btn-delete" title="Xóa" data-id="${branch.ID}"><i class="fas fa-trash"></i></button>
                    </div>
                </td>
            </tr>
        `;
        tableBody.insertAdjacentHTML('beforeend', row);
    });

    renderPagination();
}

function renderPagination() {
    const totalPages = Math.ceil(branches.length / rowsPerPage);
    const pagination = document.getElementById('pagination');
    pagination.innerHTML = '';

    for (let i = 1; i <= totalPages; i++) {
        const button = document.createElement('button');
        button.textContent = i;
        button.classList.add('page-btn');
        if (i === currentPage) button.classList.add('active');
        button.addEventListener('click', () => {
            currentPage = i;
            displayBranches(currentPage);
        });
        pagination.appendChild(button);
    }
}

// Hiển thị trang đầu tiên
displayBranches(currentPage);

// Xử lý modal thêm chi nhánh
const modal = document.getElementById('addBranchModal');
const addBranchBtn = document.getElementById('addBranchBtn');
const addBranchForm = document.getElementById('addBranchForm');
const closeBtn = document.querySelector('#addBranchModal .close');
const cancelBtn = document.getElementById('cancelBtn');

// Mở modal khi nhấn nút "Thêm chi nhánh"
addBranchBtn.addEventListener('click', () => {
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
});

// Đóng modal khi nhấn nút "x"
closeBtn.addEventListener('click', () => {
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
    addBranchForm.reset();
});

// Đóng modal khi nhấn nút "Hủy"
cancelBtn.addEventListener('click', () => {
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
    addBranchForm.reset();
});

// Đóng modal khi nhấn ra ngoài modal
window.addEventListener('click', (event) => {
    if (event.target === modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
        addBranchForm.reset();
    }
});

// Đóng modal khi nhấn ESC
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        if (modal.style.display === 'block') {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
            addBranchForm.reset();
        }
        if (viewModal.style.display === 'block') {
            viewModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        if (editModal.style.display === 'block') {
            editModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        if (deleteModal.style.display === 'block') {
            deleteModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    }
});

addBranchForm.addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(addBranchForm);
    const phone = formData.get('phone');

    if (!/^[0-9]{10,11}$/.test(phone)) {
        alert('Số điện thoại không hợp lệ! Vui lòng nhập 10-11 chữ số.');
        return;
    }

    fetch('store', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
            if (data.success) {
                modal.style.display = 'none';
                addBranchForm.reset();
                window.location.href = 'branch'; // reload trang
            }
        })
        .catch(err => {
            console.error(err);
            alert('Có lỗi xảy ra!');
        });
});

// ---- Modal Xem Chi Tiết ----
const viewModal = document.getElementById("viewBranchModal");
const viewClose = document.querySelector(".view-close");
const viewCancel = document.querySelector(".view-cancel");

// Gán sự kiện nút xem
document.addEventListener('click', function(e) {
    if (e.target.closest('.btn-view')) {
        const id = e.target.closest('.btn-view').dataset.id;
        const branch = branches.find(b => b.ID == id);

        if (branch) {
            // Đổ dữ liệu vào modal
            document.getElementById('view_id').textContent = branch.ID;
            document.getElementById('view_name').textContent = branch.Name;
            document.getElementById('view_address').textContent = branch.Address;
            document.getElementById('view_phone').textContent = branch.Phone || 'Chưa có';
            
            const statusSpan = document.getElementById('view_status');
            if (branch.Status === 'active') {
                statusSpan.innerHTML = '<span class="status-badge status-active">Đang hoạt động</span>';
            } else {
                statusSpan.innerHTML = '<span class="status-badge status-inactive">Ngưng hoạt động</span>';
            }

            viewModal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }
    }
});

// Đóng modal xem
if (viewClose) {
    viewClose.addEventListener('click', () => {
        viewModal.style.display = 'none';
        document.body.style.overflow = 'auto';
    });
}
if (viewCancel) {
    viewCancel.addEventListener('click', () => {
        viewModal.style.display = 'none';
        document.body.style.overflow = 'auto';
    });
}
window.addEventListener('click', e => { 
    if (e.target === viewModal) {
        viewModal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
});

// ---- Modal Sửa ----
const editModal = document.getElementById("editBranchModal");
const editClose = document.querySelector(".edit-close");
const editCancel = document.querySelector(".edit-cancel");
const editForm = document.getElementById("editBranchForm");

// Gán sự kiện nút sửa
document.addEventListener('click', function(e) {
    if (e.target.closest('.btn-edit')) {
        const id = e.target.closest('.btn-edit').dataset.id;
        const branch = branches.find(b => b.ID == id);

        // Đổ dữ liệu vào form
        document.getElementById('editBranchID').value = branch.ID;
        document.getElementById('editBranchName').value = branch.Name;
        document.getElementById('editBranchAddress').value = branch.Address;
        document.getElementById('editBranchPhone').value = branch.Phone;
        document.getElementById('editBranchStatus').value = branch.Status;

        editModal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
});

// Đóng modal edit
editClose.addEventListener('click', () => {
    editModal.style.display = 'none';
    document.body.style.overflow = 'auto';
});
editCancel.addEventListener('click', () => {
    editModal.style.display = 'none';
    document.body.style.overflow = 'auto';
});
window.addEventListener('click', e => { 
    if (e.target === editModal) {
        editModal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
});

// Submit edit form (fetch)
editForm.addEventListener('submit', function(e) {
    e.preventDefault();

    let formData = new FormData(editForm);

    fetch('update', {  // route: admin/branch/update
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        alert(data.message);
        if (data.success) {
            window.location.reload();
        }
    });
});

// ---- Modal Xóa ----
const deleteModal = document.getElementById("deleteBranchModal");
const cancelDeleteBtn = document.getElementById("cancelDeleteBtn");
const confirmDeleteBtn = document.getElementById("confirmDeleteBtn");
const deleteCloseBtn = document.querySelector('#deleteBranchModal .close-btn');

let deleteID = null;

// Mở modal xóa
document.addEventListener('click', function(e) {
    if (e.target.closest('.btn-delete')) {
        deleteID = e.target.closest('.btn-delete').dataset.id;
        deleteModal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
});

// Đóng modal xóa
if (deleteCloseBtn) {
    deleteCloseBtn.addEventListener('click', () => {
        deleteModal.style.display = 'none';
        document.body.style.overflow = 'auto';
        deleteID = null;
    });
}
cancelDeleteBtn.addEventListener('click', () => {
    deleteModal.style.display = 'none';
    document.body.style.overflow = 'auto';
    deleteID = null;
});

// Xác nhận xóa
confirmDeleteBtn.addEventListener('click', () => {
    let formData = new FormData();
    formData.append("id", deleteID);

    fetch("delete", {
        method: "POST",
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        alert(data.message);
        if (data.success) {
            window.location.reload();
        }
    });
});

// Click ra ngoài modal
window.addEventListener('click', e => {
    if (e.target === deleteModal) {
        deleteModal.style.display = 'none';
        document.body.style.overflow = 'auto';
        deleteID = null;
    }
});


</script>
