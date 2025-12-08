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
                <button onclick="openAddressModal()" 
                    class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90">
                    + Thêm địa chỉ
                </button>
                    <div class="text-center py-12">
                        <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-600 mb-4">Chưa có sản phẩm yêu thích</h3>
                        <p class="text-gray-500 mb-8">Hãy thêm những sản phẩm bạn yêu thích để dễ dàng tìm lại</p>
                        <a href="menu.html" class="btn-primary">Khám phá thực đơn</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Address Modal -->
<div id="addressModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white p-6 rounded-xl w-full max-w-md shadow-xl">
        <h2 class="text-xl font-bold mb-4">Thêm địa chỉ mới</h2>

        <form id="addAddressForm">
            <input id="newAddress" type="text" 
                   placeholder="Nhập địa chỉ..." 
                   class="w-full border px-4 py-2 rounded-lg mb-4">

            <!-- Thêm checkbox mặc định -->
            <div class="mb-4 flex items-center gap-2">
                <input type="checkbox" id="isDefault" class="h-4 w-4">
                <label for="isDefault" class="text-gray-700">Đặt làm địa chỉ mặc định</label>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeAddressModal()" 
                        class="px-4 py-2 bg-gray-200 rounded-lg">Hủy</button>
                <button type="submit" 
                        class="px-4 py-2 bg-primary text-white rounded-lg">Lưu</button>
            </div>
        </form>
    </div>
</div>

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

function openAddressModal() {
    document.getElementById("addressModal").classList.remove("hidden");
}

function closeAddressModal() {
    document.getElementById("addressModal").classList.add("hidden");
}

document.getElementById("addAddressForm").addEventListener("submit", function(e) {
    e.preventDefault();

    const address = document.getElementById("newAddress").value.trim();
    if (!address) return alert("Vui lòng nhập địa chỉ!");

    fetch("https://caffeshop.hieuthuocyentam.id.vn/profile/addAddress", {
        method: "POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify({ address })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert("Thêm địa chỉ thành công!");
            location.reload();
        } else {
            alert("Không thể thêm địa chỉ.");
        }
    });
});


document.getElementById("addAddressForm").addEventListener("submit", function(e) {
    e.preventDefault();

    const address = document.getElementById("newAddress").value.trim();
    const isDefault = document.getElementById("isDefault").checked ? 1 : 0;

    if (!address) return alert("Vui lòng nhập địa chỉ!");

    // Lấy latitude và longitude từ Nominatim
    fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(address)}&format=json`)
        .then(res => res.json())
        .then(data => {
            if (data.length === 0) {
                return alert("Không tìm thấy địa chỉ. Vui lòng thử lại.");
            }

            const latitude = data[0].lat;
            const longitude = data[0].lon;

            // Gửi lên backend cùng địa chỉ và mặc định
            fetch("https://caffeshop.hieuthuocyentam.id.vn/profile/addAddress", {
                method: "POST",
                headers: {"Content-Type": "application/json"},
                body: JSON.stringify({ 
                    address, 
                    isDefault, 
                    latitude, 
                    longitude 
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert("Thêm địa chỉ thành công!");
                    location.reload();
                } else {
                    alert("Không thể thêm địa chỉ.");
                }
            });
        })
        .catch(err => {
            console.error(err);
            alert("Đã có lỗi khi lấy tọa độ.");
        });
});


</script>
<script src='https://caffeshop.hieuthuocyentam.id.vn/public/js/profile.js'></script>
    <!-- Footer  -->
<?php
    include 'app/views/layout/footer.php'; 
?>
