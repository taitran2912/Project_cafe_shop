<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cảm ơn bạn</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #f5ede0 0%, #f9f5f0 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            color: #d97836;
            text-decoration: none;
            margin-bottom: 30px;
            font-size: 14px;
            transition: color 0.3s;
        }

        .back-link:hover {
            color: #b85f28;
        }

        .back-link::before {
            content: "←";
            margin-right: 8px;
            font-size: 18px;
        }

        /* Progress Steps */
        .steps-container {
            display: flex;
            gap: 20px;
            margin-bottom: 40px;
            align-items: center;
            justify-content: flex-start;
            flex-wrap: wrap;
        }

        .step {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 20px;
            border: 2px solid #d97836;
            border-radius: 12px;
            background: white;
            min-width: 180px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .step:hover {
            background: #faf6f2;
        }

        .step.active {
            background: #faf6f2;
        }

        .step-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #d97836;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 20px;
            flex-shrink: 0;
        }

        .step-icon.checkmark {
            content: "✓";
        }

        .step-content h3 {
            font-size: 16px;
            color: #333;
            font-weight: 600;
        }

        .step-content p {
            font-size: 13px;
            color: #999;
        }

        /* Main Content */
        .content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 40px;
        }

        .thank-you-section {
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .thank-you-icon {
            width: 80px;
            height: 80px;
            background: #d97836;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 48px;
            margin-bottom: 20px;
            animation: scaleIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
            }
            to {
                transform: scale(1);
            }
        }

        .thank-you-section h2 {
            font-size: 28px;
            color: #333;
            margin-bottom: 12px;
            font-weight: 600;
        }

        .thank-you-section p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .order-number {
            background: #faf6f2;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #d97836;
        }

        .order-number label {
            display: block;
            font-size: 12px;
            color: #999;
            margin-bottom: 4px;
            font-weight: 600;
        }

        .order-number span {
            font-size: 18px;
            color: #333;
            font-weight: 600;
            font-family: 'Courier New', monospace;
        }

        /* Summary Section */
        .summary-section {
            background: white;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .summary-section h3 {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
            font-weight: 600;
            border-bottom: 1px solid #f0f0f0;
            padding-bottom: 15px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #f5f5f5;
        }

        .summary-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .summary-label {
            color: #666;
            font-size: 14px;
        }

        .summary-value {
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }

        .summary-value.highlight {
            color: #d97836;
            font-weight: 700;
            font-size: 16px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #f0f0f0;
            font-size: 18px;
            font-weight: 700;
            color: #333;
        }

        .total-row .amount {
            color: #d97836;
            font-size: 24px;
        }

        /* Buttons */
        .button-group {
            display: flex;
            gap: 12px;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        .btn {
            flex: 1;
            min-width: 150px;
            padding: 14px 24px;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            text-align: center;
            display: inline-block;
        }

        .btn-primary {
            background: #d97836;
            color: white;
        }

        .btn-primary:hover {
            background: #b85f28;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(217, 120, 54, 0.3);
        }

        .btn-secondary {
            background: #f5ede0;
            color: #d97836;
            border: 2px solid #d97836;
        }

        .btn-secondary:hover {
            background: #ece2d3;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .content {
                grid-template-columns: 1fr;
            }

            .steps-container {
                gap: 10px;
            }

            .step {
                min-width: 140px;
                padding: 12px 16px;
            }

            .thank-you-section,
            .summary-section {
                padding: 24px;
            }

            .thank-you-section h2 {
                font-size: 22px;
            }

            .button-group {
                flex-direction: column;
            }

            .btn {
                min-width: auto;
            }
        }

        /* Animation */
        .fade-in {
            animation: fadeIn 0.8s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="container fade-in">
        <a href="#" class="back-link">Quay lại của hàng</a>

        <!-- Progress Steps -->
        <div class="steps-container">
            <div class="step active">
                <div class="step-icon checkmark">✓</div>
                <div class="step-content">
                    <h3>Gỏi hàng</h3>
                    <p>Hoàn tất</p>
                </div>
            </div>
            <div class="step active">
                <div class="step-icon checkmark">✓</div>
                <div class="step-content">
                    <h3>Thanh toán</h3>
                    <p>Hoàn tất</p>
                </div>
            </div>
            <div class="step active">
                <div class="step-icon" style="background: #d97836;">3</div>
                <div class="step-content">
                    <h3>Cảm ơn bạn</h3>
                    <p>Xong</p>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="content">
            <!-- Thank You Section -->
            <div class="thank-you-section">
                <div class="thank-you-icon">✓</div>
                <h2>Cảm ơn bạn!</h2>
                <p>Đơn hàng của bạn đã được xác nhận thành công. Chúng tôi sẽ gửi email xác nhận và thông tin theo dõi đơn hàng của bạn trong vòng 24 giờ.</p>
                
                <div class="order-number">
                    <label>Mã đơn hàng</label>
                    <span>#ORD-2024-12345</span>
                </div>

                <div style="margin-top: 20px; padding: 15px; background: #f0f8ff; border-radius: 8px; border-left: 4px solid #d97836;">
                    <p style="font-size: 13px; color: #666; margin: 0;">
                        <strong>Thời gian giao hàng dự kiến:</strong> 3-5 ngày làm việc<br>
                        <strong>Phí vận chuyển:</strong> Miễn phí
                    </p>
                </div>
            </div>

            <!-- Summary Section -->
            <div class="summary-section">
                <h3>Tóm tắt đơn hàng</h3>
                
                <div class="summary-row">
                    <span class="summary-label">Tổng tiền hàng</span>
                    <span class="summary-value highlight">850.000đ</span>
                </div>

                <div class="summary-row">
                    <span class="summary-label">Phí vận chuyển</span>
                    <span class="summary-value highlight">0đ</span>
                </div>

                <div class="summary-row">
                    <span class="summary-label">Mã giảm giá</span>
                    <span class="summary-value highlight">-50.000đ</span>
                </div>

                <div class="summary-row">
                    <span class="summary-label">Thuế (10%)</span>
                    <span class="summary-value highlight">80.000đ</span>
                </div>

                <div class="total-row">
                    <span>Tổng cộng</span>
                    <span class="amount">880.000đ</span>
                </div>

                <div class="button-group">
                    <button class="btn btn-primary" onclick="downloadInvoice()">Tải hóa đơn</button>
                    <button class="btn btn-secondary" onclick="continueShop()">Tiếp tục mua sắm</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Animation on load
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.querySelector('.container');
            container.classList.add('fade-in');
        });

        // Download Invoice function
        function downloadInvoice() {
            alert('Đang tải hóa đơn...\n\nMã đơn hàng: #ORD-2024-12345');
            // In a real application, this would download the invoice PDF
        }

        // Continue Shopping function
        function continueShop() {
            window.location.href = '/';
        }

        // Back link
        document.querySelector('.back-link').addEventListener('click', function(e) {
            e.preventDefault();
            window.history.back();
        });
    </script>
</body>
</html>