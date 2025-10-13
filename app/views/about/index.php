<!-- Header  -->
<?php include 'app/views/layout/header.php'; ?>
<!-- Page Header  -->
<section class="page-header">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="font-display text-5xl font-bold mb-4">Về chúng tôi</h1>
        <p class="text-xl opacity-90 max-w-2xl mx-auto">
            Câu chuyện về hành trình tạo nên những ly cà phê và trà tuyệt vời
        </p>
    </div>
</section>

    <!-- About Content  -->
<section class="content-section bg-background">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center mb-20">
            <div class="fade-in">
                <img src="<?= BASE_URL ?>public/image/background.jpg" 
                        alt="Không gian cửa hàng" 
                        class="w-full h-auto rounded-2xl shadow-2xl">
            </div>
            <div class="fade-in">
                <h2 class="font-display text-4xl lg:text-5xl font-bold text-foreground mb-6">
                    Câu chuyện của chúng tôi
                </h2>
                <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                    Được thành lập từ năm 2015, Café & Tea House là điểm đến lý tưởng cho những ai yêu thích hương vị đậm đà của cà phê và sự tinh tế của trà.
                </p>
                <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                    Chúng tôi tự hào mang đến những sản phẩm chất lượng cao nhất, được tuyển chọn từ những vùng đất nổi tiếng và chế biến bởi những barista tài năng.
                </p>
                
                <div class="grid grid-cols-2 gap-8 mb-8">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-primary mb-2">10+</div>
                        <div class="text-gray-600">Năm kinh nghiệm</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-primary mb-2">50k+</div>
                        <div class="text-gray-600">Khách hàng hài lòng</div>
                    </div>
                </div>
            </div>
        </div>

            <!-- Mission & Vision  -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mb-20">
            <div class="bg-white p-8 rounded-2xl shadow-lg fade-in">
                <h3 class="font-display text-2xl font-bold text-primary mb-4">Sứ mệnh</h3>
                <p class="text-gray-600 leading-relaxed">
                    Mang đến cho khách hàng những trải nghiệm tuyệt vời nhất qua từng ly cà phê và trà, 
                    tạo ra không gian ấm cúng để mọi người có thể thư giãn, làm việc và kết nối.
                </p>
            </div>
            <div class="bg-white p-8 rounded-2xl shadow-lg fade-in">
                <h3 class="font-display text-2xl font-bold text-primary mb-4">Tầm nhìn</h3>
                <p class="text-gray-600 leading-relaxed">
                    Trở thành chuỗi cà phê và trà hàng đầu Việt Nam, được biết đến với chất lượng cao, 
                    dịch vụ tận tâm và không gian độc đáo.
                </p>
            </div>
        </div>

            <!-- Values  -->
        <div class="text-center mb-16 fade-in">
            <h2 class="font-display text-4xl font-bold text-foreground mb-4">Giá trị cốt lõi</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Những giá trị định hướng mọi hoạt động của chúng tôi
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center bg-white p-8 rounded-2xl shadow-lg fade-in">
                <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-primary" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="font-display text-xl font-bold mb-3">Chất lượng</h3>
                <p class="text-gray-600">Cam kết sử dụng nguyên liệu cao cấp nhất và quy trình chế biến nghiêm ngặt</p>
            </div>

            <div class="text-center bg-white p-8 rounded-2xl shadow-lg fade-in">
                <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-primary" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/>
                    </svg>
                </div>
                <h3 class="font-display text-xl font-bold mb-3">Tận tâm</h3>
                <p class="text-gray-600">Phục vụ khách hàng với tình yêu và sự chăm sóc tận tình nhất</p>
            </div>

            <div class="text-center bg-white p-8 rounded-2xl shadow-lg fade-in">
                <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-primary" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                    </svg>
                </div>
                <h3 class="font-display text-xl font-bold mb-3">Cộng đồng</h3>
                <p class="text-gray-600">Tạo ra không gian kết nối và chia sẻ cho cộng đồng yêu cà phê</p>
            </div>
        </div>
    </div>
</section>
<!-- Footer  -->
<?php include 'app/views/layout/footer.php'; ?>