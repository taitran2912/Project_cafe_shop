<?php
// Initialize messages
$successMessage = '';
$errorMessage = '';

// Handle success messages from redirect
if (isset($_GET['success'])) {
    switch ($_GET['success']) {
        case 'confirm':
            $successMessage = 'Xác nhận đơn hàng thành công!';
            break;
        case 'update_item':
            $successMessage = 'Cập nhật trạng thái món thành công!';
            break;
    }
}

// Handle error messages
if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'confirm':
            $errorMessage = 'Xác nhận đơn hàng thất bại!';
            break;
        case 'update_item':
            $errorMessage = 'Cập nhật trạng thái món thất bại!';
            break;
    }
}

// Get orders data from database
$orderModel = new Order();
$orders = $orderModel->getPendingOrders();
$pendingCount = count(array_filter($orders, function($order) {
    return $order['Status'] === 'pending';
}));
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nhận Đơn Hàng - Cafe Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: #f5f5f5;
        }

        /* Alert Messages */
        .alert {
            padding: 16px 20px;
            margin-bottom: 24px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .alert-error {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .alert i {
            font-size: 20px;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
        }

        .header h1 {
            font-size: 28px;
            color: #3e2723;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header h1 i {
            color: #d4a574;
        }

        .notification-btn {
            position: relative;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            color: white;
            border: none;
            cursor: pointer;
            font-size: 20px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(255, 107, 107, 0.3);
        }

        .notification-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 16px rgba(255, 107, 107, 0.4);
        }

        .notification-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background-color: #ffd966;
            color: #3e2723;
            font-size: 11px;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 10px;
            min-width: 20px;
            text-align: center;
        }

        /* Orders Grid */
        .orders-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }

        .order-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .order-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            border-color: #d4a574;
        }

        .order-header {
            background: linear-gradient(135deg, #3e2723 0%, #5d4037 100%);
            color: white;
            padding: 16px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .order-number {
            font-size: 18px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .order-time {
            font-size: 12px;
            opacity: 0.85;
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .order-status {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-pending {
            background-color: #ffd966;
            color: #3e2723;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }

        .status-confirmed {
            background-color: #70ad47;
            color: white;
        }

        .order-content {
            padding: 20px;
        }

        .customer-info {
            margin-bottom: 16px;
            padding-bottom: 16px;
            border-bottom: 2px solid #f0f0f0;
        }

        .customer-info p {
            font-size: 13px;
            color: #666;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .customer-info i {
            color: #d4a574;
            width: 16px;
        }

        .customer-name {
            font-weight: 700;
            color: #3e2723;
            font-size: 16px;
        }

        .branch-info {
            background: linear-gradient(135deg, #fff8f0 0%, #fef5e7 100%);
            padding: 10px 12px;
            border-radius: 8px;
            margin-bottom: 16px;
            border-left: 3px solid #d4a574;
        }

        .branch-info i {
            color: #d4a574;
        }

        .items-list {
            margin-bottom: 16px;
        }

        .item {
            padding: 14px;
            background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%);
            border-radius: 10px;
            margin-bottom: 12px;
            border-left: 4px solid #d4a574;
            transition: all 0.3s ease;
        }

        .item:hover {
            background: linear-gradient(135deg, #fff8f0 0%, #fef5e7 100%);
            transform: translateX(4px);
        }

        .item-name {
            font-weight: 600;
            color: #3e2723;
            font-size: 15px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .item-name i {
            color: #d4a574;
            font-size: 14px;
        }

        .item-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .item-quantity {
            font-size: 13px;
            color: #666;
            font-weight: 500;
        }

        .item-price {
            font-size: 14px;
            color: #3e2723;
            font-weight: 600;
        }

        .item-status {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .status-btn {
            flex: 1;
            min-width: 110px;
            padding: 8px 12px;
            border: none;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid #ddd;
            background-color: white;
            color: #666;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .status-btn:hover {
            border-color: #d4a574;
            background-color: #fef9f3;
            transform: scale(1.05);
        }

        .status-btn.active {
            background: linear-gradient(135deg, #d4a574 0%, #c49254 100%);
            border-color: #d4a574;
            color: white;
            box-shadow: 0 4px 12px rgba(212, 165, 116, 0.3);
        }

        .status-btn.preparing {
            border-color: #ffd966;
            color: #f59e0b;
        }

        .status-btn.preparing.active {
            background: linear-gradient(135deg, #ffd966 0%, #ffcc33 100%);
            color: #3e2723;
            box-shadow: 0 4px 12px rgba(255, 217, 102, 0.3);
        }

        .status-btn.completed {
            border-color: #70ad47;
            color: #22863a;
        }

        .status-btn.completed.active {
            background: linear-gradient(135deg, #70ad47 0%, #5a9335 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(112, 173, 71, 0.3);
        }

        .status-btn.served {
            border-color: #06b6d4;
            color: #0891b2;
        }

        .status-btn.served.active {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(6, 182, 212, 0.3);
        }

        .order-footer {
            padding: 16px 20px;
            background: linear-gradient(135deg, #f9f9f9 0%, #f0f0f0 100%);
            border-top: 2px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .total-price {
            font-weight: 700;
            color: #3e2723;
            font-size: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .total-price i {
            color: #d4a574;
        }

        .confirm-btn {
            padding: 12px 24px;
            background: linear-gradient(135deg, #d4a574 0%, #c49254 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(212, 165, 116, 0.3);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .confirm-btn:hover {
            background: linear-gradient(135deg, #c49254 0%, #b38043 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(212, 165, 116, 0.4);
        }

        .confirm-btn.confirmed {
            background: linear-gradient(135deg, #70ad47 0%, #5a9335 100%);
            cursor: default;
            box-shadow: 0 4px 12px rgba(112, 173, 71, 0.3);
        }

        .confirm-btn.confirmed:hover {
            background: linear-gradient(135deg, #70ad47 0%, #5a9335 100%);
            transform: none;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        }

        .empty-state i {
            font-size: 64px;
            color: #d4a574;
            margin-bottom: 16px;
        }

        .empty-state h3 {
            color: #3e2723;
            margin-bottom: 8px;
        }

        .empty-state p {
            color: #666;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .orders-container {
                grid-template-columns: 1fr;
            }

            .header h1 {
                font-size: 22px;
            }

            .item-status {
                flex-direction: column;
            }

            .status-btn {
                min-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="content-area">
        <!-- Alert Messages -->
        <?php if ($successMessage): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <?= htmlspecialchars($successMessage) ?>
        </div>
        <?php endif; ?>

        <?php if ($errorMessage): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <?= htmlspecialchars($errorMessage) ?>
        </div>
        <?php endif; ?>

        <div class="header">
            <h1>
                <i class="fas fa-shopping-cart"></i>
                Nhận Đơn Hàng
            </h1>
            <button class="notification-btn" title="Thông báo đơn hàng mới">
                <i class="fas fa-bell"></i>
                <?php if ($pendingCount > 0): ?>
                <span class="notification-badge"><?= $pendingCount ?></span>
                <?php endif; ?>
            </button>
        </div>

        <!-- Orders Container -->
        <div class="orders-container" id="ordersContainer">
            <?php if (empty($orders)): ?>
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h3>Chưa có đơn hàng nào</h3>
                    <p>Các đơn hàng mới sẽ hiển thị tại đây</p>
                </div>
            <?php else: ?>
                <?php foreach ($orders as $order): ?>
                <div class="order-card" data-order-id="<?= $order['ID'] ?>">
                    <div class="order-header">
                        <div>
                            <div class="order-number">
                                <i class="fas fa-hashtag"></i>
                                ĐH<?= str_pad($order['ID'], 3, '0', STR_PAD_LEFT) ?>
                            </div>
                            <div class="order-time">
                                <i class="far fa-clock"></i>
                                <?= date('H:i', strtotime($order['Time'])) ?>
                            </div>
                        </div>
                        <span class="order-status <?= $order['Status'] === 'pending' ? 'status-pending' : 'status-confirmed' ?>">
                            <?= $order['Status'] === 'pending' ? 'Chờ xác nhận' : 'Đã xác nhận' ?>
                        </span>
                    </div>

                    <div class="order-content">
                        <div class="customer-info">
                            <p class="customer-name">
                                <i class="fas fa-user"></i>
                                <?= htmlspecialchars($order['CustomerName']) ?>
                            </p>
                            <p>
                                <i class="fas fa-phone"></i>
                                <?= htmlspecialchars($order['CustomerPhone']) ?>
                            </p>
                            <p>
                                <i class="fas fa-map-marker-alt"></i>
                                <?= htmlspecialchars($order['Address']) ?>
                            </p>
                        </div>

                        <?php if (!empty($order['BranchName'])): ?>
                        <div class="branch-info">
                            <i class="fas fa-store"></i>
                            <strong>Chi nhánh:</strong> <?= htmlspecialchars($order['BranchName']) ?>
                        </div>
                        <?php endif; ?>

                        <div class="items-list">
                            <?php 
                            $orderDetails = $orderModel->getOrderDetails($order['ID']);
                            foreach ($orderDetails as $item): 
                            ?>
                            <div class="item" data-item-id="<?= $item['ID'] ?>">
                                <div class="item-name">
                                    <i class="fas fa-coffee"></i>
                                    <?= htmlspecialchars($item['ProductName']) ?>
                                </div>
                                <div class="item-details">
                                    <span class="item-quantity">
                                        <i class="fas fa-times"></i> <?= $item['Quantity'] ?>
                                    </span>
                                    <span class="item-price">
                                        <?= number_format($item['Price'], 0, ',', '.') ?>đ
                                    </span>
                                </div>
                                <div class="item-status">
                                    <button class="status-btn preparing <?= isset($item['ItemStatus']) && $item['ItemStatus'] === 'preparing' ? 'active' : '' ?>" 
                                        onclick="updateItemStatus(<?= $item['ID'] ?>, 'preparing')">
                                        <i class="fas fa-sync-alt"></i> Đang chế biến
                                    </button>
                                    <button class="status-btn completed <?= isset($item['ItemStatus']) && $item['ItemStatus'] === 'completed' ? 'active' : '' ?>" 
                                        onclick="updateItemStatus(<?= $item['ID'] ?>, 'completed')">
                                        <i class="fas fa-check"></i> Hoàn thành
                                    </button>
                                    <button class="status-btn served <?= isset($item['ItemStatus']) && $item['ItemStatus'] === 'served' ? 'active' : '' ?>" 
                                        onclick="updateItemStatus(<?= $item['ID'] ?>, 'served')">
                                        <i class="fas fa-utensils"></i> Đã phục vụ
                                    </button>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="order-footer">
                        <div class="total-price">
                            <i class="fas fa-money-bill-wave"></i>
                            <?= number_format($order['Total'], 0, ',', '.') ?> VNĐ
                        </div>
                        <form method="POST" action="" style="display: inline;">
                            <input type="hidden" name="action" value="confirm_order">
                            <input type="hidden" name="order_id" value="<?= $order['ID'] ?>">
                            <button type="submit" 
                                class="confirm-btn <?= $order['Status'] === 'confirmed' ? 'confirmed' : '' ?>" 
                                <?= $order['Status'] === 'confirmed' ? 'disabled' : '' ?>>
                                <?php if ($order['Status'] === 'confirmed'): ?>
                                    <i class="fas fa-check-circle"></i> Đã xác nhận
                                <?php else: ?>
                                    <i class="fas fa-check"></i> Xác nhận đơn
                                <?php endif; ?>
                            </button>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
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
    </script>
</body>
</html>
