<!-- Header -->
<?php
    include 'app/views/layout/header.php'; 
?>

<!-- Payment Section -->
<main>
    <div class="container">
        <h1 class="page-title">Thanh Toán Đơn Hàng</h1>

        <div class="success-message" id="successMessage">
            <i class="fas fa-check-circle"></i> Đơn hàng của bạn đã được xử lý thành công!
        </div>

        <!-- <div class="checkout-container"> -->
            <!-- Checkout Form -->
            <!-- <form class="checkout-form" id="checkoutForm"> -->
                <!-- Delivery Address -->
                <!-- <div class="form-section">
                    <h3><i class="fas fa-map-marker-alt"></i> Địa Chỉ Giao Hàng</h3>
                    <div class="form-group">
                        <label for="address">Địa chỉ *</label>
                        <input type="text" id="address" name="address" placeholder="Số nhà, tên đường" required>
                        <span class="error-message">Vui lòng nhập địa chỉ</span>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="ward">Phường/Xã *</label>
                            <input type="text" id="ward" name="ward" required>
                            <span class="error-message">Vui lòng nhập phường/xã</span>
                        </div>
                        <div class="form-group">
                            <label for="district">Quận/Huyện *</label>
                            <input type="text" id="district" name="district" required>
                            <span class="error-message">Vui lòng nhập quận/huyện</span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="city">Thành phố *</label>
                            <input type="text" id="city" name="city" required>
                            <span class="error-message">Vui lòng nhập thành phố</span>
                        </div>
                        <div class="form-group">
                            <label for="zipcode">Mã bưu điện</label>
                            <input type="text" id="zipcode" name="zipcode">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="notes">Ghi chú đơn hàng</label>
                        <textarea id="notes" name="notes" placeholder="Ghi chú thêm cho đơn hàng (tùy chọn)"></textarea>
                    </div>
                </div> -->

                <!-- Payment Method -->
                <!-- <div class="form-section">
                    <h3><i class="fas fa-credit-card"></i> Phương Thức Thanh Toán</h3>
                    <div class="payment-methods">
                        <div class="payment-option">
                            <input type="radio" id="card" name="payment" value="card" checked>
                            <label for="card" class="payment-label">
                                <i class="fas fa-credit-card"></i>
                                <span>Thẻ Tín Dụng</span>
                            </label>
                        </div>
                        <div class="payment-option">
                            <input type="radio" id="bank" name="payment" value="bank">
                            <label for="bank" class="payment-label">
                                <i class="fas fa-university"></i>
                                <span>Chuyển Khoản</span>
                            </label>
                        </div>
                        <div class="payment-option">
                            <input type="radio" id="wallet" name="payment" value="wallet">
                            <label for="wallet" class="payment-label">
                                <i class="fas fa-wallet"></i>
                                <span>Ví Điện Tử</span>
                            </label>
                        </div>
                        <div class="payment-option">
                            <input type="radio" id="cod" name="payment" value="cod">
                            <label for="cod" class="payment-label">
                                <i class="fas fa-money-bill"></i>
                                <span>Thanh Toán Khi Nhận</span>
                            </label>
                        </div>
                    </div>

                    
                    <div id="cardDetails" style="display: none;">
                        <div class="form-group">
                            <label for="cardName">Tên Chủ Thẻ *</label>
                            <input type="text" id="cardName" name="cardName">
                            <span class="error-message">Vui lòng nhập tên chủ thẻ</span>
                        </div>
                        <div class="form-group">
                            <label for="cardNumber">Số Thẻ *</label>
                            <input type="text" id="cardNumber" name="cardNumber" placeholder="1234 5678 9012 3456" maxlength="19">
                            <span class="error-message">Vui lòng nhập số thẻ hợp lệ</span>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="expiry">Ngày Hết Hạn *</label>
                                <input type="text" id="expiry" name="expiry" placeholder="MM/YY" maxlength="5">
                                <span class="error-message">Vui lòng nhập ngày hết hạn</span>
                            </div>
                            <div class="form-group">
                                <label for="cvv">CVV *</label>
                                <input type="text" id="cvv" name="cvv" placeholder="123" maxlength="3">
                                <span class="error-message">Vui lòng nhập CVV</span>
                            </div>
                        </div>
                    </div>
                </div> -->

                <!-- Buttons -->
                <!-- <div class="button-group">
                    <a href="cart.html" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay Lại
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check"></i> Hoàn Tất Thanh Toán
                    </button>
                </div> -->
            <!-- </form> -->

            <!-- Order Summary -->
            <div class="container-sm order-summary">
                <h3><i class="fas fa-shopping-cart"></i> Tóm Tắt Đơn Hàng</h3>
<?php if (!empty($data['product'])): ?>
    <?php foreach ($data['product'] as $product): ?>
                <div id="orderItems">
                    <div class="order-item">
                        <div class="item-info">
                            <div class="item-name"><?= htmlspecialchars($product['Name']) ?></div>
                            <div class="item-quantity"><?= $product['Quantity'] ?></div>
                        </div>
                        <div class="item-price"><?= number_format($product['Price'])     ?>đ</div>
                    </div>
    <?php endforeach; ?>
<?php endif; ?>
                </div>

                <div class="summary-divider"></div>

                <div class="summary-row">
                    <span>Tạm tính:</span>
                    <span>115.000đ</span>
                </div>
                <div class="summary-row">
                    <span>Phí giao hàng:</span>
                    <span>15.000đ</span>
                </div>
                <div class="summary-row">
                    <span>Giảm giá:</span>
                    <span>0đ</span>
                </div>

                <div class="summary-row">
                    <span>Điểm thưởng:</span>
                    <span>0đ</span>
                </div>

                <div class="summary-divider"></div>

                <div class="summary-row total">
                    <span>Tổng cộng:</span>
                    <span>130.000đ</span>
                </div>

                <div class="button-group">
                    <a href="cart.html" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay Lại
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check"></i> Hoàn Tất Thanh Toán
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Footer -->
<?php
    include 'app/views/layout/footer.php'; 
?>