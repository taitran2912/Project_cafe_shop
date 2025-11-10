<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý khuyến mãi - Cafe Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="qlkm.css">
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
