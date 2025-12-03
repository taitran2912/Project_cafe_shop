 
<body>
    <div class="thank-you-container">
        <div class="thank-you-card">
            <!-- Success Icon -->
            <div class="success-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>

            <!-- Title -->
            <h1 class="thank-you-title">Cảm ơn <span>bạn</span>!</h1>
            <p class="thank-you-subtitle">
                Đơn hàng của bạn đã được xác nhận thành công.<br>
                Chúng tôi sẽ giao hàng sớm nhất có thể.
            </p>

            <!-- Order Details -->
            <div class="order-details" id="orderDetails">
                <div class="order-item">
                    <span class="order-label">Mã đơn hàng:</span>
                    <span class="order-value confirmation-number" id="orderNumber"><?= $data['orderCode'] ?></span>
                </div>
                <div class="order-item">
                    <span class="order-label">Thời gian đặt hàng:</span>
                    <span class="order-value" id="orderTime"><?= $data['Date'] ?></span>
                </div>
                <div class="order-item">
                    <span class="order-label">Số lượng sản phẩm:</span>
                    <span class="order-value" id="productCount"><?= $data['Quantity'] ?></span>
                </div>
                <div class="order-item">
                    <span class="order-label">Tổng tiền:</span>
                    <span class="order-value" id="totalAmount"><?= $data['Total']?></span>
                </div>
            </div>

            <!-- Delivery Info -->
            <div class="delivery-info">
                <h5>📦 Thông tin giao hàng</h5>
                <p>
                    Phí vận chuyển: <strong><?= $data['Shipping_Cost']?></strong><br>
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="https://caffeshop.hieuthuocyentam.id.vn/menu" class="btn-primary-cafe">Tiếp tục mua sắm</a>

            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>