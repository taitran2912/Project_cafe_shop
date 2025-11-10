<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phân quyền nhân viên - Cafe Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="phan-quyen-nhan-vien.css">
</head>
<style>
    * {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
  background-color: #f5f5f5;
  color: #333;
}

/* Dashboard Container */
.dashboard-container {
  display: flex;
  min-height: 100vh;
}

/* Sidebar */
.sidebar {
  width: 280px;
  background: linear-gradient(135deg, #3d2817 0%, #5a3d2a 100%);
  color: white;
  padding: 20px;
  position: fixed;
  height: 100vh;
  overflow-y: auto;
  box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
}

.sidebar-header {
  margin-bottom: 30px;
  padding-bottom: 20px;
  border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}

.logo {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 24px;
  font-weight: bold;
}

.logo i {
  font-size: 28px;
  color: #d4a574;
}

.sidebar-nav {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.nav-item {
  display: flex;
  align-items: center;
  gap: 15px;
  padding: 12px 15px;
  color: rgba(255, 255, 255, 0.8);
  text-decoration: none;
  border-radius: 8px;
  transition: all 0.3s ease;
  font-size: 14px;
}

.nav-item:hover {
  background-color: rgba(255, 255, 255, 0.1);
  color: white;
}

.nav-item.active {
  background-color: #d4a574;
  color: #3d2817;
  font-weight: 600;
}

.nav-item i {
  font-size: 18px;
  width: 20px;
}

/* Main Content */
.main-content {
  margin-left: 280px;
  flex: 1;
  display: flex;
  flex-direction: column;
}

/* Top Bar */
.top-bar {
  background: white;
  padding: 20px 30px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
  border-bottom: 3px solid #d4a574;
}

.page-title h1 {
  font-size: 28px;
  color: #3d2817;
  font-weight: 600;
  margin: 0;
}

.top-bar-right {
  display: flex;
  align-items: center;
  gap: 20px;
}

.admin-info {
  color: #666;
  font-size: 14px;
}

.btn-logout {
  background-color: #8b5a2b;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 6px;
  cursor: pointer;
  font-size: 14px;
  display: flex;
  align-items: center;
  gap: 8px;
  transition: background-color 0.3s ease;
}

.btn-logout:hover {
  background-color: #6b4423;
}

/* Content Area */
.content-area {
  flex: 1;
  padding: 30px;
  background-color: #f5f5f5;
}

/* Action Bar */
.action-bar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 25px;
  gap: 20px;
}

.btn-add {
  background-color: #d4a574;
  color: #3d2817;
  border: none;
  padding: 10px 20px;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 8px;
  transition: background-color 0.3s ease;
}

.btn-add:hover {
  background-color: #c99560;
}

.search-box {
  position: relative;
  flex: 1;
  max-width: 400px;
}

.search-box input {
  width: 100%;
  padding: 10px 15px 10px 40px;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-size: 14px;
  background-color: white;
}

.search-box input:focus {
  outline: none;
  border-color: #d4a574;
  box-shadow: 0 0 0 3px rgba(212, 165, 116, 0.1);
}

.search-box i {
  position: absolute;
  left: 12px;
  top: 50%;
  transform: translateY(-50%);
  color: #999;
}

/* Table Container */
.table-container {
  background: white;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
  margin-bottom: 20px;
}

.data-table {
  width: 100%;
  border-collapse: collapse;
}

.data-table thead {
  background-color: #6b4423;
  color: white;
}

.data-table th {
  padding: 15px;
  text-align: left;
  font-weight: 600;
  font-size: 13px;
  letter-spacing: 0.5px;
}

.data-table td {
  padding: 15px;
  border-bottom: 1px solid #eee;
  font-size: 14px;
}

.data-table tbody tr:hover {
  background-color: #f9f9f9;
}

/* Role Badges */
.role-badge {
  display: inline-block;
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
}

.role-badge.admin {
  background-color: #ffe6e6;
  color: #c41e3a;
}

.role-badge.manager {
  background-color: #fff4e6;
  color: #ff9800;
}

.role-badge.staff {
  background-color: #e6f3ff;
  color: #0066cc;
}

/* Status Badges */
.status-badge {
  display: inline-block;
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
}

.status-badge.active {
  background-color: #d4edda;
  color: #155724;
}

.status-badge.inactive {
  background-color: #f8d7da;
  color: #721c24;
}

/* Action Buttons */
.action-buttons {
  display: flex;
  gap: 8px;
}

.btn-action {
  width: 32px;
  height: 32px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 14px;
  transition: all 0.3s ease;
}

.btn-action.view {
  background-color: #e3f2fd;
  color: #1976d2;
}

.btn-action.view:hover {
  background-color: #1976d2;
  color: white;
}

.btn-action.edit {
  background-color: #fff3e0;
  color: #f57c00;
}

.btn-action.edit:hover {
  background-color: #f57c00;
  color: white;
}

.btn-action.delete {
  background-color: #ffebee;
  color: #c41e3a;
}

.btn-action.delete:hover {
  background-color: #c41e3a;
  color: white;
}

/* Pagination */
.pagination-container {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 10px;
  padding: 20px;
  background: white;
  border-radius: 8px;
}

.btn-page {
  width: 36px;
  height: 36px;
  border: 1px solid #ddd;
  background-color: white;
  border-radius: 4px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
  font-size: 12px;
}

.btn-page:hover {
  background-color: #d4a574;
  color: white;
  border-color: #d4a574;
}

.page-info {
  margin: 0 10px;
  font-size: 14px;
  color: #666;
}

/* Responsive Design */
@media (max-width: 768px) {
  .sidebar {
    width: 70px;
    padding: 10px;
  }

  .sidebar-header {
    margin-bottom: 20px;
    padding-bottom: 15px;
  }

  .logo span {
    display: none;
  }

  .nav-item span {
    display: none;
  }

  .nav-item {
    justify-content: center;
    padding: 12px;
  }

  .main-content {
    margin-left: 70px;
  }

  .top-bar {
    flex-direction: column;
    gap: 15px;
    align-items: flex-start;
  }

  .top-bar-right {
    width: 100%;
    justify-content: space-between;
  }

  .action-bar {
    flex-direction: column;
    align-items: stretch;
  }

  .search-box {
    max-width: 100%;
  }

  .data-table {
    font-size: 12px;
  }

  .data-table th,
  .data-table td {
    padding: 10px;
  }

  .action-buttons {
    gap: 4px;
  }

  .btn-action {
    width: 28px;
    height: 28px;
    font-size: 12px;
  }
}

</style>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <i class="fas fa-mug-hot"></i>
                    <span>Cafe Manager</span>
                </div>
            </div>
            <nav class="sidebar-nav">
                <a href="chi-nhanh.html" class="nav-item">
                    <i class="fas fa-utensils"></i>
                    <span>Quản lý thực đơn</span>
                </a>
                <a href="chi-nhanh.html" class="nav-item">
                    <i class="fas fa-store"></i>
                    <span>Quản lý chi nhánh</span>
                </a>
                <a href="thong-ke-bao-cao.html" class="nav-item">
                    <i class="fas fa-chart-bar"></i>
                    <span>Thống kê báo cáo</span>
                </a>
                <a href="phan-quyen-nhan-vien.html" class="nav-item active">
                    <i class="fas fa-users-cog"></i>
                    <span>Phân quyền nhân viên</span>
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-tag"></i>
                    <span>Quản lý khuyến mãi</span>
                </a>
            </nav>
        </aside>

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
                    <button class="btn btn-add">
                        <i class="fas fa-plus"></i> Thêm nhân viên
                    </button>
                    <div class="search-box">
                        <input type="text" placeholder="Tìm kiếm nhân viên...">
                        <i class="fas fa-search"></i>
                    </div>
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
                        <tbody>
                            <tr>
                                <td>NV001</td>
                                <td>Nguyễn Văn A</td>
                                <td>••••••••••••</td>
                                <td>0912345678</td>
                                <td><span class="role-badge admin">Admin</span></td>
                                <td><span class="status-badge active">Đang hoạt động</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-action view" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn-action edit" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn-action delete" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>NV002</td>
                                <td>Trần Thị B</td>
                                <td>••••••••••••</td>
                                <td>0987654321</td>
                                <td><span class="role-badge manager">Quản lý</span></td>
                                <td><span class="status-badge active">Đang hoạt động</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-action view" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn-action edit" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn-action delete" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>NV003</td>
                                <td>Lê Văn C</td>
                                <td>••••••••••••</td>
                                <td>0901234567</td>
                                <td><span class="role-badge staff">Nhân viên</span></td>
                                <td><span class="status-badge active">Đang hoạt động</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-action view" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn-action edit" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn-action delete" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>NV004</td>
                                <td>Phạm Thị D</td>
                                <td>••••••••••••</td>
                                <td>0923456789</td>
                                <td><span class="role-badge staff">Nhân viên</span></td>
                                <td><span class="status-badge inactive">Tạm dừng</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-action view" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn-action edit" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn-action delete" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>NV005</td>
                                <td>Hoàng Văn E</td>
                                <td>••••••••••••</td>
                                <td>0934567890</td>
                                <td><span class="role-badge manager">Quản lý</span></td>
                                <td><span class="status-badge active">Đang hoạt động</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-action view" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn-action edit" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn-action delete" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>NV006</td>
                                <td>Đỗ Thị F</td>
                                <td>••••••••••••</td>
                                <td>0945678901</td>
                                <td><span class="role-badge staff">Nhân viên</span></td>
                                <td><span class="status-badge active">Đang hoạt động</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-action view" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn-action edit" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn-action delete" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="pagination-container">
                    <button class="btn-page"><i class="fas fa-chevron-left"></i></button>
                    <button class="btn-page"><i class="fas fa-chevron-left"></i></button>
                    <span class="page-info">Trang <strong>1</strong> trên 7 trang</span>
                    <button class="btn-page"><i class="fas fa-chevron-right"></i></button>
                    <button class="btn-page"><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
