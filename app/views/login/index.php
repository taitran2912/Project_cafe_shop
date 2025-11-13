<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="public/css/login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-image">
            <h1>Cafe & Tea House</h1>
            <p>Chào mừng bạn trở lại! Đăng nhập để trải nghiệm những ly cà phê và trà tuyệt vời nhất.</p>
        </div>

        <div class="login-form-container">
            <a href="index§" class="back-home">
                <i class="fas fa-arrow-left"></i>
                Về trang chủ
            </a>

            <div class="form-header">
                <h2>Đăng nhập</h2>
                <p>Chào mừng bạn quay trở lại!</p>
            </div>

            <div class="error-message" id="errorMessage"></div>

            <form id="loginForm" action="<?= BASE_URL ?>login/auth" method="POST">
                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-wrapper">
                        <input type="email" id="email" name="email" placeholder="example@email.com" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="Nhập mật khẩu" required>
                    </div>
                </div>

                <div class="form-options">
                    <!-- <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Ghi nhớ đăng nhập</label>
                    </div> -->
                    <a href="register" class="forgot-password">Quên mật khẩu?</a>
                </div>

                <button type="submit" class="login-btn">Đăng nhập</button>
            </form>

            <!-- <div class="divider">
                <span>Hoặc đăng nhập với</span>
            </div> -->
            <!-- <div class="social-login">
                <button class="social-btn" onclick="socialLogin('google')">
                    <i class="fab fa-google"></i>
                    Google
                </button>
                <button class="social-btn" onclick="socialLogin('facebook')">
                    <i class="fab fa-facebook"></i>
                    Facebook
                </button>
            </div> -->

            <div class="signup-link">
                Chưa có tài khoản? <a href="register.html">Đăng ký ngay</a>
            </div>
        </div>
    </div>

    <script>
        const loginForm = document.getElementById('loginForm');
        const errorMessage = document.getElementById('errorMessage');

        loginForm.addEventListener('submit', function(e) {
            // e.preventDefault();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            // Basic validation
            if (!email || !password) {
                showError('Vui lòng điền đầy đủ thông tin');
                return;
            }

            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                showError('Email không hợp lệ');
                return;
            }

            // Password length validation
            if (password.length < 6) {
                showError('Mật khẩu phải có ít nhất 6 ký tự');
                return;
            }


        });

        function showError(message) {
            errorMessage.textContent = message;
            errorMessage.style.display = 'block';
            
            setTimeout(() => {
                errorMessage.style.display = 'none';
            }, 3000);
        }

        // function socialLogin(provider) {
        //     console.log('[v0] Social login with:', provider);
        //     alert(`Đăng nhập với ${provider} (Chức năng đang phát triển)`);
        // }

        // Hide error message when user starts typing
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('input', () => {
                errorMessage.style.display = 'none';
            });
        });
    </script>
</body>
</html>
