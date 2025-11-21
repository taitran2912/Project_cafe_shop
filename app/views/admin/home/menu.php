<?php 
// Form submissions are handled in index.php BEFORE HTML output

// Initialize messages
$successMessage = '';
$errorMessage = '';

// Handle success messages from redirect
if (isset($_GET['success'])) {
    switch ($_GET['success']) {
        case 'add':
            $successMessage = 'Thêm sản phẩm thành công!';
            break;
        case 'edit':
            $successMessage = 'Cập nhật sản phẩm thành công!';
            break;
        case 'delete':
            $successMessage = 'Xóa sản phẩm thành công!';
            break;
    }
}

// Handle search
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
$isSearching = !empty($searchQuery);

// Get products - either from data passed by controller or fetch directly
if (!isset($products)) {
    $productModel = new Product();
    $allProducts = $productModel->getAllProducts();
} else {
    $allProducts = $products;
}

// Filter products if searching
if ($isSearching) {
    $products = array_filter($allProducts, function($product) use ($searchQuery) {
        $searchLower = strtolower($searchQuery);
        return stripos($product['Name'], $searchLower) !== false ||
               stripos($product['Description'], $searchLower) !== false ||
               stripos($product['ID'], $searchLower) !== false;
    });
    $products = array_values($products); // Re-index array
} else {
    $products = $allProducts;
}

