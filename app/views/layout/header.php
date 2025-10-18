<?php
    // session_start();
    $userID = $_SESSION['user']['ID'];
    $userName = $_SESSION['user']['Name'];
    $userEmail = $_SESSION['user']['Email'];
    $userRole = $_SESSION['user']['Role'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/styles.css">
    <?php if (isset($data['css'])): ?>
        <link rel="stylesheet" href="<?= BASE_URL ?>public/css/<?= $data['css'] ?>">
    <?php endif; ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<body>
<header class="fixed top-0 w-full bg-white/95 backdrop-blur-sm z-50 border-b border-gray-100">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <div class="font-display text-2xl font-bold text-primary">
                        Café & Tea House
                    </div>
                </div>
                
                 <!-- Desktop Menu  -->
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-8">
                        <a href="index" class="text-primary font-medium">Trang chủ</a>
                        <a href="menu" class="text-gray-700 hover:text-primary transition-colors">Thực đơn</a>
                        <a href="about" class="text-gray-700 hover:text-primary transition-colors">Về chúng tôi</a>
                        <a href="contact" class="text-gray-700 hover:text-primary transition-colors">Liên hệ</a>
<?php if(isset($userID)): ?>
                        <a href="cart" class="text-gray-700 hover:text-primary transition-colors">Giỏ hàng</a>
<?php else: ?>
                        <a href="login" class="text-gray-700 hover:text-primary transition-colors">Giỏ hàng</a>
<?php endif; ?>
                    <?php if ($userID !=0): ?>
                        <!-- <a href="profile" class="text-yellow-950 hover:text-primary transition-colors">My Profile</a> -->
                        <div class="relative group">
                            <button class="text-yellow-950 hover:text-primary transition-colors font-medium">
                                <?= htmlspecialchars($userName) ?> ▼
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                                <a href="profile" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-t-lg">Hồ sơ của tôi</a>
                                <a href="logout" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-b-lg">Đăng xuất</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="login" class="text-gray-700 hover:text-primary transition-colors">Đăng nhập</a>
                    <?php endif; ?>
                    </div>
                </div>

                 <!-- Mobile menu button  -->
                <div class="md:hidden">
                    <button id="mobile-menu-btn" class="text-gray-700 hover:text-primary">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </nav>

         <!-- Mobile Menu  -->
        <div id="mobile-menu" class="mobile-menu fixed top-16 left-0 w-full h-screen bg-white z-40 md:hidden">
            <div class="px-4 py-6 space-y-4">
                <a href="index.html" class="block text-primary font-medium text-lg">Trang chủ</a>
                <a href="menu.html" class="block text-gray-700 hover:text-primary text-lg">Thực đơn</a>
                <a href="about.html" class="block text-gray-700 hover:text-primary text-lg">Về chúng tôi</a>
                <a href="contact.html" class="block text-gray-700 hover:text-primary text-lg">Liên hệ</a>
                <a href="cart.html" class="block text-gray-700 hover:text-primary text-lg">Giỏ hàng</a>
                <a href="profile.html" class="block text-gray-700 hover:text-primary text-lg">Tài khoản</a>
            </div>
        </div>
    </header>