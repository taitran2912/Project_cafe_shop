<?php 
/**
 * Branch Management (Quản lý Chi nhánh)
 * CRUD operations with AJAX search functionality
 */

require_once __DIR__ . '/../../../../controllers/BranchController.php';

$branchController = new BranchController();

// AJAX search endpoint
if (isset($_GET['ajax_search'])) {
    $q = isset($_GET['q']) ? trim($_GET['q']) : '';
    $page = max(1, isset($_GET['page']) ? (int)$_GET['page'] : 1);
    $limit = max(1, isset($_GET['limit']) ? (int)$_GET['limit'] : 5);
    
    $result = $branchController->search($q, $page, $limit);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
    exit;
}

// Handle POST actions before output
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $currentPage = max(1, isset($_GET['page']) ? (int)$_GET['page'] : 1);
    
    switch ($action) {
        case 'add_branch':
            $res = $branchController->store($_POST);
            header('Location: ' . $_SERVER['PHP_SELF'] . '?page=1&added=' . ($res ? '1' : '0'));
            exit;
            
        case 'edit_branch':
            $id = isset($_POST['ID']) ? (int)$_POST['ID'] : 0;
            $res = $id > 0 ? $branchController->update($id, $_POST) : false;
            header('Location: ' . $_SERVER['PHP_SELF'] . '?page=' . $currentPage . '&edited=' . ($res ? '1' : '0'));
            exit;
            
        case 'delete_branch':
            $id = isset($_POST['ID']) ? (int)$_POST['ID'] : 0;
            $res = $id > 0 ? $branchController->delete($id) : false;
            header('Location: ' . $_SERVER['PHP_SELF'] . '?page=' . $currentPage . '&deleted=' . ($res ? '1' : '0'));
            exit;
    }
}

// Pagination parameters
$page = max(1, isset($_GET['page']) ? (int)$_GET['page'] : 1);
$limit = max(1, isset($_GET['limit']) ? (int)$_GET['limit'] : 5);

$pagination = $branchController->paginate($page, $limit);
$branches = $pagination['branches'];
$totalItems = $pagination['totalItems'];
$totalPages = $pagination['totalPages'];
$page = $pagination['currentPage'];
$limit = $pagination['limit'];

// Helper function for pagination URLs
function buildPageUrl($pageNum) {
    $params = $_GET;
    $params['page'] = $pageNum;
    $params['limit'] = $GLOBALS['limit'];
    return htmlspecialchars($_SERVER['PHP_SELF'] . '?' . http_build_query($params));
}
?>



<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Chi nhánh - Cafe Management</title>
    <link rel="stylesheet" href="styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="./qlcn.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

