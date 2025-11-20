<!-- Header -->
<?php include 'app/views/layout/header.php'; ?>

<main class="py-4 mt-5">
<div class="container">

    <div class="row">

        <!-- LEFT: Order Items -->
        <div class="col-lg-7 mb-4">

            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="mb-3">
                        <i class="fas fa-coffee"></i> Sản Phẩm Đã Chọn
                    </h4>

                    <div id="orderItems">
                        <?php 
                            $subtotal = 0;
                            if (!empty($data['cart'])):
                                foreach ($data['cart'] as $item):
                                    $totalItem = $item['Price'] * $item['Quantity'];
                                    $subtotal += $totalItem;
                        ?>

                        <div class="d-flex align-items-center border-bottom py-3">

                            <!-- Icon -->
                            <div class="me-3">
                                <div class="bg-light border rounded-circle d-flex 
                                            align-items-center justify-content-center"
                                     style="width: 50px; height: 50px;">
                                    <i class="fas fa-coffee"></i>
                                </div>
                            </div>

                            <!-- Details -->
                            <div class="flex-grow-1">
                                <h6 class="mb-1"><?= htmlspecialchars($item['Name']) ?></h6>
                                <small class="text-muted">
                                    <?= htmlspecialchars($item['Note'] ?? "Không có tuỳ chọn") ?>
                                </small>
                                <div class="text-muted mt-1">x<?= $item['Quantity'] ?></div>
                            </div>

                            <!-- Price -->
                            <div class="fw-bold">
                                <?= number_format($totalItem) ?> ₫
                            </div>
                        </div>

                        <?php 
                                endforeach;
                            endif;
                        ?>
                    </div>
                </div>
            </div>

        </div>

        <!-- RIGHT: Payment Summary -->
        <div class="col-lg-5">

            <div class="card shadow-sm">
                <div class="card-body">

                    <h4 class="mb-3">Tổng Thanh Toán</h4>

                    <?php 
                        $shipping_fee = $data['shipping_fee'] ?? 0;
                        $totalAll = $subtotal + $shipping_fee;
                    ?>

                    <ul class="list-group mb-3">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Tạm tính</span>
                            <strong id="subtotal"><?= number_format($subtotal) ?> ₫</strong>
                        </li>

                        <li class="list-group-item d-flex justify-content-between">
                            <span>Phí vận chuyển</span>
                            <strong id="shippingFee"><?= number_format($shipping_fee) ?> ₫</strong>
                        </li>

                        <li class="list-group-item d-flex justify-content-between bg-light fw-bold">
                            <span>Tổng cộng</span>
                            <span id="totalAmount"><?= number_format($totalAll) ?> ₫</span>
                        </li>
                    </ul>

                    <!-- Payment Method -->
                    <div class="mb-3">
                        <h5>Quét mã QR để thanh toánn</h5>

                        <div class="d-flex justify-content-center mb-3">
                            <img src="<?= htmlspecialchars($data['QR']) ?>"
                                 class="img-fluid border rounded p-2"
                                 style="max-width: 250px;"
                                 alt="QR Code Thanh Toán">
                        </div>

                        <div class="text-center">
                            <p class="mb-1 fw-semibold">
                              <?= htmlspecialchars($data['bank']['bank_code']) ?> : <?= htmlspecialchars($data['bank']['account_number']) ?>
                            </p>
                            <p class="mb-1">
                                <?= htmlspecialchars($data['bank']['account_name']) ?>
                            </p>
                            <p class="mb-1">
                                <?= htmlspecialchars($data['OrderCode']) ?>
                            </p>
                        </div>
                    </div>

                    <a href="payment" class="btn btn-outline-secondary w-100">
                        Quay lại thanh toán
                    </a>

                </div>
            </div>

        </div>

    </div>

</div>
</main>

<!-- Footer -->
<?php include 'app/views/layout/footer.php'; ?>
