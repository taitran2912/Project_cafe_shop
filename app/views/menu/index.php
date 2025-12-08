<!-- Header  -->
<?php include 'app/views/layout/header.php'; ?>

<!-- Page Header  -->
<section class="page-header">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="font-display text-5xl font-bold mb-4">Thực đơn</h1>
        <p class="text-xl opacity-90 max-w-2xl mx-auto">
            Khám phá bộ sưu tập đồ uống cao cấp được tuyển chọn kỹ lưỡng
        </p>
    </div>
</section>

<!-- Gợi ý cho bạn  -->
<section class="content-section">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <h2 class="text-3xl font-bold mb-6">Gợi ý cho bạn ✨</h2>

      <div id="suggest-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"></div>
  </div>
</section>

<!-- Menu Section  -->
<section class="content-section bg-muted">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Menu Categories -->
        <div class="flex justify-center mb-12">
            <div class="flex flex-wrap gap-4">

                <button class="menu-category active px-6 py-3 rounded-full bg-brown text-white font-medium"
                        data-category="all">Tất cả</button>

                <!-- Yêu thích -->
                <button class="menu-category px-6 py-3 rounded-full bg-white text-brown font-medium"
                        data-category="favorite">
                    Yêu thích
                </button>

                <?php if (!empty($data['categories'])): ?>
                  <?php foreach ($data['categories'] as $categories): ?>
                    <button class="menu-category px-6 py-3 rounded-full bg-white text-brown font-medium"
                        data-category="<?= htmlspecialchars($categories['Name']) ?>">
                        <?= htmlspecialchars($categories['Name']) ?>
                    </button>
                  <?php endforeach; ?>
                <?php endif; ?>

            </div>
        </div>

        <!-- Menu Grid -->
        <div id="menu-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"></div>

        <!-- Load More -->
        <div class="text-center mt-12">
            <button id="load-more-btn" class="btn" style="display:none; background-color:rgb(210,180,140);">
                Xem thêm
            </button>
            <div id="loading" class="hidden">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-brown"></div>
                <p class="mt-2 text-gray-600">Đang tải...</p>
            </div>
        </div>

    </div>
</section>

<!-- Footer -->
<?php include 'app/views/layout/footer.php'; ?>

<script>
// ======================= DATA ==========================
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

// ======================= GLOBAL ==========================
let currentCategory = "all"
let displayedItems = 0
const itemsPerLoad = 9

// ======================= UTIL ==========================
function formatPrice(price) {
  return new Intl.NumberFormat("vi-VN").format(price) + "đ"
}

// ======================= FAVORITES ==========================
function getFavorites() {
  return JSON.parse(localStorage.getItem("favorites") || "[]")
}

function saveFavorites(arr) {
  localStorage.setItem("favorites", JSON.stringify(arr))
}

function toggleFavorite(id) {
  let fav = getFavorites()

  if (fav.includes(id)) fav = fav.filter(x => x !== id)
  else fav.push(id)

  saveFavorites(fav)
  updateFavoriteUI()
}

function updateFavoriteUI() {
  const fav = getFavorites()
  document.querySelectorAll(".favorite-btn").forEach(btn => {
    fav.includes(parseInt(btn.dataset.id))
      ? btn.style.color = "red"
      : btn.style.color = "#bbb"
  })
}

// ======================= HTML TEMPLATE ==========================
function createMenuItemHTML(item) {
  return `
    <div class="menu-item ${item.category} card-hover bg-white rounded-2xl overflow-hidden shadow-lg fade-in">
        <img src="${item.image}" alt="${item.name}" class="w-full h-48 object-cover">
        <div class="p-6">
            <h3 class="font-display text-xl font-semibold mb-2">${item.name}</h3>
            <p class="text-gray-600 mb-4">${item.description}</p>

            <div class="flex justify-between items-center">
                <span class="text-2xl font-bold text-brown">${formatPrice(item.price)}</span>

                <div class="flex items-center gap-3">
                    <button class="favorite-btn text-xl" data-id="${item.id}">❤</button>

                    <?php if (isset($userID)): ?>
                    <button class="btn btn-brown text-white px-4 py-2 add-to-cart" 
                        data-id="${item.id}"
                        data-name="${item.name}" 
                        data-price="${item.price}"
                        data-image="${item.image}"
                        style="background-color:#8b4513;">
                        Thêm vào giỏ
                    </button>
                    <?php else: ?>
                    <a href="login" class="btn-brown text-sm px-4 py-2">Thêm vào giỏ</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
  `
}

// ======================= FILTER ==========================
function getFilteredItems() {
  if (currentCategory === "favorite") {
    const fav = getFavorites()
    return menuItems.filter(item => fav.includes(item.id))
  }

  return currentCategory === "all"
    ? menuItems
    : menuItems.filter(item => item.category === currentCategory)
}

// ======================= LOAD ITEMS ==========================
function loadMenuItems() {
  const filtered = getFilteredItems()
  const menuGrid = document.getElementById("menu-grid")
  const loadMoreBtn = document.getElementById("load-more-btn")

  if (displayedItems === 0) menuGrid.innerHTML = ""

  const nextItems = filtered.slice(displayedItems, displayedItems + itemsPerLoad)
  nextItems.forEach(item => menuGrid.innerHTML += createMenuItemHTML(item))

  displayedItems += nextItems.length

  loadMoreBtn.style.display = displayedItems >= filtered.length ? "none" : "inline-block"

  setTimeout(() => updateFavoriteUI(), 100)
  addCartEventListeners()
}

function filterItems(category) {
  currentCategory = category
  displayedItems = 0

  document.querySelectorAll(".menu-category").forEach(btn => {
    btn.classList.remove("active", "bg-brown", "text-white")
    btn.classList.add("bg-white", "text-brown")
  })

  const active = document.querySelector(`[data-category="${category}"]`)
  active.classList.add("active", "bg-brown", "text-white")

  loadMenuItems()
}

// ======================= CART ==========================
function addCartEventListeners() {
  document.querySelectorAll(".add-to-cart").forEach(button => {
    button.addEventListener("click", function () {

      const item = {
        id: Number(this.dataset.id),
        quantity: 1,
      }

      fetch("menu/addToCart/<?=$userID?>", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `id_product=${item.id}&quantity=${item.quantity}`,
      })

      this.textContent = "Đã thêm!"
      this.style.backgroundColor = "#d2b48c"

      setTimeout(() => {
        this.textContent = "Thêm vào giỏ"
        this.style.backgroundColor = "#8b4513"
      }, 1500)
    })
  })
}

// ======================= SUGGESTIONS ==========================
function loadSuggestions() {
  const suggestGrid = document.getElementById("suggest-grid")

  const suggestions = [...menuItems]
    .sort(() => Math.random() - 0.5)
    .slice(0, 3)

  suggestGrid.innerHTML = suggestions.map(item => createMenuItemHTML(item)).join("")

  setTimeout(() => updateFavoriteUI(), 100)
}

// ======================= INIT ==========================
document.addEventListener("DOMContentLoaded", () => {
  loadSuggestions()
  loadMenuItems()

  document.querySelectorAll(".menu-category").forEach(btn => {
    btn.addEventListener("click", () => filterItems(btn.dataset.category))
  })

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
