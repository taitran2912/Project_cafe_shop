    <!-- Content Header -->
    <div class="content-header">
        <button class="btn btn-primary" id="btnAddProduct">
            <i class="fas fa-plus"></i> Thêm sản phẩm
        </button>
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Tìm kiếm sản phẩm..." 
                   value="<?= isset($searchQuery) ? htmlspecialchars($searchQuery) : '' ?>" autocomplete="off">
            <?php if (isset($isSearching) && $isSearching): ?>
                <button type="button" class="clear-search-btn" id="clearSearchBtn" title="Xóa tìm kiếm">
                    <i class="fas fa-times"></i>
                </button>
            <?php endif; ?>
        </div>
    </div>

    <?php if (isset($isSearching) && $isSearching && !empty($products)): ?>
        <div class="search-info">
            <i class="fas fa-info-circle"></i>
            Tìm thấy <strong><?= count($products) ?></strong> sản phẩm với từ khóa "<strong><?= htmlspecialchars($searchQuery) ?></strong>"
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
            <tbody id="productBody"></tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="pagination" id="pagination"></div>

    <!-- Modals -->
    <?php include 'modals/menuModal.php'; ?>

</div>

<!-- Embed product data for JavaScript -->
<script>
    const productsData = <?= json_encode(isset($products) ? $products : []) ?>;
    const BASE_URL = '<?= BASE_URL ?>';
    const hasErrorMessage = <?= (isset($errorMessage) && !empty($errorMessage)) ? 'true' : 'false' ?>;

    // ================== Khai báo biến ==================
    let currentCategory = 'all';
    let displayedItems = 0;
    const itemsPerLoad = 9;

    // ================== Hàm hiển thị sản phẩm ==================
    function formatPrice(price) {
        return new Intl.NumberFormat('vi-VN').format(price) + 'đ';
    }

    function createProductRow(item) {
        return `
            <tr>
                <td>${item.ID}</td>
                <td>${item.Name_Category}</td>
                <td>${item.Name}</td>
                <td>${item.Description}</td>
                <td>${formatPrice(item.Price)}</td>
                <td>${item.Status || 'Đang bán'}</td>
                <td>
                    <button class="btn btn-info view-btn" data-id="${item.ID}">Xem</button>
                    <button class="btn btn-warning edit-btn" data-id="${item.ID}">Sửa</button>
                    <button class="btn btn-danger delete-btn" data-id="${item.ID}" data-name="${item.Name}">Xóa</button>
                </td>
            </tr>
        `;
    }

    function loadProducts() {
        const tbody = document.getElementById('productBody');
        tbody.innerHTML = '';
        productsData.forEach(product => {
            tbody.innerHTML += createProductRow(product);
        });
        addProductEventListeners();
    }

    // ================== Event listeners cho nút ==================
    function addProductEventListeners() {
        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                const product = productsData.find(p => p.ID == id);
                if (product) {
                    document.getElementById('view_id').textContent = product.ID;
                    document.getElementById('view_category').textContent = product.Name_Category;
                    document.getElementById('view_name').textContent = product.Name;
                    document.getElementById('view_description').textContent = product.Description;
                    document.getElementById('view_price').textContent = formatPrice(product.Price);
                    document.getElementById('view_status').textContent = product.Status || 'Đang bán';
                    document.getElementById('viewProductModal').style.display = 'block';
                }
            });
        });

        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const name = btn.dataset.name;
                const id = btn.dataset.id;
                document.getElementById('delete_product_name').textContent = name;
                document.getElementById('confirmDeleteBtn').dataset.id = id;
                document.getElementById('deleteConfirmModal').style.display = 'block';
            });
        });
    }

    // ================== Khởi tạo ==================
    document.addEventListener('DOMContentLoaded', () => {
        loadProducts();

        // Modal close events
        document.querySelectorAll('.close-btn, #cancelBtn, #cancelEditBtn, #closeViewBtn, #cancelDeleteBtn')
            .forEach(btn => btn.addEventListener('click', () => {
                btn.closest('.modal').style.display = 'none';
            }));
    });
</script>

