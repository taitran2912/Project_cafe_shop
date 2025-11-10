<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý khuyến mãi - Cafe Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="quan-ly-khuyen-mai.css">
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
  padding: 20px 0;
  position: fixed;
  height: 100vh;
  overflow-y: auto;
  box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
}

.sidebar-header {
  padding: 20px;
  border-bottom: 2px solid rgba(255, 255, 255, 0.1);
  margin-bottom: 20px;
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
  padding: 0 10px;
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
  transition: background-color 0.3s ease;
}

.btn-logout:hover {
  background-color: #6b4423;
}

/* Content Area */
.content-area {
  flex: 1;
  padding: 30px;
  background-color: #f9f7f4;
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
  transition: background-color 0.3s ease;
  display: flex;
  align-items: center;
  gap: 8px;
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

.search-box i {
  position: absolute;
  right: 12px;
  top: 50%;
  transform: translateY(-50%);
  color: #999;
}

/* Table */
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
  background-color: #f9f7f4;
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
  background-color: #fff3cd;
  color: #856404;
}

.status-badge.expired {
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
  color: #d32f2f;
}

.btn-action.delete:hover {
  background-color: #d32f2f;
  color: white;
}

/* Pagination */
.pagination-container {
  display: flex;
  justify-content: center;
  gap: 8px;
  margin-top: 20px;
}

.btn-page {
  width: 36px;
  height: 36px;
  border: 1px solid #ddd;
  background-color: white;
  border-radius: 4px;
  cursor: pointer;
  font-size: 14px;
  transition: all 0.3s ease;
}

.btn-page:hover {
  background-color: #f0f0f0;
}

.btn-page.active {
  background-color: #6b4423;
  color: white;
  border-color: #6b4423;
}

/* Responsive */
@media (max-width: 768px) {
  .sidebar {
    width: 70px;
    padding: 10px 0;
  }

  .sidebar-header {
    padding: 10px;
  }

  .logo span {
    display: none;
  }

  .nav-item span {
    display: none;
  }

  .main-content {
    margin-left: 70px;
  }

  .top-bar {
    flex-direction: column;
    gap: 15px;
    align-items: flex-start;
  }

  .action-bar {
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
    padding: 10px;
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
                <a href="phan-quyen-nhan-vien.html" class="nav-item">
                    <i class="fas fa-users"></i>
                    <span>Phân quyền nhân viên</span>
                </a>
                <a href="quan-ly-khuyen-mai.html" class="nav-item active">
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
                    <h1>Quản lý khuyến mãi</h1>
                </div>
                <div class="top-bar-right">
                    <span class="admin-info">Admin - Võ Thị Thuỷ Hoa</span>
                    <button class="btn btn-logout">
                        <i class="fas fa-sign-out-alt"></i> Đăng xuất
                    </button>
                </div>
            </div>

            <!-- Content Area -->
            <div class="content-area">
                <!-- Action Bar -->
                <div class="action-bar">
                    <button class="btn btn-add">
                        <i class="fas fa-plus"></i> Thêm khuyến mãi
                    </button>
                    <div class="search-box">
                        <input type="text" placeholder="Tìm kiếm khuyến mãi...">
                        <i class="fas fa-search"></i>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>MÃ</th>
                                <th>MÔ TẢ</th>
                                <th>GIÁ TRỊ</th>
                                <th>NGÀY BẮT ĐẦU</th>
                                <th>NGÀY KẾT THÚC</th>
                                <th>TRẠNG THÁI</th>
                                <th>SỐ LƯỢNG</th>
                                <th>THAO TÁC</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>KM001</td>
                                <td>Giảm 10% cho cà phê</td>
                                <td>10%</td>
                                <td>01/01/2024</td>
                                <td>31/01/2024</td>
                                <td><span class="status-badge active">Đang hoạt động</span></td>
                                <td>150</td>
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
                                <td>KM002</td>
                                <td>Mua 2 tặng 1 trà</td>
                                <td>1 ly miễn phí</td>
                                <td>05/01/2024</td>
                                <td>15/02/2024</td>
                                <td><span class="status-badge active">Đang hoạt động</span></td>
                                <td>200</td>
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
                                <td>KM003</td>
                                <td>Giảm 20% cho bánh</td>
                                <td>20%</td>
                                <td>10/12/2023</td>
                                <td>31/12/2023</td>
                                <td><span class="status-badge expired">Đã hết hạn</span></td>
                                <td>0</td>
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
                                <td>KM004</td>
                                <td>Giảm 15% cho đơn từ 100k</td>
                                <td>15%</td>
                                <td>15/01/2024</td>
                                <td>28/02/2024</td>
                                <td><span class="status-badge active">Đang hoạt động</span></td>
                                <td>85</td>
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
                                <td>KM005</td>
                                <td>Khuyến mãi sinh nhật</td>
                                <td>Giảm 25%</td>
                                <td>01/02/2024</td>
                                <td>29/02/2024</td>
                                <td><span class="status-badge inactive">Chưa kích hoạt</span></td>
                                <td>300</td>
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
                    <button class="btn-page active">1</button>
                    <button class="btn-page">2</button>
                    <button class="btn-page">3</button>
                    <button class="btn-page"><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
