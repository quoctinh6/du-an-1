    <main class="auth-main">
        <div class="auth-card">
            <div class="auth-title">Đăng Nhập</div>
            <div class="auth-subtitle">Chào mừng trở lại cửa hàng đồng hồ Zero Watch.</div>
            
            <form action="" method="POST" style="width: 100%;">
            
                <!-- Tên đăng nhập (hoặc Email) -->
                <div class="input-group">
                    <label for="login-username-email">Tên đăng nhập hoặc Email</label>
                    <input type="text" id="login-username-email" name="username" placeholder="Nhập tên đăng nhập hoặc email của bạn" required>
                </div>
                
                <!-- Mật khẩu -->
                <div class="input-group">
                    <label for="login-password">Mật khẩu</label>
                    <input type="password" id="login-password" name="password" placeholder="••••••••" required>
                </div>
                
                <!-- Nút Đăng nhập -->
                <button type="submit" class="auth-button" name="submit">ĐĂNG NHẬP</button>

            </form>
            
            <!-- Link chuyển sang trang Đăng ký -->
            <div class="auth-footer-link">
            Bạn chưa có tài khoản? <a href="register.html">Đăng ký ngay</a>
            </div>
        </div>
    </main>

<?php
    var_dump($_POST['username']);
    var_dump($_POST['password']);
    var_dump($_SESSION['user']);
?>


