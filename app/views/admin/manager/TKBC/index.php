<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thống kê báo cáo - Cafe Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="tkbc.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <?php include __DIR__ . '/../../../layout/sidebar.php'; ?>

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
