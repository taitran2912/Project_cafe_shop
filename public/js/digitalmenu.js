// Data structure for cart
let cart = [];

// Format currency
const formatter = new Intl.NumberFormat('vi-VN', {
    style: 'currency',
    currency: 'VND',
});

function addToCart(name, price, image) {
    const existingItem = cart.find(item => item.name === name);
    
    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({
            name: name,
            price: price,
            image: image,
            quantity: 1
        });
    }
    updateCartUI();
}

function removeFromCart(name) {
    const index = cart.findIndex(item => item.name === name);
    if (index > -1) {
        if (cart[index].quantity > 1) {
            cart[index].quantity -= 1;
        } else {
            cart.splice(index, 1);
        }
    }
    updateCartUI();
}

function updateCartUI() {
    const emptyState = document.getElementById('cart-empty-state');
    const itemsContainer = document.getElementById('cart-items-container');
    const cartFooter = document.getElementById('cart-footer');
    const cartCount = document.getElementById('cart-count');
    const totalPriceEl = document.getElementById('cart-total-price');

    // Update Count
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    cartCount.innerText = totalItems;

    if (cart.length === 0) {
        emptyState.style.display = 'block';
        itemsContainer.style.display = 'none';
        cartFooter.style.display = 'none';
    } else {
        emptyState.style.display = 'none';
        itemsContainer.style.display = 'block';
        cartFooter.style.display = 'block';

        // Render Items
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
                        <button class="btn-qty" onclick="removeFromCart('${item.name}')">-</button>
                        <span>${item.quantity}</span>
                        <button class="btn-qty" onclick="addToCart('${item.name}', ${item.price}, ${item.image} )">+</button>
                    </div>
                </div>
            `;
        });

        itemsContainer.innerHTML = html;
        totalPriceEl.innerText = formatter.format(total);
    }
}

// Active link highlighting on scroll
const sections = document.querySelectorAll('.menu-section');
const navLinks = document.querySelectorAll('.category-link');

window.addEventListener('scroll', () => {
    let current = '';
    sections.forEach(section => {
        const sectionTop = section.offsetTop;
        const sectionHeight = section.clientHeight;
        if (pageYOffset >= (sectionTop - 150)) {
            current = section.getAttribute('id');
        }
    });

    navLinks.forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href').includes(current)) {
            link.classList.add('active');
        }
    });
});

