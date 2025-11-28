// Lấy dữ liệu từ PHP
let inventoryItems = [];
let BASE_URL = '';

// Kiểm tra và lấy dữ liệu từ window
if (typeof window.inventoryDataFromPHP !== 'undefined') {
    inventoryItems = window.inventoryDataFromPHP;
}
if (typeof window.BASE_URL !== 'undefined') {
    BASE_URL = window.BASE_URL;
}

// Debug: Log dữ liệu nhận được
console.log('Window object check:', {
    hasInventoryData: typeof window.inventoryDataFromPHP !== 'undefined',
    hasBASE_URL: typeof window.BASE_URL !== 'undefined'
});
console.log('Inventory Items:', inventoryItems);
console.log('Items count:', inventoryItems.length);
console.log('BASE_URL:', BASE_URL);

// Cấu hình
const rowsPerPage = 10;
let currentPage = 1;

function getStatus(quantity, minLevel) {
    if (quantity === 0) return 'out';
    if (quantity < minLevel) return 'low';
    return 'good';
}

function getStatusBadge(quantity, minLevel) {
    const status = getStatus(quantity, minLevel);
    const classes = {
        'out': 'status-out',
        'low': 'status-low',
        'good': 'status-good'
    };
    const texts = {
        'out': 'Đã hết',
        'low': 'Sắp hết',
        'good': 'Đủ hàng'
    };
    return `<span class="status-badge ${classes[status]}">${texts[status]}</span>`;
}

