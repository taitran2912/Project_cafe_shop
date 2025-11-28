// Product data from PHP
const productsData = typeof productsDataFromPHP !== 'undefined' ? productsDataFromPHP : [];

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
    if (typeof hasErrorMessage !== 'undefined' && hasErrorMessage) {
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
});

// Initialize on page load
displayProducts(currentPage);
displayPagination();
