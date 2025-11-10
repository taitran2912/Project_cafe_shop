<?php 
include '../../app/controllers/ProductController.php';

$productController = new ProductController();

// Pagination parameters (from query string)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;

// Use controller paginate helper (keeps logic in controller)
$pagination = $productController->paginate($page, $limit);
$products = $pagination['products'];
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
    <title>Quản Lý Quán Cà Phê</title>
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
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f5f1ed;
    color: #3d2817;
    line-height: 1.6;
}

/* Container Layout */
.container {
    display: flex;
    min-height: 100vh;
}



/* Main Content */
.main-content {
    flex: 1;
    margin-left: 260px;
    display: flex;
    flex-direction: column;
}

/* Header */
.header {
    background-color: #ffffff;
    padding: 20px 32px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
    position: sticky;
    top: 0;
    z-index: 100;
}

.menu-toggle {
    display: none;
    background: none;
    border: none;
    font-size: 24px;
    color: #5d4037;
    cursor: pointer;
}

.page-title {
    font-size: 24px;
    color: #5d4037;
    font-weight: 700;
    flex: 1;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 16px;
}

.user-name {
    color: #6d4c41;
    font-size: 14px;
    font-weight: 500;
}

.logout-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background-color: #8d6e63;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    transition: background-color 0.3s ease;
}

.logout-btn:hover {
    background-color: #6d4c41;
}

/* Content Area */
.content {
    padding: 32px;
    flex: 1;
}

/* Action Bar */
.action-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    gap: 16px;
}

