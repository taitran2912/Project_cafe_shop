<!-- Modal Thêm Sản Phẩm -->
<div id="addProductModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2><i class="fas fa-plus-circle"></i> Thêm Sản Phẩm Mới</h2>
            <button class="close-btn">&times;</button>
        </div>
        <form id="addProductForm" method="POST">
            <input type="hidden" name="action" value="add_product">
            <div class="form-grid">
                <div class="form-group">
                    <label for="productCategory"><i class="fas fa-list"></i> Loại sản phẩm <span class="required">*</span></label>
                    <select id="productCategory" name="category" required>
                         <option value="">-- Chọn loại sản phẩm --</option>
                            <?php foreach ($data['categories'] as $category): ?>
                                <option value="<?= (int)$category['ID'] ?>">
                                    <?= htmlspecialchars($category['Name'], ENT_QUOTES) ?>
                                </option>
                            <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="productName"><i class="fas fa-coffee"></i> Tên sản phẩm <span class="required">*</span></label>
                    <input type="text" id="productName" name="name" placeholder="Nhập tên sản phẩm" required>
                </div>
                <div class="form-group">
                    <label for="productPrice"><i class="fas fa-dollar-sign"></i> Giá (VNĐ) <span class="required">*</span></label>
                    <input type="number" id="productPrice" name="price" placeholder="Nhập giá sản phẩm" min="0" step="1000" required>
                </div>
                <div class="form-group">
                    <label for="productStatus"><i class="fas fa-toggle-on"></i> Trạng thái <span class="required">*</span></label>
                    <select id="productStatus" name="status" required>
                        <option value="active">Đang bán</option>
                        <option value="inactive">Ngừng bán</option>
                    </select>
                </div>
                <div class="form-group full-width">
                    <label for="productDescription"><i class="fas fa-align-left"></i> Mô tả</label>
                    <textarea id="productDescription" name="description" rows="3" placeholder="Nhập mô tả sản phẩm"></textarea>
                </div>
                <div class="form-group full-width">
                    <label for="productImage"><i class="fas fa-image"></i> Hình ảnh sản phẩm</label>
                    <input type="file" id="productImage" name="image" accept="image/*">
                    <div id="imagePreview" class="image-preview"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn">Hủy</button>
                <button type="submit" class="btn btn-primary">Lưu sản phẩm</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Xem Chi Tiết Sản Phẩm -->
<div id="viewProductModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2><i class="fas fa-info-circle"></i> Chi Tiết Sản Phẩm</h2>
            <button class="close-btn">&times;</button>
        </div>
        <div class="modal-body">
            <div class="detail-grid">
                <div class="detail-item"><label>Mã sản phẩm:</label><span id="view_id"></span></div>
                <div class="detail-item"><label>Loại sản phẩm:</label><span id="view_category"></span></div>
                <div class="detail-item full-width"><label>Tên sản phẩm:</label><span id="view_name"></span></div>
                <div class="detail-item full-width"><label>Mô tả:</label><span id="view_description"></span></div>
                <div class="detail-item"><label>Giá:</label><span id="view_price"></span></div>
                <div class="detail-item"><label>Trạng thái:</label><span id="view_status"></span></div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary close-btn">Đóng</button>
        </div>
    </div>
</div>

<!-- Modal Sửa Sản Phẩm -->
<div id="editProductModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2><i class="fas fa-edit"></i> Sửa Sản Phẩm</h2>
            <button class="close-btn">&times;</button>
        </div>
        <form id="editProductForm" method="POST">
            <input type="hidden" name="action" value="edit_product">
            <input type="hidden" name="product_id" id="edit_product_id">
            <div class="form-grid">
                <div class="form-group">
                    <label>Loại sản phẩm</label>
                    <select id="editProductCategory" name="category" required>
                        <?php foreach ($data['categories'] as $category): ?>
                            <option value="<?= (int)$category['ID'] ?>">
                                <?= htmlspecialchars($category['Name'], ENT_QUOTES) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Tên sản phẩm</label>
                    <input type="text" id="editProductName" name="name" required>
                </div>
                <div class="form-group">
                    <label>Giá</label>
                    <input type="number" id="editProductPrice" name="price" min="0" step="1000" required>
                </div>
                <div class="form-group">
                    <label>Trạng thái</label>
                    <select id="editProductStatus" name="status" required>
                        <option value="active">Đang bán</option>
                        <option value="inactive">Ngừng bán</option>
                    </select>
                </div>
                <div class="form-group full-width">
                    <label>Mô tả</label>
                    <textarea id="editProductDescription" name="description" rows="3"></textarea>
                </div>
                <div class="form-group full-width">
                    <label>Hình ảnh sản phẩm</label>
                    <input type="file" id="editProductImage" name="image" accept="image/*">
                    <div id="editImagePreview" class="image-preview"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn">Hủy</button>
                <button type="submit" class="btn btn-primary">Cập nhật</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Xác Nhận Xóa -->
<div id="deleteConfirmModal" class="modal">
    <div class="modal-content modal-small">
        <div class="modal-header modal-header-danger">
            <h2><i class="fas fa-exclamation-triangle"></i> Xác Nhận Xóa</h2>
            <button class="close-btn">&times;</button>
        </div>
        <div class="modal-body">
            <p>Bạn có chắc chắn muốn xóa sản phẩm <strong id="delete_product_name"></strong>?</p>
            <p><i class="fas fa-info-circle"></i> Hành động này không thể hoàn tác!</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary close-btn">Hủy</button>
            <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Xóa</button>
        </div>
    </div>
</div>

<!-- JS mở/đóng modal -->
<script>
// 1️⃣ Đóng tất cả modal bằng nút .close-btn
document.querySelectorAll('.close-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        btn.closest('.modal').style.display = 'none';
    });
});

// 2️⃣ Mở modal thêm sản phẩm
document.getElementById('btnAddProduct').addEventListener('click', () => {
    document.getElementById('addProductModal').style.display = 'block';
});

// 3️⃣ Mở modal sửa sản phẩm và điền dữ liệu
document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const modal = document.getElementById('editProductModal');
        modal.style.display = 'block';

        // Lấy dữ liệu từ data-attributes
        document.getElementById('edit_product_id').value = btn.dataset.id;
        document.getElementById('editProductName').value = btn.dataset.name;
        document.getElementById('editProductPrice').value = btn.dataset.price;
        document.getElementById('editProductDescription').value = btn.dataset.description;
        document.getElementById('editProductCategory').value = btn.dataset.category;
        document.getElementById('editProductStatus').value = btn.dataset.status;
    });
});

</script>
