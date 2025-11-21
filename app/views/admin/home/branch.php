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
      <h2>Sửa chi nhánh</h2>
      <span class="close edit-close">&times;</span>
    </div>
    <form id="editBranchForm">
      <input type="hidden" id="editBranchID" name="id">

      <div class="form-group">
        <label>Tên chi nhánh *</label>
        <input type="text" id="editBranchName" name="name" required>
      </div>

      <div class="form-group">
        <label>Địa chỉ *</label>
        <textarea id="editBranchAddress" name="address" required rows="3"></textarea>
      </div>

      <div class="form-group">
        <label>Số điện thoại *</label>
        <input type="tel" id="editBranchPhone" name="phone" required>
      </div>

      <div class="form-group">
        <label>Trạng thái</label>
        <select id="editBranchStatus" name="status">
          <option value="active">Đang hoạt động</option>
          <option value="inactive">Ngưng hoạt động</option>
        </select>
      </div>

      <div class="form-actions">
        <button type="button" class="btn btn-secondary edit-cancel">Hủy</button>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
      </div>
    </form>
  </div>
</div>
<!-- Modal Xóa Chi Nhánh -->
<div id="deleteBranchModal" class="modal">
  <div class="modal-content small">
    <h3>Bạn có chắc muốn xóa chi nhánh này không?</h3>
    <p>Hành động này không thể hoàn tác.</p>

    <div class="form-actions">
      <button type="button" class="btn btn-secondary" id="cancelDeleteBtn">Hủy</button>
      <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Xóa</button>
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
                        <!-- <button class="btn-action btn-view" title="Xem chi tiết" data-id="${branch.ID}"><i class="fas fa-eye"></i></button> -->
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
const cancelBtn = document.getElementById('cancelBtn');

// Mở modal khi nhấn nút "Thêm chi nhánh"
addBranchBtn.addEventListener('click', () => {
    modal.style.display = 'block';
});

// Đóng modal khi nhấn nút "x"
closeBtn.addEventListener('click', () => {
    modal.style.display = 'none';
    addBranchForm.reset();
});

// Đóng modal khi nhấn nút "Hủy"
cancelBtn.addEventListener('click', () => {
    modal.style.display = 'none';
    addBranchForm.reset();
});

// Đóng modal khi nhấn ra ngoài modal
window.addEventListener('click', (event) => {
    if (event.target === modal) {
        modal.style.display = 'none';
        addBranchForm.reset();
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
    }
});

// Đóng modal edit
editClose.addEventListener('click', () => editModal.style.display = 'none');
editCancel.addEventListener('click', () => editModal.style.display = 'none');
window.addEventListener('click', e => { if (e.target === editModal) editModal.style.display = 'none'; });

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

let deleteID = null;

// Mở modal xóa
document.addEventListener('click', function(e) {
    if (e.target.closest('.btn-delete')) {
        deleteID = e.target.closest('.btn-delete').dataset.id;
        deleteModal.style.display = 'block';
    }
});

// Đóng modal xóa
cancelDeleteBtn.addEventListener('click', () => {
    deleteModal.style.display = 'none';
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
    if (e.target === deleteModal) deleteModal.style.display = 'none';
});


</script>
