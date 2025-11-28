

        <!-- Content Header -->
        <div class="content-header">
            <button class="btn btn-primary" id="btnAddProduct">
                <i class="fas fa-plus"></i>
                Thêm sản phẩm
            </button> 
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Tìm kiếm sản phẩm..." value="<?= isset($searchQuery) ? htmlspecialchars($searchQuery) : '' ?>" autocomplete="off">
                <?php if (isset($isSearching) && $isSearching): ?>
                    <button type="button" class="clear-search-btn" id="clearSearchBtn" title="Xóa tìm kiếm">
                        <i class="fas fa-times"></i>
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <?php if (isset($isSearching) && $isSearching && isset($products)): ?>
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
                <tbody id="productBody">
                    <!-- Products will be rendered by JavaScript -->
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

    <!-- Modal Xác Nhận Xóa-->
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


</html>
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

    <!-- Embed product data for JavaScript -->
    <script>
        const productsDataFromPHP = <?= json_encode(isset($products) ? $products : []) ?>;
        const BASE_URL = '<?= BASE_URL ?>';
        const hasErrorMessage = <?= (isset($errorMessage) && !empty($errorMessage)) ? 'true' : 'false' ?>;
    </script>
    
    <!-- Link to external JavaScript -->

<?php $data['products'] = $products; ?>
<script>
    // Menu data
    const menuItems = [
    <?php if (!empty($data['products'])): ?>
    <?php foreach ($data['products'] as $index => $product): ?>
        {
        id: <?= (int)$product['ID'] ?>,
        name: "<?= htmlspecialchars($product['Name'], ENT_QUOTES) ?>",
        price: <?= (int)$product['Price'] ?>,
        category: "<?= htmlspecialchars($product['Name_Category'], ENT_QUOTES) ?>",
        image: "<?= BASE_URL ?>/public/image/<?= htmlspecialchars($product['Image'], ENT_QUOTES) ?>",
        description: "<?= htmlspecialchars($product['Description'], ENT_QUOTES) ?>",
        }<?= $index < count($data['products']) - 1 ? ',' : '' ?>

    <?php endforeach; ?>
    <?php endif; ?>
    ];

    console.log("Menu Items:", menuItems);
    console.log("Filtered Items:", getFilteredItems());

    let currentCategory = "all"
    let displayedItems = 0
    const itemsPerLoad = 9

    function formatPrice(price) {
    return new Intl.NumberFormat("vi-VN").format(price) + "đ"
    }

    function createMenuItemHTML(item) {
    return `
        <div class="menu-item ${item.category} card-hover bg-white rounded-2xl overflow-hidden shadow-lg fade-in">
            <img src="${item.image}" alt="${item.name}" class="w-full h-48 object-cover">
            <div class="p-6">
                <h3 class="font-display text-xl font-semibold mb-2">${item.name}</h3>
                <p class="text-gray-600 mb-4">${item.description}</p>
                <div class="flex justify-between items-center">
                    <span class="text-2xl font-bold text-primary">${formatPrice(item.price)}</span>
                    <button class="btn-primary text-sm px-4 py-2 add-to-cart" 
                            data-id="${item.id}"
                            data-name="${item.name}" 
                            data-price="${item.price}"
                            data-image="${item.image}">
                        Thêm vào giỏ
                    </button>
                </div>
            </div>
        </div>
    `
    }

    function getFilteredItems() {
    return currentCategory === "all" ? menuItems : menuItems.filter((item) => item.category === currentCategory)
    }

    function loadMenuItems() {
    const filteredItems = getFilteredItems()
    const menuGrid = document.getElementById("menu-grid")
    const loadMoreBtn = document.getElementById("load-more-btn")

    // Clear grid if starting fresh
    if (displayedItems === 0) {
    menuGrid.innerHTML = ""
    }

    // Get items to display
    const itemsToShow = filteredItems.slice(displayedItems, displayedItems + itemsPerLoad)

    // Add items to grid
    itemsToShow.forEach((item) => {
    menuGrid.innerHTML += createMenuItemHTML(item)
    })

    displayedItems += itemsToShow.length

    // Show/hide load more button
    if (displayedItems >= filteredItems.length) {
    loadMoreBtn.style.display = "none"
    } else {
    loadMoreBtn.style.display = "inline-block"
    }

    // Trigger fade-in animation
    setTimeout(() => {
    document.querySelectorAll(".fade-in:not(.visible)").forEach((el) => {
        el.classList.add("visible")
    })
    }, 100)

    // Add event listeners to new add-to-cart buttons
    addCartEventListeners()
    }

    function filterItems(category) {
    currentCategory = category
    displayedItems = 0

    // Update category buttons
    document.querySelectorAll(".menu-category").forEach((btn) => {
    btn.classList.remove("active", "bg-primary", "text-white")
    btn.classList.add("bg-white", "text-primary")
    })

    const activeBtn = document.querySelector(`[data-category="${category}"]`)
    activeBtn.classList.add("active", "bg-primary", "text-white")
    activeBtn.classList.remove("bg-white", "text-primary")

    // Load filtered items
    loadMenuItems()
    }

    function addCartEventListeners() {
    document.querySelectorAll(".add-to-cart").forEach((button) => {
    button.addEventListener("click", function () {
        const item = {
        id: Number.parseInt(this.dataset.id),
        name: this.dataset.name,
        price: Number.parseInt(this.dataset.price),
        image: this.dataset.image,
        quantity: 1,
        }

        // Assuming addToCart is defined elsewhere or imported
        // You might need to import it or define it in this file
        addToCart(item)

        // Visual feedback
        const originalText = this.textContent
        this.textContent = "Đã thêm!"
        this.style.backgroundColor = "var(--secondary)"

        setTimeout(() => {
        this.textContent = originalText
        this.style.backgroundColor = "var(--primary)"
        }, 1500)
    })
    })
    }

    // Initialize menu
    document.addEventListener("DOMContentLoaded", () => {
    loadMenuItems()

    // Category filter event listeners
    document.querySelectorAll(".menu-category").forEach((category) => {
    category.addEventListener("click", () => {
        const selectedCategory = category.getAttribute("data-category")
        filterItems(selectedCategory)
    })
    })

    // Load more button event listener
    document.getElementById("load-more-btn").addEventListener("click", function () {
    const loading = document.getElementById("loading")
    this.style.display = "none"
    loading.classList.remove("hidden")

    setTimeout(() => {
        loading.classList.add("hidden")
        loadMenuItems()
    }, 1000)
    })
    })

</script>
</body>
</html>

