<div class="content-header">
          <button class="btn btn-primary">
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
                        <button class="btn-action btn-view" title="Xem chi tiết" data-id="<?= $branch['id'] ?>">
                          <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn-action btn-edit" title="Sửa" data-id="<?= $branch['id'] ?>">
                          <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-action btn-delete" title="Xóa" data-id="<?= $branch['id'] ?>">
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
        if (i === currentPage) button.style.backgroundColor = '#b87333';
        button.addEventListener('click', () => {
            currentPage = i;
            displayBranches(currentPage);
        });
        pagination.appendChild(button);
    }
}

// Hiển thị trang đầu tiên
displayBranches(currentPage);
</script>

        
