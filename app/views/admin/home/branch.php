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
  <div class="modal-content">
    <div class="modal-header">
      <h2>Thêm chi nhánh mới</h2>
      <span class="close">&times;</span>
    </div>
    <form id="addBranchForm" method="POST">
      <input type="hidden" name="action" value="add_branch">
      <div class="form-group">
        <label for="branchName">Tên chi nhánh <span class="required">*</span></label>
        <input type="text" id="branchName" name="name" required placeholder="Nhập tên chi nhánh">
      </div>
      <div class="form-group">
        <label for="branchAddress">Địa chỉ <span class="required">*</span></label>
        <textarea id="branchAddress" name="address" required placeholder="Nhập địa chỉ chi nhánh" rows="3"></textarea>
      </div>
      <div class="form-group">
        <label for="branchPhone">Số điện thoại <span class="required">*</span></label>
        <input type="tel" id="branchPhone" name="phone" required placeholder="Nhập số điện thoại (10-11 số)" pattern="[0-9]{10,11}">
      </div>
      <div class="form-group">
        <label for="branchStatus">Trạng thái</label>
        <select id="branchStatus" name="status">
          <option value="active">Đang hoạt động</option>
          <option value="inactive">Ngưng hoạt động</option>
        </select>
      </div>
      <div class="form-actions">
        <button type="button" class="btn btn-secondary" id="cancelBtn">Hủy</button>
        <button type="submit" class="btn btn-primary">Thêm chi nhánh</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Sửa Chi Nhánh -->
<div id="editBranchModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h2>Chỉnh sửa chi nhánh</h2>
      <span class="close" data-modal="edit">&times;</span>
    </div>
    <form id="editBranchForm" method="POST">
      <input type="hidden" name="action" value="edit_branch">
      <input type="hidden" id="editBranchId" name="branch_id">
      <div class="form-group">
        <label for="editBranchName">Tên chi nhánh <span class="required">*</span></label>
        <input type="text" id="editBranchName" name="name" required placeholder="Nhập tên chi nhánh">
      </div>
      <div class="form-group">
        <label for="editBranchAddress">Địa chỉ <span class="required">*</span></label>
        <textarea id="editBranchAddress" name="address" required placeholder="Nhập địa chỉ chi nhánh" rows="3"></textarea>
      </div>
      <div class="form-group">
        <label for="editBranchPhone">Số điện thoại <span class="required">*</span></label>
        <input type="tel" id="editBranchPhone" name="phone" required placeholder="Nhập số điện thoại (10-11 số)" pattern="[0-9]{10,11}">
      </div>
      <div class="form-group">
        <label for="editBranchStatus">Trạng thái</label>
        <select id="editBranchStatus" name="status">
          <option value="active">Đang hoạt động</option>
          <option value="inactive">Ngưng hoạt động</option>
        </select>
      </div>
      <div class="form-actions">
        <button type="button" class="btn btn-secondary" id="cancelEditBtn">Hủy</button>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Xem Chi Tiết -->
<div id="viewBranchModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h2>Chi tiết chi nhánh</h2>
      <span class="close" data-modal="view">&times;</span>
    </div>
    <div style="padding: 20px;">
      <div class="detail-row">
        <strong>ID:</strong>
        <span id="viewBranchId"></span>
      </div>
      <div class="detail-row">
        <strong>Tên chi nhánh:</strong>
        <span id="viewBranchName"></span>
      </div>
      <div class="detail-row">
        <strong>Địa chỉ:</strong>
        <span id="viewBranchAddress"></span>
      </div>
      <div class="detail-row">
        <strong>Số điện thoại:</strong>
        <span id="viewBranchPhone"></span>
      </div>
      <div class="detail-row">
        <strong>Trạng thái:</strong>
        <span id="viewBranchStatus"></span>
      </div>
      <div class="form-actions" style="margin-top: 20px;">
        <button type="button" class="btn btn-secondary" id="closeViewBtn">Đóng</button>
      </div>
    </div>
  </div>
</div>

