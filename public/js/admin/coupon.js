// Coupon Management JavaScript
// Data: couponsData, BASE_URL được truyền từ PHP

// Pagination settings
let currentPage = 1;
const itemsPerPage = 5;
let totalPages = Math.ceil(couponsData.length / itemsPerPage);

// Format time for display (HH:MM:SS -> HH:MM)
function formatTime(timeString) {
    if (!timeString) return '';
    // If it's HH:MM:SS, remove seconds
    if (timeString.match(/^\d{2}:\d{2}:\d{2}$/)) {
        return timeString.substring(0, 5); // Get HH:MM
    }
    return timeString;
}

// Escape HTML
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Display coupons for current page
function displaycoupons(page) {
    const tbody = document.getElementById('couponBody');
    const start = (page - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const pagecoupons = couponsData.slice(start, end);

    if (pagecoupons.length === 0) {
        tbody.innerHTML = '<tr><td colspan="9" style="text-align:center;padding:20px;">Không có khuyến mãi nào</td></tr>';
        return;
    }

    tbody.innerHTML = '';
    pagecoupons.forEach(coupon => {
        const row = document.createElement('tr');
        
        let statusClass = 'status-active';
        let statusText = 'Đang hoạt động';
        if (coupon.Status === 'inactive') {
            statusClass = 'status-inactive';
            statusText = 'Chưa kích hoạt';
        } else if (coupon.Status === 'expired') {
            statusClass = 'status-expired';
            statusText = 'Đã hết hạn';
        }

        row.innerHTML = `
            <td>${coupon.ID}</td>
            <td>${escapeHtml(coupon.Code)}</td>
            <td>${escapeHtml(coupon.Description)}</td>
            <td>${escapeHtml(coupon.Discount_value)}</td>
            <td>${formatTime(coupon.Start)}</td>
            <td>${formatTime(coupon.End)}</td>
            <td>${coupon.Quantity}</td>
            <td><span class="status-badge ${statusClass}">${statusText}</span></td>
            <td>
                <div class="action-buttons">
                    <button class="btn-action btn-view" title="Xem chi tiết" 
                        data-id="${coupon.ID}"
                        data-code="${escapeHtml(coupon.Code)}"
                        data-description="${escapeHtml(coupon.Description)}"
                        data-value="${escapeHtml(coupon.Discount_value)}"
                        data-start="${coupon.Start}"
                        data-end="${coupon.End}"
                        data-quantity="${coupon.Quantity}"
                        data-status="${coupon.Status}">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn-action btn-edit" title="Sửa"
                        data-id="${coupon.ID}"
                        data-code="${escapeHtml(coupon.Code)}"
                        data-description="${escapeHtml(coupon.Description)}"
                        data-value="${escapeHtml(coupon.Discount_value)}"
                        data-start="${coupon.Start}"
                        data-end="${coupon.End}"
                        data-quantity="${coupon.Quantity}"
                        data-status="${coupon.Status}">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn-action btn-delete" title="Xóa"
                        data-id="${coupon.ID}"
                        data-code="${escapeHtml(coupon.Code)}">
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
            displaycoupons(currentPage);
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
            displaycoupons(currentPage);
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
            displaycoupons(currentPage);
            displayPagination();
        }
    };
    pagination.appendChild(nextBtn);

    // Page info
    const pageInfo = document.createElement('span');
    pageInfo.className = 'page-info';
    pageInfo.textContent = totalPages > 0 
        ? `Trang ${currentPage}/${totalPages} — Tổng: ${couponsData.length} mục`
        : 'Không có khuyến mãi';
    pagination.appendChild(pageInfo);
}

// Modal elements
const modal = document.getElementById('addcouponModal');
const viewModal = document.getElementById('viewcouponModal');
const editModal = document.getElementById('editcouponModal');
const deleteModal = document.getElementById('deleteConfirmModal');
let deletecouponId = null;

// Helper functions for modal
function openModal(modalElement) {
    modalElement.style.display = 'block';
    modalElement.classList.add('show');
    document.body.classList.add('modal-open');
    document.body.style.overflow = 'hidden';
}

function closeModalElement(modalElement) {
    modalElement.style.display = 'none';
    modalElement.classList.remove('show');
    document.body.classList.remove('modal-open');
    document.body.style.overflow = 'auto';
}

// Attach event listeners to buttons
function attachEventListeners() {
    // View buttons
    document.querySelectorAll('.btn-view').forEach(btn => {
        btn.onclick = function() {
            document.getElementById('view_id').textContent = this.dataset.id;
            document.getElementById('view_code').textContent = this.dataset.code;
            document.getElementById('view_description').textContent = this.dataset.description;
            document.getElementById('view_value').textContent = this.dataset.value;
            document.getElementById('view_quantity').textContent = this.dataset.quantity;
            document.getElementById('view_start_date').textContent = formatTime(this.dataset.start);
            document.getElementById('view_end_date').textContent = formatTime(this.dataset.end);
            
            const statusSpan = document.getElementById('view_status');
            let statusClass = 'status-active';
            let statusText = 'Đang hoạt động';
            if (this.dataset.status === 'inactive') {
                statusClass = 'status-inactive';
                statusText = 'Chưa kích hoạt';
            } else if (this.dataset.status === 'expired') {
                statusClass = 'status-expired';
                statusText = 'Đã hết hạn';
            }
            statusSpan.innerHTML = `<span class="status-badge ${statusClass}">${statusText}</span>`;

            openModal(viewModal);
        };
    });

    // Edit buttons
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.onclick = function() {
            document.getElementById('edit_coupon_id').value = this.dataset.id;
            document.getElementById('editcouponCode').value = this.dataset.code;
            document.getElementById('editcouponDescription').value = this.dataset.description;
            document.getElementById('editcouponValue').value = this.dataset.value;
            document.getElementById('editcouponStartDate').value = formatTime(this.dataset.start);
            document.getElementById('editcouponEndDate').value = formatTime(this.dataset.end);
            document.getElementById('editcouponQuantity').value = this.dataset.quantity;
            document.getElementById('editcouponStatus').value = this.dataset.status;

            openModal(editModal);
        };
    });

    // Delete buttons
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.onclick = function() {
            deletecouponId = this.dataset.id;
            document.getElementById('delete_coupon_code').textContent = this.dataset.code;
            openModal(deleteModal);
        };
    });
}

// Add coupon button
document.getElementById('btnAddcoupon').onclick = () => {
    openModal(modal);
};

// Close modals
function closeModal() {
    closeModalElement(modal);
    document.getElementById('addcouponForm').reset();
}

function closeViewModal() {
    closeModalElement(viewModal);
}

function closeEditModal() {
    closeModalElement(editModal);
    document.getElementById('editcouponForm').reset();
}

function closeDeleteModal() {
    closeModalElement(deleteModal);
    deletecouponId = null;
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
    if (deletecouponId) {
        window.location.href = BASE_URL + 'admin/coupon?action=delete&id=' + deletecouponId;
    }
};

// Close modal when clicking outside
window.onclick = (e) => {
    if (e.target === modal) closeModal();
    if (e.target === viewModal) closeViewModal();
    if (e.target === editModal) closeEditModal();
    if (e.target === deleteModal) closeDeleteModal();
};

// Form validation
document.getElementById('addcouponForm').onsubmit = (e) => {
    const code = document.getElementById('couponCode').value.trim();
    const description = document.getElementById('couponDescription').value.trim();
    const value = document.getElementById('couponValue').value.trim();
    const startDate = document.getElementById('couponStartDate').value;
    const endDate = document.getElementById('couponEndDate').value;

    if (!code) {
        e.preventDefault();
        alert('Vui lòng nhập mã khuyến mãi!');
        return false;
    }
    if (!description) {
        e.preventDefault();
        alert('Vui lòng nhập mô tả!');
        return false;
    }
    if (!value) {
        e.preventDefault();
        alert('Vui lòng nhập giá trị khuyến mãi!');
        return false;
    }
    if (!startDate || !endDate) {
        e.preventDefault();
        alert('Vui lòng chọn giờ bắt đầu và kết thúc!');
        return false;
    }
    return true;
};

document.getElementById('editcouponForm').onsubmit = (e) => {
    const code = document.getElementById('editcouponCode').value.trim();
    const description = document.getElementById('editcouponDescription').value.trim();
    const value = document.getElementById('editcouponValue').value.trim();
    const startDate = document.getElementById('editcouponStartDate').value;
    const endDate = document.getElementById('editcouponEndDate').value;

    if (!code) {
        e.preventDefault();
        alert('Vui lòng nhập mã khuyến mãi!');
        return false;
    }
    if (!description) {
        e.preventDefault();
        alert('Vui lòng nhập mô tả!');
        return false;
    }
    if (!value) {
        e.preventDefault();
        alert('Vui lòng nhập giá trị khuyến mãi!');
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
});

// Add keyboard support (ESC to close)
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        if (modal.style.display === 'block') closeModal();
        if (viewModal.style.display === 'block') closeViewModal();
        if (editModal.style.display === 'block') closeEditModal();
        if (deleteModal.style.display === 'block') closeDeleteModal();
    }
});

// Initialize on page load
displaycoupons(currentPage);
displayPagination();
