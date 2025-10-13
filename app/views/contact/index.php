<!-- Header  -->
<?php include 'app/views/layout/header.php'; ?>
<!-- Page Header  -->
 <!-- Page Header  -->
    <section class="page-header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="font-display text-5xl font-bold mb-4">Liên hệ</h1>
            <p class="text-xl opacity-90 max-w-2xl mx-auto">
                Hãy liên hệ với chúng tôi để được tư vấn và hỗ trợ tốt nhất
            </p>
        </div>
    </section>

     <!-- Contact Content  -->
    <section class="content-section bg-muted">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
                <div class="fade-in">
                    <div class="bg-white rounded-2xl p-8 shadow-lg">
                        <h3 class="font-display text-2xl font-semibold mb-6">Thông tin liên hệ</h3>
                        
                        <div class="space-y-6">
                            <div class="flex items-start space-x-4">
                                <div class="w-6 h-6 text-primary mt-1">
                                    <svg fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-foreground">Địa chỉ</h4>
<?php $count = 1;?>

<?php if (!empty($data['branch'])): ?>
    <?php foreach ($data['branch'] as $branch): ?>
                                    <p class="text-gray-600">Chi nhánh <?php echo $count;?>: <?= htmlspecialchars($branch['Address']) ?></p>
        <?php $count++; ?>
    <?php endforeach; ?>
<?php else: ?>
                                    <p>Chưa có chi nhánh nào.</p>
<?php endif; ?>
                                    <!-- <p class="text-gray-600">123 Đường Nguyễn Huệ, Quận 1, TP.HCM</p> -->
                                </div>
                            </div>

                            <div class="flex items-start space-x-4">
                                <div class="w-6 h-6 text-primary mt-1">
                                    <svg fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-foreground">Điện thoại</h4>
                                    <p class="text-gray-600">0123 456 789</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-4">
                                <div class="w-6 h-6 text-primary mt-1">
                                    <svg fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-foreground">Email</h4>
                                    <p class="text-gray-600">info@cafeteahouse.com</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-4">
                                <div class="w-6 h-6 text-primary mt-1">
                                    <svg fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-foreground">Giờ mở cửa</h4>
                                    <p class="text-gray-600">7:00 - 22:00 (Hàng ngày)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="fade-in">
                    <form id="contact-form" class="bg-white rounded-2xl p-8 shadow-lg">
                        <h3 class="font-display text-2xl font-semibold mb-6">Gửi tin nhắn</h3>
                        
                        <div class="space-y-6">
                            <div class="form-group">
                                <label class="form-label">Họ tên *</label>
                                <input type="text" name="name" class="form-input" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Email *</label>
                                <input type="email" name="email" class="form-input" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Số điện thoại</label>
                                <input type="tel" name="phone" class="form-input">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Chủ đề</label>
                                <select name="subject" class="form-input">
                                    <option value="">Chọn chủ đề</option>
                                    <option value="general">Thông tin chung</option>
                                    <option value="reservation">Đặt bàn</option>
                                    <option value="complaint">Khiếu nại</option>
                                    <option value="suggestion">Góp ý</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Tin nhắn *</label>
                                <textarea name="message" rows="4" class="form-input form-textarea" required></textarea>
                            </div>
                            
                            <button type="submit" class="btn-primary w-full">Gửi tin nhắn</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
<!-- Footer  -->
<?php include 'app/views/layout/footer.php'; ?>
