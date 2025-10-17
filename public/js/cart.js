function updateCartDisplay(data) {
  const cart = Array.isArray(data) ? data : [];
  let html = "";
  let total = 0;

  cart.forEach(item => {
    const subtotal = item.Price * item.Quantity;
    total += subtotal;

    html += `
      <div class="cart-item">
        <div class="cart-item-left">
          <img src="http://localhost/Project_cafe_shop/public/image/${item.Image}" alt="${item.Name}">
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
}

/* --- ✅ Sửa lại phần updateQuantity & removeFromCart --- */

function updateQuantity(productId, quantity) {
  if (quantity <= 0) return removeFromCart(productId);
  $.post("http://localhost/Project_cafe_shop/index.php?url=cart/update", { customer_id: 1, product_id: productId, quantity })
    .done(() => {
      loadCart(); // gọi lại hàm loadCart() thay vì reload trang
    })
    .fail(err => console.error("Lỗi cập nhật số lượng:", err));
}

function removeFromCart(productId) {
  $.post("/Cart/delete", { customer_id: 1, product_id: productId })
    .done(() => {
      loadCart(); // gọi lại loadCart() để cập nhật giao diện
    })
    .fail(err => console.error("Lỗi xóa sản phẩm:", err));
}

/* --- Hàm loadCart vẫn giữ nguyên --- */
function loadCart() {
  $.ajax({
    url: "http://localhost/Project_cafe_shop/index.php?url=cart/getCart",
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
