<?php
// ==============================
// ⚙️ Cấu hình Database
// ==============================
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'cf_shop');

// ==============================
// 🌐 Cấu hình Base URL động
// ==============================

// Tự động lấy giao thức (http / https)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';

// Lấy domain + port (vd: localhost:8000 hoặc d30d1732b307.ngrok-free.app)
$host = $_SERVER['HTTP_HOST'];

// Vì bạn truy cập qua /Project_cafe_shop/, nên basePath cần có thư mục này
$basePath = '/Project_cafe_shop/';

// Gộp thành BASE_URL (tự hoạt động đúng cho localhost & ngrok)
define('BASE_URL', $protocol . $host . $basePath);
?>
