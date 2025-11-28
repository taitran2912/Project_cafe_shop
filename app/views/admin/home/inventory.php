<!-- Success/Error Messages -->
<?php if (isset($successMessage) && !empty($successMessage)): ?>
    <div class="alert alert-success">
    <i class="fas fa-check-circle"></i>
    <?= htmlspecialchars($successMessage) ?>
</div>
<?php endif; ?>

<?php if (isset($errorMessage) && !empty($errorMessage)): ?>
<div class="alert alert-error">
    <i class="fas fa-exclamation-circle"></i>
    <?= htmlspecialchars($errorMessage) ?>
</div>
<?php endif; ?>

<!-- Stats Cards -->
<div class="stats-container">
    <div class="stat-card">
        <div class="stat-label">Tổng nguyên liệu</div>
        <div class="stat-value"><?= isset($stats['total']) ? $stats['total'] : 0 ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Đủ hàng</div>
        <div class="stat-value" style="color: #2e7d32;">
            <?= isset($stats['good']) ? $stats['good'] : 0 ?>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Sắp hết</div>
        <div class="stat-value" style="color: #f57f17;">
            <?= isset($stats['low']) ? $stats['low'] : 0 ?>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Đã hết</div>
        <div class="stat-value" style="color: #c33;">
            <?= isset($stats['out_of_stock']) ? $stats['out_of_stock'] : 0 ?>
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
            <!-- Items will be rendered by JavaScript -->
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
                    <?php if (isset($materials)): ?>
                        <?php foreach ($materials as $material): ?>
                            <option value="<?= $material['ID'] ?>"><?= htmlspecialchars($material['Name']) ?> (<?= htmlspecialchars($material['Unit']) ?>)</option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="itemBranch">Chi nhánh <span class="required">*</span></label>
                <select id="itemBranch" name="id_branch" required>
                    <option value="">-- Chọn chi nhánh --</option>
                    <?php if (isset($branches)): ?>
                        <?php foreach ($branches as $branch): ?>
                            <option value="<?= $branch['ID'] ?>"><?= htmlspecialchars($branch['Name']) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
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
                    <?php if (isset($materials)): ?>
                        <?php foreach ($materials as $material): ?>
                            <option value="<?= $material['ID'] ?>"><?= htmlspecialchars($material['Name']) ?> (<?= htmlspecialchars($material['Unit']) ?>)</option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="editItemBranch">Chi nhánh <span class="required">*</span></label>
                <select id="editItemBranch" name="id_branch" required>
                    <option value="">-- Chọn chi nhánh --</option>
                    <?php if (isset($branches)): ?>
                        <?php foreach ($branches as $branch): ?>
                            <option value="<?= $branch['ID'] ?>"><?= htmlspecialchars($branch['Name']) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
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

<!-- Embed inventory data for JavaScript -->
<script>
    // Gán vào window object để đảm bảo scope toàn cục
    window.inventoryDataFromPHP = <?= json_encode(isset($items) ? $items : [], JSON_UNESCAPED_UNICODE) ?>;
    window.BASE_URL = '<?= BASE_URL ?>';
    
    // Debug log
    console.log('Data embedded from PHP:');
    console.log('Items:', window.inventoryDataFromPHP);
    console.log('Items count:', window.inventoryDataFromPHP.length);
    console.log('BASE_URL:', window.BASE_URL);
</script>

<!-- Link to external JavaScript -->
<script src="<?= BASE_URL ?>public/js/admin/inventory.js"></script>