.btn {
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-primary {
    background-color: #d7a86e;
    color: #3d2817;
}

.btn-primary:hover {
    background-color: #c49858;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(215, 168, 110, 0.3);
}

.search-box {
    position: relative;
    width: 320px;
}

.search-box input {
    width: 100%;
    padding: 12px 40px 12px 16px;
    border: 2px solid #d7ccc8;
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.3s ease;
}

.search-box input:focus {
    outline: none;
    border-color: #d7a86e;
}

.search-box i {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #8d6e63;
}

/* Table */
.table-container {
    background-color: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
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
    padding: 16px;
    text-align: left;
    font-weight: 600;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.data-table tbody tr {
    border-bottom: 1px solid #efebe9;
    transition: background-color 0.2s ease;
}

.data-table tbody tr:hover {
    background-color: #faf8f6;
}

.data-table td {
    padding: 16px;
    font-size: 14px;
    color: #4e342e;
}

/* Status Badge */
.status {
    display: inline-block;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
}

.status-active {
    background-color: #c8e6c9;
    color: #2e7d32;
}

.status-inactive {
    background-color: #ffcdd2;
    color: #c62828;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 8px;
}

.btn-icon {
    width: 36px;
    height: 36px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    font-size: 14px;
}

.btn-view {
    background-color: #e3f2fd;
    color: #1976d2;
}

.btn-view:hover {
    background-color: #1976d2;
    color: white;
    transform: translateY(-2px);
}

.btn-edit {
    background-color: #fff3e0;
    color: #f57c00;
}

.btn-edit:hover {
    background-color: #f57c00;
    color: white;
    transform: translateY(-2px);
}

.btn-delete {
    background-color: #ffebee;
    color: #d32f2f;
}

.btn-delete:hover {
    background-color: #d32f2f;
    color: white;
    transform: translateY(-2px);
}

/* Pagination */
.pagination {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    margin-top: 24px;
}

.page-btn {
    width: 40px;
    height: 40px;
    border: 2px solid #d7ccc8;
    background-color: white;
    border-radius: 8px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6d4c41;
    transition: all 0.3s ease;
}

.page-btn:hover:not(:disabled) {
    background-color: #d7a86e;
    border-color: #d7a86e;
    color: white;
}

.page-btn:disabled {
    opacity: 0.4;
    cursor: not-allowed;
}

.page-info {
    padding: 0 16px;
    color: #6d4c41;
    font-weight: 500;
    font-size: 14px;
}

/* Responsive */
@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%);
        transition: transform 0.3s ease;
    }
    
    .main-content {
        margin-left: 0;
    }
    
    .menu-toggle {
        display: block;
    }
    
    .action-bar {
        flex-direction: column;
        align-items: stretch;
    }
    
    .search-box {
        width: 100%;
    }
    
    .table-container {
        overflow-x: auto;
    }
    
    .user-name {
        display: none;
    }
}
</style>
<body>
    <div class="container">
        <!-- Sidebar (shared) -->
        <?php include __DIR__ . '/sidebar.php'; ?>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="header">
                <button class="menu-toggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="page-title">Quản lý thực đơn</h1>
                <div class="user-info">
                    <span class="user-name">Admin - Võ Thị Thúy Hoa</span>
                    <button class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        Đăng xuất
                    </button>
                </div>
            </header>

            <!-- Content Area -->
            <div class="content">
                <!-- Action Bar -->
                <div class="action-bar">
                    <button class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Thêm sản phẩm
                    </button>
                    <div class="search-box">
                        <input type="text" placeholder="Tìm kiếm sản phẩm...">
                        <i class="fas fa-search"></i>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Mã sản phẩm</th>
                                <th>Loại sản phẩm</th>
                                <th>Tên sản phẩm</th>
                                <th>Mô tả</th>
                                <th>Giá</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if (!empty($products)) {
                                    foreach($products as $product) {
                                        echo '
                                        <tr>
                                            <td>'.$product['ID'].'</td>
                                            <td>'.$product['ID_category'].'</td>
                                            <td>'.$product['Name'].'</td>
                                            <td>'.$product['Description'].'</td>
                                            <td>'.number_format($product['Price'], 0, ',', '.').' VNĐ</td>
                                            <td>';
                                        if ($product['Status'] == 'active') {
                                            echo '<span class="status status-active">Đang bán</span>';
                                        } else {
                                            echo '<span class="status status-inactive">Ngừng bán</span>';
                                        }
                                        echo '</td>
                                            <td>
                                                <div class="action-buttons">
                                                    <button class="btn-icon btn-view" title="Xem chi tiết">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn-icon btn-edit" title="Sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn-icon btn-delete" title="Xóa">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="7" style="text-align: center; padding: 20px;">Không có sản phẩm nào.</td></tr>';
                                }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="pagination">
                    <?php
                        function page_url($p) {
                            $params = $_GET;
                            $params['page'] = $p;
                            $params['limit'] = $GLOBALS['limit'];
                            return htmlspecialchars($_SERVER['PHP_SELF'] . '?' . http_build_query($params));
                        }

                        // Prev
                        if ($page > 1) {
                            echo '<a class="page-btn" href="' . page_url($page - 1) . '"><i class="fas fa-angle-left"></i></a>';
                        } else {
                            echo '<button class="page-btn" disabled><i class="fas fa-angle-left"></i></button>';
                        }

                        // Pages (simple full list — replace with window logic if needed)
                        for ($p = 1; $p <= $totalPages; $p++) {
                            if ($p == $page) {
                                echo '<button class="page-btn" disabled style="background-color:#d7a86e;border-color:#d7a86e;color:#3d2817;font-weight:600;">' . $p . '</button>';
                            } else {
                                echo '<a class="page-btn" href="' . page_url($p) . '">' . $p . '</a>';
                            }
                        }

                        // Next
                        if ($page < $totalPages) {
                            echo '<a class="page-btn" href="' . page_url($page + 1) . '"><i class="fas fa-angle-right"></i></a>';
                        } else {
                            echo '<button class="page-btn" disabled><i class="fas fa-angle-right"></i></button>';
                        }
                    ?>

                    <span class="page-info">Trang <?php echo $page; ?>/<?php echo $totalPages; ?> — Tổng: <?php echo $totalItems; ?> mục</span>
                </div>
            </div>
        </main>
    </div>
</body>
</html>