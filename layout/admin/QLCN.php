<?php 
include '../../app/controllers/BranchController.php';

$branchController = new BranchController();

// Pagination parameters (from query string)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;

// Use controller paginate helper (keeps logic in controller)
$pagination = $branchController->paginate($page, $limit);
$branches = $pagination['branches'];
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
    <title>Quản lý Chi nhánh - Cafe Management</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<style>
   * {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
  background-color: #f5f1ed;
  color: #3d2817;
}

/* Dashboard Container */
.dashboard {
  display: flex;
  min-height: 100vh;
}

/* Sidebar */
.sidebar {
  width: 250px;
  background-color: #5d4037;
  padding: 20px 0;
  box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
  display: flex;
  flex-direction: column;
}

.logo {
  display: flex;
  align-items: center;
  gap: 10px;
  color: #d4a574;
  padding: 15px 20px;
  font-size: 18px;
  font-weight: 600;
  border-bottom: 1px solid rgba(245, 241, 237, 0.2);
}

.logo i {
  font-size: 24px;
  color: #d7a86e;
}

.nav-menu {
  list-style: none;
  flex: 1;
  padding: 20px 0;
}

.nav-item {
  display: flex;
  align-items: center;
  gap: 12px;
  color: #e0d5c7;
  padding: 12px 20px;
  text-decoration: none;
  transition: all 0.3s;
  border-left: 3px solid transparent;
}

.nav-item:hover {
 background-color: rgba(215, 168, 110, 0.15);
color: #f5f1ed;
  padding-left: 25px;
}

.nav-item.active {
  background-color: #d7a86e;
    color: #3d2817;
  color: #fff;
}

.nav-item i {
  font-size: 16px;
  width: 20px;
}

.sidebar-footer {
  border-top: 1px solid #5a3d2a;
  padding: 15px 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.user-info {
  display: flex;
  align-items: center;
  gap: 10px;
  color: #d4a574;
}

.user-info i {
  font-size: 24px;
}

.user-name {
  font-weight: 600;
  font-size: 14px;
}

.user-role {
  font-size: 12px;
  color: #a0826d;
}

.btn-logout {
  background: none;
  border: none;
  color: #d4a574;
  cursor: pointer;
  font-size: 16px;
  transition: color 0.3s;
}

.btn-logout:hover {
  color: #fff;
}

/* Main Content */
.main-content {
  flex: 1;
  display: flex;
  flex-direction: column;
  background-color: #f5f1ed;
}

.top-bar {
  background-color: #f4f2f1;
  color: white;
  padding: 15px 30px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.btn-menu-toggle {
  background: none;
  border: none;
  color: white;
  font-size: 20px;
  cursor: pointer;
  display: none;
}

.page-title {
  font-size: 24px;
    color: #5d4037;
    font-weight: 700;
    flex: 1;
}

.top-bar-actions {
  display: flex;
  gap: 15px;
  align-items: center;
}

.btn-notification {
  background: none;
  border: none;
  color: white;
  font-size: 18px;
  cursor: pointer;
  position: relative;
}

.badge {
  position: absolute;
  top: -8px;
  right: -8px;
  background-color: #e74c3c;
  color: white;
  border-radius: 50%;
  width: 20px;
  height: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 12px;
  font-weight: 600;
}

.content-wrapper {
  flex: 1;
  padding: 30px;
  overflow-y: auto;
}

.content-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
  gap: 20px;
}

.search-box {
  display: flex;
  align-items: center;
  gap: 10px;
  background-color: white;
  padding: 10px 15px;
  border-radius: 5px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
  flex: 1;
  max-width: 400px;
}

.search-box i {
  color: #6b4423;
}

.search-box input {
  border: none;
  outline: none;
  flex: 1;
  font-size: 14px;
}

/* Buttons */
.btn {
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 14px;
  font-weight: 500;
  transition: all 0.3s;
  display: inline-flex;
  align-items: center;
  gap: 8px;
}

.btn-primary {
  background-color: #6b4423;
  color: white;
}

.btn-primary:hover {
  background-color: #8b5a2b;
}

.btn-sm {
  padding: 6px 12px;
  font-size: 12px;
}

/* Table */
.table-container {
  background-color: white;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  overflow: hidden;
}

.data-table {
  width: 100%;
  border-collapse: collapse;
}

.data-table thead {
  background-color: #8d6e63;
  color: white;
}

.data-table th {
  padding: 15px;
  text-align: left;
  font-weight: 600;
  border-bottom: 2px solid #6b4423;
}

.data-table td {
  padding: 12px 15px;
  border-bottom: 1px solid #e0e0e0;
}

.data-table tbody tr:hover {
  background-color: #f9f7f4;
}

.data-table tbody tr:nth-child(even) {
  background-color: #faf8f5;
}

.status-badge {
  padding: 5px 10px;
  border-radius: 4px;
  font-size: 12px;
  font-weight: 500;
  display: inline-block;
}

.status-active {
  background-color: #d4edda;
  color: #155724;
}

.status-inactive {
  background-color: #f8d7da;
  color: #721c24;
}

.action-buttons {
  display: flex;
  gap: 8px;
}

.btn-action {
  background: none;
  border: none;
  cursor: pointer;
  font-size: 16px;
  padding: 6px 8px;
  border-radius: 4px;
  transition: all 0.3s;
}

.btn-view {
  color: #3498db;
}

.btn-view:hover {
  background-color: #e3f2fd;
  color: #2980b9;
}

.btn-edit {
  color: #f39c12;
}

.btn-edit:hover {
  background-color: #fff3e0;
  color: #e67e22;
}

.btn-delete {
  color: #e74c3c;
}

.btn-delete:hover {
  background-color: #ffebee;
  color: #c0392b;
}

/* Pagination */
.pagination {
  display: flex;
  justify-content: center;
  gap: 5px;
  margin-top: 20px;
  padding: 15px;
  background-color: #f9f7f4;
}

.btn-page {
  padding: 8px 12px;
  border: 1px solid #d4a574;
  border-radius: 4px;
  cursor: pointer;
  background-color: white;
  color: #6b4423;
  transition: all 0.3s;
  font-size: 14px;
}

.btn-page:hover:not(:disabled) {
  background-color: #6b4423;
  color: white;
}

.btn-page.active {
  background-color: #6b4423;
  color: white;
  border-color: #6b4423;
}

.btn-page:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}


