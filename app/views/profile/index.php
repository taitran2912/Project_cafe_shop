    <!-- Header -->
<?php
    include 'app/views/layout/header.php'; 
?>
    <!-- Page Header  -->
<section class="page-header">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="font-display text-5xl font-bold mb-4">Tài khoản</h1>
        <p class="text-xl opacity-90 max-w-2xl mx-auto">
            Quản lý thông tin cá nhân và đơn hàng của bạn
        </p>
    </div>
</section>

    <!-- Profile Content  -->
<section class="content-section bg-muted">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Profile Sidebar  -->
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
                        <button class="profile-tab active w-full text-left px-4 py-3 rounded-lg bg-primary text-white" data-tab="info">
                            Thông tin cá nhân
                        </button>
                        <button class="profile-tab w-full text-left px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100" data-tab="orders">
                            Đơn hàng của tôi
                        </button>
                        <button class="profile-tab w-full text-left px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100" data-tab="favorites">
                            Địa chỉ nhận hàng
                        </button>
                    </nav>
                </div>
            </div>

                <!-- Profile Content  -->
            <div class="lg:col-span-2">
                    <!-- Personal Info Tab  -->
                <div id="info-tab" class="tab-content bg-white rounded-2xl p-8 shadow-lg">
                    <h2 class="font-display text-2xl font-bold mb-6">Thông tin cá nhân</h2>
                    
                    <form class="space-y-6" method="POST" action="https://caffeshop.hieuthuocyentam.id.vn/profile/updateInfor">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label class="form-label">Họ tên</label>
                                <input type="text" class="form-input" value="<?= $data['Name'] ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-input" value="<?= $data['Mail'] ?>">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label class="form-label">Số điện thoại</label>
                                <input type="tel" class="form-input" value="<?= $data['Phone'] ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Điểm thưởng</label>
                                 <p class="form-input cursor-not-allowed bg-gray-100"><?= htmlspecialchars($data['Point']) ?></p>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn-primary">Cập nhật thông tin</button>
                    </form>
                </div>

                    <!-- Orders Tab  -->
                <div id="orders-tab" class="tab-content bg-white rounded-2xl p-8 shadow-lg" style="display: none;">
                    <h2 class="font-display text-2xl font-bold mb-6">Đơn hàng của tôi</h2>
                    <div id="orders-list">
                            Orders will be loaded here 
                    </div>
                </div>

                    <!-- Favorites Tab  -->
                <div id="favorites-tab" class="tab-content bg-white rounded-2xl p-8 shadow-lg" style="display: none;">
                    <h2 class="font-display text-2xl font-bold mb-6">Địa chỉ nhận hàng</h2>
                    <button onclick="openAddressModal()" 
                            class="btn-primary mb-6 px-4 py-2 rounded-lg">
                        + Thêm địa chỉ
                    </button>


                </div>
            </div>
        </div>
    </div>
</section>
<script>
const profileData = {
    orders: [
        <?php foreach ($data['Order'] as $o): ?>
        {
            id: <?= $o['ID'] ?>,
            date: "<?= $o['Date'] ?>",
            status: "<?= $o['Status'] ?>",

            ship: <?= $o['Shipping_Cost'] ?>,
            total: <?= $o['Total'] ?>,
            items: [
                <?php foreach ($o['Details'] as $it): ?>
                {
                    name: "<?= htmlspecialchars($it['Name']) ?>",
                    quantity: <?= $it['Quantity'] ?>,
                    price: <?= $it['Price'] ?>
                },
                <?php endforeach; ?>
            ]
        },
        <?php endforeach; ?>
    ],
    addresses: [
        <?php foreach ($data['Address'] as $a): ?>
        {
            id: <?= $a['ID'] ?>,
            address: "<?= htmlspecialchars($a['Address']) ?>",
            isDefault: <?= $a['AddressDefault'] ?>
        },
        <?php endforeach; ?>
    ]
};
</script>
<script src='https://caffeshop.hieuthuocyentam.id.vn/public/js/profile.js'></script>
    <!-- Footer  -->
<?php
    include 'app/views/layout/footer.php'; 
?>
