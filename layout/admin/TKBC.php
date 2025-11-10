<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thống kê báo cáo - Cafe Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="thong-ke-bao-cao.css">
</head>
<style>
    * {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

:root {
  --primary-brown: #3d2817;
  --secondary-brown: #6b4423;
  --accent-brown: #8b5a2b;
  --light-brown: #d4a574;
  --bg-light: #f5f1ed;
  --text-dark: #2c2c2c;
  --text-light: #666;
  --border-color: #e0d5c7;
  --success: #4caf50;
  --warning: #ff9800;
  --danger: #f44336;
  --info: #2196f3;
}

body {
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
  background-color: var(--bg-light);
  color: var(--text-dark);
}

.dashboard-container {
  display: flex;
  min-height: 100vh;
}

/* Sidebar */
.sidebar {
  width: 280px;
  background-color: var(--primary-brown);
  color: white;
  padding: 20px 0;
  position: fixed;
  height: 100vh;
  overflow-y: auto;
  box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
}

.sidebar-header {
  padding: 0 20px 30px;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.logo {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 20px;
  font-weight: bold;
}

.logo i {
  font-size: 28px;
  color: var(--light-brown);
}

.sidebar-nav {
  display: flex;
  flex-direction: column;
  gap: 10px;
  padding: 20px 10px;
}

.nav-item {
  display: flex;
  align-items: center;
  gap: 15px;
  padding: 12px 20px;
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
  background-color: var(--light-brown);
  color: var(--primary-brown);
  font-weight: 600;
}

.nav-item i {
  font-size: 18px;
  width: 20px;
}

/* Main Content */
.main-content {
  flex: 1;
  margin-left: 280px;
  display: flex;
  flex-direction: column;
}

/* Top Bar */
.top-bar {
  background-color: white;
  padding: 20px 30px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid var(--border-color);
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.page-title h1 {
  font-size: 28px;
  color: var(--text-dark);
  margin: 0;
  font-weight: 600;
}

.top-bar-right {
  display: flex;
  align-items: center;
  gap: 20px;
}

.admin-info {
  color: var(--text-light);
  font-size: 14px;
}

.btn-logout {
  background-color: var(--secondary-brown);
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
  background-color: var(--accent-brown);
}

/* Content Area */
.content-area {
  flex: 1;
  padding: 30px;
  overflow-y: auto;
}

/* Filter Section */
.filter-section {
  display: flex;
  gap: 20px;
  margin-bottom: 30px;
  background-color: white;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.filter-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
  flex: 1;
}

.filter-group label {
  font-size: 14px;
  font-weight: 600;
  color: var(--text-dark);
}

.form-select {
  padding: 8px 12px;
  border: 1px solid var(--border-color);
  border-radius: 6px;
  font-size: 14px;
  color: var(--text-dark);
  background-color: white;
  cursor: pointer;
}

.form-select:focus {
  outline: none;
  border-color: var(--accent-brown);
  box-shadow: 0 0 0 3px rgba(139, 90, 43, 0.1);
}

/* KPI Section */
.kpi-section {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 20px;
  margin-bottom: 30px;
}

.kpi-card {
  background-color: white;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
  display: flex;
  gap: 15px;
  align-items: flex-start;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.kpi-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.kpi-icon {
  width: 60px;
  height: 60px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 28px;
  color: white;
}

.kpi-icon.revenue {
  background-color: #4caf50;
}

.kpi-icon.orders {
  background-color: #2196f3;
}

.kpi-icon.customers {
  background-color: #ff9800;
}

.kpi-icon.average {
  background-color: #9c27b0;
}

.kpi-content h3 {
  font-size: 14px;
  color: var(--text-light);
  margin: 0 0 8px 0;
  font-weight: 500;
}

.kpi-value {
  font-size: 24px;
  font-weight: bold;
  color: var(--text-dark);
  margin: 0 0 8px 0;
}

.kpi-change {
  font-size: 12px;
  display: flex;
  align-items: center;
  gap: 4px;
}

.kpi-change.positive {
  color: #4caf50;
}

.kpi-change.negative {
  color: #f44336;
}

/* Charts Section */
.charts-section {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
  gap: 20px;
  margin-bottom: 30px;
}

.chart-card {
  background-color: white;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.chart-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.chart-header h3 {
  font-size: 16px;
  color: var(--text-dark);
  margin: 0;
  font-weight: 600;
}

.btn-chart-control {
  background-color: transparent;
  border: 1px solid var(--border-color);
  padding: 6px 12px;
  border-radius: 6px;
  cursor: pointer;
  color: var(--text-light);
  transition: all 0.3s ease;
}

.btn-chart-control:hover {
  background-color: var(--bg-light);
  color: var(--accent-brown);
}

.chart-container {
  position: relative;
  height: 300px;
}

/* Data Section */
.data-section {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
  gap: 20px;
}

.data-card {
  background-color: white;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.data-header {
  margin-bottom: 20px;
}

.data-header h3 {
  font-size: 16px;
  color: var(--text-dark);
  margin: 0;
  font-weight: 600;
}

/* Data Table */
.data-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 14px;
}

.data-table thead {
  background-color: var(--primary-brown);
  color: white;
}

.data-table th {
  padding: 12px;
  text-align: left;
  font-weight: 600;
  border: none;
}

.data-table td {
  padding: 12px;
  border-bottom: 1px solid var(--border-color);
  color: var(--text-dark);
}

.data-table tbody tr:hover {
  background-color: var(--bg-light);
}

.badge-percent {
  background-color: var(--light-brown);
  color: var(--primary-brown);
  padding: 4px 8px;
  border-radius: 4px;
  font-weight: 600;
  font-size: 12px;
}

.status-badge {
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
}

.status-badge.active {
  background-color: #c8e6c9;
  color: #2e7d32;
}

.status-badge.inactive {
  background-color: #ffccbc;
  color: #d84315;
}

/* Responsive */
@media (max-width: 768px) {
  .sidebar {
    width: 70px;
    padding: 10px 0;
  }

  .sidebar-header {
    padding: 0 10px 20px;
  }

  .logo span {
    display: none;
  }

  .nav-item {
    justify-content: center;
    padding: 12px;
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

  .top-bar-right {
    width: 100%;
    justify-content: space-between;
  }

  .content-area {
    padding: 15px;
  }

  .filter-section {
    flex-direction: column;
  }

  .kpi-section {
    grid-template-columns: 1fr;
  }

  .charts-section {
    grid-template-columns: 1fr;
  }

  .data-section {
    grid-template-columns: 1fr;
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
                <a href="thong-ke-bao-cao.html" class="nav-item active">
                    <i class="fas fa-chart-bar"></i>
                    <span>Thống kê báo cáo</span>
                </a>
                <a href="#" class="nav-item">
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
        <div class="main-content">
            <!-- Top Bar -->
            <header class="top-bar">
                <div class="page-title">
                    <h1>Thống kê báo cáo</h1>
                </div>
                <div class="top-bar-right">
                    <span class="admin-info">Admin - Võ Thị Thuỷ Hoa</span>
                    <button class="btn-logout">
                        <i class="fas fa-sign-out-alt"></i> Đăng xuất
                    </button>
                </div>
            </header>

            <!-- Content Area -->
            <div class="content-area">
                <!-- Filter Section -->
                <div class="filter-section">
                    <div class="filter-group">
                        <label>Chọn khoảng thời gian:</label>
                        <select class="form-select">
                            <option>Hôm nay</option>
                            <option>7 ngày qua</option>
                            <option>30 ngày qua</option>
                            <option>Tháng này</option>
                            <option>Năm này</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Chi nhánh:</label>
                        <select class="form-select">
                            <option>Tất cả chi nhánh</option>
                            <option>Chi nhánh 1</option>
                            <option>Chi nhánh 2</option>
                            <option>Chi nhánh 3</option>
                        </select>
                    </div>
                </div>

                <!-- KPI Cards -->
                <div class="kpi-section">
                    <div class="kpi-card">
                        <div class="kpi-icon revenue">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div class="kpi-content">
                            <h3>Doanh thu</h3>
                            <p class="kpi-value">45.250.000 VND</p>
                            <span class="kpi-change positive">
                                <i class="fas fa-arrow-up"></i> +12.5% so với tháng trước
                            </span>
                        </div>
                    </div>

                    <div class="kpi-card">
                        <div class="kpi-icon orders">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="kpi-content">
                            <h3>Tổng đơn hàng</h3>
                            <p class="kpi-value">1.245</p>
                            <span class="kpi-change positive">
                                <i class="fas fa-arrow-up"></i> +8.3% so với tháng trước
                            </span>
                        </div>
                    </div>

                    <div class="kpi-card">
                        <div class="kpi-icon customers">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="kpi-content">
                            <h3>Khách hàng mới</h3>
                            <p class="kpi-value">342</p>
                            <span class="kpi-change positive">
                                <i class="fas fa-arrow-up"></i> +5.2% so với tháng trước
                            </span>
                        </div>
                    </div>

                    <div class="kpi-card">
                        <div class="kpi-icon average">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="kpi-content">
                            <h3>Giá trị đơn trung bình</h3>
                            <p class="kpi-value">36.350 VND</p>
                            <span class="kpi-change negative">
                                <i class="fas fa-arrow-down"></i> -2.1% so với tháng trước
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="charts-section">
                    <!-- Revenue Chart -->
                    <div class="chart-card">
                        <div class="chart-header">
                            <h3>Doanh thu theo ngày</h3>
                            <div class="chart-controls">
                                <button class="btn-chart-control">
                                    <i class="fas fa-download"></i>
                                </button>
                            </div>
                        </div>
                        <div class="chart-container">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>

                    <!-- Orders Chart -->
                    <div class="chart-card">
                        <div class="chart-header">
                            <h3>Số lượng đơn hàng theo ngày</h3>
                            <div class="chart-controls">
                                <button class="btn-chart-control">
                                    <i class="fas fa-download"></i>
                                </button>
                            </div>
                        </div>
                        <div class="chart-container">
                            <canvas id="ordersChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Top Products & Branches -->
                <div class="data-section">
                    <!-- Top Products -->
                    <div class="data-card">
                        <div class="data-header">
                            <h3>Sản phẩm bán chạy nhất</h3>
                        </div>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Số lượng bán</th>
                                    <th>Doanh thu</th>
                                    <th>% Tổng doanh thu</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Cappuccino</td>
                                    <td>245</td>
                                    <td>15.925.000 VND</td>
                                    <td><span class="badge-percent">35.2%</span></td>
                                </tr>
                                <tr>
                                    <td>Cafe Latte</td>
                                    <td>198</td>
                                    <td>12.870.000 VND</td>
                                    <td><span class="badge-percent">28.5%</span></td>
                                </tr>
                                <tr>
                                    <td>Espresso</td>
                                    <td>156</td>
                                    <td>6.084.000 VND</td>
                                    <td><span class="badge-percent">13.5%</span></td>
                                </tr>
                                <tr>
                                    <td>Trà Thạch Đào</td>
                                    <td>142</td>
                                    <td>5.538.000 VND</td>
                                    <td><span class="badge-percent">12.2%</span></td>
                                </tr>
                                <tr>
                                    <td>Tiramisu</td>
                                    <td>98</td>
                                    <td>2.940.000 VND</td>
                                    <td><span class="badge-percent">6.5%</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Branch Performance -->
                    <div class="data-card">
                        <div class="data-header">
                            <h3>Hiệu suất chi nhánh</h3>
                        </div>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Chi nhánh</th>
                                    <th>Doanh thu</th>
                                    <th>Số đơn</th>
                                    <th>Trung bình/đơn</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Chi nhánh 1 - Tây Hồ</td>
                                    <td>18.500.000 VND</td>
                                    <td>512</td>
                                    <td>36.133 VND</td>
                                    <td><span class="status-badge active">Hoạt động</span></td>
                                </tr>
                                <tr>
                                    <td>Chi nhánh 2 - Hoàn Kiếm</td>
                                    <td>16.250.000 VND</td>
                                    <td>445</td>
                                    <td>36.517 VND</td>
                                    <td><span class="status-badge active">Hoạt động</span></td>
                                </tr>
                                <tr>
                                    <td>Chi nhánh 3 - Cầu Giấy</td>
                                    <td>10.500.000 VND</td>
                                    <td>288</td>
                                    <td>36.458 VND</td>
                                    <td><span class="status-badge active">Hoạt động</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script src="thong-ke-bao-cao.js"></script>
</body>
</html>
