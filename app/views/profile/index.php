<?php include 'app/views/layout/header.php'; ?>

<!-- Page Header -->
<section class="page-header">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="font-display text-5xl font-bold mb-4">Tài khoản</h1>
        <p class="text-xl opacity-90 max-w-2xl mx-auto">
            Quản lý thông tin cá nhân và đơn hàng của bạn
        </p>
    </div>
</section>

<!-- Profile Content -->
<section class="content-section bg-muted">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl p-6 shadow-lg">
                    <div class="text-center mb-6">
                        <div class="w-24 h-24 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-12 h-12 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h3 class="font-display text-xl font-bold"><?= $data['Name'] ?></h3>
                        <p class="text-gray-600"><?= $data['Mail'] ?></p>
                    </div>

                    <nav class="space-y-2">
                        <button class="profile-tab active w-full text-left px-4 py-3 rounded-lg bg-primary text-white" data-tab="info">Thông tin cá nhân</button>
                        <button class="profile-tab w-full text-left px-4 py-3 rounded-lg hover:bg-gray-100" data-tab="orders">Đơn hàng của tôi</button>
                        <button class="profile-tab w-full text-left px-4 py-3 rounded-lg hover:bg-gray-100" data-tab="addresses">Địa chỉ nhận hàng</button>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-2">

                <!-- INFO -->
                <div id="info-tab" class="tab-content bg-white rounded-2xl p-8 shadow-lg">
                    <h2 class="font-display text-2xl font-bold mb-6">Thông tin cá nhân</h2>

                    <form class="space-y-6" method="POST" action="/profile/updateInfor">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="form-label">Họ tên</label>
                                <input type="text" class="form-input" name="Name" value="<?= $data['Name'] ?>">
                            </div>
                            <div>
                                <label class="form-label">Email</label>
                                <input type="email" class="form-input" name="Mail" value="<?= $data['Mail'] ?>">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="form-label">Số điện thoại</label>
                                <input type="tel" class="form-input" name="Phone" value="<?= $data['Phone'] ?>">
                            </div>
                            <div>
                                <label class="form-label">Điểm thưởng</label>
                                <p class="form-input bg-gray-100 cursor-not-allowed">
                                    <?= htmlspecialchars($data['Point']) ?>
                                </p>
                            </div>
                        </div>

                        <button type="submit" class="btn-primary">Cập nhật thông tin</button>
                    </form>
                </div>

                <!-- ORDERS -->
                <div id="orders-tab" class="tab-content bg-white rounded-2xl p-8 shadow-lg hidden">
                    <h2 class="font-display text-2xl font-bold mb-6">Đơn hàng của tôi</h2>
                    <div id="orders-list"></div>
                </div>

                <!-- ADDRESSES -->
                <div id="favorites-tab" class="tab-content bg-white rounded-2xl p-8 shadow-lg hidden">

                    <div class="flex justify-end mb-6">
                        <button onclick="openAddressModal()" 
                                class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90">
                            + Thêm địa chỉ
                        </button>
                    </div>

                    <div id="address-list">
                        <!-- JS render address list -->
                    </div>

                </div>

            </div>
        </div>
    </div>
</section>

<!-- Modal Thêm Địa Chỉ -->
<div id="address-modal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white w-full max-w-lg rounded-2xl p-6">
        <h3 class="text-xl font-bold mb-4">Thêm địa chỉ mới</h3>

        <form id="addAddressForm">
            <textarea name="address" class="form-input w-full h-32" placeholder="Nhập địa chỉ..."></textarea>

            <div class="flex justify-end gap-3 mt-4">
                <button type="button" onclick="closeAddressModal()" class="px-4 py-2 bg-gray-200 rounded-lg">
                    Hủy
                </button>
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg">
                    Lưu
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const profileData = {
    orders: <?= json_encode($data['Order']) ?>,
    addresses: <?= json_encode($data['Address']) ?>
};
</script>

<script src="/public/js/profile.js"></script>

<?php include 'app/views/layout/footer.php'; ?>
