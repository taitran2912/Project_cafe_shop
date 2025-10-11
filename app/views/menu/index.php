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

     <!-- Menu Section  -->
    <section class="content-section bg-muted">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
             <!-- Menu Categories  -->
            <div class="flex justify-center mb-12">
                <div class="flex flex-wrap gap-4">
                    <button class="menu-category active px-6 py-3 rounded-full bg-primary text-white font-medium" data-category="all">
                        Tất cả
                    </button>
                    <button class="menu-category px-6 py-3 rounded-full bg-white text-primary font-medium hover:bg-primary hover:text-white transition-colors" data-category="coffee">
                        Cà phê
                    </button>
                    <button class="menu-category px-6 py-3 rounded-full bg-white text-primary font-medium hover:bg-primary hover:text-white transition-colors" data-category="tea">
                        Trà
                    </button>
                    <button class="menu-category px-6 py-3 rounded-full bg-white text-primary font-medium hover:bg-primary hover:text-white transition-colors" data-category="cold">
                        Đồ uống lạnh
                    </button>
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
  // Coffee items
  {
    id: 1,
    name: "Espresso Đặc Biệt",
    price: 45000,
    category: "coffee",
    image: "public/espresso-coffee-cup.png",
    description: "Cà phê espresso nguyên chất từ hạt Arabica cao cấp",
  },
  {
    id: 2,
    name: "Cappuccino Nghệ Thuật",
    price: 55000,
    category: "coffee",
    image: "public/cappuccino-with-latte-art.jpg",
    description: "Cappuccino với latte art tinh tế và sữa tươi béo ngậy",
  },
  {
    id: 3,
    name: "Americano Đậm Đà",
    price: 40000,
    category: "coffee",
    image: "public/americano-coffee-black.jpg",
    description: "Cà phê Americano đậm đà, thơm nồng",
  },
  {
    id: 4,
    name: "Latte Vanilla",
    price: 50000,
    category: "coffee",
    image: "public/vanilla-latte-coffee.jpg",
    description: "Latte thơm ngon với hương vani tự nhiên",
  },
  {
    id: 5,
    name: "Mocha Chocolate",
    price: 60000,
    category: "coffee",
    image: "public/mocha-chocolate-coffee.jpg",
    description: "Sự kết hợp hoàn hảo giữa cà phê và chocolate",
  },

  // Tea items
  {
    id: 6,
    name: "Trà Xanh Cao Cấp",
    price: 40000,
    category: "tea",
    image: "public/green-tea-in-elegant-cup.jpg",
    description: "Trà xanh Sencha Nhật Bản với hương vị thanh mát",
  },
  {
    id: 7,
    name: "Earl Grey Hoàng Gia",
    price: 50000,
    category: "tea",
    image: "public/earl-grey-tea-cup.jpg",
    description: "Trà đen Earl Grey với tinh dầu bergamot thơm nồng",
  },
  {
    id: 8,
    name: "Trà Oolong Đài Loan",
    price: 55000,
    category: "tea",
    image: "public/oolong-tea-traditional.jpg",
    description: "Trà Oolong cao cấp từ Đài Loan, hương vị độc đáo",
  },
  {
    id: 9,
    name: "Trà Hoa Nhài",
    price: 45000,
    category: "tea",
    image: "public/jasmine-tea-flowers.jpg",
    description: "Trà xanh thơm với hương hoa nhài tự nhiên",
  },

  // Cold drinks
  {
    id: 10,
    name: "Cà Phê Đá Đặc Biệt",
    price: 48000,
    category: "cold",
    image: "public/iced-coffee-with-milk-foam.jpg",
    description: "Cà phê đá với sữa tươi và foam mịn màng",
  },
  {
    id: 11,
    name: "Trà Sữa Trân Châu",
    price: 42000,
    category: "cold",
    image: "public/bubble-tea-with-pearls.jpg",
    description: "Trà sữa thơm ngon với trân châu đen dai giòn",
  },
  {
    id: 12,
    name: "Matcha Latte Đá",
    price: 52000,
    category: "cold",
    image: "public/iced-matcha-latte.png",
    description: "Matcha Nhật Bản nguyên chất với sữa tươi",
  },
  {
    id: 13,
    name: "Trà Đào Cam Sả",
    price: 38000,
    category: "cold",
    image: "public/peach-tea-with-herbs.jpg",
    description: "Trà trái cây tươi mát với đào, cam và sả",
  },
  {
    id: 14,
    name: "Cold Brew Coffee",
    price: 46000,
    category: "cold",
    image: "public/cold-brew-coffee-glass.jpg",
    description: "Cà phê pha lạnh 12 tiếng, vị đậm đà không đắng",
  },
  {
    id: 15,
    name: "Smoothie Xoài",
    price: 44000,
    category: "cold",
    image: "public/mango-smoothie-tropical.jpg",
    description: "Sinh tố xoài tươi ngon, bổ dưỡng",
  },
    // More Coffee items
  {
    id: 16,
    name: "Flat White",
    price: 52000,
    category: "coffee",
    image: "public/flat-white-coffee.jpg",
    description: "Cà phê Flat White mịn màng với lớp milk foam mỏng",
  },
  {
    id: 17,
    name: "Caramel Macchiato",
    price: 58000,
    category: "coffee",
    image: "public/caramel-macchiato.jpg",
    description: "Sự hòa quyện giữa espresso, sữa và caramel ngọt ngào",
  },
  {
    id: 18,
    name: "Affogato",
    price: 65000,
    category: "coffee",
    image: "public/affogato-coffee-icecream.jpg",
    description: "Espresso nóng rưới lên kem vanilla mát lạnh",
  },

  // More Tea items
  {
    id: 19,
    name: "Trà Bạc Hà",
    price: 42000,
    category: "tea",
    image: "public/mint-tea-fresh.jpg",
    description: "Trà xanh thanh mát kết hợp lá bạc hà tươi",
  },
  {
    id: 20,
    name: "Trà Gừng Mật Ong",
    price: 43000,
    category: "tea",
    image: "public/ginger-honey-tea.jpg",
    description: "Trà gừng ấm áp pha mật ong thiên nhiên",
  },
  {
    id: 21,
    name: "Trà Dâu Tây",
    price: 47000,
    category: "tea",
    image: "public/strawberry-tea.jpg",
    description: "Trà hoa quả tươi với hương dâu tây tự nhiên",
  },

  // More Cold drinks
  {
    id: 22,
    name: "Sinh Tố Dâu",
    price: 46000,
    category: "cold",
    image: "public/strawberry-smoothie.jpg",
    description: "Sinh tố dâu tươi thơm ngon bổ dưỡng",
  },
  {
    id: 23,
    name: "Sinh Tố Bơ",
    price: 48000,
    category: "cold",
    image: "public/avocado-smoothie.jpg",
    description: "Sinh tố bơ béo ngậy, giàu dinh dưỡng",
  },
  {
    id: 24,
    name: "Nước Cam Ép",
    price: 35000,
    category: "cold",
    image: "public/orange-juice-fresh.jpg",
    description: "Nước cam ép nguyên chất, giàu vitamin C",
  },
  {
    id: 25,
    name: "Nước Ép Dưa Hấu",
    price: 34000,
    category: "cold",
    image: "public/watermelon-juice.jpg",
    description: "Nước ép dưa hấu mát lạnh, giải nhiệt mùa hè",
  },
  {
    id: 26,
    name: "Yogurt Đá Xay Việt Quất",
    price: 49000,
    category: "cold",
    image: "public/blueberry-yogurt-iceblend.jpg",
    description: "Sữa chua đá xay kết hợp việt quất chua ngọt",
  },
  {
    id: 27,
    name: "Chocolate Đá Xay",
    price: 55000,
    category: "cold",
    image: "public/chocolate-frappe.jpg",
    description: "Đá xay chocolate ngọt ngào, topping kem tươi",
  },

  // Dessert items
  {
    id: 28,
    name: "Cheesecake Dâu Tây",
    price: 60000,
    category: "dessert",
    image: "public/strawberry-cheesecake.jpg",
    description: "Bánh cheesecake mềm mịn với topping dâu tây",
  },
  {
    id: 29,
    name: "Tiramisu Truyền Thống",
    price: 62000,
    category: "dessert",
    image: "public/tiramisu-classic.jpg",
    description: "Tiramisu Ý với hương vị cà phê & cacao đặc trưng",
  },
  {
    id: 30,
    name: "Bánh Cookies Socola",
    price: 30000,
    category: "dessert",
    image: "public/chocolate-cookies.jpg",
    description: "Cookies giòn tan với hương vị chocolate ngọt ngào",
  },

]

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
                    <button class="btn-primary text-sm px-4 py-2 add-to-cart" 
                            data-id="${item.id}"
                            data-name="${item.name}" 
                            data-price="${item.price}"
                            data-image="${item.image}">
                        Thêm vào giỏ
                    </button>
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
    btn.classList.remove("active", "bg-primary", "text-white")
    btn.classList.add("bg-white", "text-primary")
  })

  const activeBtn = document.querySelector(`[data-category="${category}"]`)
  activeBtn.classList.add("active", "bg-primary", "text-white")
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