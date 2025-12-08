<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Đặt Hàng Cà Phê</title>
    <link rel="stylesheet" href="https://caffeshop.hieuthuocyentam.id.vn/public/css/guest.css">
</head>
<body>
    <div class="container">
        <!-- Back Link -->
        <div class="back-link">
            <a href="">← Quay lại cửa hàng</a>
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
                    <span id="order-location">Đơn hàng của bạn</span>
                </div>

                <div class="items-list" id="order-items"></div>
            </div>

            <!-- Right Column -->
            <div class="order-summary">
                <h2 class="summary-title">Tóm tắt đơn hàng</h2>

                <div class="summary-item">
                    <span>Tổng tiền hàng</span>
                    <span class="price" id="subtotal">0đ</span>
                </div>

                <div class="summary-item">
                    <span>Điểm đã tích</span>
                    <span class="price" id="user-points">0đ</span>
                </div>

                <div class="summary-item">
                    <span>Điểm muốn dùng</span>
                    <input type="number" id="usePoints" min="0" value="0" class="input-small">
                </div>

                <div class="summary-item">
                    <span>Mã giảm giá</span>
                    <input type="text" id="couponCode" placeholder="Nhập mã..." class="input-small">
                </div>

                <button class="btn-apply" onclick="applyCoupon()">Áp dụng mã</button>

                <div class="summary-item discount">
                    <span>Giảm giá</span>
                    <span class="price" id="discountAmount">0đ</span>
                </div>

                <div class="summary-item total">
                    <span>Tổng cộng</span>
                    <span class="price" id="total">0đ</span>
                </div>

                <button class="btn-checkout">Tiếp tục thanh toán →</button>
            </div>
        </div>
    </div>

<script>
// =====================================================
// KHỞI CHẠY TRANG CHECKOUT
// =====================================================
document.addEventListener("DOMContentLoaded", () => {

    const order = JSON.parse(localStorage.getItem("pendingOrder"));
    console.log("PENDING ORDER:", order);

    if (!order || !order.items?.length) {
        document.getElementById("order-items").innerHTML =
            "<p class='text-danger'>Giỏ hàng trống, vui lòng quay lại menu.</p>";
        document.querySelector(".btn-checkout").style.display = "none";
        return;
    }

    setOrderLocation(order);
    renderOrderItems(order.items);
    updateSummary(order.items);

    // =====================================================
    // LẤY SỐ ĐIỆN THOẠI KHÁCH – ƯU TIÊN 4 NGUỒN
    // =====================================================
    let phone =
        order.customerPhone
        ?? order.phone
        ?? new URLSearchParams(window.location.search).get("phone")
        ?? localStorage.getItem("customerPhone")
        ?? null;

    console.log("PHONE FOR POINTS:", phone);

    if (phone) {
        loadUserPoints(phone);
    }
});


// =====================================================
// HIỂN THỊ "Đơn hàng của bạn tại cửa hàng ..."
// =====================================================
function setOrderLocation(order) {
    const store = order.storeName ?? "Cửa hàng";
    const table = order.tableNumber ? `tại bàn ${order.tableNumber}` : "mang về";

    document.getElementById("order-location").innerText =
        `Đơn hàng của bạn tại ${store} - ${table}`;
}


// =====================================================
// DANH SÁCH SẢN PHẨM
// =====================================================
function renderOrderItems(items) {
    const container = document.getElementById("order-items");
    container.innerHTML = "";

    items.forEach(item => {
        container.innerHTML += `
            <div class="order-item">
                <img src="https://caffeshop.hieuthuocyentam.id.vn/public/image/${item.image ?? 'default.jpg'}"
                    alt="${item.name}" class="item-image">

                <div class="item-info">
                    <div class="item-name">${item.name}</div>
                    <div class="item-qty">Số lượng: ${item.quantity}</div>
                </div>

                <div class="item-price">
                    ${(item.price * item.quantity).toLocaleString('vi-VN')}đ
                </div>
            </div>
        `;
    });
}


// =====================================================
// TÍNH TỔNG TIỀN HÀNG
// =====================================================
function updateSubtotal(items) {
    let subtotal = items.reduce((sum, item) =>
        sum + (item.price * item.quantity), 0
    );

    document.getElementById("subtotal").innerText =
        subtotal.toLocaleString("vi-VN") + "đ";

    return subtotal;
}


// =====================================================
// TÍNH TỔNG CỘNG (KHÔNG THUẾ)
// =====================================================
function updateSummary(items) {
    let total = updateSubtotal(items);
    document.getElementById("total").innerText =
        total.toLocaleString("vi-VN") + "đ";
}


// =====================================================
// GỌI API LẤY ĐIỂM – CHẠY 100%
// =====================================================
function loadUserPoints(phone) {
    fetch(`https://caffeshop.hieuthuocyentam.id.vn/checkout/points?phone=${phone}`)
        .then(res => res.json())
        .then(data => {
            console.log("POINT DATA:", data);

            const element = document.getElementById("user-points");

            if (data.success) {
                const points = Number(data.points) || 0;
                element.innerText = points.toLocaleString("vi-VN") + "đ";
            } else {
                element.innerText = "0đ";
            }
        })
        .catch(err => {
            console.error("Lỗi load điểm:", err);
            document.getElementById("user-points").innerText = "0đ";
        });
}

document.getElementById("usePoints").addEventListener("input", updateSummaryAfterPoints);

function updateSummaryAfterPoints() {
    const order = JSON.parse(localStorage.getItem("pendingOrder"));
    const subtotal = updateSubtotal(order.items);

    const userPoints = Number(document.getElementById("user-points").innerText.replace(/\D/g, "")) || 0;
    const usePoints = Number(document.getElementById("usePoints").value) || 0;

    // Không cho dùng điểm vượt quá điểm đang có
    if (usePoints > userPoints) {
        document.getElementById("usePoints").value = userPoints;
    }

    // Không cho dùng quá tổng tiền
    let validUsePoints = Math.min(usePoints, subtotal);

    let finalTotal = subtotal - validUsePoints;

    document.getElementById("total").innerText =
        finalTotal.toLocaleString("vi-VN") + "đ";

    return finalTotal;
}

function confirmOrder() {
    const order = JSON.parse(localStorage.getItem("pendingOrder"));
    if (!order) {
        alert("Không tìm thấy đơn hàng!");
        return;
    }

    const usePoints = Number(document.getElementById("usePoints").value) || 0;

    order.usePoints = usePoints;        // Gửi lên server
    order.finalTotal = updateSummaryAfterPoints(); // Tổng tiền sau giảm

    fetch("https://caffeshop.hieuthuocyentam.id.vn/api/order/save", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(order)
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            localStorage.removeItem("pendingOrder");
            window.location.href = "https://caffeshop.hieuthuocyentam.id.vn/thankyou";
        } else {
            alert("Không thể đặt hàng, vui lòng thử lại!");
        }
    })
    .catch(err => {
        console.error(err);
        alert("Lỗi kết nối server!");
    });
}

</script>
</body>
</html>
