<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Đặt Hàng Cà Phê</title>
    <link rel="stylesheet" href="https://caffeshop.hieuthuocyentam.id.vn/public.css/style.css">
</head>
<body>
    <div class="container">
        <!-- Back Link -->
        <div class="back-link">
            <a href="#">← Quay lại cửa hàng</a>
        </div>

        <!-- Steps -->
        <div class="steps">
            <div class="step completed">
                <div class="step-number">✓</div>
                <div class="step-content">
                    <div class="step-title">Giỏ hàng</div>
                    <div class="step-subtitle">Hoàn tất</div>
                </div>
            </div>
            <div class="step-line"></div>
            <div class="step active">
                <div class="step-number">2</div>
                <div class="step-content">
                    <div class="step-title">Thanh toán</div>
                    <div class="step-subtitle">Hiện tại</div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Left Column -->
            <div class="order-items">
                <div class="section-title">
                    <span class="icon">📦</span>
                    <span>Đơn hàng của bạn</span>
                </div>

                <div class="items-list">
                    <!-- Item 1 -->
                    <div class="order-item">
                        <img src="https://images.unsplash.com/photo-1559056199-641a0ac8b3f7?w=80&h=80&fit=crop" alt="Cà phê đen đắng" class="item-image">
                        <div class="item-info">
                            <div class="item-name">Cà phê đen đắng</div>
                            <div class="item-qty">Số lượng: 2</div>
                        </div>
                        <div class="item-price">50.000đ</div>
                    </div>

                    <!-- Item 2 -->
                    <div class="order-item">
                        <img src="https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=80&h=80&fit=crop" alt="Cà phê sữa đá" class="item-image">
                        <div class="item-info">
                            <div class="item-name">Cà phê sữa đá</div>
                            <div class="item-qty">Số lượng: 1</div>
                        </div>
                        <div class="item-price">28.000đ</div>
                    </div>

                    <!-- Item 3 -->
                    <div class="order-item">
                        <img src="https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=80&h=80&fit=crop" alt="Bạc xỉu" class="item-image">
                        <div class="item-info">
                            <div class="item-name">Bạc xỉu</div>
                            <div class="item-qty">Số lượng: 1</div>
                        </div>
                        <div class="item-price">30.000đ</div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="order-summary">
                <h2 class="summary-title">Tóm tắt đơn hàng</h2>

                <div class="summary-item">
                    <span>Tổng tiền hàng</span>
                    <span class="price">108.000đ</span>
                </div>

                <div class="summary-item">
                    <span>Thuế (5%)</span>
                    <span class="price">5.400đ</span>
                </div>

                <div class="summary-item total">
                    <span>Tổng cộng</span>
                    <span class="price">113.400đ</span>
                </div>

                <button class="btn-checkout">Tiếp tục thanh toán →</button>

                <div class="delivery-time">
                    <span class="clock-icon">⏱️</span>
                    <span>Thời gian giao hàng: 15-30 phút</span>
                </div>
            </div>
        </div>

        <!-- Delivery Info -->
        <div class="delivery-info">
            <h2 class="section-title">Thông tin giao hàng</h2>

            <div class="info-row">
                <div class="form-group">
                    <label>Họ và tên</label>
                    <input type="text" placeholder="Nhập tên của bạn" class="form-input">
                </div>
                <div class="form-group">
                    <label>Số điện thoại</label>
                    <input type="tel" placeholder="0xxx xxx xxx" class="form-input">
                </div>
            </div>

            <div class="form-group">
                <label>Địa chỉ giao hàng</label>
                <input type="text" placeholder="Nhập địa chỉ" class="form-input full-width">
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
