// Utility functions
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
    case "Ordered": return "status-pending"
    case "Confirmed": return "status-processing"
    case "Completed": return "status-completed"
    case "Cancelled": return "status-cancelled"
    default: return "status-pending"
  }
}

function getStatusText(status) {
  switch (status) {
    case "Ordered": return "Chờ xử lý"
    case "Confirmed": return "Nhận đơn"
    case "Completed": return "Hoàn thành"
    case "Cancelled": return "Đã hủy"
    default: return "Chờ xử lý"
  }
}

function loadAddresses() {
    const list = profileData.addresses;
    const container = document.getElementById("address-list");

    if (!list || list.length === 0) {
        container.innerHTML = `
            <div class="text-center py-12">
                <p class="text-gray-500">Chưa có địa chỉ. Hãy thêm địa chỉ mới.</p>
            </div>
        `;
        return;
    }

    container.innerHTML = list.map(a => `
        <div class="border border-gray-200 rounded-xl p-5 mb-4">
            <div class="flex justify-between items-start">
                <div>
                    <p class="font-bold text-lg">${a.address}</p>
                    ${a.isDefault ? 
                        `<span class="inline-block px-3 py-1 mt-2 bg-primary text-white rounded-full text-xs">
                            Mặc định
                        </span>` 
                    : ""}
                </div>
                <button class="text-primary hover:underline text-sm">Sửa</button>
            </div>
        </div>
    `).join("");
}


function cancelOrder(orderId) {
  const order = profileData.orders.find(o => o.id === orderId)
  if (order && confirm("Bạn có chắc chắn muốn hủy đơn hàng này?")) {
    order.status = "cancelled"
    loadOrders()
    alert("Đơn hàng đã được hủy thành công!")
  }
}

// Tab switching
document.addEventListener("DOMContentLoaded", () => {
  const tabs = document.querySelectorAll(".profile-tab")
  const tabContents = document.querySelectorAll(".tab-content")

  tabs.forEach(tab => {
    tab.addEventListener("click", function () {
      const targetTab = this.dataset.tab

      // Reset style tabs
      tabs.forEach(t => {
        t.classList.remove("active", "bg-primary", "text-white")
        t.classList.add("text-gray-700", "hover:bg-gray-100")
      })

      // Active tab
      this.classList.add("active", "bg-primary", "text-white")
      this.classList.remove("text-gray-700", "hover:bg-gray-100")

      // Hide / show tab content
      tabContents.forEach(content => content.style.display = "none")
      document.getElementById(`${targetTab}-tab`).style.display = "block"

      // Load từng tab
      if (targetTab === "orders") loadOrders()
      if (targetTab === "favorites") loadAddresses() // ⭐ THÊM DÒNG NÀY
    })
  })

  // Load orders on page load (if needed)
  loadOrders()
})

function loadAddresses() {
    const list = profileData.addresses;
    const container = document.getElementById("address-list"); // ⭐ chỉ update phần list

    if (!list || list.length === 0) {
        container.innerHTML = `
            <div class="text-center py-12">
                <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 5h2l3 7h9l3-7h2M6 19a2 2 0 100-4 2 2 0 000 4zm12 0a2 2 0 100-4 2 2 0 000 4z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-600 mb-4">Chưa có địa chỉ nhận hàng</h3>
                <p class="text-gray-500">Hãy thêm địa chỉ mới để giao hàng nhanh hơn</p>
            </div>
        `;
        return;
    }

    const html = list.map(a => `
        <div class="border border-gray-200 rounded-xl p-5 mb-4">
            <div class="flex justify-between items-start">
                <div>
                    <p class="font-bold text-lg">${a.address}</p>
                    ${a.isDefault ? `<span class="inline-block px-3 py-1 mt-2 bg-primary text-white rounded-full text-xs">Mặc định</span>` : ""}
                </div>
                <button class="text-primary hover:underline text-sm">Sửa</button>
            </div>
        </div>
    `).join("");

    container.innerHTML = html;
}




// Make functions global
window.viewOrderDetails = viewOrderDetails
window.cancelOrder = cancelOrder