// Update item status via AJAX
function updateItemStatus(itemId, newStatus) {
    const formData = new FormData();
    formData.append('action', 'update_item_status');
    formData.append('item_id', itemId);
    formData.append('status', newStatus);

    fetch('', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update UI - remove active class from all buttons in this item
            const itemElement = document.querySelector(`[data-item-id="${itemId}"]`);
            if (itemElement) {
                const buttons = itemElement.querySelectorAll('.status-btn');
                buttons.forEach(btn => btn.classList.remove('active'));
                
                // Add active class to clicked button
                const activeButton = itemElement.querySelector(`.status-btn.${newStatus}`);
                if (activeButton) {
                    activeButton.classList.add('active');
                }
            }
        } else {
            alert('Cập nhật trạng thái thất bại!');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra!');
    });
}

// Auto-refresh notification badge
setInterval(() => {
    fetch('?action=get_pending_count')
        .then(response => response.json())
        .then(data => {
            const badge = document.querySelector('.notification-badge');
            if (data.count > 0) {
                if (badge) {
                    badge.textContent = data.count;
                } else {
                    const btn = document.querySelector('.notification-btn');
                    const newBadge = document.createElement('span');
                    newBadge.className = 'notification-badge';
                    newBadge.textContent = data.count;
                    btn.appendChild(newBadge);
                }
            } else if (badge) {
                badge.remove();
            }
        });
}, 30000); // Refresh every 30 seconds
