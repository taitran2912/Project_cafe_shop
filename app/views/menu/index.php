<!-- Header  -->
<?php include 'app/views/layout/header.php'; ?>

<!-- Main  -->
<!-- Page Header  -->
<section class="page-header">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="font-display text-5xl font-bold mb-4">Thực đơn</h1>
        <p class="text-xl opacity-90 max-w-2xl mx-auto">
            Khám phá bộ sưu tập đồ uống cao cấp được tuyển chọn kỹ lưỡng
        </p>
    </div>
</section>

<section class="content-section">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <h2 class="text-3xl font-bold mb-6">Gợi ý cho bạn ✨</h2>

      <div class="relative overflow-hidden">
          <!-- Nút trái -->
          <button id="suggest-prev" 
              class="absolute left-0 top-1/2 -translate-y-1/2 bg-white p-3 rounded-full shadow hover:bg-gray-100 z-10">
              ◀
          </button>

          <!-- Vùng chứa slider -->
          <div id="suggest-slider" class="flex transition-transform duration-700 ease-in-out"></div>

          <!-- Nút phải -->
          <button id="suggest-next" 
              class="absolute right-0 top-1/2 -translate-y-1/2 bg-white p-3 rounded-full shadow hover:bg-gray-100 z-10">
              ▶
          </button>
      </div>

  </div>
</section>

<!-- Menu Section  -->
<section class="content-section bg-muted">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Menu Categories  -->
        <div class="flex justify-center mb-12">
            <div class="flex flex-wrap gap-4">
                <button class="menu-category active px-6 py-3 rounded-full bg-brown text-white font-medium" data-category="all">
                    Tất cả
                </button>
<?php if (!empty($data['categories'])): ?>
  <?php foreach ($data['categories'] as $categories): ?>
                <button class="menu-category px-6 py-3 rounded-full bg-white text-brown font-medium hover:bg-brown-light hover:text-be transition-colors" data-category="<?= htmlspecialchars($categories['Name']) ?>">
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
            <button id="load-more-btn" class="btn" style="display: none; background-color: rgb(210, 180, 140);">
                Xem thêm
            </button>
            <div id="loading" class="hidden">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-brown"></div>
                <p class="mt-2 text-gray-600">Đang tải...</p>
            </div>
        </div>
    </div>
</section>

<!-- Footer  -->
<?php include 'app/views/layout/footer.php'; ?>

<script>
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
                <span class="text-2xl font-bold text-brown">${formatPrice(item.price)}</span>
<?php if (isset($userID)): ?>
                <button class="btn btn-brown text-white px-4 py-2 add-to-cart" 
                        data-id="${item.id}"
                        data-name="${item.name}" 
                        data-price="${item.price}"
                        data-image="${item.image}"
                        style="background-color: rgb(139, 69, 19);">
                    Thêm vào giỏ
                </button>
<?php else: ?>
                <a href="login" class="btn-brown text-sm px-4 py-2">
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

  if (displayedItems === 0) menuGrid.innerHTML = ""

  const itemsToShow = filteredItems.slice(displayedItems, displayedItems + itemsPerLoad)

  itemsToShow.forEach(item => menuGrid.innerHTML += createMenuItemHTML(item))

  displayedItems += itemsToShow.length

  loadMoreBtn.style.display = (displayedItems >= filteredItems.length) ? "none" : "inline-block"

  setTimeout(() => {
    document.querySelectorAll(".fade-in:not(.visible)").forEach(el => el.classList.add("visible"))
  }, 100)

  addCartEventListeners()
}

function filterItems(category) {
  currentCategory = category
  displayedItems = 0

  document.querySelectorAll(".menu-category").forEach(btn => {
    btn.classList.remove("active", "bg-brown", "text-white")
    btn.classList.add("bg-white", "text-brown")
  })

  const activeBtn = document.querySelector(`[data-category="${category}"]`)
  activeBtn.classList.add("active", "bg-brown", "text-white")
  activeBtn.classList.remove("bg-white", "text-brown")

  loadMenuItems()
}

