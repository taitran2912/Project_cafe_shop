<?php include 'app/views/layout/header.php'; ?>

<h1 class="cart-title">Giỏ hàng của bạn</h1>

<div class="cart-container">
  <div class="cart-items" id="cart-content">
    <!-- cart.js sẽ render nội dung vào đây -->
  </div>
</div>

<div class="cart-container"> 
    <button class="checkout-btn" onclick="window.location.href='payment'">
        Thanh toán
    </button>
    <button class="continue-btn" onclick="window.location.href='menu'">
        Tiếp tục mua sắm
    </button>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="<?= BASE_URL ?>public/js/cart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    loadCart();
});

function loadCart() {
  $.ajax({
    url: '<?= BASE_URL ?>/index.php?url=cart/getCart/<?= $userID;?>',
    type: 'GET',
    dataType: 'json',
    success: function(data) {
      updateCartDisplay(data)
    },
    error: function(xhr) {
      console.error("Không tải được giỏ hàng:", xhr.responseText)
    }
  })
}

</script>

<?php include 'app/views/layout/footer.php'; ?>