// Data structure for cart
let cart = [];

// Format currency
const formatter = new Intl.NumberFormat('vi-VN', {
    style: 'currency',
    currency: 'VND',
});

function addToCart(name, price) {
    const existingItem = cart.find(item => item.name === name);
    
    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({
            name: name,
            price: price,
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
                        <button class="btn-qty" onclick="addToCart('${item.name}', ${item.price})">+</button>
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

document.addEventListener("DOMContentLoaded", () => {
    const modalEl = document.getElementById("customerPhoneModal");
    const modal = new bootstrap.Modal(modalEl);

    modal.show();

    // Khi modal đóng, nếu chưa nhập SĐT thì gọi fetchFavorite
    modalEl.addEventListener('hidden.bs.modal', () => {
        if (!window.customerPhone) {
            fetchFavorite();
        }
    });
});
// ====================================================
// Lấy số điện thoại
// ====================================================
function confirmCustomerPhone() {
    let phone = document.getElementById('customerPhone').value.trim();
    window.customerPhone = phone;

    bootstrap.Modal.getInstance(document.getElementById('customerPhoneModal')).hide();

    if (phone) {
        fetchFavoriteProducts(phone);
    }
}
// ====================================================
// API: lấy món yêu thích theo SĐT
// ====================================================
function fetchFavoriteProducts(phone) {
    fetch(`https://caffeshop.hieuthuocyentam.id.vn/digitalmenu/favorite?phone=${phone}`)
        .then(r => r.json())
        .then(data => {
            if (data.length > 0) displayFavoriteSuggestions(data);
        });
}

// API: lấy món phổ biến
function fetchFavorite() {
    fetch(`https://caffeshop.hieuthuocyentam.id.vn/digitalmenu/popular`)
        .then(r => r.json())
        .then(data => {
            if (data.length > 0) displayFavoriteSuggestions(data);
        });
}
// ====================================================
// Render danh sách món yêu thích
// ====================================================
function displayFavoriteSuggestions(products) {
    const container = document.getElementById('favorite-container');

    products.forEach(p => {
        container.innerHTML += `
            <div class="col">
                <div class="product-card h-100">
                    <div class="product-img-wrapper">
                        <img src="https://caffeshop.hieuthuocyentam.id.vn/public/image/${p.Image}" class="product-img">
                    </div>

                    <div class="product-body">
                        <div class="product-title">${p.Name}</div>
                        <div class="product-price">${Number(p.Price).toLocaleString('vi-VN')}₫</div>

                        <button class="btn-add" onclick="addToCart('${p.Name}', ${p.Price})">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
    });
}