</style>
<body>
    <div class="dashboard">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../../layout/sidebar.php'; ?>

    <!-- Main Content -->
    <main class="main-content">
      <header class="top-bar">
        <button class="btn-menu-toggle">
          <i class="fas fa-bars"></i>
        </button>
        <h1 class="page-title">Quản lý Chi nhánh</h1>
        <div class="top-bar-actions">
          <button class="btn-notification">
            <i class="fas fa-bell"></i>
            <span class="badge">3</span>
          </button>
        </div>
      </header>

      <div class="content-wrapper">
        <div class="content-header">
          
          <button type="button" class="btn btn-primary btn-add" data-bs-toggle="modal" data-bs-target="#addBranchModal">
            <i class="fas fa-plus"></i>
            Thêm chi nhánh
          </button>
          <div class="search-box">
            <i class="fas fa-search"></i>
            <input id="branchSearchInput" type="text" placeholder="Tìm kiếm chi nhánh..." value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
          </div>
        </div>

        <div class="table-container">
          <table class="data-table">
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
            <tbody>
              <?php foreach ($branches as $branch): ?>
              <tr>
                <td><?php echo htmlspecialchars($branch['ID']); ?></td>
                <td><?php echo htmlspecialchars($branch['Name']); ?></td>
                <td><?php echo htmlspecialchars($branch['Address']); ?></td>
                <td><?php echo htmlspecialchars($branch['Phone']); ?></td>
                <td>
                  <span class="status-badge status-active">
                    <?php echo ($branch['Status'] ?? 'active') === 'inactive' ? 'Tạm dừng' : 'Đang hoạt động'; ?>
                  </span>
                </td>
                <td>
                  <div class="action-buttons">
                    <button class="btn-action btn-view" title="Xem chi tiết"
                      data-id="<?php echo htmlspecialchars($branch['ID']); ?>"
                      data-name="<?php echo htmlspecialchars($branch['Name']); ?>"
                      data-address="<?php echo htmlspecialchars($branch['Address']); ?>"
                      data-phone="<?php echo htmlspecialchars($branch['Phone']); ?>"
                      data-status="<?php echo htmlspecialchars($branch['Status'] ?? 'active'); ?>"
                    >
                      <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn-action btn-edit" title="Sửa"
                      data-id="<?php echo htmlspecialchars($branch['ID']); ?>"
                      data-name="<?php echo htmlspecialchars($branch['Name']); ?>"
                      data-address="<?php echo htmlspecialchars($branch['Address']); ?>"
                      data-phone="<?php echo htmlspecialchars($branch['Phone']); ?>"
                      data-status="<?php echo htmlspecialchars($branch['Status'] ?? 'active'); ?>"
                    >
                      <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn-action btn-delete" title="Xóa"
                      data-id="<?php echo htmlspecialchars($branch['ID']); ?>"
                      data-name="<?php echo htmlspecialchars($branch['Name']); ?>"
                    >
                      <i class="fas fa-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <div class="pagination">
          <?php
            // Previous button
            if ($page > 1) {
              echo '<a class="btn-page" href="' . buildPageUrl($page - 1) . '"><i class="fas fa-chevron-left"></i></a>';
            } else {
              echo '<button class="btn-page" disabled><i class="fas fa-chevron-left"></i></button>';
            }

            // Page numbers
            for ($i = 1; $i <= $totalPages; $i++) {
              if ($i == $page) {
                echo '<button class="btn-page active">' . $i . '</button>';
              } else {
                echo '<a class="btn-page" href="' . buildPageUrl($i) . '">' . $i . '</a>';
              }
            }

            // Next button
            if ($page < $totalPages) {
              echo '<a class="btn-page" href="' . buildPageUrl($page + 1) . '"><i class="fas fa-chevron-right"></i></a>';
            } else {
              echo '<button class="btn-page" disabled><i class="fas fa-chevron-right"></i></button>';
            }
          ?>
        </div>
      </div>
    </main>
  </div>

  <!-- Add Branch Modal -->
  <div class="modal fade" id="addBranchModal" tabindex="-1" aria-labelledby="addBranchLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
          <input type="hidden" name="action" value="add_branch">
          <div class="modal-header">
            <h5 class="modal-title" id="addBranchLabel">Thêm chi nhánh</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="branchName" class="form-label">Tên chi nhánh</label>
              <input type="text" class="form-control" id="branchName" name="Name" required>
            </div>
            <div class="mb-3">
              <label for="branchAddress" class="form-label">Địa chỉ</label>
              <input type="text" class="form-control" id="branchAddress" name="Address">
            </div>
            <div class="mb-3">
              <label for="branchPhone" class="form-label">Số điện thoại</label>
              <input type="text" class="form-control" id="branchPhone" name="Phone">
            </div>
            <div class="mb-3">
              <label for="branchStatus" class="form-label">Trạng thái</label>
              <select id="branchStatus" name="Status" class="form-select">
                <option value="active">Đang hoạt động</option>
                <option value="inactive">Tạm dừng</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
            <button type="submit" class="btn btn-primary">Thêm</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- View Branch Modal -->
  <div class="modal fade" id="viewBranchModal" tabindex="-1" aria-labelledby="viewBranchLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewBranchLabel">Chi tiết chi nhánh</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <ul class="list-group list-group-flush">
            <li class="list-group-item"><strong>ID: </strong><span id="viewBranchId"></span></li>
            <li class="list-group-item"><strong>Tên: </strong><span id="viewBranchName"></span></li>
            <li class="list-group-item"><strong>Địa chỉ: </strong><span id="viewBranchAddress"></span></li>
            <li class="list-group-item"><strong>Số điện thoại: </strong><span id="viewBranchPhone"></span></li>
            <li class="list-group-item"><strong>Trạng thái: </strong><span id="viewBranchStatus"></span></li>
          </ul>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit Branch Modal -->
  <div class="modal fade" id="editBranchModal" tabindex="-1" aria-labelledby="editBranchLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
          <input type="hidden" name="action" value="edit_branch">
          <input type="hidden" name="ID" id="editBranchId" value="">
          <div class="modal-header">
            <h5 class="modal-title" id="editBranchLabel">Sửa chi nhánh</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="editBranchName" class="form-label">Tên chi nhánh</label>
              <input type="text" class="form-control" id="editBranchName" name="Name" required>
            </div>
            <div class="mb-3">
              <label for="editBranchAddress" class="form-label">Địa chỉ</label>
              <input type="text" class="form-control" id="editBranchAddress" name="Address">
            </div>
            <div class="mb-3">
              <label for="editBranchPhone" class="form-label">Số điện thoại</label>
              <input type="text" class="form-control" id="editBranchPhone" name="Phone">
            </div>
            <div class="mb-3">
              <label for="editBranchStatus" class="form-label">Trạng thái</label>
              <select id="editBranchStatus" name="Status" class="form-select">
                <option value="active">Đang hoạt động</option>
                <option value="inactive">Tạm dừng</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
            <button type="submit" class="btn btn-primary">Lưu</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Delete Branch Modal -->
  <div class="modal fade" id="deleteBranchModal" tabindex="-1" aria-labelledby="deleteBranchLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
          <input type="hidden" name="action" value="delete_branch">
          <input type="hidden" name="ID" id="deleteBranchId" value="">
          <div class="modal-header">
            <h5 class="modal-title" id="deleteBranchLabel">Xác nhận xóa</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p id="deleteBranchText">Bạn có chắc muốn xóa chi nhánh này?</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
            <button type="submit" class="btn btn-danger">Xóa</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    /**
     * Branch Management - Client-side functionality
     * Handles AJAX search, modal interactions, and dynamic table updates
     */
    
    // Configuration
    const CONFIG = {
      pageLimit: <?php echo (int)$limit; ?>,
      debounceDelay: 300
    };
    
    let currentSearch = '';
    
    // Utility functions
    const utils = {
      escapeHtml: (str) => {
        if (!str) return '';
        return String(str)
          .replace(/&/g, '&amp;')
          .replace(/</g, '&lt;')
          .replace(/>/g, '&gt;');
      },
      
      escapeAttr: (str) => {
        if (!str) return '';
        return String(str)
          .replace(/&/g, '&amp;')
          .replace(/"/g, '&quot;')
          .replace(/'/g, '&#39;')
          .replace(/</g, '&lt;');
      },
      
      debounce: (fn, wait) => {
        let timeout;
        return function(...args) {
          clearTimeout(timeout);
          timeout = setTimeout(() => fn.apply(this, args), wait);
        };
      }
    };
    
    // Modal handlers
    const modalHandlers = {
      view: (btn) => {
        const data = {
          id: btn.getAttribute('data-id'),
          name: btn.getAttribute('data-name') || '',
          address: btn.getAttribute('data-address') || '',
          phone: btn.getAttribute('data-phone') || '',
          status: btn.getAttribute('data-status') || 'active'
        };
        
        document.getElementById('viewBranchId').textContent = data.id;
        document.getElementById('viewBranchName').textContent = data.name;
        document.getElementById('viewBranchAddress').textContent = data.address;
        document.getElementById('viewBranchPhone').textContent = data.phone;
        document.getElementById('viewBranchStatus').textContent = 
          data.status === 'inactive' ? 'Tạm dừng' : 'Đang hoạt động';
        
        new bootstrap.Modal(document.getElementById('viewBranchModal')).show();
      },
      
      edit: (btn) => {
        const data = {
          id: btn.getAttribute('data-id'),
          name: btn.getAttribute('data-name') || '',
          address: btn.getAttribute('data-address') || '',
          phone: btn.getAttribute('data-phone') || '',
          status: btn.getAttribute('data-status') || 'active'
        };
        
        document.getElementById('editBranchId').value = data.id;
        document.getElementById('editBranchName').value = data.name;
        document.getElementById('editBranchAddress').value = data.address;
        document.getElementById('editBranchPhone').value = data.phone;
        document.getElementById('editBranchStatus').value = data.status;
        
        new bootstrap.Modal(document.getElementById('editBranchModal')).show();
      },
      
      delete: (btn) => {
        const id = btn.getAttribute('data-id');
        const name = btn.getAttribute('data-name') || '';
        
        document.getElementById('deleteBranchId').value = id;
        document.getElementById('deleteBranchText').textContent = 
          `Bạn có chắc muốn xóa chi nhánh "${name}" (ID: ${id})?`;
        
        new bootstrap.Modal(document.getElementById('deleteBranchModal')).show();
      }
    };
    
    // Bind action buttons
    function bindRowButtons() {
      document.querySelectorAll('.btn-view').forEach(btn => {
        btn.onclick = () => modalHandlers.view(btn);
      });
      
      document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.onclick = () => modalHandlers.edit(btn);
      });
      
      document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.onclick = () => modalHandlers.delete(btn);
      });
    }
    
    // Render table rows
    function renderRows(branches) {
      const tbody = document.querySelector('.data-table tbody');
      
      if (!branches || branches.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center">Không có kết quả</td></tr>';
        return;
      }
      
      tbody.innerHTML = branches.map(b => {
        const status = (b.Status === 'inactive') ? 'Tạm dừng' : 'Đang hoạt động';
        const statusVal = b.Status || 'active';
        
        return `
          <tr>
            <td>${b.ID || ''}</td>
            <td>${utils.escapeHtml(b.Name)}</td>
            <td>${utils.escapeHtml(b.Address)}</td>
            <td>${utils.escapeHtml(b.Phone)}</td>
            <td><span class="status-badge status-active">${status}</span></td>
            <td>
              <div class="action-buttons">
                <button class="btn-action btn-view" title="Xem chi tiết"
                  data-id="${b.ID}" 
                  data-name="${utils.escapeAttr(b.Name)}" 
                  data-address="${utils.escapeAttr(b.Address)}" 
                  data-phone="${utils.escapeAttr(b.Phone)}" 
                  data-status="${statusVal}">
                  <i class="fas fa-eye"></i>
                </button>
                <button class="btn-action btn-edit" title="Sửa"
                  data-id="${b.ID}" 
                  data-name="${utils.escapeAttr(b.Name)}" 
                  data-address="${utils.escapeAttr(b.Address)}" 
                  data-phone="${utils.escapeAttr(b.Phone)}" 
                  data-status="${statusVal}">
                  <i class="fas fa-edit"></i>
                </button>
                <button class="btn-action btn-delete" title="Xóa"
                  data-id="${b.ID}" 
                  data-name="${utils.escapeAttr(b.Name)}">
                  <i class="fas fa-trash"></i>
                </button>
              </div>
            </td>
          </tr>
        `;
      }).join('');
      
      bindRowButtons();
    }
    
    // Render pagination
    function renderPagination(meta) {
      const container = document.querySelector('.pagination');
      const { currentPage = 1, totalPages = 1 } = meta;
      
      let html = '';
      
      // Previous button
      html += currentPage > 1
        ? `<a class="btn-page" data-page="${currentPage - 1}"><i class="fas fa-chevron-left"></i></a>`
        : '<button class="btn-page" disabled><i class="fas fa-chevron-left"></i></button>';
      
      // Page numbers
      for (let i = 1; i <= totalPages; i++) {
        html += i === currentPage
          ? `<button class="btn-page active">${i}</button>`
          : `<a class="btn-page" data-page="${i}">${i}</a>`;
      }
      
      // Next button
      html += currentPage < totalPages
        ? `<a class="btn-page" data-page="${currentPage + 1}"><i class="fas fa-chevron-right"></i></a>`
        : '<button class="btn-page" disabled><i class="fas fa-chevron-right"></i></button>';
      
      container.innerHTML = html;
      
      // Attach click handlers
      container.querySelectorAll('.btn-page[data-page]').forEach(el => {
        el.onclick = () => {
          const page = parseInt(el.getAttribute('data-page')) || 1;
          doSearch(currentSearch, page);
        };
      });
    }
    
    // Perform AJAX search
    function doSearch(query, page = 1) {
      currentSearch = query || '';
      const url = `${window.location.pathname}?ajax_search=1&q=${encodeURIComponent(currentSearch)}&page=${page}&limit=${CONFIG.pageLimit}`;
      
      fetch(url, { credentials: 'same-origin' })
        .then(res => res.json())
        .then(data => {
          renderRows(data.branches || []);
          renderPagination({
            currentPage: data.currentPage,
            totalPages: data.totalPages
          });
        })
        .catch(err => {
          console.error('Search error:', err);
          renderRows([]);
        });
    }
    
    // Initialize
    document.addEventListener('DOMContentLoaded', () => {
      // Toggle sidebar
      document.querySelector('.btn-menu-toggle')?.addEventListener('click', () => {
        document.querySelector('.sidebar')?.classList.toggle('active');
      });
      
      // Search input with debounce
      const searchInput = document.getElementById('branchSearchInput');
      if (searchInput) {
        const debouncedSearch = utils.debounce(
          () => doSearch(searchInput.value.trim(), 1),
          CONFIG.debounceDelay
        );
        searchInput.addEventListener('input', debouncedSearch);
      }
      
      // Bind initial buttons
      bindRowButtons();
      
      // Perform initial search if query present
      <?php if (isset($_GET['q']) && trim($_GET['q']) !== ''): ?>
      doSearch(<?php echo json_encode(trim($_GET['q'])); ?>, <?php echo (int)$page; ?>);
      <?php endif; ?>
    });
  </script>
</body>
</html>