/* Responsive */
@media (max-width: 768px) {
  .dashboard {
    flex-direction: column;
  }

  .sidebar {
    width: 100%;
    flex-direction: row;
    overflow-x: auto;
  }

  .nav-menu {
    display: flex;
    flex-direction: row;
    padding: 0;
  }

  .btn-menu-toggle {
    display: block;
  }

  .content-wrapper {
    padding: 15px;
  }

  .content-header {
    flex-direction: column;
  }

  .search-box {
    max-width: 100%;
  }

  .data-table {
    font-size: 12px;
  }

  .data-table th,
  .data-table td {
    padding: 8px;
  }
}

</style>
<body>
    <div class="dashboard">
    <!-- Sidebar (shared) -->
    <?php include __DIR__ . '/sidebar.php'; ?>

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
                <td><?php echo $branch['ID']; ?></td>
                <td><?php echo $branch['Name']; ?></td>
                <td><?php echo $branch['Address']; ?></td>
                <td><?php echo $branch['Phone']; ?></td>
                <td><span class="status-badge status-active">Đang hoạt động</span></td>
                <td>
                  <div class="action-buttons">
                    <button class="btn-action btn-view" title="Xem chi tiết">
                      <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn-action btn-edit" title="Sửa">
                      <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn-action btn-delete" title="Xóa">
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
            // build page url preserving other query params
            function page_url($p) {
              $params = $_GET;
              $params['page'] = $p;
              $params['limit'] = $GLOBALS['limit'];
              return htmlspecialchars($_SERVER['PHP_SELF'] . '?' . http_build_query($params));
            }

            // prev
            if ($page > 1) {
              echo '<a class="btn-page" href="' . page_url($page - 1) . '"><i class="fas fa-chevron-left"></i></a>';
            } else {
              echo '<button class="btn-page" disabled><i class="fas fa-chevron-left"></i></button>';
            }

            // pages (concise)
            for ($i = 1; $i <= $totalPages; $i++) {
              if ($i == $page) {
                echo '<button class="btn-page active">' . $i . '</button>';
              } else {
                echo '<a class="btn-page" href="' . page_url($i) . '">' . $i . '</a>';
              }
            }

            // next
            if ($page < $totalPages) {
              echo '<a class="btn-page" href="' . page_url($page + 1) . '"><i class="fas fa-chevron-right"></i></a>';
            } else {
              echo '<button class="btn-page" disabled><i class="fas fa-chevron-right"></i></button>';
            }
          ?>
        </div>
      </div>
    </main>
  </div>

  <script>
    // Toggle sidebar on mobile
    document.querySelector('.btn-menu-toggle').addEventListener('click', function() {
      document.querySelector('.sidebar').classList.toggle('active');
    });

    // Action button handlers
    document.querySelectorAll('.btn-view').forEach(btn => {
      btn.addEventListener('click', function() {
        alert('Xem chi tiết chi nhánh');
      });
    });

    document.querySelectorAll('.btn-edit').forEach(btn => {
      btn.addEventListener('click', function() {
        alert('Sửa thông tin chi nhánh');
      });
    });

    document.querySelectorAll('.btn-delete').forEach(btn => {
      btn.addEventListener('click', function() {
        if(confirm('Bạn có chắc muốn xóa chi nhánh này?')) {
          alert('Đã xóa chi nhánh');
        }
      });
    });
  </script>
</body>
</html>