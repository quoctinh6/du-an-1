<main class="auth-main">
    <div class="auth-card">
        <div class="auth-title">Đăng Nhập</div>
        
        <?php if (!empty($error)): ?>
            <div class="auth-subtitle" style="color: red; padding: 10px;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="auth-subtitle" style="color: green; padding: 10px;">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>
        
        <form action="<?= BASE_URL ?>index.php/User/login" method="POST" style="width: 100%;"> 

            <div class="input-group">
                <label for="login-username-email">Tên đăng nhập hoặc Email</label>
                <input type="text" id="login-username-email" name="username" placeholder="nhập email của bạn" required>
            </div>
            
            <div class="input-group">
                <label for="login-password">Mật khẩu</label>
                <input type="password" id="login-password" name="password" placeholder="••••••••" required>
            </div>

            <button type="submit" class="auth-button" name="submit">ĐĂNG NHẬP</button>

        </form>

        <div class="auth-footer-link">
            Bạn chưa có tài khoản? <a href="<?= BASE_URL ?>index.php/User/register">Đăng ký ngay</a>
        </div>
        <div class="auth-footer-link">
            <a href="<?= BASE_URL ?>index.php/User/reset">Quên mật khẩu?</a>
        </div>
    </div>
</main>