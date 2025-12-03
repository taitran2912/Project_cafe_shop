// Profile page functionality
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
  const ordersList = document.getElementById("orders-list")

  if (orders.length === 0) {
    ordersList.innerHTML = `
            <div class="text-center py-12">
                <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-600 mb-4">Chưa có đơn hàng nào</h3>
                <p class="text-gray-500 mb-8">Bạn chưa có đơn hàng nào. Hãy đặt hàng ngay!</p>
                <a href="menu.html" class="btn-primary">Đặt hàng ngay</a>
            </div>
        `
    return
  }

  const ordersHTML = orders
    .map(
      (order) => `
        <div class="border border-gray-200 rounded-lg p-6 mb-4">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="font-bold text-lg">Đơn hàng #${order.id}</h3>
                    <p class="text-gray-600">${formatDate(order.date)}</p>
                </div>
                <span class="order-status ${getStatusClass(order.status)}">${getStatusText(order.status)}</span>
            </div>
            
            <div class="space-y-2 mb-4">
                ${order.items
                  .map(
                    (item) => `
                    <div class="flex justify-between items-center">
                        <span>${item.name} x${item.quantity}</span>
                        <span>${formatPrice(item.price * item.quantity)}</span>
                    </div>
                `,
                  )
                  .join("")}
            </div>
            
            <div class="border-t pt-4 flex justify-between items-center">
                <span class="font-bold">Tổng cộng: ${formatPrice(order.total)}</span>
                <div class="space-x-2">
                    <button onclick="viewOrderDetails(${order.id})" class="btn-secondary text-sm px-4 py-2">Chi tiết</button>
                    ${order.status === "pending" ? `<button onclick="cancelOrder(${order.id})" class="text-red-500 hover:text-red-700 text-sm">Hủy đơn</button>` : ""}
                </div>
            </div>
        </div>
    `,
    )
    .join("")

  ordersList.innerHTML = ordersHTML
}

function viewOrderDetails(orderId) {
  alert(`Xem chi tiết đơn hàng #${orderId}`)
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

// Tab switching functionality


// Make functions available globally
window.viewOrderDetails = viewOrderDetails
window.cancelOrder = cancelOrder
