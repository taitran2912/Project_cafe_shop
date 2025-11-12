<?php 

// Initialize messages
$successMessage = '';
$errorMessage = '';

// Handle form submission for adding product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_product') {
    // Prepare data from form
    $productData = [
        'ID_category' => isset($_POST['category']) ? (int)$_POST['category'] : null,
        'Name' => isset($_POST['name']) ? trim($_POST['name']) : '',
        'Description' => isset($_POST['description']) ? trim($_POST['description']) : '',
        'Price' => isset($_POST['price']) ? (float)$_POST['price'] : 0,
        'Status' => isset($_POST['status']) ? trim($_POST['status']) : 'active',
        'Image' => ''
    ];

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../../../../../public/image/products/';
        
        // Create directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($fileExtension, $allowedExtensions)) {
            $newFileName = uniqid('product_') . '.' . $fileExtension;
            $uploadPath = $uploadDir . $newFileName;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                $productData['Image'] = 'public/image/products/' . $newFileName;
            }
        }
    }

    // Validate required fields
    if (empty($productData['Name'])) {
        $errorMessage = 'Tên sản phẩm không được để trống!';
    } elseif ($productData['ID_category'] === null || $productData['ID_category'] <= 0) {
        $errorMessage = 'Vui lòng chọn loại sản phẩm!';
    } elseif ($productData['Price'] <= 0) {
        $errorMessage = 'Giá sản phẩm phải lớn hơn 0!';
    } else {
        // Call controller to store product
        $result = $productController->store($productData);
        
        if ($result) {
            $successMessage = 'Thêm sản phẩm thành công!';
            // Redirect to avoid resubmission
            header('Location: ' . $_SERVER['PHP_SELF'] . '?success=1');
            exit;
        } else {
            $errorMessage = 'Có lỗi xảy ra khi thêm sản phẩm. Vui lòng thử lại!';
        }
    }
}

// Handle form submission for updating product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit_product') {
    $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    
    if ($productId > 0) {
        $productData = [
            'ID_category' => isset($_POST['category']) ? (int)$_POST['category'] : null,
            'Name' => isset($_POST['name']) ? trim($_POST['name']) : '',
            'Description' => isset($_POST['description']) ? trim($_POST['description']) : '',
            'Price' => isset($_POST['price']) ? (float)$_POST['price'] : 0,
            'Status' => isset($_POST['status']) ? trim($_POST['status']) : 'active'
        ];

        // Handle image upload for edit
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../../../../public/image/products/';
            
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            
            if (in_array($fileExtension, $allowedExtensions)) {
                $newFileName = uniqid('product_') . '.' . $fileExtension;
                $uploadPath = $uploadDir . $newFileName;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                    $productData['Image'] = 'public/image/products/' . $newFileName;
                }
            }
        }

        // Validate
        if (empty($productData['Name'])) {
            $errorMessage = 'Tên sản phẩm không được để trống!';
        } elseif ($productData['Price'] <= 0) {
            $errorMessage = 'Giá sản phẩm phải lớn hơn 0!';
        } else {
            $result = $productController->update($productId, $productData);
            
            if ($result) {
                header('Location: ' . $_SERVER['PHP_SELF'] . '?success=2');
                exit;
            } else {
                $errorMessage = 'Có lỗi xảy ra khi cập nhật sản phẩm!';
            }
        }
    }
}

// Handle delete product
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $productId = (int)$_GET['id'];
    if ($productId > 0) {
        $result = $productController->delete($productId);
        if ($result) {
            header('Location: ' . $_SERVER['PHP_SELF'] . '?success=3');
            exit;
        } else {
            $errorMessage = 'Có lỗi xảy ra khi xóa sản phẩm!';
        }
    }
}

// Check for success message from redirect
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $successMessage = 'Thêm sản phẩm thành công!';
} elseif (isset($_GET['success']) && $_GET['success'] == 2) {
    $successMessage = 'Cập nhật sản phẩm thành công!';
} elseif (isset($_GET['success']) && $_GET['success'] == 3) {
    $successMessage = 'Xóa sản phẩm thành công!';
}