?>

            <!-- Content Area -->
    <div class="content">
        <!-- Success/Error Messages -->
        <?php if (!empty($successMessage)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo htmlspecialchars($successMessage); ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <!-- Content Header -->
        <div class="content-header">
            <button class="btn btn-primary" id="btnAddProduct">
                <i class="fas fa-plus"></i>
                Thêm sản phẩm
            </button>
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Tìm kiếm sản phẩm..." value="<?php echo htmlspecialchars($searchQuery); ?>" autocomplete="off">
                <?php if ($isSearching): ?>
                    <button type="button" class="clear-search-btn" id="clearSearchBtn" title="Xóa tìm kiếm">
                        <i class="fas fa-times"></i>
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($isSearching): ?>
            <div class="search-info">
                <i class="fas fa-info-circle"></i>
                Tìm thấy <strong><?php echo count($products); ?></strong> sản phẩm với từ khóa "<strong><?php echo htmlspecialchars($searchQuery); ?></strong>"
            </div>
        <?php endif; ?>

        <!-- Table Container -->
        <div class="table-container">
            <table class="data-table" id="productTable">
                <thead>
                    <tr>
                        <th>Mã SP</th>
                        <th>Loại</th>
                        <th>Tên sản phẩm</th>
                        <th>Mô tả</th>
                        <th>Giá</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody id="productBody">
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
                                            <button class="btn-icon btn-view" title="Xem chi tiết" 
                                                data-id="'.$product['ID'].'"
                                                data-category="'.$product['ID_category'].'"
                                                data-name="'.htmlspecialchars($product['Name']).'"
                                                data-description="'.htmlspecialchars($product['Description']).'"
                                                data-price="'.$product['Price'].'"
                                                data-status="'.$product['Status'].'">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn-icon btn-edit" title="Sửa"
                                                data-id="'.$product['ID'].'"
                                                data-category="'.$product['ID_category'].'"
                                                data-name="'.htmlspecialchars($product['Name']).'"
                                                data-description="'.htmlspecialchars($product['Description']).'"
                                                data-price="'.$product['Price'].'"
                                                data-status="'.$product['Status'].'">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn-icon btn-delete" title="Xóa"
                                                data-id="'.$product['ID'].'"
                                                data-name="'.htmlspecialchars($product['Name']).'">
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
        <div class="pagination" id="pagination"></div>
    </div>


    <!-- Modal Thêm Sản Phẩm -->
    <div id="addProductModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-plus-circle"></i> Thêm Sản Phẩm Mới</h2>
                <button class="close-btn" id="closeModal">&times;</button>
            </div>
            <form id="addProductForm" method="POST" action="" enctype="multipart/form-data">
                <input type="hidden" name="action" value="add_product">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="productCategory">
                            <i class="fas fa-list"></i> Loại sản phẩm <span class="required">*</span>
                        </label>
                        <select id="productCategory" name="category" required>
                            <option value="">-- Chọn loại sản phẩm --</option>
                            <option value="1">Cà phê</option>
                            <option value="2">Trà</option>
                            <option value="3">Sinh tố</option>
                            <option value="4">Bánh ngọt</option>
                            <option value="5">Đồ ăn nhẹ</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="productName">
                            <i class="fas fa-coffee"></i> Tên sản phẩm <span class="required">*</span>
                        </label>
                        <input type="text" id="productName" name="name" placeholder="Nhập tên sản phẩm" required>
                    </div>

                    <div class="form-group">
                        <label for="productPrice">
                            <i class="fas fa-dollar-sign"></i> Giá (VNĐ) <span class="required">*</span>
                        </label>
                        <input type="number" id="productPrice" name="price" placeholder="Nhập giá sản phẩm" min="0" step="1000" required>
                    </div>

                    <div class="form-group">
                        <label for="productStatus">
                            <i class="fas fa-toggle-on"></i> Trạng thái <span class="required">*</span>
                        </label>
                        <select id="productStatus" name="status" required>
                            <option value="active">Đang bán</option>
                            <option value="inactive">Ngừng bán</option>
                        </select>
                    </div>

                    <div class="form-group full-width">
                        <label for="productDescription">
                            <i class="fas fa-align-left"></i> Mô tả
                        </label>
                        <textarea id="productDescription" name="description" rows="3" placeholder="Nhập mô tả sản phẩm"></textarea>
                    </div>

                    <div class="form-group full-width">
                        <label for="productImage">
                            <i class="fas fa-image"></i> Hình ảnh sản phẩm
                        </label>
                        <input type="file" id="productImage" name="image" accept="image/*">
                        <div id="imagePreview" class="image-preview"></div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="cancelBtn">
                        <i class="fas fa-times"></i> Hủy
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Lưu sản phẩm
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Xem Chi Tiết Sản Phẩm -->
    <div id="viewProductModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-info-circle"></i> Chi Tiết Sản Phẩm</h2>
                <button class="close-btn" id="closeViewModal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="detail-grid">
                    <div class="detail-item">
                        <label><i class="fas fa-hashtag"></i> Mã sản phẩm:</label>
                        <span id="view_id"></span>
                    </div>
                    <div class="detail-item">
                        <label><i class="fas fa-list"></i> Loại sản phẩm:</label>
                        <span id="view_category"></span>
                    </div>
                    <div class="detail-item full-width">
                        <label><i class="fas fa-coffee"></i> Tên sản phẩm:</label>
                        <span id="view_name"></span>
                    </div>
                    <div class="detail-item full-width">
                        <label><i class="fas fa-align-left"></i> Mô tả:</label>
                        <span id="view_description"></span>
                    </div>
                    <div class="detail-item">
                        <label><i class="fas fa-dollar-sign"></i> Giá:</label>
                        <span id="view_price"></span>
                    </div>
                    <div class="detail-item">
                        <label><i class="fas fa-toggle-on"></i> Trạng thái:</label>
                        <span id="view_status"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="closeViewBtn">
                    <i class="fas fa-times"></i> Đóng
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Sửa Sản Phẩm -->
    <div id="editProductModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-edit"></i> Sửa Sản Phẩm</h2>
                <button class="close-btn" id="closeEditModal">&times;</button>
            </div>
            <form id="editProductForm" method="POST" action="" enctype="multipart/form-data">
                <input type="hidden" name="action" value="edit_product">
                <input type="hidden" name="product_id" id="edit_product_id">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="editProductCategory">
                            <i class="fas fa-list"></i> Loại sản phẩm <span class="required">*</span>
                        </label>
                        <select id="editProductCategory" name="category" required>
                            <option value="">-- Chọn loại sản phẩm --</option>
                            <option value="1">Cà phê</option>
                            <option value="2">Trà</option>
                            <option value="3">Sinh tố</option>
                            <option value="4">Bánh ngọt</option>
                            <option value="5">Đồ ăn nhẹ</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="editProductName">
                            <i class="fas fa-coffee"></i> Tên sản phẩm <span class="required">*</span>
                        </label>
                        <input type="text" id="editProductName" name="name" placeholder="Nhập tên sản phẩm" required>
                    </div>

                    <div class="form-group">
                        <label for="editProductPrice">
                            <i class="fas fa-dollar-sign"></i> Giá (VNĐ) <span class="required">*</span>
                        </label>
                        <input type="number" id="editProductPrice" name="price" placeholder="Nhập giá sản phẩm" min="0" step="1000" required>
                    </div>

                    <div class="form-group">
                        <label for="editProductStatus">
                            <i class="fas fa-toggle-on"></i> Trạng thái <span class="required">*</span>
                        </label>
                        <select id="editProductStatus" name="status" required>
                            <option value="active">Đang bán</option>
                            <option value="inactive">Ngừng bán</option>
                        </select>
                    </div>

                    <div class="form-group full-width">
                        <label for="editProductDescription">
                            <i class="fas fa-align-left"></i> Mô tả
                        </label>
                        <textarea id="editProductDescription" name="description" rows="3" placeholder="Nhập mô tả sản phẩm"></textarea>
                    </div>

                    <div class="form-group full-width">
                        <label for="editProductImage">
                            <i class="fas fa-image"></i> Hình ảnh sản phẩm (để trống nếu không muốn thay đổi)
                        </label>
                        <input type="file" id="editProductImage" name="image" accept="image/*">
                        <div id="editImagePreview" class="image-preview"></div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="cancelEditBtn">
                        <i class="fas fa-times"></i> Hủy
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Cập nhật
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Xác Nhận Xóa -->
    <div id="deleteConfirmModal" class="modal">
        <div class="modal-content modal-small">
            <div class="modal-header modal-header-danger">
                <h2><i class="fas fa-exclamation-triangle"></i> Xác Nhận Xóa</h2>
                <button class="close-btn" id="closeDeleteModal">&times;</button>
            </div>
            <div class="modal-body">
                <p class="delete-message">
                    Bạn có chắc chắn muốn xóa sản phẩm <strong id="delete_product_name"></strong>?
                </p>
                <p class="delete-warning">
                    <i class="fas fa-info-circle"></i> Hành động này không thể hoàn tác!
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="cancelDeleteBtn">
                    <i class="fas fa-times"></i> Hủy
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="fas fa-trash"></i> Xóa
                </button>
            </div>
        </div>
    </div>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
        }

        .content {
            padding: 30px;
            margin-left: 0;
            background-color: #f8f9fa;
            min-height: 100vh;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 15px;
            animation: slideDown 0.4s ease;
        }

        .alert-success {
            background-color: #d4edda;
            border-left: 4px solid #28a745;
            color: #155724;
        }

        .alert-error {
            background-color: #f8d7da;
            border-left: 4px solid #dc3545;
            color: #721c24;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
            }
            to {
                opacity: 0;
            }
        }

        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            gap: 20px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #d7a86e 0%, #c49856 100%);
            color: #3d2817;
            box-shadow: 0 4px 12px rgba(215, 168, 110, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(215, 168, 110, 0.4);
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .search-box {
            position: relative;
            flex: 0 0 400px;
            max-width: 400px;
        }

        .search-box input {
            width: 100%;
            padding: 12px 45px 12px 45px;
            border: 2px solid #e0e0e0;
            border-radius: 25px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .search-box input:focus {
            outline: none;
            border-color: #d7a86e;
            box-shadow: 0 0 0 3px rgba(215, 168, 110, 0.1);
        }

        .search-box i.fa-search {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        .clear-search-btn {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #999;
            cursor: pointer;
            padding: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .clear-search-btn:hover {
            color: #d7a86e;
        }

        .search-info {
            background-color: #e8f4fd;
            border-left: 4px solid #0288d1;
            padding: 12px 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #01579b;
        }

        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            overflow: hidden;
            margin-bottom: 20px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table thead {
            background: linear-gradient(135deg, #3d2817 0%, #5d3d24 100%);
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
            border-bottom: 1px solid #f0f0f0;
            transition: background-color 0.2s ease;
        }

        .data-table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .data-table td {
            padding: 14px 16px;
            font-size: 14px;
            color: #333;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
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
            padding: 8px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .btn-view {
            background-color: #17a2b8;
            color: white;
        }

        .btn-view:hover {
            background-color: #138496;
            transform: translateY(-2px);
        }

        .btn-edit {
            background-color: #ffc107;
            color: #3d2817;
        }

        .btn-edit:hover {
            background-color: #e0a800;
            transform: translateY(-2px);
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
        }

        .btn-delete:hover {
            background-color: #c82333;
            transform: translateY(-2px);
        }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .page-btn {
            padding: 10px 16px;
            border: 2px solid #d7a86e;
            background-color: white;
            color: #3d2817;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            min-width: 45px;
        }

        .page-btn:hover:not(:disabled) {
            background-color: #d7a86e;
            color: white;
            transform: translateY(-2px);
        }

        .page-btn:disabled {
            cursor: not-allowed;
            opacity: 0.6;
        }

        .page-info {
            margin-left: 15px;
            color: #666;
            font-size: 14px;
            font-weight: 500;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.6);
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal-content {
            background-color: white;
            margin: 3% auto;
            padding: 0;
            border-radius: 16px;
            width: 90%;
            max-width: 700px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            animation: slideDown 0.4s ease;
        }

        .modal-small {
            max-width: 500px;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            background: linear-gradient(135deg, #d7a86e 0%, #c49856 100%);
            color: #3d2817;
            padding: 20px 25px;
            border-radius: 16px 16px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header-danger {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
        }

        .modal-header h2 {
            font-size: 22px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 32px;
            cursor: pointer;
            color: inherit;
            line-height: 1;
            padding: 0;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s ease;
        }

        .close-btn:hover {
            transform: scale(1.2);
        }

        .modal-body {
            padding: 25px;
        }

        .modal-footer {
            padding: 20px 25px;
            border-top: 1px solid #e9ecef;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group.full-width {
            grid-column: span 2;
        }

        .form-group label {
            font-weight: 600;
            margin-bottom: 8px;
            color: #3d2817;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .required {
            color: #dc3545;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #d7a86e;
            box-shadow: 0 0 0 3px rgba(215, 168, 110, 0.1);
        }

        .image-preview {
            margin-top: 12px;
            border: 2px dashed #d7a86e;
            border-radius: 8px;
            padding: 10px;
            min-height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #fafafa;
        }

        .image-preview img {
            max-width: 100%;
            max-height: 200px;
            border-radius: 8px;
            object-fit: contain;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .detail-item.full-width {
            grid-column: span 2;
        }

        .detail-item label {
            font-weight: 600;
            color: #666;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .detail-item span {
            font-size: 15px;
            color: #333;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 6px;
        }

        .delete-message {
            font-size: 16px;
            color: #333;
            margin-bottom: 15px;
            text-align: center;
        }

        .delete-warning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 12px;
            border-radius: 6px;
            color: #856404;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        @media (max-width: 768px) {
            .content {
                padding: 15px;
            }

            .content-header {
                flex-direction: column;
                align-items: stretch;
            }

            .search-box {
                flex: 1;
                max-width: 100%;
            }

            .form-grid,
            .detail-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full-width,
            .detail-item.full-width {
                grid-column: span 1;
            }

            .modal-content {
                width: 95%;
                margin: 10% auto;
            }

            .data-table {
                font-size: 12px;
            }

            .data-table th,
            .data-table td {
                padding: 10px 8px;
            }
        }
    </style>

    <script>
        // Product data from PHP
        const productsData = <?php echo json_encode($products); ?>;
        
        // Category names mapping
        const categoryNames = {
            '1': 'Cà phê',
            '2': 'Trà',
            '3': 'Sinh tố',
            '4': 'Bánh ngọt',
            '5': 'Đồ ăn nhẹ'
        };

        // Pagination settings
        let currentPage = 1;
        const itemsPerPage = 5;
        let totalPages = Math.ceil(productsData.length / itemsPerPage);

        // Display products for current page
        function displayProducts(page) {
            const tbody = document.getElementById('productBody');
            const start = (page - 1) * itemsPerPage;
            const end = start + itemsPerPage;
            const pageProducts = productsData.slice(start, end);

            if (pageProducts.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;padding:20px;">Không có sản phẩm nào</td></tr>';
                return;
            }

            tbody.innerHTML = '';
            pageProducts.forEach(product => {
                const row = document.createElement('tr');
                const categoryName = categoryNames[product.ID_category] || 'N/A';
                const priceFormatted = parseFloat(product.Price).toLocaleString('vi-VN') + ' VNĐ';
                const statusClass = product.Status === 'active' ? 'status-active' : 'status-inactive';
                const statusText = product.Status === 'active' ? 'Đang bán' : 'Ngừng bán';
                const descShort = product.Description && product.Description.length > 50 
                    ? product.Description.substring(0, 50) + '...' 
                    : (product.Description || '');

                row.innerHTML = `
                    <td>${product.ID}</td>
                    <td>${categoryName}</td>
                    <td>${escapeHtml(product.Name)}</td>
                    <td>${escapeHtml(descShort)}</td>
                    <td>${priceFormatted}</td>
                    <td><span class="status-badge ${statusClass}">${statusText}</span></td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-action btn-view" title="Xem chi tiết" 
                                data-id="${product.ID}"
                                data-category="${product.ID_category}"
                                data-name="${escapeHtml(product.Name)}"
                                data-description="${escapeHtml(product.Description || '')}"
                                data-price="${product.Price}"
                                data-status="${product.Status}">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn-action btn-edit" title="Sửa"
                                data-id="${product.ID}"
                                data-category="${product.ID_category}"
                                data-name="${escapeHtml(product.Name)}"
                                data-description="${escapeHtml(product.Description || '')}"
                                data-price="${product.Price}"
                                data-status="${product.Status}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-action btn-delete" title="Xóa"
                                data-id="${product.ID}"
                                data-name="${escapeHtml(product.Name)}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                `;
                tbody.appendChild(row);
            });

            attachEventListeners();
        }

        // Display pagination controls
        function displayPagination() {
            const pagination = document.getElementById('pagination');
            pagination.innerHTML = '';

            if (totalPages === 0) return;

            // Previous button
            const prevBtn = document.createElement('button');
            prevBtn.className = 'page-btn';
            prevBtn.innerHTML = '<i class="fas fa-angle-left"></i>';
            prevBtn.disabled = currentPage === 1;
            prevBtn.onclick = () => {
                if (currentPage > 1) {
                    currentPage--;
                    displayProducts(currentPage);
                    displayPagination();
                }
            };
            pagination.appendChild(prevBtn);

            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                const pageBtn = document.createElement('button');
                pageBtn.className = 'page-btn';
                pageBtn.textContent = i;
                if (i === currentPage) {
                    pageBtn.disabled = true;
                    pageBtn.style.cssText = 'background-color:#d7a86e;border-color:#d7a86e;color:#3d2817;font-weight:600;';
                }
                pageBtn.onclick = () => {
                    currentPage = i;
                    displayProducts(currentPage);
                    displayPagination();
                };
                pagination.appendChild(pageBtn);
            }

            // Next button
            const nextBtn = document.createElement('button');
            nextBtn.className = 'page-btn';
            nextBtn.innerHTML = '<i class="fas fa-angle-right"></i>';
            nextBtn.disabled = currentPage === totalPages;
            nextBtn.onclick = () => {
                if (currentPage < totalPages) {
                    currentPage++;
                    displayProducts(currentPage);
                    displayPagination();
                }
            };
            pagination.appendChild(nextBtn);

            // Page info
            const pageInfo = document.createElement('span');
            pageInfo.className = 'page-info';
            pageInfo.textContent = totalPages > 0 
                ? `Trang ${currentPage}/${totalPages} — Tổng: ${productsData.length} mục`
                : 'Không có sản phẩm';
            pagination.appendChild(pageInfo);
        }

        // Escape HTML
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Attach event listeners to buttons
        function attachEventListeners() {
            // View buttons
            document.querySelectorAll('.btn-view').forEach(btn => {
                btn.onclick = function() {
                    const category = this.dataset.category;
                    document.getElementById('view_id').textContent = this.dataset.id;
                    document.getElementById('view_category').textContent = categoryNames[category] || 'N/A';
                    document.getElementById('view_name').textContent = this.dataset.name;
                    document.getElementById('view_description').textContent = this.dataset.description || 'Không có mô tả';
                    document.getElementById('view_price').textContent = parseFloat(this.dataset.price).toLocaleString('vi-VN') + ' VNĐ';
                    
                    const statusSpan = document.getElementById('view_status');
                    if (this.dataset.status === 'active') {
                        statusSpan.innerHTML = '<span class="status-badge status-active">Đang bán</span>';
                    } else {
                        statusSpan.innerHTML = '<span class="status-badge status-inactive">Ngừng bán</span>';
                    }

                    viewModal.style.display = 'block';
                    document.body.style.overflow = 'hidden';
                };
            });

            // Edit buttons
            document.querySelectorAll('.btn-edit').forEach(btn => {
                btn.onclick = function() {
                    document.getElementById('edit_product_id').value = this.dataset.id;
                    document.getElementById('editProductCategory').value = this.dataset.category;
                    document.getElementById('editProductName').value = this.dataset.name;
                    document.getElementById('editProductDescription').value = this.dataset.description;
                    document.getElementById('editProductPrice').value = this.dataset.price;
                    document.getElementById('editProductStatus').value = this.dataset.status;

                    editModal.style.display = 'block';
                    document.body.style.overflow = 'hidden';
                };
            });

            // Delete buttons
            document.querySelectorAll('.btn-delete').forEach(btn => {
                btn.onclick = function() {
                    deleteProductId = this.dataset.id;
                    document.getElementById('delete_product_name').textContent = this.dataset.name;
                    deleteModal.style.display = 'block';
                    document.body.style.overflow = 'hidden';
                };
            });
        }

        // Modal elements
        const modal = document.getElementById('addProductModal');
        const viewModal = document.getElementById('viewProductModal');
        const editModal = document.getElementById('editProductModal');
        const deleteModal = document.getElementById('deleteConfirmModal');
        let deleteProductId = null;

        // Add product button
        document.getElementById('btnAddProduct').onclick = () => {
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        };

        // Close modals
        function closeModal() {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
            document.getElementById('addProductForm').reset();
            document.getElementById('imagePreview').innerHTML = '';
        }

        function closeViewModal() {
            viewModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        function closeEditModal() {
            editModal.style.display = 'none';
            document.body.style.overflow = 'auto';
            document.getElementById('editProductForm').reset();
            document.getElementById('editImagePreview').innerHTML = '';
        }

        function closeDeleteModal() {
            deleteModal.style.display = 'none';
            document.body.style.overflow = 'auto';
            deleteProductId = null;
        }

        document.getElementById('closeModal').onclick = closeModal;
        document.getElementById('cancelBtn').onclick = closeModal;
        document.getElementById('closeViewModal').onclick = closeViewModal;
        document.getElementById('closeViewBtn').onclick = closeViewModal;
        document.getElementById('closeEditModal').onclick = closeEditModal;
        document.getElementById('cancelEditBtn').onclick = closeEditModal;
        document.getElementById('closeDeleteModal').onclick = closeDeleteModal;
        document.getElementById('cancelDeleteBtn').onclick = closeDeleteModal;

        // Confirm delete
        document.getElementById('confirmDeleteBtn').onclick = function() {
            if (deleteProductId) {
                const currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('action', 'delete');
                currentUrl.searchParams.set('id', deleteProductId);
                window.location.href = currentUrl.toString();
            }
        };

        // Close modal when clicking outside
        window.onclick = (e) => {
            if (e.target === modal) closeModal();
            if (e.target === viewModal) closeViewModal();
            if (e.target === editModal) closeEditModal();
            if (e.target === deleteModal) closeDeleteModal();
        };

        // Image preview for add form
        document.getElementById('productImage').onchange = function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('imagePreview');
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                };
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = '';
            }
        };

        // Image preview for edit form
        document.getElementById('editProductImage').onchange = function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('editImagePreview');
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                };
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = '';
            }
        };

        // Form validation
        document.getElementById('addProductForm').onsubmit = (e) => {
            const name = document.getElementById('productName').value.trim();
            const category = document.getElementById('productCategory').value;
            const price = document.getElementById('productPrice').value;

            if (!name) {
                e.preventDefault();
                alert('Vui lòng nhập tên sản phẩm!');
                return false;
            }
            if (!category) {
                e.preventDefault();
                alert('Vui lòng chọn loại sản phẩm!');
                return false;
            }
            if (!price || price <= 0) {
                e.preventDefault();
                alert('Vui lòng nhập giá sản phẩm hợp lệ!');
                return false;
            }
            return true;
        };

        document.getElementById('editProductForm').onsubmit = (e) => {
            const name = document.getElementById('editProductName').value.trim();
            const category = document.getElementById('editProductCategory').value;
            const price = document.getElementById('editProductPrice').value;

            if (!name) {
                e.preventDefault();
                alert('Vui lòng nhập tên sản phẩm!');
                return false;
            }
            if (!category) {
                e.preventDefault();
                alert('Vui lòng chọn loại sản phẩm!');
                return false;
            }
            if (!price || price <= 0) {
                e.preventDefault();
                alert('Vui lòng nhập giá sản phẩm hợp lệ!');
                return false;
            }
            return true;
        };

        // Search functionality (page reload)
        const searchInput = document.getElementById('searchInput');
        const clearSearchBtn = document.getElementById('clearSearchBtn');

        searchInput.onkeypress = (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                const query = searchInput.value.trim();
                window.location.href = query ? `?search=${encodeURIComponent(query)}` : window.location.pathname;
            }
        };

        if (clearSearchBtn) {
            clearSearchBtn.onclick = () => {
                window.location.href = window.location.pathname;
            };
        }

        // Auto-hide alerts
        document.addEventListener('DOMContentLoaded', () => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.animation = 'fadeOut 0.5s ease';
                    setTimeout(() => alert.style.display = 'none', 500);
                }, 5000);
            });

            // Keep modal open if error
            <?php if (!empty($errorMessage)): ?>
                modal.style.display = 'block';
                document.body.style.overflow = 'hidden';
            <?php endif; ?>
        });

        // Initialize on page load
        displayProducts(currentPage);
        displayPagination();
    </script>
</body>
</html>