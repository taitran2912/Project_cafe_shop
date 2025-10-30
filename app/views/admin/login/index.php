<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập - Quán Cafe</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/LoginAdmin.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <h1>☕ Cafe Manager</h1>
                <p>Hệ thống quản lý quán cafe</p>
            </div>
            
            <form id="loginForm" class="login-form">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="admin@cafe.com" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                </div>
                
                <div class="form-group checkbox">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Ghi nhớ tôi</label>
                </div>
                
                <button type="submit" class="btn-login">Đăng Nhập</button>
            </form>
        </div>
    </div>

    <script src="<?= BASE_URL ?>public/admin/login.js"></script>
</body>
</html>