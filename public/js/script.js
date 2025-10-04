// Common JavaScript functionality for all pages

// Mobile menu toggle
document.addEventListener("DOMContentLoaded", () => {
  const mobileMenuBtn = document.getElementById("mobile-menu-btn")
  const mobileMenu = document.getElementById("mobile-menu")

  if (mobileMenuBtn && mobileMenu) {
    mobileMenuBtn.addEventListener("click", () => {
      mobileMenu.classList.toggle("active")
    })

    // Close mobile menu when clicking on links
    const mobileLinks = mobileMenu.querySelectorAll("a")
    mobileLinks.forEach((link) => {
      link.addEventListener("click", () => {
        mobileMenu.classList.remove("active")
      })
    })
  }

  // Fade in animation on scroll
  const observerOptions = {
    threshold: 0.1,
    rootMargin: "0px 0px -50px 0px",
  }

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("visible")
      }
    })
  }, observerOptions)

  document.querySelectorAll(".fade-in").forEach((el) => {
    observer.observe(el)
  })

  // Header background on scroll
  window.addEventListener("scroll", () => {
    const header = document.querySelector("header")
    if (header) {
      if (window.scrollY > 100) {
        header.style.backgroundColor = "rgba(255, 255, 255, 0.98)"
        header.style.backdropFilter = "blur(10px)"
      } else {
        header.style.backgroundColor = "rgba(255, 255, 255, 0.95)"
        header.style.backdropFilter = "blur(5px)"
      }
    }
  })

  // Contact form submission
  const contactForm = document.getElementById("contact-form")
  if (contactForm) {
    contactForm.addEventListener("submit", function (e) {
      e.preventDefault()

      const submitBtn = this.querySelector('button[type="submit"]')
      const originalText = submitBtn.textContent

      submitBtn.textContent = "Đang gửi..."
      submitBtn.disabled = true

      // Simulate form submission
      setTimeout(() => {
        submitBtn.textContent = "Đã gửi thành công!"
        submitBtn.style.backgroundColor = "var(--secondary)"

        setTimeout(() => {
          submitBtn.textContent = originalText
          submitBtn.disabled = false
          submitBtn.style.backgroundColor = "var(--primary)"
          this.reset()
        }, 2000)
      }, 1000)
    })
  }
})

// Cart functionality - make available globally
function addToCart(item) {
  const cart = JSON.parse(localStorage.getItem("cart")) || []
  const existingItem = cart.find((cartItem) => cartItem.id === item.id)

  if (existingItem) {
    existingItem.quantity += 1
  } else {
    cart.push({ ...item, quantity: 1 })
  }

  localStorage.setItem("cart", JSON.stringify(cart))

  // Show success message
  const event = new CustomEvent("itemAddedToCart", { detail: item })
  document.dispatchEvent(event)
}

// Make addToCart available globally
window.addToCart = addToCart
