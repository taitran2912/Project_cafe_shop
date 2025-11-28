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
