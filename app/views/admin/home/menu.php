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

<script>
const productsData = <?= json_encode(isset($products) ? $products : []) ?>;

// ========== Format giá ==========
function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN').format(price) + 'đ';
}

// ========== Render bảng ==========
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
                <button class="btn btn-info view-btn" 
                        data-id="${item.ID}"
                        data-name="${item.Name}"
                        data-category="${item.Name_Category}"
                        data-price="${item.Price}"
                        data-status="${item.Status || 'active'}"
                        data-description="${item.Description}">
                    Xem
                </button>
                <button class="btn btn-warning edit-btn" 
                        data-id="${item.ID}"
                        data-name="${item.Name}"
                        data-category="${item.CategoryID}"
                        data-price="${item.Price}"
                        data-status="${item.Status || 'active'}"
                        data-description="${item.Description}">
                    Sửa
                </button>
                <button class="btn btn-danger delete-btn" 
                        data-id="${item.ID}" 
                        data-name="${item.Name}">
                    Xóa
                </button>
            </td>
        </tr>
    `;
}

function loadProducts() {
    const tbody = document.getElementById('productBody');
    tbody.innerHTML = productsData.map(createProductRow).join('');
}

// ========== Event delegation cho bảng ==========
document.getElementById('productBody').addEventListener('click', e => {
    const target = e.target;

    // Xem sản phẩm
    if (target.classList.contains('view-btn')) {
        const product = productsData.find(p => p.ID == target.dataset.id);
        if (product) {
            document.getElementById('view_id').textContent = product.ID;
            document.getElementById('view_category').textContent = product.Name_Category;
            document.getElementById('view_name').textContent = product.Name;
            document.getElementById('view_description').textContent = product.Description;
            document.getElementById('view_price').textContent = formatPrice(product.Price);
            document.getElementById('view_status').textContent = product.Status || 'Đang bán';
            document.getElementById('viewProductModal').style.display = 'block';
        }
    }

    // Sửa sản phẩm
    if (target.classList.contains('edit-btn')) {
        document.getElementById('edit_product_id').value = target.dataset.id;
        document.getElementById('editProductName').value = target.dataset.name;
        document.getElementById('editProductPrice').value = target.dataset.price;
        document.getElementById('editProductDescription').value = target.dataset.description;
        document.getElementById('editProductCategory').value = target.dataset.category;
        document.getElementById('editProductStatus').value = target.dataset.status;
        document.getElementById('editProductModal').style.display = 'block';
    }

    // Xóa sản phẩm
    if (target.classList.contains('delete-btn')) {
        document.getElementById('delete_product_name').textContent = target.dataset.name;
        document.getElementById('confirmDeleteBtn').dataset.id = target.dataset.id;
        document.getElementById('deleteConfirmModal').style.display = 'block';
    }
});

// ========== Mở modal thêm sản phẩm ==========
document.getElementById('btnAddProduct').addEventListener('click', () => {
    document.getElementById('addProductModal').style.display = 'block';
});

// ========== Đóng modal ==========
document.querySelectorAll('.close-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        btn.closest('.modal').style.display = 'none';
    });
});

// ========== Khởi tạo ==========
document.addEventListener('DOMContentLoaded', () => {
    loadProducts();
});
</script>
