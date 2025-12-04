    <!-- Header -->
<?php
    include 'app/views/layout/header.php'; 
?>
    <!-- Page Header  -->
<section class="page-header">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="font-display text-5xl font-bold mb-4">Tài khoản</h1>
        <p class="text-xl opacity-90 max-w-2xl mx-auto">
            Quản lý thông tin cá nhân và đơn hàng của bạn
        </p>
    </div>
</section>

    <!-- Profile Content  -->
<section class="content-section bg-muted">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Profile Sidebar  -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl p-6 shadow-lg">
                    <div class="text-center mb-6">
                        <div class="w-24 h-24 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-12 h-12 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h3 class="font-display text-xl font-bold"><?= $data['Name'] ?></h3>
                        <p class="text-gray-600"><?= $data['Mail'] ?></p>
                    </div>
                    
                    <nav class="space-y-2">
                        <button class="profile-tab active w-full text-left px-4 py-3 rounded-lg bg-primary text-white" data-tab="info">
                            Thông tin cá nhân
                        </button>
                        <button class="profile-tab w-full text-left px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100" data-tab="orders">
                            Đơn hàng của tôi
                        </button>
                        <button class="profile-tab w-full text-left px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100" data-tab="favorites">
                            Địa chỉ nhận hàng
                        </button>
                    </nav>
                </div>
            </div>

                <!-- Profile Content  -->
            <div class="lg:col-span-2">
                    <!-- Personal Info Tab  -->
                <div id="info-tab" class="tab-content bg-white rounded-2xl p-8 shadow-lg">
                    <h2 class="font-display text-2xl font-bold mb-6">Thông tin cá nhân</h2>
                    
                    <form class="space-y-6" method="POST" action="https://caffeshop.hieuthuocyentam.id.vn/profile/updateInfor">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label class="form-label">Họ tên</label>
                                <input type="text" class="form-input" value="<?= $data['Name'] ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-input" value="<?= $data['Mail'] ?>">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label class="form-label">Số điện thoại</label>
                                <input type="tel" class="form-input" value="<?= $data['Phone'] ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Điểm thưởng</label>
                                 <p class="form-input cursor-not-allowed bg-gray-100"><?= htmlspecialchars($data['Point']) ?></p>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn-primary">Cập nhật thông tin</button>
                    </form>
                </div>

                    <!-- Orders Tab  -->
                <div id="orders-tab" class="tab-content bg-white rounded-2xl p-8 shadow-lg" style="display: none;">
                    <h2 class="font-display text-2xl font-bold mb-6">Đơn hàng của tôi</h2>
                    <div id="orders-list">
                            Orders will be loaded here 
                    </div>
                </div>

                    <!-- Favorites Tab  -->
                <div id="favorites-tab" class="tab-content bg-white rounded-2xl p-8 shadow-lg" style="display: none;">
                    <h2 class="font-display text-2xl font-bold mb-6">Sản phẩm yêu thích</h2>
                    <div class="text-center py-12">
                        <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-600 mb-4">Chưa có sản phẩm yêu thích</h3>
                        <p class="text-gray-500 mb-8">Hãy thêm những sản phẩm bạn yêu thích để dễ dàng tìm lại</p>
                        <a href="menu.html" class="btn-primary">Khám phá thực đơn</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>

const profileData = {
    orders: [
        <?php foreach ($data['Order'] as $o): ?>
        {
            id: <?= $o['ID'] ?>,
            date: "<?= $o['Time'] ?>",
            status: "<?= strtolower($o['Status']) ?>",
            ship: <?= $o['Shipping_Cost'] ?>,
            total: <?= $o['Total'] ?>,
            items: [
                <?php foreach ($o['Details'] as $it): ?>
                {
                    name: "<?= htmlspecialchars($it['Name']) ?>",
                    quantity: <?= $it['Quantity'] ?>,
                    price: <?= $it['Price'] ?>
                },
                <?php endforeach; ?>
            ]
        },
        <?php endforeach; ?>
    ]
};

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
    case "Pending": return "status-pending"
    case "Processing": return "status-processing"
    case "Completed": return "status-completed"
    case "Cancelled": return "status-cancelled"
    default: return "status-pending"
  }
}

function getStatusText(status) {
  switch (status) {
    case "Pending": return "Chờ xử lý"
    case "Processing": return "Đang xử lý"
    case "Completed": return "Hoàn thành"
    case "Cancelled": return "Đã hủy"
    default: return "Chờ xử lý"
  }
}

// Load orders from profileData object
function loadOrders() {
  const orders = profileData.orders
  const ordersList = document.getElementById("orders-list")

  if (!orders || orders.length === 0) {
    ordersList.innerHTML = `
      <div class="text-center py-12">
        <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
          <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-600 mb-4">Chưa có đơn hàng nào</h3>
        <p class="text-gray-500 mb-8">Bạn chưa có đơn hàng nào. Hãy đặt hàng ngay!</p>
        <a href="menu" class="btn-primary">Đặt hàng ngay</a>
      </div>
    `
    return
  }

  const ordersHTML = orders.map(order => `
    <div class="border border-gray-200 rounded-lg p-6 mb-4">
      <div class="flex justify-between items-start mb-4">
        <div>
          <h3 class="font-bold text-lg">Đơn hàng #${order.id}</h3>
          <p class="text-gray-600">${formatDate(order.date)}</p>
        </div>
        <span class="order-status ${getStatusClass(order.status)}">${getStatusText(order.status)}</span>
      </div>

      <div class="space-y-2 mb-4">
        ${order.items.map(item => `
          <div class="flex justify-between items-center">
            <span>${item.name} x${item.quantity}</span>
            <span>${formatPrice(item.price * item.quantity)}</span>
          </div>
        `).join("")}
        <div class="flex justify-between items-center">
            <span>Tiền ship</span>
            <span>${formatPrice(order.ship)}</span>
            </div>
      </div>

      <div class="border-t pt-4 flex justify-between items-center">
        <span class="font-bold">Tổng cộng: ${formatPrice(order.total)}</span>
        <div class="space-x-2">
          <button onclick="viewOrderDetails(${order.id})" class="btn-secondary text-sm px-4 py-2">Chi tiết</button>
          ${order.status === "Pending" ? `
          <button onclick="cancelOrder(${order.id})" class="text-red-500 hover:text-red-700 text-sm">Hủy đơn</button>` : ""}
        </div>
      </div>
    </div>
  `).join("")

  ordersList.innerHTML = ordersHTML
}

// View / Cancel order functions
function viewOrderDetails(orderId) {
  const order = profileData.orders.find(o => o.id === orderId)
  if (order) alert(`Chi tiết đơn hàng #${order.id}`)
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

      tabs.forEach(t => {
        t.classList.remove("active", "bg-primary", "text-white")
        t.classList.add("text-gray-700", "hover:bg-gray-100")
      })

      this.classList.add("active", "bg-primary", "text-white")
      this.classList.remove("text-gray-700", "hover:bg-gray-100")

      tabContents.forEach(content => content.style.display = "none")
      document.getElementById(`${targetTab}-tab`).style.display = "block"

      if (targetTab === "orders") loadOrders()
    })
  })

  // Load orders on page load if orders tab is active
  loadOrders()
})

// Make functions global
window.viewOrderDetails = viewOrderDetails
window.cancelOrder = cancelOrder

</script>
    <!-- Footer  -->
<?php
    include 'app/views/layout/footer.php'; 
?>