<style>
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
    display: flex;
    align-items: center;
    justify-content: center;
  }

  #pagination .page-btn:hover:not(.active) {
    background: linear-gradient(135deg, #d4a373 0%, #b87333 100%);
    color: white;
    border-color: #b87333;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(184, 115, 51, 0.3);
  }

  #pagination .page-btn.active {
    background: linear-gradient(135deg, #b87333 0%, #a0621c 100%);
    color: white;
    border-color: #a0621c;
    box-shadow: 0 4px 12px rgba(184, 115, 51, 0.4);
    transform: scale(1.05);
  }

  /* ========== MODAL OVERLAY ========== */
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
    from { 
      opacity: 0;
      backdrop-filter: blur(0);
    }
    to { 
      opacity: 1;
      backdrop-filter: blur(4px);
    }
  }

  /* ========== MODAL CONTENT ========== */
  .modal-content {
    background: white;
    margin: 3% auto;
    padding: 0;
    border-radius: 16px;
    width: 90%;
    max-width: 550px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: slideDown 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    overflow: hidden;
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

  /* ========== MODAL HEADER ========== */
  .modal-header {
    padding: 24px 28px;
    background: linear-gradient(135deg, #b87333 0%, #a0621c 100%);
    color: white;
    border-radius: 16px 16px 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  }

  .modal-header h2 {
    margin: 0;
    font-size: 22px;
    font-weight: 700;
    letter-spacing: -0.5px;
  }

  /* ========== CLOSE BUTTON ========== */
  .close {
    color: white;
    font-size: 32px;
    font-weight: 300;
    cursor: pointer;
    transition: all 0.3s ease;
    line-height: 1;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
  }

  .close:hover,
  .close:focus {
    background: rgba(255, 255, 255, 0.2);
    transform: rotate(90deg);
  }

  /* ========== FORM STYLES ========== */
  #addBranchForm,
  #editBranchForm {
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
    margin-left: 2px;
  }

  .form-group input,
  .form-group textarea,
  .form-group select {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 14px;
    transition: all 0.3s ease;
    box-sizing: border-box;
    font-family: inherit;
    background: #f8fafc;
  }

  .form-group input:focus,
  .form-group textarea:focus,
  .form-group select:focus {
    outline: none;
    border-color: #b87333;
    background: white;
    box-shadow: 0 0 0 3px rgba(184, 115, 51, 0.1);
    transform: translateY(-1px);
  }

  .form-group textarea {
    resize: vertical;
    min-height: 80px;
  }

  /* ========== FORM ACTIONS ========== */
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
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    letter-spacing: 0.3px;
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
    background: rgba(255, 255, 255, 0.3);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
  }

  .btn:active::before {
    width: 300px;
    height: 300px;
  }

  .btn-primary {
    background: linear-gradient(135deg, #b87333 0%, #a0621c 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(184, 115, 51, 0.3);
  }

  .btn-primary:hover {
    background: linear-gradient(135deg, #a0621c 0%, #8b5217 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(184, 115, 51, 0.4);
  }

  .btn-primary:active {
    transform: translateY(0);
  }

  .btn-secondary {
    background: #e2e8f0;
    color: #4a5568;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  }

  .btn-secondary:hover {
    background: #cbd5e0;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  }

  .btn-secondary:active {
    transform: translateY(0);
  }

  /* ========== DETAIL VIEW STYLES ========== */
  .detail-row {
    padding: 16px 0;
    border-bottom: 2px solid #f0f0f0;
    display: flex;
    gap: 16px;
    align-items: flex-start;
  }
  
  .detail-row:last-child {
    border-bottom: none;
  }
  
  .detail-row strong {
    min-width: 140px;
    color: #2d3748;
    font-weight: 600;
    font-size: 14px;
  }
  
  .detail-row span {
    color: #4a5568;
    flex: 1;
    font-size: 14px;
    line-height: 1.6;
  }

  /* ========== ALERT STYLES ========== */
  .alert {
    padding: 16px 20px;
    margin-bottom: 24px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 14px;
    font-weight: 500;
    animation: slideInDown 0.4s ease;
  }

  @keyframes slideInDown {
    from {
      transform: translateY(-20px);
      opacity: 0;
    }
    to {
      transform: translateY(0);
      opacity: 1;
    }
  }

  .alert-success {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    color: #155724;
    border: 2px solid #b1dfbb;
  }

  .alert-danger {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    color: #721c24;
    border: 2px solid #f1b0b7;
  }

  /* ========== RESPONSIVE ========== */
  @media (max-width: 768px) {
    .modal-content {
      width: 95%;
      margin: 10% auto;
    }

    .modal-header {
      padding: 18px 20px;
    }

    .modal-header h2 {
      font-size: 18px;
    }

    #addBranchForm,
    #editBranchForm {
      padding: 20px;
    }

    .form-actions {
      flex-direction: column-reverse;
    }

    .btn {
      width: 100%;
    }

    .detail-row {
      flex-direction: column;
      gap: 8px;
    }

    .detail-row strong {
      min-width: auto;
    }
  }
</style>

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
                        <button class="btn-action btn-view" title="Xem chi tiết" 
                                data-id="${branch.ID}"
                                data-name="${branch.Name}"
                                data-address="${branch.Address}"
                                data-phone="${branch.Phone || ''}"
                                data-status="${branch.Status}">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn-action btn-edit" title="Sửa"
                                data-id="${branch.ID}"
                                data-name="${branch.Name}"
                                data-address="${branch.Address}"
                                data-phone="${branch.Phone || ''}"
                                data-status="${branch.Status}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-action btn-delete" title="Xóa"
                                data-id="${branch.ID}"
                                data-name="${branch.Name}">
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

// ========== ADD MODAL ==========
const addModal = document.getElementById('addBranchModal');
const addBranchBtn = document.getElementById('addBranchBtn');
const addBranchForm = document.getElementById('addBranchForm');
const cancelBtn = document.getElementById('cancelBtn');

addBranchBtn.addEventListener('click', function() {
    addModal.style.display = 'block';
});

cancelBtn.addEventListener('click', function() {
    addModal.style.display = 'none';
    addBranchForm.reset();
});

addBranchForm.addEventListener('submit', function(e) {
    const phone = document.getElementById('branchPhone').value;
    if (!/^[0-9]{10,11}$/.test(phone)) {
        e.preventDefault();
        alert('Số điện thoại không hợp lệ! Vui lòng nhập 10-11 chữ số.');
        return false;
    }
    return true;
});

// ========== EDIT MODAL ==========
const editModal = document.getElementById('editBranchModal');
const editBranchForm = document.getElementById('editBranchForm');
const cancelEditBtn = document.getElementById('cancelEditBtn');

cancelEditBtn.addEventListener('click', function() {
    editModal.style.display = 'none';
    editBranchForm.reset();
});

editBranchForm.addEventListener('submit', function(e) {
    const phone = document.getElementById('editBranchPhone').value;
    if (!/^[0-9]{10,11}$/.test(phone)) {
        e.preventDefault();
        alert('Số điện thoại không hợp lệ! Vui lòng nhập 10-11 chữ số.');
        return false;
    }
    return true;
});

// ========== VIEW MODAL ==========
const viewModal = document.getElementById('viewBranchModal');
const closeViewBtn = document.getElementById('closeViewBtn');

closeViewBtn.addEventListener('click', function() {
    viewModal.style.display = 'none';
});

// ========== BUTTON HANDLERS ==========
document.addEventListener('click', function(e) {
    // View button
    if (e.target.closest('.btn-view')) {
        const btn = e.target.closest('.btn-view');
        document.getElementById('viewBranchId').textContent = btn.dataset.id;
        document.getElementById('viewBranchName').textContent = btn.dataset.name;
        document.getElementById('viewBranchAddress').textContent = btn.dataset.address;
        document.getElementById('viewBranchPhone').textContent = btn.dataset.phone;
        document.getElementById('viewBranchStatus').innerHTML = 
            `<span class="status-badge ${btn.dataset.status === 'active' ? 'status-active' : 'status-inactive'}">
                ${btn.dataset.status === 'active' ? 'Đang hoạt động' : 'Ngưng hoạt động'}
            </span>`;
        viewModal.style.display = 'block';
    }
    
    // Edit button
    if (e.target.closest('.btn-edit')) {
        const btn = e.target.closest('.btn-edit');
        document.getElementById('editBranchId').value = btn.dataset.id;
        document.getElementById('editBranchName').value = btn.dataset.name;
        document.getElementById('editBranchAddress').value = btn.dataset.address;
        document.getElementById('editBranchPhone').value = btn.dataset.phone;
        document.getElementById('editBranchStatus').value = btn.dataset.status;
        editModal.style.display = 'block';
    }
    
    // Delete button
    if (e.target.closest('.btn-delete')) {
        const btn = e.target.closest('.btn-delete');
        const id = btn.dataset.id;
        const name = btn.dataset.name;
        if (confirm(`Bạn có chắc chắn muốn xóa chi nhánh "${name}"?`)) {
            window.location.href = `/Project_cafe_shop/admin/branch?action=delete&id=${id}`;
        }
    }
});

// ========== CLOSE MODALS ==========
document.querySelectorAll('.close').forEach(closeBtn => {
    closeBtn.addEventListener('click', function() {
        const modalType = this.dataset.modal;
        if (modalType === 'edit') {
            editModal.style.display = 'none';
            editBranchForm.reset();
        } else if (modalType === 'view') {
            viewModal.style.display = 'none';
        } else {
            addModal.style.display = 'none';
            addBranchForm.reset();
        }
    });
});

// Close modal when clicking outside
window.addEventListener('click', function(event) {
    if (event.target == addModal) {
        addModal.style.display = 'none';
        addBranchForm.reset();
    }
    if (event.target == editModal) {
        editModal.style.display = 'none';
        editBranchForm.reset();
    }
    if (event.target == viewModal) {
        viewModal.style.display = 'none';
    }
});
</script>
