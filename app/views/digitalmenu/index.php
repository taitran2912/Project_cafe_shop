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
                <img src="https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=200&h=200" alt="Logo">
            </div>
            <div>
                <div class="text-secondary small mb-1">Cà phê - Trà - Bánh ngọt</div>
                <div class="small text-muted mb-1">
                    <i class="fas fa-map-marker-alt me-1"></i> <?= $data['storeName'] ?> - <?= $data['storeAddress'] ?>
                </div>
                <div class="small text-muted">
                    <span><i class="far fa-clock me-1"></i> 07:00 - 22:30</span>
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

        <!-- LEFT SIDEBAR -->
        <div class="col-md-2 d-none d-md-block">
            <div class="category-sidebar position-sticky" style="top: 20px;">
                <div class="categories">

                    <a href="#favorite-section" class="category-link active">Món yêu thích</a>
                    <a href="#recommended-section" class="category-link">Gợi ý cho bạn</a>
                    <a href="#new-items-section" class="category-link">Món mới</a>

                    <?php foreach ($data['categories'] as $cat): ?>
                        <a href="#section-<?= $cat['ID'] ?>" class="category-link"><?= $cat['Name'] ?></a>
                    <?php endforeach; ?>

                </div>
            </div>
        </div>

        <!-- CENTER CONTENT -->
        <div class="col-md-10 col-lg-7 menu-content">

            <!-- ⭐ FAVORITE SECTION -->
            <div id="favorite-section" class="menu-section mb-5">
                <h3 class="menu-section-title">Món bạn yêu thích</h3>
                <div id="favorite-container" class="row g-3 row-cols-2 row-cols-md-3"></div>
            </div>

            <!-- ⭐ RECOMMENDED SECTION -->
            <div id="recommended-section" class="menu-section mb-5">
                <h3 class="menu-section-title">Gợi ý cho bạn</h3>
                <div id="recommended-container" class="row g-3 row-cols-2 row-cols-md-3"></div>
            </div>

            <!-- ⭐ NEW ITEMS SECTION -->
            <div id="new-items-section" class="menu-section mb-5">
                <h3 class="menu-section-title">Món mới ra mắt</h3>
                <div id="new-items-container" class="row g-3 row-cols-2 row-cols-md-3"></div>
            </div>

            <!-- ⭐ MENU THEO CATEGORY -->
            <?php foreach ($data['categories'] as $cat): ?>
                <div id="section-<?= $cat['ID'] ?>" class="menu-section mb-5">
                    <h3 class="menu-section-title"><?= $cat['Name'] ?></h3>

                    <div class="row g-3 row-cols-2 row-cols-md-3">

                        <?php foreach ($data['products'] as $p): ?>
                            <?php if ($p['ID_category'] == $cat['ID']): ?>
                                <div class="col">
                                    <div class="product-card h-100">
                                        <div class="product-img-wrapper">
                                            <img src="https://caffeshop.hieuthuocyentam.id.vn/public/image/<?= $p['Image'] ?>" class="product-img">
                                        </div>

                                        <div class="product-body">
                                            <div class="product-title"><?= $p['Name'] ?></div>
                                            <div class="product-desc"><?= $p['Description'] ?></div>

                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="product-price"><?= number_format($p['Price'],0,',','.') ?>₫</div>
                                                <button class="btn-add" onclick="addToCart('<?= $p['Name'] ?>', <?= $p['Price'] ?>)">
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

        <!-- RIGHT CART -->
        <div class="col-lg-3 d-none d-lg-block">
            <div class="cart-sidebar position-sticky" style="top: 20px;">

                <div class="search-box mb-3">
                    <i class="fas fa-search"></i>
                    <input type="text" class="form-control" placeholder="Bạn đang cần tìm món gì?">
                </div>

                <h6 class="fw-bold mb-3">Giỏ hàng của bạn</h6>

                <div id="cart-empty-state" class="cart-empty text-center">
                    <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                    <p class="small">Không có món ăn trong giỏ hàng</p>
                </div>

                <div id="cart-items-container" class="cart-items" style="display: none;"></div>

                <div id="cart-footer" class="cart-total" style="display: none;">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tổng cộng:</span>
                        <span class="fw-bold text-primary" id="cart-total-price">0đ</span>
                    </div>
                    <button class="btn btn-primary w-100 btn-checkout" onclick="goToPayment()">
                        Thanh toán ngay
                    </button>
                </div>

            </div>
        </div>

    </div>
</div>



<!-- ==========================================================
    PHONE MODAL
=========================================================== -->
<div class="modal fade" id="customerPhoneModal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header"><h5 class="modal-title">Nhập số điện thoại</h5></div>

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


<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../public/js/digitalmenu.js"></script>

<script>
// =====================================================
// SHOW PHONE MODAL
// =====================================================
document.addEventListener('DOMContentLoaded', () => {
    new bootstrap.Modal('#customerPhoneModal').show();
});

// =====================================================
// PHONE HANDLER
// =====================================================
function confirmCustomerPhone() {
    let phone = document.getElementById('customerPhone').value.trim();
    bootstrap.Modal.getInstance(document.getElementById('customerPhoneModal')).hide();

    fetchFavoriteProducts(phone !== "" ? phone : null);
    fetchRecommendedItems();
    fetchNewItems();
}

// =====================================================
// API FETCHERS
// =====================================================
function fetchFavoriteProducts(phone) {
    fetch(`https://caffeshop.hieuthuocyentam.id.vn/digitalmenu/favorite?phone=${phone ?? ''}`)
        .then(r => r.json())
        .then(data => renderList(data, 'favorite-container'));
}

function fetchRecommendedItems() {
    fetch(`https://caffeshop.hieuthuocyentam.id.vn/digitalmenu/recommend`)
        .then(r => r.json())
        .then(data => renderList(data, 'recommended-container'));
}

function fetchNewItems() {
    fetch(`https://caffeshop.hieuthuocyentam.id.vn/digitalmenu/new`)
        .then(r => r.json())
        .then(data => renderList(data, 'new-items-container'));
}

// =====================================================
// RENDER PRODUCT CARD
// =====================================================
function renderList(products, containerId) {
    const container = document.getElementById(containerId);
    container.innerHTML = "";

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
</script>

</body>
</html>
