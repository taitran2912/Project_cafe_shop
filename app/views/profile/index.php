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
                        <h3 class="font-display text-xl font-bold">Nguyễn Văn A</h3>
                        <p class="text-gray-600">nguyenvana@email.com</p>
                    </div>
                    
                    <nav class="space-y-2">
                        <button class="profile-tab active w-full text-left px-4 py-3 rounded-lg bg-primary text-white" data-tab="info">
                            Thông tin cá nhân
                        </button>
                        <button class="profile-tab w-full text-left px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100" data-tab="orders">
                            Đơn hàng của tôi
                        </button>
                        <button class="profile-tab w-full text-left px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100" data-tab="favorites">
                            Sản phẩm yêu thích
                        </button>
                    </nav>
                </div>
            </div>

                <!-- Profile Content  -->
            <div class="lg:col-span-2">
                    <!-- Personal Info Tab  -->
                <div id="info-tab" class="tab-content bg-white rounded-2xl p-8 shadow-lg">
                    <h2 class="font-display text-2xl font-bold mb-6">Thông tin cá nhân</h2>
                    
                    <form class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label class="form-label">Họ tên</label>
                                <input type="text" class="form-input" value="Nguyễn Văn A">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-input" value="nguyenvana@email.com">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label class="form-label">Số điện thoại</label>
                                <input type="tel" class="form-input" value="0123456789">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Ngày sinh</label>
                                <input type="date" class="form-input" value="1990-01-01">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Địa chỉ</label>
                            <textarea class="form-input form-textarea">123 Đường ABC, Quận 1, TP.HCM</textarea>
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
                    <h2 class="font-display text-2xl font-bold mb-6">Sản phẩm yêu thích</h2>
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

    <!-- Footer  -->
<?php
    include 'app/views/layout/footer.php'; 
?>
