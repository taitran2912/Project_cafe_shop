// Branch Management JavaScript
// Data: branches, BASE_URL được truyền từ PHP

// Cấu hình phân trang
const rowsPerPage = 5;
let currentPage = 1;

// Hiển thị danh sách chi nhánh theo trang
function displayBranches(page) {
    const tableBody = document.getElementById('branchBody');
    tableBody.innerHTML = '';

    const start = (page - 1) * rowsPerPage;
    const end = start + rowsPerPage;
    const pageData = branches.slice(start, end);

    pageData.forEach(branch => {
        const row = `
            <tr>
                <td>${branch.ID}</td>
                <td>${branch.Name}</td>
                <td>${branch.Address}</td>
                <td>${branch.Phone || ''}</td>
                <td>
                    <span class="status-badge ${branch.Status === 'active' ? 'status-active' : 'status-inactive'}">
                        ${branch.Status === 'active' ? 'Đang hoạt động' : 'Ngưng hoạt động'}
                    </span>
                </td>
                <td>
                    <div class="action-buttons">
                        <button class="btn-action btn-view" title="Xem chi tiết" data-id="${branch.ID}"><i class="fas fa-eye"></i></button>
                        <button class="btn-action btn-edit" title="Sửa" data-id="${branch.ID}"><i class="fas fa-edit"></i></button>
                        <button class="btn-action btn-delete" title="Xóa" data-id="${branch.ID}"><i class="fas fa-trash"></i></button>
                    </div>
                </td>
            </tr>
        `;
        tableBody.insertAdjacentHTML('beforeend', row);
    });

    renderPagination();
}

// Render phân trang
function renderPagination() {
    const totalPages = Math.ceil(branches.length / rowsPerPage);
    const pagination = document.getElementById('pagination');
    pagination.innerHTML = '';

    for (let i = 1; i <= totalPages; i++) {
        const button = document.createElement('button');
        button.textContent = i;
        button.classList.add('page-btn');
        if (i === currentPage) button.classList.add('active');
        button.addEventListener('click', () => {
            currentPage = i;
            displayBranches(currentPage);
        });
        pagination.appendChild(button);
    }
}

// Hiển thị trang đầu tiên
displayBranches(currentPage);

// ===== MODAL THÊM CHI NHÁNH =====
const modal = document.getElementById('addBranchModal');
const addBranchBtn = document.getElementById('addBranchBtn');
const addBranchForm = document.getElementById('addBranchForm');
const closeBtn = document.querySelector('#addBranchModal .close');
const cancelBtn = document.getElementById('cancelBtn');

addBranchBtn.addEventListener('click', () => {
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
});

closeBtn.addEventListener('click', () => {
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
    addBranchForm.reset();
});

cancelBtn.addEventListener('click', () => {
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
    addBranchForm.reset();
});

// ===== MODAL XEM CHI TIẾT =====
const viewModal = document.getElementById("viewBranchModal");
const viewClose = document.querySelector(".view-close");
const viewCancel = document.querySelector(".view-cancel");

document.addEventListener('click', function(e) {
    if (e.target.closest('.btn-view')) {
        const id = e.target.closest('.btn-view').dataset.id;
        const branch = branches.find(b => b.ID == id);

        if (branch) {
            document.getElementById('view_id').textContent = branch.ID;
            document.getElementById('view_name').textContent = branch.Name;
            document.getElementById('view_address').textContent = branch.Address;
            document.getElementById('view_phone').textContent = branch.Phone || 'Chưa có';
            
            const statusSpan = document.getElementById('view_status');
            if (branch.Status === 'active') {
                statusSpan.innerHTML = '<span class="status-badge status-active">Đang hoạt động</span>';
            } else {
                statusSpan.innerHTML = '<span class="status-badge status-inactive">Ngưng hoạt động</span>';
            }

            viewModal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }
    }
});

if (viewClose) {
    viewClose.addEventListener('click', () => {
        viewModal.style.display = 'none';
        document.body.style.overflow = 'auto';
    });
}
if (viewCancel) {
    viewCancel.addEventListener('click', () => {
        viewModal.style.display = 'none';
        document.body.style.overflow = 'auto';
    });
}

// ===== MODAL SỬA CHI NHÁNH =====
const editModal = document.getElementById("editBranchModal");
const editClose = document.querySelector(".edit-close");
const editCancel = document.querySelector(".edit-cancel");
const editForm = document.getElementById("editBranchForm");

document.addEventListener('click', function(e) {
    if (e.target.closest('.btn-edit')) {
        const id = e.target.closest('.btn-edit').dataset.id;
        const branch = branches.find(b => b.ID == id);

        document.getElementById('editBranchID').value = branch.ID;
        document.getElementById('editBranchName').value = branch.Name;
        document.getElementById('editBranchAddress').value = branch.Address;
        document.getElementById('editBranchPhone').value = branch.Phone;
        document.getElementById('editBranchStatus').value = branch.Status;

        editModal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
});

editClose.addEventListener('click', () => {
    editModal.style.display = 'none';
    document.body.style.overflow = 'auto';
});
editCancel.addEventListener('click', () => {
    editModal.style.display = 'none';
    document.body.style.overflow = 'auto';
});

// ===== MODAL XÓA CHI NHÁNH =====
const deleteModal = document.getElementById("deleteBranchModal");
const cancelDeleteBtn = document.getElementById("cancelDeleteBtn");
const confirmDeleteBtn = document.getElementById("confirmDeleteBtn");
const deleteCloseBtn = document.querySelector('#deleteBranchModal .close-btn');
const deleteCloseModalBtn = document.querySelector('#deleteBranchModal .delete-close');

let deleteID = null;

document.addEventListener('click', function(e) {
    if (e.target.closest('.btn-delete')) {
        deleteID = e.target.closest('.btn-delete').dataset.id;
        deleteModal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
});

if (deleteCloseBtn) {
    deleteCloseBtn.addEventListener('click', () => {
        deleteModal.style.display = 'none';
        document.body.style.overflow = 'auto';
        deleteID = null;
    });
}
if (deleteCloseModalBtn) {
    deleteCloseModalBtn.addEventListener('click', () => {
        deleteModal.style.display = 'none';
        document.body.style.overflow = 'auto';
        deleteID = null;
    });
}
cancelDeleteBtn.addEventListener('click', () => {
    deleteModal.style.display = 'none';
    document.body.style.overflow = 'auto';
    deleteID = null;
});

confirmDeleteBtn.addEventListener('click', () => {
    window.location.href = BASE_URL + 'admin/branch?action=delete&id=' + deleteID;
});

// ===== ĐÓNG MODAL KHI CLICK NGOÀI =====
window.addEventListener('click', (event) => {
    if (event.target === modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
        addBranchForm.reset();
    }
    if (event.target === viewModal) {
        viewModal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
    if (event.target === editModal) {
        editModal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
    if (event.target === deleteModal) {
        deleteModal.style.display = 'none';
        document.body.style.overflow = 'auto';
        deleteID = null;
    }
});

// ===== ĐÓNG MODAL KHI NHẤN ESC =====
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        if (modal.style.display === 'block') {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
            addBranchForm.reset();
        }
        if (viewModal.style.display === 'block') {
            viewModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        if (editModal.style.display === 'block') {
            editModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        if (deleteModal.style.display === 'block') {
            deleteModal.style.display = 'none';
            document.body.style.overflow = 'auto';
            deleteID = null;
        }
    }
});
