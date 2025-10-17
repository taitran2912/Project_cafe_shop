    <!-- Header  -->
<?php
    include 'app/views/layout/header.php'; 
?>
    <!-- Hero Section  -->
<section class="hero-bg min-h-screen flex items-center pt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="fade-in">
                <h1 class="font-display text-5xl lg:text-7xl font-bold text-foreground mb-6 leading-tight">
                    Hương vị
                    <span class="text-primary">tuyệt vời</span>
                    trong từng giọt
                </h1>
                <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                    Khám phá bộ sưu tập cà phê và trà cao cấp được tuyển chọn kỹ lưỡng từ những vùng đất nổi tiếng nhất thế giới.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="menu" class="btn-primary text-center">Khám phá thực đơn</a>
                </div>
            </div>
            <div class="fade-in">
                <div class="relative">
                    <img src="<?= BASE_URL ?>/public/image/background.jpg" 
                            alt="Cà phê cao cấp" 
                            class="w-full h-auto rounded-2xl shadow-2xl" style="max-height: 500px; object-fit: cover;">
                    <div class="absolute -bottom-6 -left-6 bg-white p-6 rounded-xl shadow-lg">
                        <div class="text-3xl font-bold text-primary">100+</div>
                        <div class="text-gray-600">Loại đồ uống</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

    <!-- Featured Products  -->
<section class="py-20 bg-muted">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 fade-in">
            <h2 class="font-display text-4xl lg:text-5xl font-bold text-foreground mb-4">
                Sản phẩm nổi bật
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Những món đồ uống được yêu thích nhất tại cửa hàng của chúng tôi.
            </p>
        </div>
<!--  -->

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
        
<?php if (!empty($data['products'])): ?>
            <?php foreach ($data['products'] as $product): ?>

            <div class="card-hover bg-white rounded-2xl overflow-hidden shadow-lg fade-in">
                <img src="<?= BASE_URL ?>/public/image/<?= htmlspecialchars($product['Image']) ?>" 
                        alt="<?= htmlspecialchars($product['Name']) ?>" 
                        class="w-full h-48 object-cover">
                <div class="p-6">
                    <h3 class="font-display text-xl font-semibold mb-2">
                        <?= htmlspecialchars($product['Name']) ?>
                    </h3>

                    <p class="text-gray-600 mb-4">
                        <?= htmlspecialchars($product['Description']) ?>
                    </p>

                    <div class="flex justify-between items-center">
                        <span class="text-2xl font-bold text-primary">
                            <?= number_format($product['Price']) ?>đ
                        </span>
                        <button class="btn-primary text-sm px-4 py-2 add-to-cart" 
                                data-name="<?= htmlspecialchars($product['Name']) ?>" 
                                data-price="<?= number_format($product['Price']) ?>">
                            Thêm vào giỏ
                        </button>
                    </div>

                </div>
            </div>

            <?php endforeach; ?>
<?php else: ?>
    <p>Chưa có sản phẩm nào.</p>
<?php endif; ?>
        </div>

<!--  -->
        <div class="text-center">
            <a href="menu.html" class="btn-primary">Xem tất cả sản phẩm</a>
        </div>
    </div>
</section>

    <!-- Footer  -->
<?php include 'app/views/layout/footer.php'; ?>


