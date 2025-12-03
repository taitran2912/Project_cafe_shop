<?php include 'app/views/layout/header.php'; ?>

<style>
    .thank-you-title {
        color: #8b4513;
        font-weight: bold;
    }
    .order-value {
        color: #8b4513;
        font-weight: 600;
    }
    .confirmation-number {
        font-size: 20px;
        color: #8b4513;
        font-weight: bold;
    }
    .delivery-info strong {
        color: #8b4513;
    }
</style>

<body>
    <div class="thank-you-container">
        <div class="thank-you-card">

            <!-- Success Icon -->
            <div class="success-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>

            <h1 class="thank-you-title">Cảm ơn bạn!</h1>
            <p class="thank-you-subtitle">
                Đơn hàng của bạn đã được xác nhận thành công.<br>
                Chúng tôi sẽ giao hàng sớm nhất có thể.
            </p>

            <!-- Order Details -->
            <div class="order-details" id="orderDetails">
                
                <div class="order-item">
                    <span class="order-label">Mã đơn hàng:</span>
                    <p class="order-value confirmation-number">
                        <?= htmlspecialchars($data['orderCode']) ?>
                    </p>
                </div>

                <div class="order-item">
                    <span class="order-label">Thời gian đặt hàng:</span>
                    <span class="order-value">
                        <?= htmlspecialchars($data['Date']) ?>
                    </span>
                </div>

                <div class="order-item">
                    <span class="order-label">Số lượng sản phẩm:</span>
                    <span class="order-value">
                        <?= number_format($data['Quantity']) ?>
                    </span>
                </div>

                <div class="order-item">
                    <span class="order-label">Tổng tiền:</span>
                    <span class="order-value">
                        <?= number_format($data['Total']) ?> đ
                    </span>
                </div>

            </div>

            <!-- Delivery Info -->
            <div class="delivery-info">
                <h5>Thông tin giao hàng</h5>
                <p>
                    Phí vận chuyển: 
                    <strong><?= number_format($data['Shipping_Cost']) ?> đ</strong><br>
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="https://caffeshop.hieuthuocyentam.id.vn/menu" class="btn-primary-cafe">
                    Tiếp tục mua sắm
                </a>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <?php include 'app/views/layout/footer.php'; ?>
</body>
