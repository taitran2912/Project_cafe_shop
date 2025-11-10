<?php 
include __DIR__ . '/../../../../controllers/AccountController.php';
$accountController = new AccountController();

// Handle POST actions (add or edit) BEFORE any HTML output to allow redirect
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
  if ($_POST['action'] === 'add_account') {
    $res = $accountController->store($_POST);
    header('Location: ' . $_SERVER['PHP_SELF'] . '?added=' . ($res ? '1' : '0'));
    exit;
  }
  if ($_POST['action'] === 'edit_account') {
    $id = isset($_POST['ID']) ? (int)$_POST['ID'] : 0;
    $res = false;
    if ($id > 0) {
      $res = $accountController->update($id, $_POST);
    }
    header('Location: ' . $_SERVER['PHP_SELF'] . '?edited=' . ($res ? '1' : '0'));
    exit;
  }
  if ($_POST['action'] === 'delete_account') {
    $id = isset($_POST['ID']) ? (int)$_POST['ID'] : 0;
    $res = false;
    if ($id > 0) {
      $res = $accountController->delete($id);
    }
    header('Location: ' . $_SERVER['PHP_SELF'] . '?deleted=' . ($res ? '1' : '0'));
    exit;
  }
}

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
// search query
$search = isset($_GET['q']) ? trim($_GET['q']) : null;