function displayTable(page = 1) {
    const tableBody = document.getElementById('tableBody');
    if (!tableBody) return;
    
    tableBody.innerHTML = '';

    const start = (page - 1) * rowsPerPage;
    const end = start + rowsPerPage;
    const pageData = inventoryItems.slice(start, end);

    pageData.forEach((item, index) => {
        const minLevel = 50;
        const row = `
            <tr>
                <td>${start + index + 1}</td>
                <td>${item.MaterialName}</td>
                <td>${item.BranchName}</td>
                <td>${item.Unit}</td>
                <td><strong>${item.Quantity}</strong></td>
                <td>${minLevel}</td>
                <td>${getStatusBadge(item.Quantity, minLevel)}</td>
                <td>
                    <div class="action-buttons">
                        <button class="btn-action btn-view" 
                                data-id="${item.ID}"
                                data-materialname="${item.MaterialName}"
                                data-branchname="${item.BranchName}"
                                data-branchid="${item.BranchID}"
                                data-unit="${item.Unit}"
                                data-quantity="${item.Quantity}">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn-action btn-edit"
                                data-id="${item.ID}"
                                data-materialid="${item.ID_Material}"
                                data-materialname="${item.MaterialName}"
                                data-branchname="${item.BranchName}"
                                data-branchid="${item.BranchID}"
                                data-unit="${item.Unit}"
                                data-quantity="${item.Quantity}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-action btn-delete"
                                data-id="${item.ID}"
                                data-materialname="${item.MaterialName}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
        tableBody.insertAdjacentHTML('beforeend', row);
    });

    renderPagination();
}

function renderPagination() {
    const totalPages = Math.ceil(inventoryItems.length / rowsPerPage);
    const pagination = document.getElementById('pagination');
    if (!pagination) return;
    
    pagination.innerHTML = '';

    for (let i = 1; i <= totalPages; i++) {
        const button = document.createElement('button');
        button.textContent = i;
        button.classList.add('page-btn');
        if (i === currentPage) button.classList.add('active');
        button.addEventListener('click', () => {
            currentPage = i;
            displayTable(currentPage);
        });
        pagination.appendChild(button);
    }
}

// ========== MODALS ==========
const addModal = document.getElementById('addItemModal');
const editModal = document.getElementById('editItemModal');
const viewModal = document.getElementById('viewItemModal');
const addItemBtn = document.getElementById('addItemBtn');
const cancelBtn = document.getElementById('cancelBtn');
const cancelEditBtn = document.getElementById('cancelEditBtn');
const closeViewBtn = document.getElementById('closeViewBtn');

if (addItemBtn) {
    addItemBtn.addEventListener('click', () => {
        addModal.style.display = 'block';
    });
}

if (cancelBtn) {
    cancelBtn.addEventListener('click', () => {
        addModal.style.display = 'none';
        document.getElementById('addItemForm').reset();
    });
}

if (cancelEditBtn) {
    cancelEditBtn.addEventListener('click', () => {
        editModal.style.display = 'none';
        document.getElementById('editItemForm').reset();
    });
}

if (closeViewBtn) {
    closeViewBtn.addEventListener('click', () => {
        viewModal.style.display = 'none';
    });
}

// ========== BUTTON HANDLERS ==========
document.addEventListener('click', function(e) {
    // View
    if (e.target.closest('.btn-view')) {
        const btn = e.target.closest('.btn-view');
        document.getElementById('viewItemId').textContent = btn.dataset.id;
        document.getElementById('viewItemMaterialName').textContent = btn.dataset.materialname;
        document.getElementById('viewItemBranchName').textContent = btn.dataset.branchname;
        document.getElementById('viewItemUnit').textContent = btn.dataset.unit;
        document.getElementById('viewItemQuantity').textContent = btn.dataset.quantity;
        
        const quantity = parseInt(btn.dataset.quantity);
        const minLevel = 50;
        document.getElementById('viewItemStatus').innerHTML = getStatusBadge(quantity, minLevel);
        
        viewModal.style.display = 'block';
    }
    
    // Edit
    if (e.target.closest('.btn-edit')) {
        const btn = e.target.closest('.btn-edit');
        document.getElementById('editItemId').value = btn.dataset.id;
        document.getElementById('editItemMaterial').value = btn.dataset.materialid;
        document.getElementById('editItemBranch').value = btn.dataset.branchid;
        document.getElementById('editItemQuantity').value = btn.dataset.quantity;
        editModal.style.display = 'block';
    }
    
    // Delete
    if (e.target.closest('.btn-delete')) {
        const btn = e.target.closest('.btn-delete');
        const id = btn.dataset.id;
        const name = btn.dataset.materialname;
        if (confirm(`Bạn có chắc chắn muốn xóa kho "${name}"?`)) {
            window.location.href = `${BASE_URL}admin/inventory?action=delete&id=${id}`;
        }
    }
});

// ========== CLOSE MODALS ==========
document.querySelectorAll('.close').forEach(closeBtn => {
    closeBtn.addEventListener('click', function() {
        const modalType = this.dataset.modal;
        if (modalType === 'edit') {
            editModal.style.display = 'none';
        } else if (modalType === 'view') {
            viewModal.style.display = 'none';
        } else {
            addModal.style.display = 'none';
        }
    });
});

window.addEventListener('click', function(event) {
    if (event.target == addModal) {
        addModal.style.display = 'none';
    }
    if (event.target == editModal) {
        editModal.style.display = 'none';
    }
    if (event.target == viewModal) {
        viewModal.style.display = 'none';
    }
});

// ========== SEARCH ==========
const searchInput = document.getElementById('searchInput');
if (searchInput) {
    searchInput.addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('#tableBody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
}

// ========== FILTER ==========
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        const filter = this.dataset.filter;
        const rows = document.querySelectorAll('#tableBody tr');
        
        rows.forEach(row => {
            if (filter === 'all') {
                row.style.display = '';
            } else {
                const statusBadge = row.querySelector('.status-badge');
                const hasClass = statusBadge.classList.contains(`status-${filter}`);
                row.style.display = hasClass ? '' : 'none';
            }
        });
    });
});

// Hiển thị trang đầu tiên khi load
if (inventoryItems.length > 0) {
    displayTable(currentPage);
}
