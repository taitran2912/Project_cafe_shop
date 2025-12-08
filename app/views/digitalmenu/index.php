<?php
function renderProductCard($p) {
    return '
    <div class="col">
        <div class="product-card h-100">
            <div class="product-img-wrapper">
                <img src="/public/image/'.$p['Image'].'" class="product-img" alt="'.htmlspecialchars($p['Name']).'">
            </div>

            <div class="product-body">
                <div class="product-title">'.$p['Name'].'</div>
                <div class="product-price">'.number_format($p['Price'],0,",",".").'₫</div>

                <button class="btn-add" onclick="addToCart(\''.$p['Name'].'\', '.$p['Price'].', \''.$p['Image'].'\')">
                    <i class="fa fa-plus"></i>
                </button>
            </div>
        </div>
    </div>';
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?></title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="https://caffeshop.hieuthuocyentam.id.vn/public/css/digitalmenu.css">

    <!-- JS -->
    <script src="https://caffeshop.hieuthuocyentam.id.vn/public/js/digitalmenu.js" defer></script>
</head>
<body>

<!-- ==========================================================
    HEADER
=========================================================== -->
<header class="shop-header py-3 shadow-sm bg-white mb-3">
    <div class="container d-flex align-items-center gap-3">
        <img src="https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=200&h=200"
             alt="Logo"
             class="rounded-circle"
             style="width:70px;height:70px;object-fit:cover">

        <div>
            <div class="small text-secondary">Cà phê - Trà - Bánh ngọt</div>
            <div class="small text-muted">
                <i class="fa fa-map-marker-alt me-1"></i>
                <?= $data['storeName'] ?> — <?= $data['storeAddress'] ?>
            </div>
            <div class="small text-muted">
                <i class="fa fa-clock me-1"></i> 07:00 - 22:30
            </div>
        </div>
    </div>
</header>

<div class="container-fluid px-md-5 mb-5">
    <div class="row">

        <!-- ==========================================================
            LEFT SIDEBAR CATEGORY
        =========================================================== -->
        <aside class="col-md-2 d-none d-md-block">
            <div class="category-sidebar position-sticky" style="top:20px;">
                <nav class="categories">
                    <a href="#favorite-section" class="category-link active">Món yêu thích</a>
                    <a href="#recommended-section" class="category-link">Gợi ý cho bạn</a>
                    <a href="#new-items-section" class="category-link">Món mới</a>

                    <?php foreach ($data['categories'] as $cat): ?>
                        <a href="#section-<?= $cat['ID'] ?>" class="category-link">
                            <?= htmlspecialchars($cat['Name']) ?>
                        </a>
                    <?php endforeach; ?>
                </nav>
            </div>
        </aside>

        <!-- ==========================================================
            MAIN CONTENT
        =========================================================== -->
        <main class="col-md-10 col-lg-7">

            <!-- ===========================
                MÓN YÊU THÍCH
            ============================ -->
            <section id="favorite-section" class="menu-section mb-5">
                <h3 class="menu-section-title">Món bạn yêu thích</h3>
                <div id="favorite-container" class="row g-3 row-cols-2 row-cols-md-3"></div>
            </section>

            <!-- ===========================
                GỢI Ý
            ============================ -->
            <section id="recommended-section" class="menu-section mb-5">
                <h3 class="menu-section-title">Gợi ý cho bạn</h3>
                <div class="row g-3 row-cols-2 row-cols-md-3">
                    <?php foreach ($data['recommended'] as $p): ?>
                        <?= renderProductCard($p) ?>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- ===========================
                MÓN MỚI
            ============================ -->
            <section id="new-items-section" class="menu-section mb-5">
                <h3 class="menu-section-title">Món mới ra mắt</h3>
                <div class="row g-3 row-cols-2 row-cols-md-3">
                    <?php foreach ($data['newItems'] as $p): ?>
                        <?= renderProductCard($p) ?>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- ===========================
                THEO DANH MỤC
            ============================ -->
            <?php foreach ($data['categories'] as $cat): ?>
                <section id="section-<?= $cat['ID'] ?>" class="menu-section mb-5">
                    <h3 class="menu-section-title"><?= $cat['Name'] ?></h3>

                    <div class="row g-3 row-cols-2 row-cols-md-3">
                        <?php foreach ($data['products'] as $product): ?>
                            <?php if ($product['ID_category'] == $cat['ID']): ?>
                                <?= renderProductCard($product) ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endforeach; ?>
        </main>

        <!-- ==========================================================
            CART SIDEBAR
        =========================================================== -->
        <aside class="col-lg-3 d-none d-lg-block">
            <div class="cart-sidebar position-sticky" style="top:20px;">
                
                <!-- Search -->
                <div class="search-box mb-3">
                    <i class="fa fa-search"></i>
                    <input type="text" class="form-control" placeholder="Bạn đang cần tìm món gì?">
                </div>

                <!-- Bàn -->
                <?php if ($data['tableNumber'] ?? false): ?>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0">Bàn số <?= $data['tableNumber'] ?></h6>
                    </div>
                <?php endif; ?>

                <!-- Cart Header -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Giỏ hàng của bạn</h6>
                    <span class="badge bg-secondary" id="cart-count">0</span>
                </div>

                <!-- Empty -->
                <div id="cart-empty-state" class="cart-empty text-center">
                    <i class="fa fa-shopping-cart fa-2x mb-2"></i>
                    <p class="small">Không có món trong giỏ</p>
                </div>

                <div id="cart-items-container" class="cart-items" style="display:none;"></div>

                <div id="cart-footer" class="cart-total mt-3" style="display:none;">
                    <div class="d-flex justify-content-between">
                        <span>Tổng cộng:</span>
                        <span class="fw-bold text-primary" id="cart-total-price">0đ</span>
                    </div>
                    <button onclick="goToPayment()" class="btn btn-primary w-100 mt-3">Thanh toán ngay</button>
                </div>

            </div>
        </aside>
    </div>
</div>

<!-- ==========================================================
    MODAL NHẬP SĐT
=========================================================== -->
<div class="modal fade" id="customerPhoneModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <header class="modal-header">
                <h5 class="modal-title">Nhập số điện thoại</h5>
            </header>

            <main class="modal-body">
                <input type="text" id="customerPhone" class="form-control" placeholder="Nhập số điện thoại...">
            </main>

            <footer class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button class="btn btn-primary" onclick="confirmCustomerPhone()">Xác nhận</button>
            </footer>

        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // =====================
    // CART STORAGE
    // =====================
    let cart = [];

    // Format tiền VND
    function formatPrice(num) {
        return num.toLocaleString("vi-VN") + "₫";
    }

    // =====================
    // ADD TO CART
    // =====================
    function addToCart(name, price, image) {
        const item = cart.find(p => p.name === name);

        if (item) {
            item.qty++;
        } else {
            cart.push({
                name, price, image, qty: 1
            });
        }

        updateCartUI();
    }

    // =====================
    // UPDATE CART UI (DESKTOP + MOBILE)
    // =====================
    function updateCartUI() {
        const desktopList = document.getElementById("cart-items-container");
        const desktopEmpty = document.getElementById("cart-empty-state");
        const desktopTotalBox = document.getElementById("cart-footer");
        const desktopCount = document.getElementById("cart-count");

        const mobileList = document.getElementById("mobile-cart-items");
        const mobileCount = document.getElementById("mobile-cart-count");
        const mobileTotal = document.getElementById("mobile-cart-total");

        desktopList.innerHTML = "";
        mobileList.innerHTML = "";

        // Nếu giỏ trống
        if (cart.length === 0) {
            desktopEmpty.style.display = "block";
            desktopTotalBox.style.display = "none";
            desktopCount.textContent = "0";
            mobileCount.textContent = "0";
            mobileTotal.textContent = "0₫";
            return;
        }

        desktopEmpty.style.display = "none";
        desktopTotalBox.style.display = "block";

        let total = 0;

        cart.forEach((p, index) => {
            total += p.qty * p.price;

            // ---------------------------
            // DESKTOP CART
            // ---------------------------
            desktopList.innerHTML += `
                <div class="cart-item d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <div class="fw-bold">${p.name}</div>
                        <div class="text-secondary small">${formatPrice(p.price)}</div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <button class="btn btn-light btn-sm" onclick="changeQty(${index}, -1)">-</button>
                        <span>${p.qty}</span>
                        <button class="btn btn-light btn-sm" onclick="changeQty(${index}, 1)">+</button>
                    </div>
                </div>
            `;

            // ---------------------------
            // MOBILE CART
            // ---------------------------
            mobileList.innerHTML += `
                <div class="mb-3">
                    <div class="fw-bold">${p.name}</div>
                    <div class="text-secondary small">${formatPrice(p.price)}</div>

                    <div class="d-flex align-items-center gap-3 mt-1">
                        <button class="btn btn-light btn-sm" onclick="changeQty(${index}, -1)">-</button>
                        <span>${p.qty}</span>
                        <button class="btn btn-light btn-sm" onclick="changeQty(${index}, 1)">+</button>
                    </div>
                </div>
            `;
        });

        // Update totals
        document.getElementById("cart-total-price").textContent = formatPrice(total);
        mobileTotal.textContent = formatPrice(total);

        // Badge
        const count = cart.reduce((a, c) => a + c.qty, 0);
        desktopCount.textContent = count;
        mobileCount.textContent = count;
    }

    // =====================
    // CHANGE QTY
    // =====================
    function changeQty(index, amount) {
        cart[index].qty += amount;

        if (cart[index].qty <= 0)
            cart.splice(index, 1);

        updateCartUI();
    }

    // =====================
    // MOBILE CART SLIDE
    // =====================
    document.getElementById("mobile-cart-btn").onclick = () => {
        document.getElementById("mobile-cart-sheet").classList.add("active");
    };
    function closeCartSheet() {
        document.getElementById("mobile-cart-sheet").classList.remove("active");
    }
</script>
    <button id="mobile-cart-btn" class="mobile-cart-button">
        <i class="fa fa-shopping-cart"></i>
        <span id="mobile-cart-count">0</span>
    </button>

    <div id="mobile-cart-sheet" class="mobile-cart-sheet">
    <div class="sheet-header">
        <span>Giỏ hàng của bạn</span>
        <button class="close-sheet" onclick="closeCartSheet()">✕</button>
    </div>

    <div id="mobile-cart-items"></div>

    <div class="sheet-footer">
        <div class="d-flex justify-content-between">
            <span>Tổng cộng:</span>
            <strong id="mobile-cart-total"></strong>
        </div>
        <button class="btn btn-primary w-100 mt-2">Thanh toán</button>
    </div>
</div>
</body>
</html>