// Handle search
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
$isSearching = !empty($searchQuery);

// Handle search
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
$isSearching = !empty($searchQuery);

// Pagination parameters (from query string)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;

// Use controller paginate helper or search
if ($isSearching) {
    $pagination = $productController->search($searchQuery, $page, $limit);
} else {
    $pagination = $productController->paginate($page, $limit);
}

$products = $pagination['products'];
$totalItems = $pagination['totalItems'];
$totalPages = $pagination['totalPages'];
$page = $pagination['currentPage'];
$limit = $pagination['limit'];

?>

            <!-- Content Area -->
    <div class="content">
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

        <!-- Action Bar -->
        <div class="action-bar">
            <button class="btn btn-primary" id="btnAddProduct">
                <i class="fas fa-plus"></i>
                Thêm sản phẩm
            </button>
            <div class="search-form">
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Tìm kiếm sản phẩm..." value="<?php echo htmlspecialchars($searchQuery); ?>" autocomplete="off">
                    <button type="button" class="search-btn" id="searchBtn">
                        <i class="fas fa-search"></i>
                    </button>
                    <button type="button" class="clear-search-btn" id="clearSearchBtn" style="display: none;" title="Xóa tìm kiếm">
                        <i class="fas fa-times"></i>
                    </button>
                    <div class="search-loading" id="searchLoading" style="display: none;">
                        <i class="fas fa-spinner fa-spin"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="search-info" id="searchInfo" style="display: none;">
            <i class="fas fa-info-circle"></i>
            <span id="searchInfoText"></span>
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
        <div class="pagination">
            <?php
                function page_url($p) {
                    $params = $_GET;
                    $params['page'] = $p;
                    $params['limit'] = $GLOBALS['limit'];
                    return htmlspecialchars($_SERVER['PHP_SELF'] . '?' . http_build_query($params));
                }

                if ($totalPages > 0) {
                    if ($page > 1) {
                        echo '<a class="page-btn" href="' . page_url($page - 1) . '"><i class="fas fa-angle-left"></i></a>';
                    } else {
                        echo '<button class="page-btn" disabled><i class="fas fa-angle-left"></i></button>';
                    }

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
                }
            ?>

            <span class="page-info">
                <?php if ($totalPages > 0): ?>
                    Trang <?php echo $page; ?>/<?php echo $totalPages; ?> — Tổng: <?php echo $totalItems; ?> mục
                <?php else: ?>
                    Không có sản phẩm
                <?php endif; ?>
            </span>
        </div>
    </div>


    <!-- Modal Thêm Sản Phẩm -->
    <div id="addProductModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-plus-circle"></i> Thêm Sản Phẩm Mới</h2>
                <button class="close-btn" id="closeModal">&times;</button>
            </div>
            <form id="addProductForm" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data">
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
            <form id="editProductForm" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data">
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

    <script>
        // Realtime Search Variables
        let searchTimeout = null;
        let currentSearchQuery = '';
        let currentPage = 1;
        const searchDelay = 500; // milliseconds

        const searchInput = document.getElementById('searchInput');
        const searchBtn = document.getElementById('searchBtn');
        const clearSearchBtn = document.getElementById('clearSearchBtn');
        const searchLoading = document.getElementById('searchLoading');
        const searchInfo = document.getElementById('searchInfo');
        const searchInfoText = document.getElementById('searchInfoText');

        // Category names mapping
        const categoryNames = {
            '1': 'Cà phê',
            '2': 'Trà',
            '3': 'Sinh tố',
            '4': 'Bánh ngọt',
            '5': 'Đồ ăn nhẹ'
        };

        // Realtime search on input
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            
            // Show/hide clear button
            if (query.length > 0) {
                clearSearchBtn.style.display = 'flex';
            } else {
                clearSearchBtn.style.display = 'none';
            }

            // Clear previous timeout
            if (searchTimeout) {
                clearTimeout(searchTimeout);
            }

            // Set new timeout for search
            searchTimeout = setTimeout(() => {
                performSearch(query, 1);
            }, searchDelay);
        });

        // Search button click
        searchBtn.addEventListener('click', function() {
            const query = searchInput.value.trim();
            performSearch(query, 1);
        });

        // Clear search button
        clearSearchBtn.addEventListener('click', function() {
            searchInput.value = '';
            clearSearchBtn.style.display = 'none';
            performSearch('', 1);
        });

        // Search on Enter key
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const query = this.value.trim();
                performSearch(query, 1);
            }
        });

        // Perform search via AJAX
        function performSearch(query, page = 1) {
            currentSearchQuery = query;
            currentPage = page;

            // Show loading
            searchLoading.style.display = 'flex';

            // Build URL
            const url = `search_api.php?q=${encodeURIComponent(query)}&page=${page}&limit=5`;

            // Fetch data
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    searchLoading.style.display = 'none';

                    if (data.success) {
                        updateProductTable(data.products);
                        updatePagination(data.pagination);
                        updateSearchInfo(query, data.pagination.totalItems);
                    } else {
                        console.error('Search error:', data.error);
                        alert('Có lỗi xảy ra khi tìm kiếm!');
                    }
                })
                .catch(error => {
                    searchLoading.style.display = 'none';
                    console.error('Fetch error:', error);
                    alert('Không thể kết nối đến server!');
                });
        }

        // Update product table
        function updateProductTable(products) {
            const tbody = document.querySelector('.data-table tbody');
            
            if (products.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 20px;">Không tìm thấy sản phẩm nào.</td></tr>';
                return;
            }

            let html = '';
            products.forEach(product => {
                const statusClass = product.status === 'active' ? 'status-active' : 'status-inactive';
                const statusText = product.status === 'active' ? 'Đang bán' : 'Ngừng bán';

                html += `
                    <tr>
                        <td>${product.id}</td>
                        <td>${product.categoryName}</td>
                        <td>${escapeHtml(product.name)}</td>
                        <td>${escapeHtml(product.description || '')}</td>
                        <td>${product.priceFormatted}</td>
                        <td><span class="status ${statusClass}">${statusText}</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view" title="Xem chi tiết"
                                    data-id="${product.id}"
                                    data-category="${product.category}"
                                    data-name="${escapeHtml(product.name)}"
                                    data-description="${escapeHtml(product.description || '')}"
                                    data-price="${product.price}"
                                    data-status="${product.status}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-icon btn-edit" title="Sửa"
                                    data-id="${product.id}"
                                    data-category="${product.category}"
                                    data-name="${escapeHtml(product.name)}"
                                    data-description="${escapeHtml(product.description || '')}"
                                    data-price="${product.price}"
                                    data-status="${product.status}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-icon btn-delete" title="Xóa"
                                    data-id="${product.id}"
                                    data-name="${escapeHtml(product.name)}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });

            tbody.innerHTML = html;

            // Reattach event listeners
            attachActionButtonListeners();
        }

        // Update pagination
        function updatePagination(pagination) {
            const paginationDiv = document.querySelector('.pagination');
            const { totalItems, totalPages, currentPage, limit } = pagination;

            let html = '';

            if (totalPages > 0) {
                // Previous button
                if (currentPage > 1) {
                    html += `<button class="page-btn" onclick="performSearch('${currentSearchQuery}', ${currentPage - 1})"><i class="fas fa-angle-left"></i></button>`;
                } else {
                    html += '<button class="page-btn" disabled><i class="fas fa-angle-left"></i></button>';
                }

                // Page numbers
                for (let p = 1; p <= totalPages; p++) {
                    if (p === currentPage) {
                        html += `<button class="page-btn" disabled style="background-color:#d7a86e;border-color:#d7a86e;color:#3d2817;font-weight:600;">${p}</button>`;
                    } else {
                        html += `<button class="page-btn" onclick="performSearch('${currentSearchQuery}', ${p})">${p}</button>`;
                    }
                }

                // Next button
                if (currentPage < totalPages) {
                    html += `<button class="page-btn" onclick="performSearch('${currentSearchQuery}', ${currentPage + 1})"><i class="fas fa-angle-right"></i></button>`;
                } else {
                    html += '<button class="page-btn" disabled><i class="fas fa-angle-right"></i></button>';
                }
            }

            // Page info
            html += '<span class="page-info">';
            if (totalPages > 0) {
                html += `Trang ${currentPage}/${totalPages} — Tổng: ${totalItems} mục`;
            } else {
                html += 'Không có sản phẩm';
            }
            html += '</span>';

            paginationDiv.innerHTML = html;
        }

        // Update search info
        function updateSearchInfo(query, totalItems) {
            if (query.length > 0) {
                searchInfoText.innerHTML = `Tìm thấy <strong>${totalItems}</strong> sản phẩm với từ khóa "<strong>${escapeHtml(query)}</strong>"`;
                searchInfo.style.display = 'flex';
            } else {
                searchInfo.style.display = 'none';
            }
        }

        // Escape HTML to prevent XSS
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Attach event listeners to action buttons
        function attachActionButtonListeners() {
            // View buttons
            document.querySelectorAll('.btn-view').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const category = this.dataset.category;
                    const name = this.dataset.name;
                    const description = this.dataset.description;
                    const price = parseFloat(this.dataset.price);
                    const status = this.dataset.status;

                    document.getElementById('view_id').textContent = id;
                    document.getElementById('view_category').textContent = categoryNames[category] || 'N/A';
                    document.getElementById('view_name').textContent = name;
                    document.getElementById('view_description').textContent = description || 'Không có mô tả';
                    document.getElementById('view_price').textContent = price.toLocaleString('vi-VN') + ' VNĐ';
                    
                    const statusSpan = document.getElementById('view_status');
                    if (status === 'active') {
                        statusSpan.innerHTML = '<span class="status status-active">Đang bán</span>';
                    } else {
                        statusSpan.innerHTML = '<span class="status status-inactive">Ngừng bán</span>';
                    }

                    viewModal.style.display = 'block';
                    document.body.style.overflow = 'hidden';
                });
            });

            // Edit buttons
            document.querySelectorAll('.btn-edit').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const category = this.dataset.category;
                    const name = this.dataset.name;
                    const description = this.dataset.description;
                    const price = this.dataset.price;
                    const status = this.dataset.status;

                    document.getElementById('edit_product_id').value = id;
                    document.getElementById('editProductCategory').value = category;
                    document.getElementById('editProductName').value = name;
                    document.getElementById('editProductDescription').value = description;
                    document.getElementById('editProductPrice').value = price;
                    document.getElementById('editProductStatus').value = status;

                    editModal.style.display = 'block';
                    document.body.style.overflow = 'hidden';
                });
            });

            // Delete buttons
            document.querySelectorAll('.btn-delete').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const name = this.dataset.name;

                    deleteProductId = id;
                    document.getElementById('delete_product_name').textContent = name;

                    deleteModal.style.display = 'block';
                    document.body.style.overflow = 'hidden';
                });
            });
        }

        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', () => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.animation = 'fadeOut 0.5s ease';
                    setTimeout(() => {
                        alert.style.display = 'none';
                    }, 500);
                }, 5000);
            });

            // If there's an error, keep modal open
            <?php if (!empty($errorMessage)): ?>
                modal.style.display = 'block';
                document.body.style.overflow = 'hidden';
            <?php endif; ?>

            // Show clear button if there's initial search query
            <?php if (!empty($searchQuery)): ?>
                clearSearchBtn.style.display = 'flex';
            <?php endif; ?>

            // Attach initial event listeners
            attachActionButtonListeners();
        });

        // Modal handling
        const modal = document.getElementById('addProductModal');
        const viewModal = document.getElementById('viewProductModal');
        const editModal = document.getElementById('editProductModal');
        const deleteModal = document.getElementById('deleteConfirmModal');
        const btnAdd = document.getElementById('btnAddProduct');
        const btnClose = document.getElementById('closeModal');
        const btnCancel = document.getElementById('cancelBtn');
        const form = document.getElementById('addProductForm');

        // View modal elements
        const btnCloseView = document.getElementById('closeViewModal');
        const btnCloseViewBtn = document.getElementById('closeViewBtn');

        // Edit modal elements
        const btnCloseEdit = document.getElementById('closeEditModal');
        const btnCancelEdit = document.getElementById('cancelEditBtn');
        const editForm = document.getElementById('editProductForm');

        // Delete modal elements
        const btnCloseDelete = document.getElementById('closeDeleteModal');
        const btnCancelDelete = document.getElementById('cancelDeleteBtn');
        const btnConfirmDelete = document.getElementById('confirmDeleteBtn');
        let deleteProductId = null;

        // Open add modal
        btnAdd.addEventListener('click', () => {
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        });

        // Close add modal
        function closeModal() {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
            form.reset();
            document.getElementById('imagePreview').innerHTML = '';
        }

        btnClose.addEventListener('click', closeModal);
        btnCancel.addEventListener('click', closeModal);

        // Close view modal
        function closeViewModal() {
            viewModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        btnCloseView.addEventListener('click', closeViewModal);
        btnCloseViewBtn.addEventListener('click', closeViewModal);

        // Close edit modal
        function closeEditModal() {
            editModal.style.display = 'none';
            document.body.style.overflow = 'auto';
            editForm.reset();
            document.getElementById('editImagePreview').innerHTML = '';
        }

        btnCloseEdit.addEventListener('click', closeEditModal);
        btnCancelEdit.addEventListener('click', closeEditModal);

        // Close delete modal
        function closeDeleteModal() {
            deleteModal.style.display = 'none';
            document.body.style.overflow = 'auto';
            deleteProductId = null;
        }

        btnCloseDelete.addEventListener('click', closeDeleteModal);
        btnCancelDelete.addEventListener('click', closeDeleteModal);

        // Confirm delete
        btnConfirmDelete.addEventListener('click', function() {
            if (deleteProductId) {
                const params = new URLSearchParams(window.location.search);
                params.set('action', 'delete');
                params.set('id', deleteProductId);
                window.location.href = window.location.pathname + '?' + params.toString();
            }
        });

        // Close modal when clicking outside
        window.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal();
            }
            if (e.target === viewModal) {
                closeViewModal();
            }
            if (e.target === editModal) {
                closeEditModal();
            }
            if (e.target === deleteModal) {
                closeDeleteModal();
            }
        });

        // Image preview for add form
        document.getElementById('productImage').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('imagePreview');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                }
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = '';
            }
        });

        // Image preview for edit form
        document.getElementById('editProductImage').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('editImagePreview');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                }
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = '';
            }
        });

        // Form submission for add
        form.addEventListener('submit', (e) => {
            // Validate form
            const productName = document.getElementById('productName').value.trim();
            const productCategory = document.getElementById('productCategory').value;
            const productPrice = document.getElementById('productPrice').value;

            if (!productName) {
                e.preventDefault();
                alert('Vui lòng nhập tên sản phẩm!');
                return false;
            }

            if (!productCategory) {
                e.preventDefault();
                alert('Vui lòng chọn loại sản phẩm!');
                return false;
            }

            if (!productPrice || productPrice <= 0) {
                e.preventDefault();
                alert('Vui lòng nhập giá sản phẩm hợp lệ!');
                return false;
            }

            // If validation passes, submit the form
            return true;
        });

        // Form submission for edit
        editForm.addEventListener('submit', (e) => {
            const productName = document.getElementById('editProductName').value.trim();
            const productCategory = document.getElementById('editProductCategory').value;
            const productPrice = document.getElementById('editProductPrice').value;

            if (!productName) {
                e.preventDefault();
                alert('Vui lòng nhập tên sản phẩm!');
                return false;
            }

            if (!productCategory) {
                e.preventDefault();
                alert('Vui lòng chọn loại sản phẩm!');
                return false;
            }

            if (!productPrice || productPrice <= 0) {
                e.preventDefault();
                alert('Vui lòng nhập giá sản phẩm hợp lệ!');
                return false;
            }

            return true;
        });

        // Sidebar toggle (mobile)
        const menuToggle = document.querySelector('.menu-toggle');
        const sidebar = document.querySelector('.sidebar');
        
        if (menuToggle) {
            menuToggle.addEventListener('click', () => {
                sidebar.classList.toggle('active');
            });
        }
    </script>
</body>
</html>