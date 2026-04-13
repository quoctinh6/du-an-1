<main class="auth-main">
    <div class="auth-card">
        <div class="auth-title">Quên Mật Khẩu</div>
        
        <!-- Khu vực hiển thị thông báo lỗi/thành công từ Controller -->
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
        
        <!-- Form gửi yêu cầu reset password -->
        <!-- Đã cập nhật action trỏ về ResetpwCtrl/send -->
        <form action="<?= BASE_URL ?>index.php/Resetpw/send" method="POST" style="width: 100%;"> 

            <div class="input-group">
                <label for="reset-email">Nhập Email đăng ký</label>
                <input type="email" id="reset-email" name="email" placeholder="example@gmail.com" required>
            </div>
            
            <button type="submit" class="auth-button" name="submit">Gửi yêu cầu</button>

        </form>

        <!-- Link quay lại trang đăng nhập -->
        <div class="auth-footer-link">
            <a href="<?= BASE_URL ?>index.php/User/login">Quay lại trang Đăng nhập</a>
        </div>
    </div>
</main>