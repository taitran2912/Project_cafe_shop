 <!-- Header  -->
<?php include 'app/views/layout/header.php'; ?>

    <!-- Main  -->
          <!-- Page Header  -->
    <section class="page-header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="font-display text-5xl font-bold mb-4">Thực đơn <?= $userID ?></h1>
            <p class="text-xl opacity-90 max-w-2xl mx-auto">
                Khám phá bộ sưu tập đồ uống cao cấp được tuyển chọn kỹ lưỡng
            </p>
        </div>
    </section>

     <!-- Menu Section  -->
    <section class="content-section bg-muted">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
             <!-- Menu Categories  -->
            <div class="flex justify-center mb-12">
                <div class="flex flex-wrap gap-4">
                    <button class="menu-category active px-6 py-3 rounded-full bg-primary text-white font-medium" data-category="all">
                        Tất cả
                    </button>
<?php if (!empty($data['categories'])): ?>
  <?php foreach ($data['categories'] as $categories): ?>
                    <button class="menu-category px-6 py-3 rounded-full bg-white font-medium hover:bg-primary hover:text-white transition-colors" data-category="<?= htmlspecialchars($categories['Name']) ?>">
                        <?= htmlspecialchars($categories['Name']) ?>
                    </button>
  <?php endforeach; ?>
<?php endif; ?>
                </div>
            </div>

             <!-- Menu Items Grid  -->
            <div id="menu-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                 Initial 9 products will be loaded here 
            </div>

             <!-- Load More Button  -->
            <div class="text-center mt-12">
                <button id="load-more-btn" class="btn-primary" style="display: none;">
                    Xem thêm sản phẩm
                </button>
                <div id="loading" class="hidden">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
                    <p class="mt-2 text-gray-600">Đang tải...</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer  -->
<?php include 'app/views/layout/footer.php'; ?>

<script>
    // Menu data
const menuItems = [
<?php if (!empty($data['products'])): ?>
  <?php foreach ($data['products'] as $index => $product): ?>
    {
      id: <?= (int)$product['ID'] ?>,
      name: "<?= htmlspecialchars($product['Name'], ENT_QUOTES) ?>",
      price: <?= (int)$product['Price'] ?>,
      category: "<?= htmlspecialchars($product['Name_Category'], ENT_QUOTES) ?>",
      image: "<?= BASE_URL ?>/public/image/<?= htmlspecialchars($product['Image'], ENT_QUOTES) ?>",
      description: "<?= htmlspecialchars($product['Description'], ENT_QUOTES) ?>",
    }<?= $index < count($data['products']) - 1 ? ',' : '' ?>

  <?php endforeach; ?>
<?php endif; ?>
];

let currentCategory = "all"
let displayedItems = 0
const itemsPerLoad = 9

function formatPrice(price) {
  return new Intl.NumberFormat("vi-VN").format(price) + "đ"
}

function createMenuItemHTML(item) {
  return `
        <div class="menu-item ${item.category} card-hover bg-white rounded-2xl overflow-hidden shadow-lg fade-in">
            <img src="${item.image}" alt="${item.name}" class="w-full h-48 object-cover">
            <div class="p-6">
                <h3 class="font-display text-xl font-semibold mb-2">${item.name}</h3>
                <p class="text-gray-600 mb-4">${item.description}</p>
                <div class="flex justify-between items-center">
                    <span class="text-2xl font-bold text-primary">${formatPrice(item.price)}</span>
<?php if (isset($userID)): ?>
                    <button class="btn-primary text-sm px-4 py-2 add-to-cart" 
                            data-id="${item.id}"
                            data-name="${item.name}" 
                            data-price="${item.price}"
                            data-image="${item.image}">
                        Thêm vào giỏ
                    </button>
<?php else: ?>
                    <a href="login" class="btn-primary text-sm px-4 py-2">
                        Thêm vào giỏ
                    </a>
<?php endif; ?>
                </div>
            </div>
        </div>
    `
}

function getFilteredItems() {
  return currentCategory === "all" ? menuItems : menuItems.filter((item) => item.category === currentCategory)
}

function loadMenuItems() {
  const filteredItems = getFilteredItems()
  const menuGrid = document.getElementById("menu-grid")
  const loadMoreBtn = document.getElementById("load-more-btn")

  // Clear grid if starting fresh
  if (displayedItems === 0) {
    menuGrid.innerHTML = ""
  }

  // Get items to display
  const itemsToShow = filteredItems.slice(displayedItems, displayedItems + itemsPerLoad)

  // Add items to grid
  itemsToShow.forEach((item) => {
    menuGrid.innerHTML += createMenuItemHTML(item)
  })

  displayedItems += itemsToShow.length

  // Show/hide load more button
  if (displayedItems >= filteredItems.length) {
    loadMoreBtn.style.display = "none"
  } else {
    loadMoreBtn.style.display = "inline-block"
  }

  // Trigger fade-in animation
  setTimeout(() => {
    document.querySelectorAll(".fade-in:not(.visible)").forEach((el) => {
      el.classList.add("visible")
    })
  }, 100)

  // Add event listeners to new add-to-cart buttons
  addCartEventListeners()
}

function filterItems(category) {
  currentCategory = category
  displayedItems = 0

  // Update category buttons
  document.querySelectorAll(".menu-category").forEach((btn) => {
    btn.classList.remove("active", "bg-brown")
    btn.classList.add("bg-white", "text-primary")
  })

  const activeBtn = document.querySelector(`[data-category="${category}"]`)
  activeBtn.classList.add("active", "bg-primary")
  activeBtn.classList.remove("bg-white", "text-primary")

  // Load filtered items
  loadMenuItems()
}

function addCartEventListeners() {
  document.querySelectorAll(".add-to-cart").forEach((button) => {
    button.addEventListener("click", function () {
      const item = {
        id: Number.parseInt(this.dataset.id),
        name: this.dataset.name,
        price: Number.parseInt(this.dataset.price),
        image: this.dataset.image,
        quantity: 1,
      }

      // Assuming addToCart is defined elsewhere or imported
      // You might need to import it or define it in this file
      addToCart(item)

      // Visual feedback
      const originalText = this.textContent
      this.textContent = "Đã thêm!"
      this.style.backgroundColor = "var(--secondary)"

      setTimeout(() => {
        this.textContent = originalText
        this.style.backgroundColor = "var(--primary)"
      }, 1500)
    })
  })
}

function addToCart(item) {
  fetch("menu/addToCart/<?=$userID?>", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: `id_product=${item.id}&quantity=${item.quantity}`,
  })
  .then(res => res.text())
  .then(data => {
    if (data.success) {
      console.log("✅ Giỏ hàng đã được cập nhật!");
    }
  });
}

// Initialize menu
document.addEventListener("DOMContentLoaded", () => {
  loadMenuItems()

  // Category filter event listeners
  document.querySelectorAll(".menu-category").forEach((category) => {
    category.addEventListener("click", () => {
      const selectedCategory = category.getAttribute("data-category")
      filterItems(selectedCategory)
    })
  })

  // Load more button event listener
  document.getElementById("load-more-btn").addEventListener("click", function () {
    const loading = document.getElementById("loading")
    this.style.display = "none"
    loading.classList.remove("hidden")

    setTimeout(() => {
      loading.classList.add("hidden")
      loadMenuItems()
    }, 1000)
  })
})

</script>