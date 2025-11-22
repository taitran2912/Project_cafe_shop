<?php 
// Form submissions are handled in index.php BEFORE HTML output

// Initialize messages
$successMessage = '';
$errorMessage = '';

// Handle success messages from redirect
if (isset($_GET['success'])) {
    switch ($_GET['success']) {
        case 'add':
            $successMessage = 'Thêm nguyên liệu thành công!';
            break;
        case 'edit':
            $successMessage = 'Cập nhật nguyên liệu thành công!';
            break;
        case 'delete':
            $successMessage = 'Xóa nguyên liệu thành công!';
            break;
    }
}

// Get inventory data from database
$inventoryModel = new Inventory();
$items = $inventoryModel->getAllInventory();
$materials = $inventoryModel->getAllMaterials();
$branches = $inventoryModel->getAllBranches();
$stats = $inventoryModel->getInventoryStats();

// Define minimum level threshold for status calculation
$minLevelThreshold = 50;

function getStatusClass($quantity, $minLevel = 50) {
    if ($quantity == 0) return 'status-out';
    if ($quantity < $minLevel) return 'status-low';
    return 'status-good';
}

function getStatusText($quantity, $minLevel = 50) {
    if ($quantity == 0) return 'Đã hết';
    if ($quantity < $minLevel) return 'Sắp hết';
    return 'Đủ hàng';
}
?>

<?php if ($successMessage): ?>
<div class="alert alert-success" style="margin-bottom: 20px; padding: 12px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 4px;">
    <?= htmlspecialchars($successMessage) ?>
</div>
<?php endif; ?>

<?php if ($errorMessage): ?>
<div class="alert alert-danger" style="margin-bottom: 20px; padding: 12px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 4px;">
    <?= htmlspecialchars($errorMessage) ?>
</div>
<?php endif; ?>

<!-- Stats Cards -->
<div class="stats-container">
    <div class="stat-card">
        <div class="stat-label">Tổng nguyên liệu</div>
        <div class="stat-value"><?= $stats['total'] ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Đủ hàng</div>
        <div class="stat-value" style="color: #2e7d32;">
            <?= $stats['good'] ?>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Sắp hết</div>
        <div class="stat-value" style="color: #f57f17;">
            <?= $stats['low'] ?>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Đã hết</div>
        <div class="stat-value" style="color: #c33;">
            <?= $stats['out_of_stock'] ?>
        </div>
    </div>
</div>

<!-- Toolbar -->
<div class="toolbar">
    <button class="btn-add" id="addItemBtn">
        <i class="fas fa-plus"></i>
        Thêm nguyên liệu
    </button>
    <input type="text" class="search-box" placeholder="Tìm kiếm nguyên liệu..." id="searchInput">
</div>

<!-- Table -->
<div class="table-container">
    <table id="inventoryTable">
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên nguyên liệu</th>
                <th>Chi nhánh</th>
                <th>Đơn vị</th>
                <th>Số lượng tồn</th>
                <th>Mức tối thiểu</th>
                <th>Tình trạng</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            <?php foreach ($items as $index => $item): ?>
            <tr>
                <td><?= $index + 1 ?></td>
                <td><?= htmlspecialchars($item['MaterialName']) ?></td>
                <td><?= htmlspecialchars($item['BranchName']) ?></td>
                <td><?= htmlspecialchars($item['Unit']) ?></td>
                <td><strong><?= $item['Quantity'] ?></strong></td>
                <td><?= $minLevelThreshold ?></td>
                <td>
                    <span class="status-badge <?= getStatusClass($item['Quantity'], $minLevelThreshold) ?>">
                        <?= getStatusText($item['Quantity'], $minLevelThreshold) ?>
                    </span>
                </td>
                <td>
                    <div class="action-buttons">
                        <button class="btn-action btn-view" title="Xem chi tiết"
                                data-id="<?= $item['ID'] ?>"
                                data-materialname="<?= htmlspecialchars($item['MaterialName']) ?>"
                                data-branchname="<?= htmlspecialchars($item['BranchName']) ?>"
                                data-branchid="<?= $item['BranchID'] ?>"
                                data-unit="<?= htmlspecialchars($item['Unit']) ?>"
                                data-quantity="<?= $item['Quantity'] ?>">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn-action btn-edit" title="Sửa"
                                data-id="<?= $item['ID'] ?>"
                                data-materialid="<?= $item['ID_Material'] ?>"
                                data-materialname="<?= htmlspecialchars($item['MaterialName']) ?>"
                                data-branchname="<?= htmlspecialchars($item['BranchName']) ?>"
                                data-branchid="<?= $item['BranchID'] ?>"
                                data-unit="<?= htmlspecialchars($item['Unit']) ?>"
                                data-quantity="<?= $item['Quantity'] ?>">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-action btn-delete" title="Xóa"
                                data-id="<?= $item['ID'] ?>"
                                data-materialname="<?= htmlspecialchars($item['MaterialName']) ?>">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div id="pagination"></div>

