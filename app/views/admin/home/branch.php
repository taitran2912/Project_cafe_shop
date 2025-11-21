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

              <?php if (!empty($data['branches'])): ?>
                <?php foreach ($data['branches'] as $branch): ?>
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
    <form id="addBranchForm">
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
        <input type="tel" id="branchPhone" name="phone" required placeholder="Nhập số điện thoại (10-11 số)">
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

<style>
  /* Căn giữa và tạo style cho pagination */
  #pagination {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin-top: 20px;
  }

  #pagination .page-btn {
    border: none;
    background-color: #eee;
    padding: 6px 12px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.2s ease;
  }

  #pagination .page-btn:hover {
    background-color: #d4a373;
    color: #fff;
  }

  #pagination .page-btn.active {
    background-color: #b87333;
    color: #fff;
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
    background-color: rgba(0,0,0,0.5);
    animation: fadeIn 0.3s;
  }

  @keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
  }

  .modal-content {
    background-color: #fefefe;
    margin: 5% auto;
    padding: 0;
    border-radius: 8px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    animation: slideDown 0.3s;
  }

  @keyframes slideDown {
    from {
      transform: translateY(-50px);
      opacity: 0;
    }
    to {
      transform: translateY(0);
      opacity: 1;
    }
  }

  .modal-header {
    padding: 20px;
    background-color: #b87333;
    color: white;
    border-radius: 8px 8px 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .modal-header h2 {
    margin: 0;
    font-size: 20px;
  }

  .close {
    color: white;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    transition: color 0.3s;
  }

  .close:hover,
  .close:focus {
    color: #f1f1f1;
  }

  #addBranchForm {
    padding: 20px;
  }

  .form-group {
    margin-bottom: 15px;
  }

  .form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
    color: #333;
  }

  .required {
    color: red;
  }

  .form-group input,
  .form-group textarea,
  .form-group select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    transition: border-color 0.3s;
    box-sizing: border-box;
  }

  .form-group input:focus,
  .form-group textarea:focus,
  .form-group select:focus {
    outline: none;
    border-color: #b87333;
  }

  .form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 20px;
  }

  .btn {
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s;
  }

  .btn-primary {
    background-color: #b87333;
    color: white;
  }

  .btn-primary:hover {
    background-color: #a0621c;
  }

  .btn-secondary {
    background-color: #6c757d;
    color: white;
  }

  .btn-secondary:hover {
    background-color: #5a6268;
  }
</style>

<script>
// Lấy dữ liệu chi nhánh từ PHP
const branches = <?= json_encode($data['branches'], JSON_UNESCAPED_UNICODE); ?>;

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
                        <button class="btn-action btn-view" title="Xem chi tiết"><i class="fas fa-eye"></i></button>
                        <button class="btn-action btn-edit" title="Sửa"><i class="fas fa-edit"></i></button>
                        <button class="btn-action btn-delete" title="Xóa"><i class="fas fa-trash"></i></button>
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

// 🎯 Xử lý nút "Xem chi tiết"
// document.addEventListener('click', function(e) {
//     if (e.target.closest('.btn-view')) {
//         const btn = e.target.closest('.btn-view');
//         const id = btn.closest('tr').querySelector('td').textContent.trim(); // Lấy ID từ cột đầu tiên
//         window.location.href = `branch_detail/${id}`;
//     }
// });

// Modal functionality
const modal = document.getElementById('addBranchModal');
const addBranchBtn = document.getElementById('addBranchBtn');
const closeBtn = document.querySelector('.close');
const cancelBtn = document.getElementById('cancelBtn');
const addBranchForm = document.getElementById('addBranchForm');

// Open modal
addBranchBtn.addEventListener('click', function() {
    modal.style.display = 'block';
});

// Close modal
closeBtn.addEventListener('click', function() {
    modal.style.display = 'none';
    addBranchForm.reset();
});

cancelBtn.addEventListener('click', function() {
    modal.style.display = 'none';
    addBranchForm.reset();
});

// Close modal when clicking outside
window.addEventListener('click', function(event) {
    if (event.target == modal) {
        modal.style.display = 'none';
        addBranchForm.reset();
    }
});

// Handle form submission
addBranchForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(addBranchForm);
    const phone = formData.get('phone');
    
    // Validate phone number
    if (!/^[0-9]{10,11}$/.test(phone)) {
        alert('Số điện thoại không hợp lệ! Vui lòng nhập 10-11 chữ số.');
        return;
    }
    
    // Send AJAX request
    fetch('<?= BASE_URL ?>branch/add', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            modal.style.display = 'none';
            addBranchForm.reset();
            // Reload page to show new branch
            window.location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi thêm chi nhánh!');
    });
});
</script>
