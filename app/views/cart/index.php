    <!-- Header -->
<?php
    include 'app/views/layout/header.php'; 
?>
<!-- Page Header  -->
<section class="page-header">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="font-display text-5xl font-bold mb-4">Giỏ hàng</h1>
        <p class="text-xl opacity-90 max-w-2xl mx-auto">
            Xem lại đơn hàng của bạn trước khi thanh toán
        </p>
    </div>
</section>

    <!-- Cart Content  -->
<section class="content-section bg-muted">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div id="cart-content">
                Cart items will be loaded here 
        </div>
    </div>
</section>

    <!-- Footer  -->
<?php
    include 'app/views/layout/footer.php'; 
?>

    <script src="<?= BASE_URL ?>public/js/cart.js"></script>
