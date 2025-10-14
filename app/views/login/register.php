<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/register.css">
</head>
<body>
    <div class="register-container">
        <div class="register-image">
            <h1>Cafe & Tea House</h1>
            <p>Tham gia cộng đồng yêu thích cà phê và trà của chúng tôi. Đăng ký ngay để nhận ưu đãi đặc biệt!</p>
        </div>

        <div class="register-form-container">
            <a href="login" class="back-home">
                <i class="fas fa-arrow-left"></i>
                Về trang chủ
            </a>

            <div class="form-header">
                <h2>Đăng ký tài khoản</h2>
                <p>Tạo tài khoản để trải nghiệm đầy đủ dịch vụ</p>
            </div>

            <div class="error-message" id="errorMessage"></div>

            <form id="registerForm">
                <div class="form-group">
                    <label for="fullname">Họ và tên</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user"></i>
                        <input type="text" id="fullname" name="fullname" placeholder="Nguyễn Văn A" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" placeholder="example@email.com" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="phone">Số điện thoại</label>
                    <div class="input-wrapper">
                        <i class="fas fa-phone"></i>
                        <input type="tel" id="phone" name="phone" placeholder="0123456789" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="Tối thiểu 6 ký tự" required>
                    </div>
                    <div class="password-strength">
                        <div class="strength-bar">
                            <div class="strength-bar-fill" id="strengthBar"></div>
                        </div>
                        <span id="strengthText"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirmPassword">Xác nhận mật khẩu</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Nhập lại mật khẩu" required>
                    </div>
                </div>

                <div class="terms-checkbox">
                    <input type="checkbox" id="terms" name="terms" required>
                    <label for="terms">
                        Tôi đồng ý với <a href="#">Điều khoản dịch vụ</a> và <a href="#">Chính sách bảo mật</a>
                    </label>
                </div>

                <button type="submit" class="register-btn">Đăng ký</button>
            </form>

            <!-- <div class="divider">
                <span>Hoặc đăng ký với</span>
            </div>

            <div class="social-login">
                <button class="social-btn" onclick="socialRegister('google')">
                    <i class="fab fa-google"></i>
                    Google
                </button>
                <button class="social-btn" onclick="socialRegister('facebook')">
                    <i class="fab fa-facebook"></i>
                    Facebook
                </button>
            </div> -->

            <div class="login-link">
                Đã có tài khoản? <a href="login">Đăng nhập ngay</a>
            </div>
        </div>
    </div>

    <script>
        const registerForm = document.getElementById('registerForm');
        const errorMessage = document.getElementById('errorMessage');
        const passwordInput = document.getElementById('password');
        const strengthBar = document.getElementById('strengthBar');
        const strengthText = document.getElementById('strengthText');

        // Password strength checker
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;

            if (password.length >= 6) strength++;
            if (password.length >= 10) strength++;
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
            if (/\d/.test(password)) strength++;
            if (/[^a-zA-Z\d]/.test(password)) strength++;

            strengthBar.className = 'strength-bar-fill';
            
            if (strength <= 2) {
                strengthBar.classList.add('strength-weak');
                strengthText.textContent = 'Mật khẩu yếu';
                strengthText.style.color = '#ff4444';
            } else if (strength <= 4) {
                strengthBar.classList.add('strength-medium');
                strengthText.textContent = 'Mật khẩu trung bình';
                strengthText.style.color = '#ffaa00';
            } else {
                strengthBar.classList.add('strength-strong');
                strengthText.textContent = 'Mật khẩu mạnh';
                strengthText.style.color = '#00C851';
            }
        });

        registerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const fullname = document.getElementById('fullname').value;
            const email = document.getElementById('email').value;
            const phone = document.getElementById('phone').value;
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const terms = document.getElementById('terms').checked;
            
            // Validation
            if (!fullname || !email || !phone || !password || !confirmPassword) {
                showError('Vui lòng điền đầy đủ thông tin');
                return;
            }

            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                showError('Email không hợp lệ');
                return;
            }

            // Phone validation
            const phoneRegex = /^[0-9]{10}$/;
            if (!phoneRegex.test(phone)) {
                showError('Số điện thoại phải có 10 chữ số');
                return;
            }

            // Password validation
            if (password.length < 6) {
                showError('Mật khẩu phải có ít nhất 6 ký tự');
                return;
            }

            if (password !== confirmPassword) {
                showError('Mật khẩu xác nhận không khớp');
                return;
            }

            if (!terms) {
                showError('Vui lòng đồng ý với điều khoản dịch vụ');
                return;
            }

            // Simulate registration (replace with actual API call)
            console.log('[v0] Registration attempt:', { fullname, email, phone, password: '***' });
            
            // Success simulation
            alert('Đăng ký thành công! Vui lòng kiểm tra email để xác thực tài khoản.');
            window.location.href = 'login.html';
        });

        function showError(message) {
            errorMessage.textContent = message;
            errorMessage.style.display = 'block';
            
            setTimeout(() => {
                errorMessage.style.display = 'none';
            }, 3000);
        }

        function socialRegister(provider) {
            console.log('[v0] Social registration with:', provider);
            alert(`Đăng ký với ${provider} (Chức năng đang phát triển)`);
        }

        // Hide error message when user starts typing
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('input', () => {
                errorMessage.style.display = 'none';
            });
        });
    </script>
</body>
</html>
