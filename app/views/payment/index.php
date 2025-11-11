<!-- Header -->
<?php include 'app/views/layout/header.php'; ?>

<main>
  <div class="container">
    <h1 class="page-title">Thanh Toán Đơn Hàng</h1>

    <!-- Địa chỉ nhận hàng -->
    <section class="address-section mb-4">
      <h3><i class="fas fa-map-marker-alt"></i> Địa Chỉ Nhận Hàng</h3>
      <div id="selectedAddress">
        <?php if (!empty($data['defaultAddress'])): ?>
          <strong><?= htmlspecialchars($data['defaultAddress']['Name']) ?></strong> 
          (<?= htmlspecialchars($data['defaultAddress']['Phone']) ?>)<br>
          <?= htmlspecialchars($data['defaultAddress']['Address']) ?>
          <input type="hidden" id="selectedAddressId" name="address_id" value="<?= $data['defaultAddress']['ID'] ?>">
        <?php else: ?>
          <em>Bạn chưa có địa chỉ mặc định. Vui lòng thêm địa chỉ mới.</em>
        <?php endif; ?>
      </div>
      <button class="btn btn-outline-primary mt-2" data-bs-toggle="modal" data-bs-target="#addressModal">
        Thay đổi
      </button>
    </section>

    <!-- Form thanh toán -->
    <form action="/checkout/submit" method="POST" id="paymentForm">
      <div class="container-sm order-summary">
        <h3><i class="fas fa-shopping-cart"></i> Tóm Tắt Đơn Hàng</h3>

        <?php if (!empty($data['product'])): ?>
          <div id="orderItems">
            <?php foreach ($data['product'] as $product): ?>
              <div class="order-item" data-price="<?= $product['Price'] ?>" data-quantity="<?= $product['Quantity'] ?>">
                <div class="item-info">
                  <div class="item-name"><?= htmlspecialchars($product['Name']) ?></div>
                  <div class="item-quantity">x<?= $product['Quantity'] ?></div>
                </div>
                <div class="item-price"><?= number_format($product['Price'] * $product['Quantity']) ?>đ</div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <div class="summary-divider"></div>

        <div class="summary-row"><span>Tạm tính:</span><span id="subtotal">0đ</span></div>
        <div class="summary-row"><span>Phí giao hàng:</span><span id="shipping">15.000đ</span></div>
        <div class="summary-row"><span>Giảm giá:</span><span>0đ</span></div>
        <div class="summary-row"><span>Điểm thưởng:</span><span>0đ</span></div>

        <div class="summary-divider"></div>

        <div class="summary-row total">
          <span>Tổng cộng:</span><span id="total">0đ</span>
        </div>

        <div class="button-group mt-3 d-flex justify-content-between">
          <a href="/cart" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay Lại
          </a>
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-check"></i> Hoàn Tất Thanh Toán
          </button>
        </div>
      </div>
    </form>
  </div>

  <!-- Address Modal -->
  <div class="modal fade" id="addressModal" tabindex="-1" aria-labelledby="addressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Địa Chỉ Của Tôi</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <?php if (!empty($data['addresses'])): ?>
            <?php foreach ($data['addresses'] as $addr): ?>
              <div class="address-item">
                <input type="radio" name="address" value="<?= $addr['ID'] ?>" 
                  <?= $addr['is_default'] ? 'checked' : '' ?>>
                <label>
                  <strong><?= htmlspecialchars($addr['Name']) ?></strong> 
                  (<?= htmlspecialchars($addr['Phone']) ?>)
                  <div><?= htmlspecialchars($addr['Address']) ?></div>
                  <?php if ($addr['is_default']): ?>
                    <span class="badge bg-danger">Mặc định</span>
                  <?php endif; ?>
                </label>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p>Chưa có địa chỉ nào.</p>
          <?php endif; ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="button" class="btn btn-primary" id="confirmAddress">Xác nhận</button>
        </div>
      </div>
    </div>
  </div>
</main>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const shipping = 15000;
  let subtotal = 0;

  document.querySelectorAll(".order-item").forEach(item => {
    const price = parseInt(item.dataset.price);
    const qty = parseInt(item.dataset.quantity);
    subtotal += price * qty;
  });

  document.getElementById("subtotal").textContent = subtotal.toLocaleString() + "đ";
  document.getElementById("total").textContent = (subtotal + shipping).toLocaleString() + "đ";
});

document.getElementById('confirmAddress')?.addEventListener('click', function() {
  const selected = document.querySelector('input[name="address"]:checked');
  if (!selected) return;

  const label = selected.closest('.address-item').querySelector('label').innerHTML;
  document.getElementById('selectedAddress').innerHTML = label;
  document.getElementById('selectedAddressId').value = selected.value;

  const modal = bootstrap.Modal.getInstance(document.getElementById('addressModal'));
  modal.hide();
});
</script>

<!-- Footer -->
<?php include 'app/views/layout/footer.php'; ?>
