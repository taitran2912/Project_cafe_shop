<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nhận Đơn Hàng - Cafe Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/orders.css">
</head>
<body>
    <div class="content-area">
        <!-- Alert Messages -->
        <?php if (isset($successMessage) && $successMessage): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <?= htmlspecialchars($successMessage) ?>
        </div>
        <?php endif; ?>

        <?php if (isset($errorMessage) && $errorMessage): ?>
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
                <?php if (isset($pendingCount) && $pendingCount > 0): ?>
                <span class="notification-badge"><?= $pendingCount ?></span>
                <?php endif; ?>
            </button>
        </div>

        <!-- Orders Container -->
        <div class="orders-container" id="ordersContainer">
            <?php if (!isset($orders) || empty($orders)): ?>
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
                            if (isset($order['details'])) {
                                foreach ($order['details'] as $item): 
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
                            <?php 
                                endforeach;
                            } // Close if (isset($order['details']))
                            ?>
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

    <script src="<?= BASE_URL ?>public/js/admin/orders.js"></script>
</body>
</html>
