<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?></title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../../public/css/digitalmenu.css">
</head>

<body>

<!-- ==========================================================
    HEADER
=========================================================== -->
<header class="shop-header">
    <div class="container-fluid px-md-5">
        <div class="d-flex align-items-center shop-info-card">
            <div class="me-3">
                <img src="https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=200&h=200"
                     alt="Logo">
            </div>

            <div>
                <div class="text-secondary small mb-1">Cà phê - Trà - Bánh ngọt</div>

                <div class="small text-muted mb-1">
                    <i class="fas fa-map-marker-alt me-1"></i>
                    <?= $data['storeName'] ?> - <?= $data['storeAddress'] ?>
                </div>

                <div class="small text-muted">
                    <i class="far fa-clock me-1"></i> 07:00 - 22:30
                </div>
            </div>
        </div>
    </div>
</header>

<!-- ==========================================================
    MAIN
=========================================================== -->
<div class="container-fluid px-md-5 mb-5">
    <div class="row">

        <!-- ==========================================================
            CATEGORY SIDEBAR
        =========================================================== -->
        <div class="col-md-2 d-none d-md-block">
            <div class="category-sidebar position-sticky" style="top: 20px;">
                <div class="categories">
                    <a href="#favorite-section" class="category-link active">Món yêu thích</a>
                    <a href="#recommended-section" class="category-link">Gợi ý cho bạn</a>
                    <a href="#new-items-section" class="category-link">Món mới</a>

                    <?php foreach ($data['categories'] as $cat): ?>
                        <a href="#section-<?= $cat['ID'] ?>" class="category-link">
                            <?= htmlspecialchars($cat['Name']) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- ==========================================================
            MENU CONTENT
        =========================================================== -->
        <div class="col-md-10 col-lg-7 menu-content">

            <!-- Món yêu thích -->
            <div id="favorite-section" class="menu-section mb-5">
                <h3 class="menu-section-title">Món bạn yêu thích</h3>
                <div id="favorite-container" class="row g-3 row-cols-2 row-cols-md-3"></div>
            </div>

            <!-- Gợi ý -->
            <div id="recommended-section" class="menu-section mb-5">
                <h3 class="menu-section-title">Gợi ý cho bạn</h3>

                <div class="row g-3 row-cols-2 row-cols-md-3">
                    <?php foreach ($data['recommended'] as $p): ?>
                        <div class="col">
                            <div class="product-card h-100">
                                <div class="product-img-wrapper">
                                    <img src="https://caffeshop.hieuthuocyentam.id.vn/public/image/<?= $p['Image'] ?>"
                                         class="product-img"
                                         alt="<?= htmlspecialchars($p['Name']) ?>">
                                </div>

                                <div class="product-body">
                                    <div class="product-title"><?= $p['Name'] ?></div>
                                    <div class="product-price"><?= number_format($p['Price'], 0, ',', '.') ?>₫</div>

                                    <button class="btn-add" onclick="addToCart('<?= $p['Name'] ?>', <?= $p['Price'] ?>)">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Món mới -->
            <div id="new-items-section" class="menu-section mb-5">
                <h3 class="menu-section-title">Món mới ra mắt</h3>

                <div class="row g-3 row-cols-2 row-cols-md-3">
                    <?php foreach ($data['newItems'] as $p): ?>
                        <div class="col">
                            <div class="product-card h-100">
                                <div class="product-img-wrapper">
                                    <img src="https://caffeshop.hieuthuocyentam.id.vn/public/image/<?= $p['Image'] ?>"
                                         class="product-img"
                                         alt="<?= htmlspecialchars($p['Name']) ?>">
                                </div>

                                <div class="product-body">
                                    <div class="product-title"><?= $p['Name'] ?></div>
                                    <div class="product-price"><?= number_format($p['Price'], 0, ',', '.') ?>₫</div>

                                    <button class="btn-add" onclick="addToCart('<?= $p['Name'] ?>', <?= $p['Price'] ?>)">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Theo danh mục -->
            <?php foreach ($data['categories'] as $cat): ?>
                <div id="section-<?= $cat['ID'] ?>" class="menu-section mb-5">
                    <h3 class="menu-section-title"><?= $cat['Name'] ?></h3>

                    <div class="row g-3 row-cols-2 row-cols-md-3">
                        <?php foreach ($data['products'] as $product): ?>
                            <?php if ($product['ID_category'] == $cat['ID']): ?>
                                <div class="col">
                                    <div class="product-card h-100">

                                        <div class="product-img-wrapper">
                                            <img src="https://caffeshop.hieuthuocyentam.id.vn/public/image/<?= $product['Image'] ?>"
                                                 class="product-img"
                                                 alt="<?= htmlspecialchars($product['Name']) ?>">
                                        </div>

                                        <div class="product-body">
                                            <div class="product-title"><?= $product['Name'] ?></div>
                                            <div class="product-desc"><?= $product['Description'] ?></div>

                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="product-price">
                                                    <?= number_format($product['Price'], 0, ',', '.') ?>₫
                                                </div>

                                                <button class="btn-add"
                                                        onclick="addToCart('<?= $product['Name'] ?>', <?= $product['Price'] ?>)">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>

        <!-- ==========================================================
            CART SIDEBAR
        =========================================================== -->
        <div class="col-lg-3 d-none d-lg-block">
            <div class="cart-sidebar position-sticky" style="top: 20px;">

                <div class="search-box mb-3">
                    <i class="fas fa-search"></i>
                    <input type="text" class="form-control" placeholder="Bạn đang cần tìm món gì?">
                </div>

                <?php if (isset($data['tableNumber'])): ?>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="m-0 fw-bold">Bàn số <?= $data['tableNumber'] ?></h6>
                    </div>
                <?php endif; ?>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="m-0 fw-bold">Giỏ hàng của bạn</h6>
                    <span class="badge bg-secondary" id="cart-count">0</span>
                </div>

                <div id="cart-empty-state" class="cart-empty text-center">
                    <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                    <p class="small">Không có món ăn trong giỏ hàng</p>
                </div>

                <div id="cart-items-container" class="cart-items" style="display: none;"></div>

                <div class="cart-total" id="cart-footer" style="display: none;">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tổng cộng:</span>
                        <span class="fw-bold text-primary" id="cart-total-price">0đ</span>
                    </div>
                    <button class="btn-checkout btn btn-primary w-100" onclick="goToPayment()">
                        Thanh toán ngay
                    </button>
                </div>

            </div>
        </div>

    </div>