<!-- Modal Add -->
<div id="addItemModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Thêm nguyên liệu vào kho</h2>
            <span class="close">&times;</span>
        </div>
        <form id="addItemForm" method="POST">
            <input type="hidden" name="action" value="add_item">
            <div class="form-group">
                <label for="itemMaterial">Nguyên liệu <span class="required">*</span></label>
                <select id="itemMaterial" name="id_material" required>
                    <option value="">-- Chọn nguyên liệu --</option>
                    <?php foreach ($materials as $material): ?>
                        <option value="<?= $material['ID'] ?>"><?= htmlspecialchars($material['Name']) ?> (<?= htmlspecialchars($material['Unit']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="itemBranch">Chi nhánh <span class="required">*</span></label>
                <select id="itemBranch" name="id_branch" required>
                    <option value="">-- Chọn chi nhánh --</option>
                    <?php foreach ($branches as $branch): ?>
                        <option value="<?= $branch['ID'] ?>"><?= htmlspecialchars($branch['Name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="itemQuantity">Số lượng tồn <span class="required">*</span></label>
                <input type="number" id="itemQuantity" name="quantity" required placeholder="0" min="0">
            </div>
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" id="cancelBtn">Hủy</button>
                <button type="submit" class="btn btn-primary">Lưu</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div id="editItemModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Chỉnh sửa kho nguyên liệu</h2>
            <span class="close" data-modal="edit">&times;</span>
        </div>
        <form id="editItemForm" method="POST">
            <input type="hidden" name="action" value="edit_item">
            <input type="hidden" id="editItemId" name="item_id">
            <div class="form-group">
                <label for="editItemMaterial">Nguyên liệu <span class="required">*</span></label>
                <select id="editItemMaterial" name="id_material" required>
                    <option value="">-- Chọn nguyên liệu --</option>
                    <?php foreach ($materials as $material): ?>
                        <option value="<?= $material['ID'] ?>"><?= htmlspecialchars($material['Name']) ?> (<?= htmlspecialchars($material['Unit']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="editItemBranch">Chi nhánh <span class="required">*</span></label>
                <select id="editItemBranch" name="id_branch" required>
                    <option value="">-- Chọn chi nhánh --</option>
                    <?php foreach ($branches as $branch): ?>
                        <option value="<?= $branch['ID'] ?>"><?= htmlspecialchars($branch['Name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="editItemQuantity">Số lượng tồn <span class="required">*</span></label>
                <input type="number" id="editItemQuantity" name="quantity" required placeholder="0" min="0">
            </div>
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" id="cancelEditBtn">Hủy</button>
                <button type="submit" class="btn btn-primary">Cập nhật</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal View -->
<div id="viewItemModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Chi tiết kho nguyên liệu</h2>
            <span class="close" data-modal="view">&times;</span>
        </div>
        <div style="padding: 20px;">
            <div class="detail-row">
                <strong>ID:</strong>
                <span id="viewItemId"></span>
            </div>
            <div class="detail-row">
                <strong>Tên nguyên liệu:</strong>
                <span id="viewItemMaterialName"></span>
            </div>
            <div class="detail-row">
                <strong>Chi nhánh:</strong>
                <span id="viewItemBranchName"></span>
            </div>
            <div class="detail-row">
                <strong>Đơn vị tính:</strong>
                <span id="viewItemUnit"></span>
            </div>
            <div class="detail-row">
                <strong>Số lượng tồn:</strong>
                <span id="viewItemQuantity"></span>
            </div>
            <div class="detail-row">
                <strong>Mức tối thiểu:</strong>
                <span id="viewItemMinLevel">50</span>
            </div>
            <div class="detail-row">
                <strong>Tình trạng:</strong>
                <span id="viewItemStatus"></span>
            </div>
            <div class="form-actions" style="margin-top: 20px;">
                <button type="button" class="btn btn-secondary" id="closeViewBtn">Đóng</button>
            </div>
        </div>
    </div>
</div>

<style>
  /* ========== STATS CARDS ========== */
  .stats-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
  }

  .stat-card {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    padding: 24px;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    border: 2px solid #f0f0f0;
  }

  .stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
  }

  .stat-label {
    color: #6c757d;
    font-size: 13px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 12px;
  }

  .stat-value {
    font-size: 32px;
    font-weight: 700;
    color: #2d3748;
    line-height: 1;
  }

  /* ========== TOOLBAR ========== */
  .toolbar {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 24px;
    flex-wrap: wrap;
  }

  .btn-add {
    background: linear-gradient(135deg, #d4a574 0%, #b87333 100%);
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 10px;
    cursor: pointer;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 4px 12px rgba(184, 115, 51, 0.3);
  }

  .btn-add:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(184, 115, 51, 0.4);
  }

  .search-box {
    flex: 1;
    min-width: 250px;
    padding: 12px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 14px;
    transition: all 0.3s ease;
    background: #f8fafc;
  }

  .search-box:focus {
    outline: none;
    border-color: #d4a574;
    background: white;
    box-shadow: 0 0 0 3px rgba(212, 165, 116, 0.1);
  }

  /* ========== FILTER BUTTONS ========== */
  .filter-buttons {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
  }

  .filter-btn {
    padding: 10px 18px;
    border: 2px solid #e2e8f0;
    background: white;
    border-radius: 10px;
    cursor: pointer;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.3s ease;
    color: #4a5568;
  }

  .filter-btn:hover {
    border-color: #d4a574;
    color: #d4a574;
    transform: translateY(-1px);
  }

  .filter-btn.active {
    background: linear-gradient(135deg, #d4a574 0%, #b87333 100%);
    border-color: #b87333;
    color: white;
  }

  /* ========== TABLE ========== */
  .table-container {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  }

  table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
  }

  thead {
    background: linear-gradient(135deg, #5c4033 0%, #4a3329 100%);
    color: white;
  }

  th {
    padding: 16px;
    text-align: left;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 0.5px;
  }

  td {
    padding: 14px 16px;
    border-bottom: 1px solid #f0f0f0;
  }

  tbody tr {
    transition: all 0.2s ease;
  }

  tbody tr:hover {
    background: #f8fafc;
  }

  /* ========== STATUS BADGES ========== */
  .status-badge {
    display: inline-block;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.3px;
  }

  .status-out {
    background: linear-gradient(135deg, #fee 0%, #fdd 100%);
    color: #c33;
    border: 1px solid #fcc;
  }

  .status-low {
    background: linear-gradient(135deg, #fff3e0 0%, #ffe5b4 100%);
    color: #f57f17;
    border: 1px solid #ffd54f;
  }

  .status-good {
    background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
    color: #2e7d32;
    border: 1px solid #a5d6a7;
  }

  /* ========== ACTION BUTTONS ========== */
  .action-buttons {
    display: flex;
    gap: 8px;
  }

  .btn-action {
    width: 36px;
    height: 36px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    transition: all 0.3s ease;
    color: white;
  }

  .btn-view {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
  }

  .btn-view:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(23, 162, 184, 0.3);
  }

  .btn-edit {
    background: linear-gradient(135deg, #1e88e5 0%, #1565c0 100%);
  }

  .btn-edit:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(30, 136, 229, 0.3);
  }

  .btn-delete {
    background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
  }

  .btn-delete:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
  }

  /* ========== PAGINATION ========== */
  #pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
    margin-top: 24px;
  }

  #pagination .page-btn {
    min-width: 40px;
    height: 40px;
    border: 2px solid #e0e0e0;
    background: white;
    color: #666;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  }

  #pagination .page-btn:hover:not(.active) {
    background: linear-gradient(135deg, #d4a373 0%, #b87333 100%);
    color: white;
    border-color: #b87333;
    transform: translateY(-2px);
  }

  #pagination .page-btn.active {
    background: linear-gradient(135deg, #b87333 0%, #a0621c 100%);
    color: white;
    border-color: #a0621c;
    transform: scale(1.05);
  }

  /* ========== MODAL ========== */
  .modal {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(4px);
    animation: fadeIn 0.3s ease;
  }

  @keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
  }

  .modal-content {
    background: white;
    margin: 3% auto;
    padding: 0;
    border-radius: 16px;
    width: 90%;
    max-width: 550px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: slideDown 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
  }

  @keyframes slideDown {
    from {
      transform: translateY(-100px) scale(0.9);
      opacity: 0;
    }
    to {
      transform: translateY(0) scale(1);
      opacity: 1;
    }
  }

  .modal-header {
    padding: 24px 28px;
    background: linear-gradient(135deg, #5c4033 0%, #4a3329 100%);
    color: white;
    border-radius: 16px 16px 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .modal-header h2 {
    margin: 0;
    font-size: 22px;
    font-weight: 700;
  }

  .close {
    color: white;
    font-size: 32px;
    font-weight: 300;
    cursor: pointer;
    transition: all 0.3s ease;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
  }

  .close:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: rotate(90deg);
  }

  /* ========== FORM ========== */
  form {
    padding: 28px;
  }

  .form-group {
    margin-bottom: 20px;
  }

  .form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #2d3748;
    font-size: 14px;
  }

  .required {
    color: #e53e3e;
  }

  .form-group input,
  .form-group select {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 14px;
    transition: all 0.3s ease;
    box-sizing: border-box;
    background: #f8fafc;
    cursor: pointer;
    pointer-events: auto;
  }

  .form-group input {
    cursor: text;
  }

  .form-group input:focus,
  .form-group select:focus {
    outline: none;
    border-color: #d4a574;
    background: white;
    box-shadow: 0 0 0 3px rgba(212, 165, 116, 0.1);
  }

  .form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 28px;
    padding-top: 20px;
    border-top: 2px solid #f0f0f0;
  }

  .btn {
    padding: 12px 28px;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.3s ease;
  }

  .btn-primary {
    background: linear-gradient(135deg, #d4a574 0%, #b87333 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(212, 165, 116, 0.3);
  }

  .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(212, 165, 116, 0.4);
  }

  .btn-secondary {
    background: #e2e8f0;
    color: #4a5568;
  }

  .btn-secondary:hover {
    background: #cbd5e0;
    transform: translateY(-2px);
  }

  /* ========== DETAIL VIEW ========== */
  .detail-row {
    padding: 16px 0;
    border-bottom: 2px solid #f0f0f0;
    display: flex;
    gap: 16px;
  }
  
  .detail-row:last-child {
    border-bottom: none;
  }
  
  .detail-row strong {
    min-width: 150px;
    color: #2d3748;
    font-weight: 600;
  }
  
  .detail-row span {
    color: #4a5568;
    flex: 1;
  }

  /* ========== RESPONSIVE ========== */
  @media (max-width: 768px) {
    .stats-container {
      grid-template-columns: repeat(2, 1fr);
    }

    .toolbar {
      flex-direction: column;
      align-items: stretch;
    }

    .search-box {
      min-width: 100%;
    }

    .modal-content {
      width: 95%;
      margin: 10% auto;
    }
  }
</style>

<script>
// Lấy dữ liệu từ PHP
const items = <?= json_encode($items, JSON_UNESCAPED_UNICODE); ?>;

// Cấu hình
const rowsPerPage = 10;
let currentPage = 1;

function getStatus(quantity, minLevel) {
    if (quantity === 0) return 'out';
    if (quantity < minLevel) return 'low';
    return 'good';
}

function getStatusBadge(quantity, minLevel) {
    const status = getStatus(quantity, minLevel);
    const classes = {
        'out': 'status-out',
        'low': 'status-low',
        'good': 'status-good'
    };
    const texts = {
        'out': 'Đã hết',
        'low': 'Sắp hết',
        'good': 'Đủ hàng'
    };
    return `<span class="status-badge ${classes[status]}">${texts[status]}</span>`;
}

function displayTable(page = 1) {
    const tableBody = document.getElementById('tableBody');
    tableBody.innerHTML = '';

    const start = (page - 1) * rowsPerPage;
    const end = start + rowsPerPage;
    const pageData = items.slice(start, end);

    pageData.forEach((item, index) => {
        const minLevel = 50;
        const row = `
            <tr>
                <td>${start + index + 1}</td>
                <td>${item.MaterialName}</td>
                <td>${item.BranchName}</td>
                <td>${item.Unit}</td>
                <td><strong>${item.Quantity}</strong></td>
                <td>${minLevel}</td>
                <td>${getStatusBadge(item.Quantity, minLevel)}</td>
                <td>
                    <div class="action-buttons">
                        <button class="btn-action btn-view" 
                                data-id="${item.ID}"
                                data-materialname="${item.MaterialName}"
                                data-branchname="${item.BranchName}"
                                data-branchid="${item.BranchID}"
                                data-unit="${item.Unit}"
                                data-quantity="${item.Quantity}">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn-action btn-edit"
                                data-id="${item.ID}"
                                data-materialid="${item.ID_Material}"
                                data-materialname="${item.MaterialName}"
                                data-branchname="${item.BranchName}"
                                data-branchid="${item.BranchID}"
                                data-unit="${item.Unit}"
                                data-quantity="${item.Quantity}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-action btn-delete"
                                data-id="${item.ID}"
                                data-materialname="${item.MaterialName}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
        tableBody.insertAdjacentHTML('beforeend', row);
    });

    renderPagination();
}

function renderPagination() {
    const totalPages = Math.ceil(items.length / rowsPerPage);
    const pagination = document.getElementById('pagination');
    pagination.innerHTML = '';

    for (let i = 1; i <= totalPages; i++) {
        const button = document.createElement('button');
        button.textContent = i;
        button.classList.add('page-btn');
        if (i === currentPage) button.classList.add('active');
        button.addEventListener('click', () => {
            currentPage = i;
            displayTable(currentPage);
        });
        pagination.appendChild(button);
    }
}

// ========== MODALS ==========
const addModal = document.getElementById('addItemModal');
const editModal = document.getElementById('editItemModal');
const viewModal = document.getElementById('viewItemModal');
const addItemBtn = document.getElementById('addItemBtn');
const cancelBtn = document.getElementById('cancelBtn');
const cancelEditBtn = document.getElementById('cancelEditBtn');
const closeViewBtn = document.getElementById('closeViewBtn');

addItemBtn.addEventListener('click', () => {
    addModal.style.display = 'block';
});

cancelBtn.addEventListener('click', () => {
    addModal.style.display = 'none';
    document.getElementById('addItemForm').reset();
});

cancelEditBtn.addEventListener('click', () => {
    editModal.style.display = 'none';
    document.getElementById('editItemForm').reset();
});

closeViewBtn.addEventListener('click', () => {
    viewModal.style.display = 'none';
});

// ========== BUTTON HANDLERS ==========
document.addEventListener('click', function(e) {
    // View
    if (e.target.closest('.btn-view')) {
        const btn = e.target.closest('.btn-view');
        document.getElementById('viewItemId').textContent = btn.dataset.id;
        document.getElementById('viewItemMaterialName').textContent = btn.dataset.materialname;
        document.getElementById('viewItemBranchName').textContent = btn.dataset.branchname;
        document.getElementById('viewItemUnit').textContent = btn.dataset.unit;
        document.getElementById('viewItemQuantity').textContent = btn.dataset.quantity;
        
        const quantity = parseInt(btn.dataset.quantity);
        const minLevel = 50;
        document.getElementById('viewItemStatus').innerHTML = getStatusBadge(quantity, minLevel);
        
        viewModal.style.display = 'block';
    }
    
    // Edit
    if (e.target.closest('.btn-edit')) {
        const btn = e.target.closest('.btn-edit');
        document.getElementById('editItemId').value = btn.dataset.id;
        document.getElementById('editItemMaterial').value = btn.dataset.materialid;
        document.getElementById('editItemBranch').value = btn.dataset.branchid;
        document.getElementById('editItemQuantity').value = btn.dataset.quantity;
        editModal.style.display = 'block';
    }
    
    // Delete
    if (e.target.closest('.btn-delete')) {
        const btn = e.target.closest('.btn-delete');
        const id = btn.dataset.id;
        const name = btn.dataset.materialname;
        if (confirm(`Bạn có chắc chắn muốn xóa kho "${name}"?`)) {
            window.location.href = `/Project_cafe_shop/admin/inventory?action=delete&id=${id}`;
        }
    }
});

// ========== CLOSE MODALS ==========
document.querySelectorAll('.close').forEach(closeBtn => {
    closeBtn.addEventListener('click', function() {
        const modalType = this.dataset.modal;
        if (modalType === 'edit') {
            editModal.style.display = 'none';
        } else if (modalType === 'view') {
            viewModal.style.display = 'none';
        } else {
            addModal.style.display = 'none';
        }
    });
});

window.addEventListener('click', function(event) {
    if (event.target == addModal) {
        addModal.style.display = 'none';
    }
    if (event.target == editModal) {
        editModal.style.display = 'none';
    }
    if (event.target == viewModal) {
        viewModal.style.display = 'none';
    }
});

// ========== SEARCH ==========
document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('#tableBody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});

// ========== FILTER ==========
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        const filter = this.dataset.filter;
        const rows = document.querySelectorAll('#tableBody tr');
        
        rows.forEach(row => {
            if (filter === 'all') {
                row.style.display = '';
            } else {
                const statusBadge = row.querySelector('.status-badge');
                const hasClass = statusBadge.classList.contains(`status-${filter}`);
                row.style.display = hasClass ? '' : 'none';
            }
        });
    });
});

// Hiển thị trang đầu tiên
displayTable(currentPage);
</script>
