// Cart functionality
let cart = JSON.parse(localStorage.getItem("cart")) || []

function formatPrice(price) {
  return new Intl.NumberFormat("vi-VN").format(price) + "đ"
}

function addToCart(item) {
  const existingItem = cart.find((cartItem) => cartItem.id === item.id)

  if (existingItem) {
    existingItem.quantity += 1
  } else {
    cart.push({ ...item, quantity: 1 })
  }

  localStorage.setItem("cart", JSON.stringify(cart))
  updateCartDisplay()
}

function removeFromCart(itemId) {
  cart = cart.filter((item) => item.id !== itemId)
  localStorage.setItem("cart", JSON.stringify(cart))
  updateCartDisplay()
}

function updateQuantity(itemId, newQuantity) {
  if (newQuantity <= 0) {
    removeFromCart(itemId)
    return
  }

  const item = cart.find((cartItem) => cartItem.id === itemId)
  if (item) {
    item.quantity = newQuantity
    localStorage.setItem("cart", JSON.stringify(cart))
    updateCartDisplay()
  }
}

function calculateTotal() {
  return cart.reduce((total, item) => total + item.price * item.quantity, 0)
}

function updateCartDisplay() {
  const cartContent = document.getElementById("cart-content")

  if (cart.length === 0) {
    cartContent.innerHTML = `
            <div class="text-center py-16">
                <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5-6m0 0h15M17 21a2 2 0 100-4 2 2 0 000 4zM9 21a2 2 0 100-4 2 2 0 000 4z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-600 mb-4">Giỏ hàng trống</h3>
                <p class="text-gray-500 mb-8">Bạn chưa có sản phẩm nào trong giỏ hàng</p>
                <a href="menu.html" class="btn-primary">Xem thực đơn</a>
            </div>
        `
    return
  }

  const cartItemsHTML = cart
    .map(
      (item) => `
        <div class="cart-item">
            <div class="flex items-center space-x-4">
                <img src="${item.image}" alt="${item.name}" class="w-20 h-20 object-cover rounded-lg">
                <div class="flex-1">
                    <h3 class="font-display text-lg font-semibold">${item.name}</h3>
                    <p class="text-primary font-bold">${formatPrice(item.price)}</p>
                </div>
                <div class="quantity-controls">
                    <button class="quantity-btn" onclick="updateQuantity(${item.id}, ${item.quantity - 1})">-</button>
                    <span class="px-4 py-2 bg-gray-50 rounded">${item.quantity}</span>
                    <button class="quantity-btn" onclick="updateQuantity(${item.id}, ${item.quantity + 1})">+</button>
                </div>
                <div class="text-right">
                    <p class="font-bold text-lg">${formatPrice(item.price * item.quantity)}</p>
                    <button onclick="removeFromCart(${item.id})" class="text-red-500 hover:text-red-700 text-sm">Xóa</button>
                </div>
            </div>
        </div>
    `,
    )
    .join("")

  const total = calculateTotal()

  cartContent.innerHTML = `
        <div class="bg-white rounded-2xl p-8 shadow-lg mb-8">
            <h2 class="font-display text-2xl font-bold mb-6">Sản phẩm trong giỏ hàng</h2>
            ${cartItemsHTML}
        </div>
        
        <div class="bg-white rounded-2xl p-8 shadow-lg">
            <h3 class="font-display text-xl font-bold mb-4">Tổng cộng</h3>
            <div class="space-y-2 mb-6">
                <div class="flex justify-between">
                    <span>Tạm tính:</span>
                    <span>${formatPrice(total)}</span>
                </div>
                <div class="flex justify-between">
                    <span>Phí giao hàng:</span>
                    <span>Miễn phí</span>
                </div>
                <div class="border-t pt-2 flex justify-between font-bold text-lg">
                    <span>Tổng cộng:</span>
                    <span class="text-primary">${formatPrice(total)}</span>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="menu.html" class="btn-secondary flex-1 text-center">Tiếp tục mua hàng</a>
                <button onclick="checkout()" class="btn-primary flex-1">Thanh toán</button>
            </div>
        </div>
    `
}

function checkout() {
  if (cart.length === 0) {
    alert("Giỏ hàng trống!")
    return
  }

  // Create order
  const order = {
    id: Date.now(),
    items: [...cart],
    total: calculateTotal(),
    status: "pending",
    date: new Date().toISOString(),
  }

  // Save order to localStorage
  const orders = JSON.parse(localStorage.getItem("orders")) || []
  orders.unshift(order)
  localStorage.setItem("orders", JSON.stringify(orders))

  // Clear cart
  cart = []
  localStorage.setItem("cart", JSON.stringify(cart))

  alert("Đặt hàng thành công! Bạn có thể xem đơn hàng trong trang Tài khoản.")
  window.location.href = "order.html"
}

// Initialize cart display when page loads
document.addEventListener("DOMContentLoaded", () => {
  updateCartDisplay()
})

// Make functions available globally
window.addToCart = addToCart
window.removeFromCart = removeFromCart
window.updateQuantity = updateQuantity
window.checkout = checkout
