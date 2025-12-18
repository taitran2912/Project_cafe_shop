<!-- Header -->
<?php include 'app/views/layout/header.php'; ?>

<main>
  <div class="container">
    <h1 class="page-title">Thanh Toán Đơn Hàng</h1>

    <!-- Địa chỉ nhận hàng -->
    <section class="address-section mb-4">
      <h3><i class="fas fa-map-marker-alt"></i> Địa Chỉ Nhận Hàng</h3>
      <div id="selectedAddress"
          data-lat="<?= $data['defaultAddress']['Latitude'] ?>"
          data-lng="<?= $data['defaultAddress']['Longitude'] ?>">

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
    <form action="checkout/submit" method="POST" id="paymentForm">
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
        <div class="summary-row"><span>Phí giao hàng:</span><span id="shipping">0đ</span></div>
        <div id="storeDistanceList" class="mt-2 small text-muted"></div>

        <div class="summary-row total">
          <span>Tổng cộng:</span><span id="total">0đ</span>
        </div>

        <input type="hidden" name="address_id" id="selectedAddressId" value="<?= $data['defaultAddress']['ID'] ?>">
        <input type="hidden" name="shipping_fee" id="shipping_fee" value="0">
        <input type="hidden" name="store_id" id="store_id" value="">

        <div class="button-group mt-3 d-flex justify-content-between">
          <a href="cart" class="btn btn-secondary">
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
                  data-lat="<?= $addr['lat'] ?>" data-lng="<?= $addr['lng'] ?>"
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
  const stores = [
    <?php foreach ($data['storeLocations'] as $store): ?>
      { id: <?= $store['ID'] ?>, lat: <?= $store['lat'] ?>, lng: <?= $store['lng'] ?>, name: "<?= htmlspecialchars($store['BranchName']) ?>" },
    <?php endforeach; ?>
  ];

  //kiểm tra có cửa hàng không

  // --- Tính subtotal ---
  let subtotal = 0;
  document.querySelectorAll(".order-item").forEach(item => {
    const price = parseInt(item.dataset.price);
    const qty = parseInt(item.dataset.quantity);
    subtotal += price * qty;
  });
  document.getElementById("subtotal").textContent = subtotal.toLocaleString() + "đ";

  // Tạm tính tổng ban đầu (ship = 0)
  document.getElementById("total").textContent = subtotal.toLocaleString() + "đ";

  // --- Modal logic ---
  const addressModal = document.getElementById('addressModal');
  let prevAddressHtml = document.getElementById('selectedAddress').innerHTML;
  let prevAddressId = document.getElementById('selectedAddressId')?.value || "";
  let prevShipping = document.getElementById("shipping").textContent;

  let addressConfirmed = false;

  // Lưu trạng thái trước khi mở modal
  addressModal.addEventListener('show.bs.modal', () => {
    prevAddressHtml = document.getElementById('selectedAddress').innerHTML;
    prevAddressId = document.getElementById('selectedAddressId')?.value || "";
    prevShipping = document.getElementById("shipping").textContent;
  });

  // Nếu modal đóng mà chưa xác nhận, khôi phục địa chỉ và phí ship cũ
  addressModal.addEventListener('hide.bs.modal', () => {
    if (!addressConfirmed) {
      document.getElementById('selectedAddress').innerHTML = prevAddressHtml;
      if (document.getElementById('selectedAddressId')) {
        document.getElementById('selectedAddressId').value = prevAddressId;
      }
      document.getElementById("shipping").textContent = prevShipping;

      const subtotalVal = parseInt(document.getElementById("subtotal").textContent.replace(/\D/g, ""));
      const shippingVal = parseInt(prevShipping.replace(/\D/g, ""));
      document.getElementById("total").textContent = (subtotalVal + shippingVal).toLocaleString() + "đ";
    }
    addressConfirmed = false;
  });

  // Xác nhận địa chỉ
  document.getElementById('confirmAddress')?.addEventListener('click', function() {
    const selected = document.querySelector('input[name="address"]:checked');
    if (!selected) return;
    addressConfirmed = true;

    const label = selected.closest('.address-item').querySelector('label').innerHTML;
    document.getElementById('selectedAddress').innerHTML = label;

    if (!document.getElementById('selectedAddressId')) {
      const input = document.createElement('input');
      input.type = 'hidden';
      input.id = 'selectedAddressId';
      input.name = 'address_id';
      document.getElementById('selectedAddress').appendChild(input);
    }
    document.getElementById('selectedAddressId').value = selected.value;

    // Tính phí ship dựa trên khoảng cách
    calculateShipping(selected, stores);

    // Đóng modal
    bootstrap.Modal.getInstance(addressModal).hide();
  });

  // Hàm tính khoảng cách (km)
  function getDistance(lat1, lon1, lat2, lon2) {
    const R = 6371; // km
    const dLat = (lat2 - lat1) * Math.PI/180;
    const dLon = (lon2 - lon1) * Math.PI/180;
    const a = Math.sin(dLat/2) ** 2 +
              Math.cos(lat1 * Math.PI/180) * Math.cos(lat2 * Math.PI/180) *
              Math.sin(dLon/2) ** 2;
    return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
  }

  // function calculateShipping(selectedAddress, stores) {
  //     const userLat = parseFloat(selectedAddress.dataset.lat);
  //     const userLng = parseFloat(selectedAddress.dataset.lng);

  //     let minDist = Infinity;
  //     let nearestStoreId = null;

  //     stores.forEach(store => {
  //         const d = getDistance(userLat, userLng, store.lat, store.lng);
  //         if (d < minDist) {
  //             minDist = d;
  //             nearestStoreId = store.id;
  //         }
  //     });

  //     const shippingFee = Math.round(minDist * 5000); // ví dụ 5k/km

  //     // Cập nhật input ẩn trong form
  //     document.getElementById("shipping_fee").value = shippingFee;
  //     document.getElementById("store_id").value = nearestStoreId;

  //     // Cập nhật hiển thị
  //     document.getElementById("shipping").textContent = shippingFee.toLocaleString() + "đ";
  //     const subtotalVal = parseInt(document.getElementById("subtotal").textContent.replace(/\D/g, ""));
  //     document.getElementById("total").textContent = (subtotalVal + shippingFee).toLocaleString() + "đ";
  // }

  function calculateShipping(selectedAddress) {
    const userLat = parseFloat(selectedAddress.dataset.lat);
    const userLng = parseFloat(selectedAddress.dataset.lng);

    let minDist = Infinity;
    let nearestStoreId = null;

    let html = '<strong>Khoảng cách đến các cửa hàng:</strong><ul class="mb-1">';

    stores.forEach(store => {
      const d = getDistance(userLat, userLng, store.lat, store.lng);
      const km = d.toFixed(2);

      html += `<li>
          Cửa hàng ${store.name}: ${km} km
        </li>`;

      if (d < minDist) {
        minDist = d;
        nearestStoreId = store.id;
      }
    });

    html += '</ul>';

    // Phí ship
    const shippingFee = Math.round(minDist * 5000);

    // Gắn input ẩn
    document.getElementById("shipping_fee").value = shippingFee;
    document.getElementById("store_id").value = nearestStoreId;

    // Hiển thị
    document.getElementById("shipping").textContent =
      shippingFee.toLocaleString() + "đ";

    const subtotalVal = parseInt(
      document.getElementById("subtotal").textContent.replace(/\D/g, "")
    );

    document.getElementById("total").textContent =
      (subtotalVal + shippingFee).toLocaleString() + "đ";

    // Hiển thị danh sách khoảng cách
    html += `<div class="text-success">
        → Giao từ cửa hàng #${nearestStoreId} (gần nhất)
      </div>`;

    document.getElementById("storeDistanceList").innerHTML = html;
  }

//

  // Tính phí ship lần đầu với địa chỉ mặc định
  const defaultSelected = document.querySelector('input[name="address"]:checked');
  if (defaultSelected) calculateShipping(defaultSelected, stores);
});

document.getElementById('confirmAddress')?.addEventListener('click', function() {
    const selected = document.querySelector('input[name="address"]:checked');
    if (!selected) return;

    // Cập nhật địa chỉ được chọn
    const label = selected.closest('.address-item').querySelector('label').innerHTML;
    document.getElementById('selectedAddress').innerHTML = label;
    document.getElementById('selectedAddressId').value = selected.value;

    // Tính phí ship và chọn cửa hàng gần nhất
    calculateShipping(selected, stores);

    // Đóng modal
    bootstrap.Modal.getInstance(document.getElementById('addressModal')).hide();
});

</script>


<!-- Footer -->
<?php include 'app/views/layout/footer.php'; ?>