function addCartEventListeners() {
  document.querySelectorAll(".add-to-cart").forEach(button => {
    button.addEventListener("click", function () {
      const item = {
        id: Number.parseInt(this.dataset.id),
        name: this.dataset.name,
        price: Number.parseInt(this.dataset.price),
        image: this.dataset.image,
        quantity: 1,
      }

      addToCart(item)

      const originalText = this.textContent
      this.textContent = "Đã thêm!"
      this.style.backgroundColor = "#d2b48c" // nâu nhạt

      setTimeout(() => {
        this.textContent = originalText
        this.style.backgroundColor = "#8b4513" // nâu đậm
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
    if (data.success) console.log("✅ Giỏ hàng đã được cập nhật!");
  });
}

document.addEventListener("DOMContentLoaded", () => {
  loadMenuItems()

  document.querySelectorAll(".menu-category").forEach(category => {
    category.addEventListener("click", () => {
      filterItems(category.getAttribute("data-category"))
    })
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

  loadSuggestions();
})

// Gợi ý món
// ====== DANH SÁCH GỢI Ý ======
const suggestItems = [
<?php if (!empty($data['suggestions'])): ?>
  <?php foreach ($data['suggestions'] as $index => $s): ?>
    {
      id: <?= (int)$s['ID'] ?>,
      name: "<?= htmlspecialchars($s['Name'], ENT_QUOTES) ?>",
      price: <?= (int)$s['Price'] ?>,
      image: "<?= BASE_URL ?>/public/image/<?= htmlspecialchars($s['Image'], ENT_QUOTES) ?>",
      description: "<?= htmlspecialchars($s['Description'] ?? '', ENT_QUOTES) ?>"
    }<?= $index < count($data['suggestions']) - 1 ? ',' : '' ?>
  <?php endforeach; ?>
<?php endif; ?>
];



function createSuggestSlide(item) {
  return `
    <div class="menu-item card-hover bg-white rounded-2xl overflow-hidden shadow-lg fade-in" style="min-width: 300px; margin-right: 20px;">
        <img src="${item.image}" alt="${item.name}" class="w-full h-48 object-cover">
        <div class="p-6">
            <h3 class="font-display text-xl font-semibold mb-2">${item.name}</h3>
            <p class="text-gray-600 mb-4">${item.description || ''}</p>
            <div class="flex justify-between items-center">
                <span class="text-2xl font-bold text-brown">${formatPrice(item.price)}</span>

                <?php if (!empty($userID)): ?>
                <button class="btn btn-brown text-white px-4 py-2 add-to-cart"
                    data-id="${item.id}"
                    data-name="${item.name}"
                    data-price="${item.price}"
                    data-image="${item.image}">
                    Thêm vào giỏ
                </button>
                <?php else: ?>
                <a href="login" class="btn-brown text-sm px-4 py-2">Thêm vào giỏ</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
  `;
}



// ====== SLIDER ======
let suggestPos = 0;
const slider = document.getElementById("suggest-slider");
const slideWidth = 320; // 300px + khoảng cách
const maxPos = suggestItems.length - 1;

function loadSuggestSlider() {
  slider.innerHTML = "";
  suggestItems.forEach(item => {
    slider.innerHTML += createSuggestSlide(item);
  });
}

loadSuggestSlider();

// Next
document.getElementById("suggest-next").addEventListener("click", () => {
  suggestPos = (suggestPos + 1) > maxPos ? 0 : suggestPos + 1;
  slider.style.transform = `translateX(-${suggestPos * slideWidth}px)`;
});

// Prev
document.getElementById("suggest-prev").addEventListener("click", () => {
  suggestPos = (suggestPos - 1) < 0 ? maxPos : suggestPos - 1;
  slider.style.transform = `translateX(-${suggestPos * slideWidth}px)`;
});

// Auto run
setInterval(() => {
  suggestPos = (suggestPos + 1) > maxPos ? 0 : suggestPos + 1;
  slider.style.transform = `translateX(-${suggestPos * slideWidth}px)`;
}, 4000);


</script>
