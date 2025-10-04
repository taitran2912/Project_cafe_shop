// Order page functionality
function formatPrice(price) {
  return new Intl.NumberFormat("vi-VN").format(price) + "đ"
}

function formatDate(dateString) {
  const date = new Date(dateString)
  return date.toLocaleDateString("vi-VN", {
    year: "numeric",
    month: "long",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  })
}

function getStatusClass(status) {
  switch (status) {
    case "pending":
      return "status-pending"
    case "processing":
      return "status-processing"
    case "completed":
      return "status-completed"
    case "cancelled":
      return "status-cancelled"
    default:
      return "status-pending"
  }
}

function getStatusText(status) {
  switch (status) {
    case "pending":
      return "Chờ xử lý"
    case "processing":
      return "Đang xử lý"
    case "completed":
      return "Hoàn thành"
    case "cancelled":
      return "Đã hủy"
    default:
      return "Chờ xử lý"
  }
}

function loadOrders() {
  const orders = JSON.parse(localStorage.getItem("orders")) || []
  const ordersContent = document.getElementById("orders-content")

  if (orders.length === 0) {
    ordersContent.innerHTML = `
            <div class="text-center py-16">
                <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-600 mb-4">Chưa có đơn hàng nào</h3>
                <p class="text-gray-500 mb-8">Bạn chưa có đơn hàng nào. Hãy đặt hàng ngay!</p>
                <a href="menu.html" class="btn-primary">Đặt hàng ngay</a>
            </div>
        `
    return
  }

  const ordersHTML = orders
    .map(
      (order) => `
        <div class="bg-white rounded-2xl p-8 shadow-lg mb-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="font-display text-2xl font-bold mb-2">Đơn hàng #${order.id}</h2>
                    <p class="text-gray-600">${formatDate(order.date)}</p>
                </div>
                <span class="order-status ${getStatusClass(order.status)}">${getStatusText(order.status)}</span>
            </div>
            
            <div class="border-t border-b border-gray-200 py-6 mb-6">
                <h3 class="font-semibold text-lg mb-4">Sản phẩm đã đặt:</h3>
                <div class="space-y-4">
                    ${order.items
                      .map(
                        (item) => `
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <img src="${item.image}" alt="${item.name}" class="w-16 h-16 object-cover rounded-lg">
                                <div>
                                    <h4 class="font-semibold">${item.name}</h4>
                                    <p class="text-gray-600">Số lượng: ${item.quantity}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold">${formatPrice(item.price * item.quantity)}</p>
                                <p class="text-sm text-gray-600">${formatPrice(item.price)}/món</p>
                            </div>
                        </div>
                    `,
                      )
                      .join("")}
                </div>
            </div>
            
            <div class="flex justify-between items-center mb-6">
                <span class="text-xl font-bold">Tổng cộng:</span>
                <span class="text-2xl font-bold text-primary">${formatPrice(order.total)}</span>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-4">
                ${
                  order.status === "pending"
                    ? `
                    <button onclick="cancelOrder(${order.id})" class="btn-secondary flex-1">Hủy đơn hàng</button>
                `
                    : ""
                }
                <button onclick="reorder(${order.id})" class="btn-primary flex-1">Đặt lại</button>
            </div>
        </div>
    `,
    )
    .join("")

  ordersContent.innerHTML = ordersHTML
}

function cancelOrder(orderId) {
  if (confirm("Bạn có chắc chắn muốn hủy đơn hàng này?")) {
    const orders = JSON.parse(localStorage.getItem("orders")) || []
    const orderIndex = orders.findIndex((order) => order.id === orderId)

    if (orderIndex !== -1) {
      orders[orderIndex].status = "cancelled"
      localStorage.setItem("orders", JSON.stringify(orders))
      loadOrders()
      alert("Đơn hàng đã được hủy thành công!")
    }
  }
}

function reorder(orderId) {
  const orders = JSON.parse(localStorage.getItem("orders")) || []
  const order = orders.find((o) => o.id === orderId)

  if (order) {
    // Add all items from the order to cart
    const cart = JSON.parse(localStorage.getItem("cart")) || []

    order.items.forEach((item) => {
      const existingItem = cart.find((cartItem) => cartItem.id === item.id)
      if (existingItem) {
        existingItem.quantity += item.quantity
      } else {
        cart.push({ ...item })
      }
    })

    localStorage.setItem("cart", JSON.stringify(cart))
    alert("Đã thêm tất cả sản phẩm vào giỏ hàng!")
    window.location.href = "cart.html"
  }
}

// Initialize orders display when page loads
document.addEventListener("DOMContentLoaded", () => {
  loadOrders()
})

// Make functions available globally
window.cancelOrder = cancelOrder
window.reorder = reorder
