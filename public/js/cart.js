function updateCartDisplay(data) {
  const cart = Array.isArray(data) ? data : [];
  let html = "";
  let total = 0;

  // 🔥 LẤY NÚT THANH TOÁN Ở ĐÂY
  const checkoutBtn = document.getElementById("checkout-btn");

  // ⚠️ Nếu giỏ hàng trống
  if (cart.length === 0) {
    html = `
      <div class="text-center text-gray-500 py-10 text-[18px]">
        Giỏ hàng của bạn đang trống.
      </div>
    `;

    if (checkoutBtn) {
      checkoutBtn.disabled = true;
      checkoutBtn.classList.add("disabled-btn");
    }

    document.getElementById("cart-content").innerHTML = html;
    return;
  }

  // 🛒 Có sản phẩm
  cart.forEach(item => {
    const subtotal = item.Price * item.Quantity;
    total += subtotal;

    html += `
      <div class="cart-item">
        <div class="cart-item-left">
          <img src="public/image/${item.Image}" alt="${item.Image}">
          <div class="cart-item-info">
            <h3>${item.Name}</h3>
            <p>${item.Description || ""}</p>
            <div class="cart-item-price">${item.Price.toLocaleString()}đ</div>
          </div>
        </div>
        
        <div class="flex items-center gap-4">
          <div class="quantity-control">
            <button onclick="updateQuantity(${item.ID_Product}, ${item.Quantity - 1})">−</button>
            <span>${item.Quantity}</span>
            <button onclick="updateQuantity(${item.ID_Product}, ${item.Quantity + 1})">+</button>
          </div>
          <i class="delete-btn fas fa-trash" onclick="removeFromCart(${item.ID_Product})"></i>
        </div>
      </div>
    `;
  });

  html += `
    <div class="text-right font-bold mt-6 text-[18px] text-[#8b4513]">
      Tổng cộng: ${total.toLocaleString()}đ
    </div>
  `;

  const cartContent = document.getElementById("cart-content");
  if (cartContent) cartContent.innerHTML = html;

  // ✔️ Có hàng → bật thanh toán
  if (checkoutBtn) {
    checkoutBtn.disabled = false;
    checkoutBtn.classList.remove("disabled-btn");
  }
}


/* --- ✅ Sửa lại phần updateQuantity & removeFromCart --- */

function updateQuantity(productId, quantity) {
  if (quantity <= 0) return removeFromCart(productId);
  $.post("cart/update", { customer_id: 1, product_id: productId, quantity })
    .done(() => {
      loadCart(); // gọi lại hàm loadCart() thay vì reload trang
    })
    .fail(err => console.error("Lỗi cập nhật số lượng:", err));
}

function removeFromCart(productId) {
  $.post("cart/delete", { customer_id: 1, product_id: productId })
    .done(() => {
      loadCart(); // gọi lại loadCart() để cập nhật giao diện
    })
    .fail(err => console.error("Lỗi xóa sản phẩm:", err));
}

/* --- Hàm loadCart vẫn giữ nguyên --- */
function loadCart() {
  $.ajax({
    url: "cart/getCart",
    type: "GET",
    dataType: "json",
    success: function (data) {
      updateCartDisplay(data);
    },
    error: function (xhr) {
      console.error("Không tải được giỏ hàng:", xhr.responseText);
    },
  });
}