// Use controller paginate helper (keeps logic in controller). Pass search if present.
$pagination = $accountController->paginate($page, $limit, $search);
$accounts = $pagination['accounts'];
$totalItems = $pagination['totalItems'];
$totalPages = $pagination['totalPages'];
$page = $pagination['currentPage'];
$limit = $pagination['limit'];

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phân quyền nhân viên - Cafe Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./pqnv.css">
</head>
<body>
  <div class="dashboard-container">

    <!-- Sidebar -->
    <?php include __DIR__ . '/../../../layout/sidebar.php'; ?>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Bar -->
            <div class="top-bar">
                <div class="page-title">
                    <h1>Phân quyền nhân viên</h1>
                </div>
                <div class="top-bar-right">
                    <span class="admin-info">Admin - Võ Thị Thuỷ Hoa</span>
                    <button class="btn-logout">
                        <i class="fas fa-sign-out-alt"></i> Đăng xuất
                    </button>
                </div>
            </div>

            <!-- Content Area -->
            <div class="content-area">
                <!-- Action Bar -->
        <div class="action-bar">
          <!-- Button triggers Bootstrap modal -->
          <button type="button" class="btn btn-add" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
            <i class="fas fa-plus"></i> Thêm nhân viên
          </button>
          <form class="search-box d-flex" method="get" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" id="pqnv-search-form">
            <input type="hidden" name="limit" value="<?php echo htmlspecialchars($limit); ?>">
            <input type="text" name="q" class="form-control me-2" placeholder="Tìm kiếm nhân viên..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
            <i class="fas fa-search"></i>
          </form>
        </div>

                <!-- Data Table -->
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>HỌ TÊN</th>
                                <th>MẬT KHẨU (MÃ HÓA)</th>
                                <th>SỐ ĐIỆN THOẠI</th>
                                <th>ROLE</th>
                                <th>TRẠNG THÁI</th>
                                <th>THAO TÁC</th>
                            </tr>
                        </thead>
                        <tbody id="accounts-tbody">
                          <?php foreach ($accounts as $account): 
                            // determine classes safely
              // normalize role: accept stored string or numeric code
              $rawRole = isset($account['Role']) ? $account['Role'] : 'staff';
              if (is_numeric($rawRole)) {
                $map = ['1' => 'admin', '2' => 'manager', '3' => 'staff'];
                $role = isset($map[$rawRole]) ? $map[$rawRole] : 'staff';
              } else {
                $role = strtolower($rawRole);
              }
              if (strpos($role, 'admin') !== false) $roleClass = 'admin';
              elseif (strpos($role, 'manager') !== false) $roleClass = 'manager';
              else $roleClass = 'staff';

                            $status = isset($account['Status']) ? strtolower($account['Status']) : 'active';
                            $statusClass = ($status === 'active') ? 'active' : 'inactive';
                            $statusText = ($status === 'active') ? 'Đang hoạt động' : 'Tạm dừng';
                          ?>

                          <tr>
                            <td><?php echo htmlspecialchars($account['ID']); ?></td>
                            <td><?php echo htmlspecialchars($account['Name']); ?></td>
                            <td>••••••••••••</td>
                            <td><?php echo htmlspecialchars($account['Phone']); ?></td>
                            <td><span class="role-badge <?php echo $roleClass; ?>"><?php echo htmlspecialchars(ucfirst($role)); ?></span></td>
                            <td><span class="status-badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span></td>
                            <td>
                              <div class="action-buttons">
                                <button class="btn-action view" title="Xem chi tiết"
                                  data-id="<?php echo htmlspecialchars($account['ID']); ?>"
                                  data-name="<?php echo htmlspecialchars($account['Name']); ?>"
                                  data-phone="<?php echo htmlspecialchars($account['Phone']); ?>"
                                  data-role="<?php echo htmlspecialchars($account['Role']); ?>"
                                  data-status="<?php echo htmlspecialchars($account['Status']); ?>"
                                >
                                  <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-action edit" title="Sửa"
                                  data-id="<?php echo htmlspecialchars($account['ID']); ?>"
                                  data-name="<?php echo htmlspecialchars($account['Name']); ?>"
                                  data-phone="<?php echo htmlspecialchars($account['Phone']); ?>"
                                  data-role="<?php echo htmlspecialchars($account['Role']); ?>"
                                  data-status="<?php echo htmlspecialchars($account['Status']); ?>"
                                >
                                  <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-action delete" title="Xóa"
                                  data-id="<?php echo htmlspecialchars($account['ID']); ?>"
                                  data-name="<?php echo htmlspecialchars($account['Name']); ?>"
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

                    <!-- Pagination -->
                    <div class="pagination-container" id="pqnv-pagination">
                      <?php
                        function page_url_pqnv($p) {
                          $params = $_GET;
                          $params['page'] = $p;
                          $params['limit'] = $GLOBALS['limit'];
                          return htmlspecialchars($_SERVER['PHP_SELF'] . '?' . http_build_query($params));
                        }

                        // Prev
                        if ($page > 1) {
                          echo '<a class="btn-page" href="' . page_url_pqnv($page - 1) . '"><i class="fas fa-chevron-left"></i></a>';
                        } else {
                          echo '<button class="btn-page" disabled><i class="fas fa-chevron-left"></i></button>';
                        }

                        // Page info
                        echo '<span class="page-info">Trang <strong>' . $page . '</strong> trên ' . $totalPages . ' — Tổng: ' . $totalItems . ' mục</span>';

                        // Next
                        if ($page < $totalPages) {
                          echo '<a class="btn-page" href="' . page_url_pqnv($page + 1) . '"><i class="fas fa-chevron-right"></i></a>';
                        } else {
                          echo '<button class="btn-page" disabled><i class="fas fa-chevron-right"></i></button>';
                        }
                      ?>
        </div>
        
        <!-- Add Employee Modal (Bootstrap) -->
        <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <input type="hidden" name="action" value="add_account">
                <div class="modal-header">
                  <h5 class="modal-title" id="addEmployeeLabel">Thêm nhân viên</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="mb-3">
                    <label for="empName" class="form-label">Họ và tên</label>
                    <input type="text" class="form-control" id="empName" name="Name" required>
                  </div>
                  <div class="mb-3">
                    <label for="empPassword" class="form-label">Mật khẩu</label>
                    <input type="password" class="form-control" id="empPassword" name="Password" required>
                  </div>
                  <div class="mb-3">
                    <label for="empPhone" class="form-label">Số điện thoại</label>
                    <input type="text" class="form-control" id="empPhone" name="Phone">
                  </div>
                  <div class="mb-3">
                    <label for="empRole" class="form-label">Role</label>
                    <select id="empRole" name="Role" class="form-select">
                      <option value="3">Staff</option>
                      <option value="2">Manager</option>
                      <option value="1">Admin</option>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label for="empStatus" class="form-label">Trạng thái</label>
                    <select id="empStatus" name="Status" class="form-select">
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

        <!-- Edit Employee Modal -->
        <div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <input type="hidden" name="action" value="edit_account">
                <input type="hidden" name="ID" id="editEmpId" value="">
                <div class="modal-header">
                  <h5 class="modal-title" id="editEmployeeLabel">Sửa nhân viên</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="mb-3">
                    <label for="editEmpName" class="form-label">Họ và tên</label>
                    <input type="text" class="form-control" id="editEmpName" name="Name" required>
                  </div>
                  <div class="mb-3">
                    <label for="editEmpPassword" class="form-label">Mật khẩu (để trống nếu không đổi)</label>
                    <input type="password" class="form-control" id="editEmpPassword" name="Password">
                  </div>
                  <div class="mb-3">
                    <label for="editEmpPhone" class="form-label">Số điện thoại</label>
                    <input type="text" class="form-control" id="editEmpPhone" name="Phone">
                  </div>
                  <div class="mb-3">
                    <label for="editEmpRole" class="form-label">Role</label>
                    <select id="editEmpRole" name="Role" class="form-select">
                      <option value="3">Staff</option>
                      <option value="2">Manager</option>
                      <option value="1">Admin</option>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label for="editEmpStatus" class="form-label">Trạng thái</label>
                    <select id="editEmpStatus" name="Status" class="form-select">
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

        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteEmployeeModal" tabindex="-1" aria-labelledby="deleteEmployeeLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <input type="hidden" name="action" value="delete_account">
                <input type="hidden" name="ID" id="deleteEmpId" value="">
                <div class="modal-header">
                  <h5 class="modal-title" id="deleteEmployeeLabel">Xác nhận xóa</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <p id="deleteConfirmText">Bạn có chắc muốn xóa nhân viên này?</p>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                  <button type="submit" class="btn btn-danger">Xóa</button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- View Details Modal -->
        <div class="modal fade" id="viewEmployeeModal" tabindex="-1" aria-labelledby="viewEmployeeLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="viewEmployeeLabel">Chi tiết nhân viên</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <ul class="list-group list-group-flush">
                  <li class="list-group-item"><strong>ID: </strong> <span id="viewEmpId"></span></li>
                  <li class="list-group-item"><strong>Họ và tên: </strong> <span id="viewEmpName"></span></li>
                  <li class="list-group-item"><strong>Số điện thoại: </strong> <span id="viewEmpPhone"></span></li>
                  <li class="list-group-item"><strong>Role: </strong> <span id="viewEmpRole"></span></li>
                  <li class="list-group-item"><strong>Trạng thái: </strong> <span id="viewEmpStatus"></span></li>
                </ul>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
              </div>
            </div>
          </div>
        </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      // populate edit modal when clicking edit buttons
      document.addEventListener('DOMContentLoaded', function() {
        // view details handler
        document.querySelectorAll('.btn-action.view').forEach(function(btn){
          btn.addEventListener('click', function(){
            var id = this.getAttribute('data-id');
            var name = this.getAttribute('data-name') || '';
            var phone = this.getAttribute('data-phone') || '';
            var role = this.getAttribute('data-role') || '';
            var status = this.getAttribute('data-status') || '';

            // normalize role for display
            var roleText = '';
            if (!isNaN(role) && role !== '') {
              if (role === '1') roleText = 'Admin';
              else if (role === '2') roleText = 'Manager';
              else roleText = 'Staff';
            } else {
              var r = role.toString().toLowerCase();
              if (r.indexOf('admin') !== -1) roleText = 'Admin';
              else if (r.indexOf('manager') !== -1) roleText = 'Manager';
              else roleText = 'Staff';
            }

            document.getElementById('viewEmpId').textContent = id;
            document.getElementById('viewEmpName').textContent = name;
            document.getElementById('viewEmpPhone').textContent = phone;
            document.getElementById('viewEmpRole').textContent = roleText;
            document.getElementById('viewEmpStatus').textContent = status === 'inactive' ? 'Tạm dừng' : 'Đang hoạt động';

            var modal = new bootstrap.Modal(document.getElementById('viewEmployeeModal'));
            modal.show();
          });
        });
        document.querySelectorAll('.btn-action.edit').forEach(function(btn){
          btn.addEventListener('click', function(){
            var id = this.getAttribute('data-id');
            var name = this.getAttribute('data-name');
            var phone = this.getAttribute('data-phone');
            var role = this.getAttribute('data-role');
            var status = this.getAttribute('data-status');

            document.getElementById('editEmpId').value = id;
            document.getElementById('editEmpName').value = name;
            document.getElementById('editEmpPhone').value = phone;
            // normalize role to numeric code for the select (1=admin,2=manager,3=staff)
            var roleCode = '';
            if (role === null) role = '';
            if (!isNaN(role) && role !== '') {
              roleCode = role;
            } else {
              var r = role.toString().toLowerCase();
              if (r.indexOf('admin') !== -1) roleCode = '1';
              else if (r.indexOf('manager') !== -1) roleCode = '2';
              else roleCode = '3';
            }
            document.getElementById('editEmpRole').value = roleCode;
            document.getElementById('editEmpStatus').value = status;
            // clear password field
            document.getElementById('editEmpPassword').value = '';

            var modal = new bootstrap.Modal(document.getElementById('editEmployeeModal'));
            modal.show();
          });
        });
        // handle delete button -> show confirmation modal
        document.querySelectorAll('.btn-action.delete').forEach(function(btn){
          btn.addEventListener('click', function(){
            var id = this.getAttribute('data-id');
            var name = this.getAttribute('data-name') || '';
            document.getElementById('deleteEmpId').value = id;
            var txt = 'Bạn có chắc muốn xóa nhân viên "' + name + '" (ID: ' + id + ')?';
            document.getElementById('deleteConfirmText').textContent = txt;
            var modal = new bootstrap.Modal(document.getElementById('deleteEmployeeModal'));
            modal.show();
          });
        });

        // --- Live search (debounced) ---
        (function(){
          var form = document.getElementById('pqnv-search-form');
          if (!form) return;
          var input = form.querySelector('input[name="q"]');
          var limitField = form.querySelector('input[name="limit"]');
          var tbody = document.getElementById('accounts-tbody');
          var paginationContainer = document.getElementById('pqnv-pagination');

          var debounce = function(fn, wait){
            var t;
            return function(){
              var args = arguments;
              clearTimeout(t);
              t = setTimeout(function(){ fn.apply(null, args); }, wait);
            };
          };

          var renderRows = function(accounts){
            if (!tbody) return;
            if (!accounts || accounts.length === 0) {
              tbody.innerHTML = '<tr><td colspan="7" class="text-center">Không có kết quả</td></tr>';
              return;
            }
            var html = '';
            accounts.forEach(function(a){
              var rawRole = a.Role === undefined ? 'staff' : a.Role;
              var role = 'staff';
              if (!isNaN(rawRole)) {
                role = (rawRole === '1') ? 'admin' : (rawRole === '2' ? 'manager' : 'staff');
              } else {
                role = (''+rawRole).toLowerCase();
              }
              var roleClass = role.indexOf('admin') !== -1 ? 'admin' : (role.indexOf('manager') !== -1 ? 'manager' : 'staff');
              var status = (a.Status || 'active').toLowerCase();
              var statusClass = status === 'active' ? 'active' : 'inactive';
              var statusText = status === 'active' ? 'Đang hoạt động' : 'Tạm dừng';

              html += '<tr>' +
                '<td>' + escapeHtml(a.ID) + '</td>' +
                '<td>' + escapeHtml(a.Name) + '</td>' +
                '<td>••••••••••••</td>' +
                '<td>' + escapeHtml(a.Phone) + '</td>' +
                '<td><span class="role-badge ' + roleClass + '">' + capitalize(role) + '</span></td>' +
                '<td><span class="status-badge ' + statusClass + '">' + statusText + '</span></td>' +
                '<td>' +
                  '<div class="action-buttons">' +
                    '<button class="btn-action view" title="Xem chi tiết" data-id="' + escapeHtml(a.ID) + '" data-name="' + escapeHtml(a.Name) + '" data-phone="' + escapeHtml(a.Phone) + '" data-role="' + escapeHtml(a.Role) + '" data-status="' + escapeHtml(a.Status) + '"><i class="fas fa-eye"></i></button>' +
                    '<button class="btn-action edit" title="Sửa" data-id="' + escapeHtml(a.ID) + '" data-name="' + escapeHtml(a.Name) + '" data-phone="' + escapeHtml(a.Phone) + '" data-role="' + escapeHtml(a.Role) + '" data-status="' + escapeHtml(a.Status) + '"><i class="fas fa-edit"></i></button>' +
                    '<button class="btn-action delete" title="Xóa" data-id="' + escapeHtml(a.ID) + '" data-name="' + escapeHtml(a.Name) + '"><i class="fas fa-trash"></i></button>' +
                  '</div>' +
                '</td>' +
              '</tr>';
            });
            tbody.innerHTML = html;
            // re-bind handlers for new buttons
            bindRowButtons();
          };

          var renderPagination = function(info){
            if (!paginationContainer) return;
            var p = info.currentPage || 1;
            var totalPages = info.totalPages || 1;
            var totalItems = info.totalItems || 0;
            var limit = info.limit || (limitField ? parseInt(limitField.value,10) : 5);

            var params = new URLSearchParams(window.location.search);
            params.set('limit', limit);

            var html = '';
            // prev
            if (p > 1) {
              params.set('page', p-1);
              html += '<a class="btn-page" href="?' + params.toString() + '"><i class="fas fa-chevron-left"></i></a>';
            } else {
              html += '<button class="btn-page" disabled><i class="fas fa-chevron-left"></i></button>';
            }
            html += '<span class="page-info">Trang <strong>' + p + '</strong> trên ' + totalPages + ' — Tổng: ' + totalItems + ' mục</span>';
            if (p < totalPages) {
              params.set('page', p+1);
              html += '<a class="btn-page" href="?' + params.toString() + '"><i class="fas fa-chevron-right"></i></a>';
            } else {
              html += '<button class="btn-page" disabled><i class="fas fa-chevron-right"></i></button>';
            }
            paginationContainer.innerHTML = html;
          };

          var escapeHtml = function(str){
            if (str === null || str === undefined) return '';
            return (''+str)
              .replace(/&/g, '&amp;')
              .replace(/</g, '&lt;')
              .replace(/>/g, '&gt;')
              .replace(/"/g, '&quot;')
              .replace(/'/g, '&#039;');
          };
          var capitalize = function(s){ if(!s) return ''; return s.charAt(0).toUpperCase() + s.slice(1); };

          var bindRowButtons = function(){
            // view
            document.querySelectorAll('#accounts-tbody .btn-action.view').forEach(function(btn){
              btn.removeEventListener('click', viewHandler);
              btn.addEventListener('click', viewHandler);
            });
            // edit
            document.querySelectorAll('#accounts-tbody .btn-action.edit').forEach(function(btn){
              btn.removeEventListener('click', editHandler);
              btn.addEventListener('click', editHandler);
            });
            // delete
            document.querySelectorAll('#accounts-tbody .btn-action.delete').forEach(function(btn){
              btn.removeEventListener('click', deleteHandler);
              btn.addEventListener('click', deleteHandler);
            });
          };

          // reuse handlers defined earlier by referencing them via named functions
          var viewHandler = function(){
            var id = this.getAttribute('data-id');
            var name = this.getAttribute('data-name') || '';
            var phone = this.getAttribute('data-phone') || '';
            var role = this.getAttribute('data-role') || '';
            var status = this.getAttribute('data-status') || '';
            var roleText = '';
            if (!isNaN(role) && role !== '') {
              if (role === '1') roleText = 'Admin';
              else if (role === '2') roleText = 'Manager';
              else roleText = 'Staff';
            } else {
              var r = role.toString().toLowerCase();
              if (r.indexOf('admin') !== -1) roleText = 'Admin';
              else if (r.indexOf('manager') !== -1) roleText = 'Manager';
              else roleText = 'Staff';
            }
            document.getElementById('viewEmpId').textContent = id;
            document.getElementById('viewEmpName').textContent = name;
            document.getElementById('viewEmpPhone').textContent = phone;
            document.getElementById('viewEmpRole').textContent = roleText;
            document.getElementById('viewEmpStatus').textContent = status === 'inactive' ? 'Tạm dừng' : 'Đang hoạt động';
            var modal = new bootstrap.Modal(document.getElementById('viewEmployeeModal'));
            modal.show();
          };

          var editHandler = function(){
            var id = this.getAttribute('data-id');
            var name = this.getAttribute('data-name');
            var phone = this.getAttribute('data-phone');
            var role = this.getAttribute('data-role');
            var status = this.getAttribute('data-status');
            document.getElementById('editEmpId').value = id;
            document.getElementById('editEmpName').value = name;
            document.getElementById('editEmpPhone').value = phone;
            var roleCode = '';
            if (role === null) role = '';
            if (!isNaN(role) && role !== '') roleCode = role;
            else {
              var r = role.toString().toLowerCase();
              if (r.indexOf('admin') !== -1) roleCode = '1';
              else if (r.indexOf('manager') !== -1) roleCode = '2';
              else roleCode = '3';
            }
            document.getElementById('editEmpRole').value = roleCode;
            document.getElementById('editEmpStatus').value = status;
            document.getElementById('editEmpPassword').value = '';
            var modal = new bootstrap.Modal(document.getElementById('editEmployeeModal'));
            modal.show();
          };

          var deleteHandler = function(){
            var id = this.getAttribute('data-id');
            var name = this.getAttribute('data-name') || '';
            document.getElementById('deleteEmpId').value = id;
            var txt = 'Bạn có chắc muốn xóa nhân viên "' + name + '" (ID: ' + id + ')?';
            document.getElementById('deleteConfirmText').textContent = txt;
            var modal = new bootstrap.Modal(document.getElementById('deleteEmployeeModal'));
            modal.show();
          };

          // Prevent normal form submit to keep realtime behavior
          form.addEventListener('submit', function(e){ e.preventDefault(); });

          var doSearch = function(q, page){
            page = page || 1;
            var lim = limitField ? parseInt(limitField.value,10) : 5;
            var url = 'search_api.php?q=' + encodeURIComponent(q || '') + '&page=' + page + '&limit=' + lim;
            fetch(url, { credentials: 'same-origin' })
              .then(function(r){ return r.json(); })
              .then(function(data){
                if (!data || !data.success) {
                  renderRows([]);
                  renderPagination({ currentPage:1, totalPages:1, totalItems:0, limit: lim });
                  return;
                }
                renderRows(data.accounts || []);
                renderPagination({ currentPage: data.currentPage, totalPages: data.totalPages, totalItems: data.totalItems, limit: data.limit });
              }).catch(function(){
                renderRows([]);
              });
          };

          var debounced = debounce(function(){ doSearch(input.value, 1); }, 300);
          input.addEventListener('input', debounced);

          // initial bind for existing buttons
          bindRowButtons();
        })();
      });
    </script>
</body>
</html>
