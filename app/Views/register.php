<main class="auth-main">
  <div class="auth-card">
    <div class="auth-title">Đăng Ký</div>
    <div class="auth-subtitle"><?= $_SESSION['error'] ?? '' ?></div>


    <form method="POST" style="width: 100%;">

      <div class="input-group">
        <label for="reg-name">Họ và tên</label>
        <input type="text" id="reg-name" name="name" placeholder="Ví dụ: Nguyễn Văn A" required>
      </div>

      <div class="input-group">
        <label for="reg-email">Email</label>
        <input type="email" id="reg-email" name="email" placeholder="email@example.com" required>
      </div>

      <div class="input-group">
        <label for="reg-phone">Số điện thoại</label>
        <input type="tel" id="reg-phone" name="phone" placeholder="09xxxxxxxx" required pattern="[0-9]{10,11}">
      </div>

      <div class="input-group">
        <label for="reg-password">Mật khẩu</label>
        <input type="password" id="reg-password" name="password" placeholder="Nhập mật khẩu (ít nhất 6 ký tự)" required
          minlength="6">
      </div>

      <div class="input-group">
        <label for="reg-confirm-password">Xác nhận mật khẩu</label>
        <input type="password" id="reg-confirm-password" name="confirm_password" placeholder="Nhập lại mật khẩu lần nữa"
          required minlength="6">
      </div>

      <button type="submit" class="auth-button">ĐĂNG KÝ TÀI KHOẢN</button>
    </form>

    <div class="auth-footer-link">
      Bạn đã có tài khoản? <a href="<?= BASE_URL ?>index.php/User/login">Đăng nhập ngay</a>
    </div>
  </div>
</main>