</div>

<!-- ==========================================================
    MODAL NHẬP SĐT
=========================================================== -->
<div class="modal fade" id="customerPhoneModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Nhập số điện thoại</h5>
            </div>

            <div class="modal-body">
                <input type="text" id="customerPhone" class="form-control" placeholder="Nhập số điện thoại...">
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button class="btn btn-primary" onclick="confirmCustomerPhone()">Xác nhận</button>
            </div>

        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://caffeshop.hieuthuocyentam.id.vn/public/js/digitalmenu.js"></script>

<script>
// ====================================================
// Hiển modal khi load trang
// ====================================================
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

function goToPayment() {
    if (cart.length === 0) {
        alert("Giỏ hàng của bạn đang trống!");
        return;
    }

    const orderData = {
        phone: window.customerPhone ?? null,
        items: cart,
        total: cart.reduce((sum, item) => sum + item.price * item.quantity, 0)
    };

    // Lưu giỏ hàng
    localStorage.setItem("pendingOrder", JSON.stringify(orderData));

    const phoneParam = orderData.phone ? orderData.phone : guest;

    // Điều hướng sang trang thanh toán (KHÔNG lỗi biến)
    window.location.href = `https://caffeshop.hieuthuocyentam.id.vn/checkout/${orderData.phone}`;
}

</script>

</body>
</html>
