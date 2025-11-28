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
                        <span class="me-3"><i class="far fa-clock me-1"></i> 07:00 - 22:30 <?= $data['userID'] ?></span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
<div class="container-fluid px-md-5 mb-5">
    <div class="row">

        <!-- Left Sidebar: Categories -->
        <div class="col-md-2 d-none d-md-block">
            <div class="category-sidebar position-sticky" style="top: 20px;">
                <div class="categories">
                    <?php foreach ($data['categories'] as $index => $cat): ?>
                        <a href="#section-<?= $cat['ID'] ?>" class="category-link <?= $index === 0 ? 'active' : '' ?>">
                            <?= $cat['Name'] ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Center: Menu Items -->
        <div class="col-md-10 col-lg-7">
            <?php foreach ($data['categories'] as $cat): ?>
                <div id="section-<?= $cat['ID'] ?>" class="menu-section mb-5">
                    <h3 class="menu-section-title"><?= $cat['Name'] ?></h3>
                    <div class="row g-3 row-cols-2 row-cols-md-3">
                        <?php foreach ($data['products'] as $product): ?>
                            <?php if ($product['ID_category'] == $cat['ID']): ?>
                                <div class="col">
                                    <div class="product-card h-100">
                                        <div class="product-img-wrapper">
                                            <img src="../../public/image/<?= $product['Image'] ?>" class="product-img" alt="<?= $product['Name'] ?>">
                                        </div>
                                        <div class="product-body">
                                            <div class="product-title"><?= $product['Name'] ?></div>
                                            <div class="product-desc"><?= $product['Description'] ?></div>
                                            <div class="d-flex justify-content-between align-items-center mt-2">
                                                <div class="product-price"><?= number_format($product['Price'],0,',','.') ?>₫</div>
                                                <button class="btn-add" onclick="addToCart('<?= $product['Name'] ?>', <?= $product['Price'] ?>)">
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

        <!-- Right Sidebar: Cart -->
        <div class="col-lg-3 d-none d-lg-block">
            <div class="cart-sidebar position-sticky" style="top: 20px;">
                

                <?php if(isset($data['tableNumber'])): ?>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="m-0 fw-bold">Bàn số <?= $data['tableNumber'] ?></h6>
                    </div>
                <?php endif; ?>

                <?php if(isset($data['storeName'])): ?>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="m-0 fw-bold">Bạn đang mua mang về!</h6>
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

<!-- Customer Phone Modal -->
<div class="modal fade" id="customerPhoneModal" tabindex="-1" aria-labelledby="customerPhoneModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="customerPhoneModalLabel">Nhập số điện thoại</h5>
      </div>
      <div class="modal-body">
        <input type="text" id="customerPhone" class="form-control" placeholder="Nhập số điện thoại...">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
        <button type="button" class="btn btn-primary" onclick="confirmCustomerPhone()">Xác nhận</button>
      </div>
    </div>
  </div>
</div>



    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL ?>public/js/digitalmenu.js"></script>

    <script>
// Hiển thị modal nhập số điện thoại khi tải trang
        // document.addEventListener('DOMContentLoaded', function () {
        //     var phoneModal = new bootstrap.Modal(document.getElementById('customerPhoneModal'));
        //     phoneModal.show();
        // });

// Xử lý xác nhận số điện thoại
        function confirmCustomerPhone() {
            var phone = document.getElementById('customerPhone').value.trim();

            if(phone === '') {
                alert('Vui lòng nhập số điện thoại!');
                return;
            }

            window.customerPhone = phone; // lưu tạm

            // Đóng modal
            var phoneModalEl = document.getElementById('customerPhoneModal');
            var modal = bootstrap.Modal.getInstance(phoneModalEl);
            modal.hide();

            console.log('Số điện thoại khách hàng:', phone);

            // Gọi gợi ý món yêu thích từ server
            fetchFavoriteProducts(phone);
        }

// Lấy món yêu thích từ server dựa trên số điện thoại
        function fetchFavoriteProducts(phone) {
            fetch(`../../digitalmenu/favorite?phone=${phone}`)
            .then(response => response.json())
            .then(data => {
                console.log('Favorite data:', data); // kiểm tra data
                if(data.length > 0) {
                    displayFavoriteSuggestions(data);
                }
            })
            .catch(error => console.error('Lỗi khi lấy món yêu thích:', error));
        }
        
// Hiển thị gợi ý món yêu thích
        function displayFavoriteSuggestions(products) {
            let container = document.createElement('div');
            container.classList.add('favorite-suggestions', 'mb-4');
            container.innerHTML = '<h5>Món bạn yêu thích</h5><div class="row g-3 row-cols-2 row-cols-md-3"></div>';

            const row = container.querySelector('div.row');

            products.forEach(product => {
                const col = document.createElement('div');
                col.classList.add('col');
                col.innerHTML = `
                    <div class="product-card h-100">
                        <div class="product-img-wrapper">
                            <img src="../../public/image/${product.Image}" class="product-img" alt="${product.Name}">
                        </div>
                        <div class="product-body">
                            <div class="product-title">${product.Name}</div>
                            <div class="product-desc">${product.Description || ''}</div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div class="product-price">${Number(product.Price).toLocaleString('vi-VN')}₫</div>
                                <button class="btn-add" onclick="addToCart('${product.Name}', ${product.Price})">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                row.appendChild(col);
            });

            // Chèn container vào đầu menu
            const menuContainer = document.querySelector('.col-md-10.col-lg-7');
            menuContainer.prepend(container);
        }


// Chuyển đến trang thanh toán
        function goToPayment() {
            // Nếu có tableNumber
            <?php if(isset($data['tableNumber'])): ?>
                window.location.href = "../../payment_store/table/<?= $data['tableNumber'] ?>";
            <?php elseif(isset($data['storeName'])): ?>
                window.location.href = "../../payment_store/store/<?= $data['storeID'] ?>";
            <?php endif; ?>
        }
    </script>

