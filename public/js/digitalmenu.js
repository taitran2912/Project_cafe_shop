// =======================
// CART DATA
// =======================
let cart = [];

// Format currency
const formatter = new Intl.NumberFormat('vi-VN', {
    style: 'currency',
    currency: 'VND',
});

// =======================
// ADD ITEM TO CART
// =======================
function addToCart(id, name, price, image) {
    const existing = cart.find(i => i.id === id);

    if (existing) {
        existing.quantity++;
    } else {
        cart.push({
            id,
            name,
            price,
            image,
            quantity: 1
        });
    }

    updateCartUI();
    saveCart();
}

// =======================
// REMOVE ITEM
// =======================
function removeFromCart(id) {
    const index = cart.findIndex(item => item.id === id);

    if (index > -1) {
        if (cart[index].quantity > 1) {
            cart[index].quantity--;
        } else {
            cart.splice(index, 1);
        }
    }
    updateCartUI();
    saveCart();
}

// =======================
// RENDER UI
// =======================
function updateCartUI() {
    const emptyState = document.getElementById('cart-empty-state');
    const itemsContainer = document.getElementById('cart-items-container');
    const cartFooter = document.getElementById('cart-footer');
    const cartCount = document.getElementById('cart-count');
    const totalPriceEl = document.getElementById('cart-total-price');

    // Count
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    cartCount.innerText = totalItems;

    if (cart.length === 0) {
        emptyState.style.display = 'block';
        itemsContainer.style.display = 'none';
        cartFooter.style.display = 'none';
        return;
    }

    emptyState.style.display = 'none';
    itemsContainer.style.display = 'block';
    cartFooter.style.display = 'block';

    let html = '';
    let total = 0;

    cart.forEach(item => {
        total += item.price * item.quantity;

        html += `
            <div class="cart-item">
                <div class="cart-item-info">
                    <div class="cart-item-title">${item.name}</div>
                    <div class="cart-item-price">${formatter.format(item.price)}</div>
                </div>

                <div class="cart-item-qty">
                    <button class="btn-qty" onclick="removeFromCart(${item.id})">-</button>
                    <span>${item.quantity}</span>
                    <button class="btn-qty" onclick="addToCart(${item.id}, '${item.name}', ${item.price}, '${item.image}')">+</button>
                </div>
            </div>
        `;
    });

    itemsContainer.innerHTML = html;
    totalPriceEl.innerText = formatter.format(total);
}

// =======================
// SAVE & LOAD CART
// =======================
function saveCart() {
    localStorage.setItem("cart", JSON.stringify(cart));
}

function loadCart() {
    const saved = localStorage.getItem("cart");
    if (saved) cart = JSON.parse(saved);
    updateCartUI();
}

document.addEventListener("DOMContentLoaded", loadCart);
