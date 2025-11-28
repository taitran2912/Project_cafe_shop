// Account data from PHP
const accountsData = typeof accountsDataFromPHP !== 'undefined' ? accountsDataFromPHP : [];

// Role names mapping
const roleNames = {
    '1': 'Admin',
    '2': 'Nhân viên',
    '3': 'Quản lý'
};

// Pagination settings
let currentPage = 1;
const itemsPerPage = 5;
let totalPages = Math.ceil(accountsData.length / itemsPerPage);

// Display accounts for current page
function displayAccounts(page) {
    const tbody = document.getElementById('accountBody');
    const start = (page - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const pageAccounts = accountsData.slice(start, end);

    if (pageAccounts.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:20px;">Không có nhân viên nào</td></tr>';
        return;
    }

    tbody.innerHTML = '';
    pageAccounts.forEach(account => {
        const row = document.createElement('tr');
        const roleName = roleNames[account.Role] || 'Nhân viên';
        const roleClass = account.Role == 1 ? 'role-admin' : (account.Role == 3 ? 'role-manager' : 'role-staff');
        const statusClass = account.Status === 'active' ? 'status-active' : 'status-inactive';
        const statusText = account.Status === 'active' ? 'Đang hoạt động' : 'Tạm dừng';

        row.innerHTML = `
            <td>${account.ID}</td>
            <td>${escapeHtml(account.Name)}</td>
            <td>${escapeHtml(account.Phone)}</td>
            <td><span class="role-badge ${roleClass}">${roleName}</span></td>
            <td><span class="status-badge ${statusClass}">${statusText}</span></td>
            <td>
                <div class="action-buttons">
                    <button class="btn-action btn-view" title="Xem chi tiết" 
                        data-id="${account.ID}"
                        data-name="${escapeHtml(account.Name)}"
                        data-phone="${escapeHtml(account.Phone)}"
                        data-role="${account.Role}"
                        data-status="${account.Status}">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn-action btn-edit" title="Sửa"
                        data-id="${account.ID}"
                        data-name="${escapeHtml(account.Name)}"
                        data-phone="${escapeHtml(account.Phone)}"
                        data-role="${account.Role}"
                        data-status="${account.Status}">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn-action btn-delete" title="Xóa"
                        data-id="${account.ID}"
                        data-name="${escapeHtml(account.Name)}">
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
            displayAccounts(currentPage);
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
            displayAccounts(currentPage);
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
            displayAccounts(currentPage);
            displayPagination();
        }
    };
    pagination.appendChild(nextBtn);

    // Page info
    const pageInfo = document.createElement('span');
    pageInfo.className = 'page-info';
    pageInfo.textContent = totalPages > 0 
        ? `Trang ${currentPage}/${totalPages} — Tổng: ${accountsData.length} mục`
        : 'Không có nhân viên';
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
            const role = this.dataset.role;
            document.getElementById('view_id').textContent = this.dataset.id;
            document.getElementById('view_name').textContent = this.dataset.name;
            document.getElementById('view_phone').textContent = this.dataset.phone;
            document.getElementById('view_role').innerHTML = `<span class="role-badge ${role == 1 ? 'role-admin' : (role == 3 ? 'role-manager' : 'role-staff')}">${roleNames[role] || 'Nhân viên'}</span>`;
            
            const statusSpan = document.getElementById('view_status');
            if (this.dataset.status === 'active') {
                statusSpan.innerHTML = '<span class="status-badge status-active">Đang hoạt động</span>';
            } else {
                statusSpan.innerHTML = '<span class="status-badge status-inactive">Tạm dừng</span>';
            }

            viewModal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        };
    });

    // Edit buttons
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.onclick = function() {
            document.getElementById('edit_account_id').value = this.dataset.id;
            document.getElementById('editAccountName').value = this.dataset.name;
            document.getElementById('editAccountPhone').value = this.dataset.phone;
            document.getElementById('editAccountRole').value = this.dataset.role;
            document.getElementById('editAccountStatus').value = this.dataset.status;

            editModal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        };
    });

    // Delete buttons
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.onclick = function() {
            deleteAccountId = this.dataset.id;
            document.getElementById('delete_account_name').textContent = this.dataset.name;
            deleteModal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        };
    });
}

// Modal elements
const modal = document.getElementById('addAccountModal');
const viewModal = document.getElementById('viewAccountModal');
const editModal = document.getElementById('editAccountModal');
const deleteModal = document.getElementById('deleteConfirmModal');
let deleteAccountId = null;

// Add account button
document.getElementById('btnAddAccount').onclick = () => {
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
};

// Close modals
function closeModal() {
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
    document.getElementById('addAccountForm').reset();
}

function closeViewModal() {
    viewModal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

function closeEditModal() {
    editModal.style.display = 'none';
    document.body.style.overflow = 'auto';
    document.getElementById('editAccountForm').reset();
}

function closeDeleteModal() {
    deleteModal.style.display = 'none';
    document.body.style.overflow = 'auto';
    deleteAccountId = null;
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
    if (deleteAccountId) {
        window.location.href = BASE_URL + 'admin/user?action=delete&id=' + deleteAccountId;
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
document.getElementById('addAccountForm').onsubmit = (e) => {
    const name = document.getElementById('accountName').value.trim();
    const phone = document.getElementById('accountPhone').value.trim();
    const password = document.getElementById('accountPassword').value.trim();

    if (!name) {
        e.preventDefault();
        alert('Vui lòng nhập họ tên!');
        return false;
    }
    if (!phone) {
        e.preventDefault();
        alert('Vui lòng nhập số điện thoại!');
        return false;
    }
    if (!password) {
        e.preventDefault();
        alert('Vui lòng nhập mật khẩu!');
        return false;
    }
    return true;
};

document.getElementById('editAccountForm').onsubmit = (e) => {
    const name = document.getElementById('editAccountName').value.trim();
    const phone = document.getElementById('editAccountPhone').value.trim();

    if (!name) {
        e.preventDefault();
        alert('Vui lòng nhập họ tên!');
        return false;
    }
    if (!phone) {
        e.preventDefault();
        alert('Vui lòng nhập số điện thoại!');
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
displayAccounts(currentPage);
displayPagination();
