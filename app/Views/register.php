<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
  <title>Đăng Ký | Zero Watch</title>
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
  
  <link rel="stylesheet" href="<?= BASE_URL ?>Views/assets/css/style.css" /> 
  </head>
<body>
  
  <header>
    <div class="header-container">
      <div class="logo" style="color: white;"><a href="<?= BASE_URL ?>">Zero Watch</a></div> 
      <nav>
        <a href="<?= BASE_URL ?>index.php/Product/index">Sản phẩm</a>
        <a href="<?= BASE_URL ?>index.php/User/favorites">Yêu thích</a>
        <a href="<?= BASE_URL ?>index.php/Contact/index">Liên hệ</a>
      </nav>
      <div class="header-actions">
        <a href="<?= BASE_URL ?>index.php/Cart/index" class="action-button cart-btn">
          <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" fill="currentColor" viewBox="0 0 24 24"><path d="M7 18c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm10 0c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm1.604-2.083l2.396-9.917h-16v-2h-3v2h1.604l3.452 13.917a2 2 0 0 0 1.944 1.25h10.192a2 2 0 0 0 1.944-1.25l.588-2.333zm-13.604-11.083v-2h16v2h-16z"/></svg>
          Giỏ hàng
        </a>
        <a href="<?= BASE_URL ?>index.php/User/login" class="action-button login-btn">Đăng nhập</a>
      </div>
    </div>
  </header>

  <main class="auth-main">
    <div class="auth-card">
      <div class="auth-title">Đăng Ký</div>
      <div class="auth-subtitle">Tạo tài khoản để khám phá bộ sưu tập đồng hồ độc quyền.</div>
      
      <?php
        if (isset($_SESSION['error'])) {
            echo '<p class="error" style="color: red; margin-bottom: 15px;">' . $_SESSION['error'] . '</p>';
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            echo '<p class="success" style="color: green; margin-bottom: 15px;">' . $_SESSION['success'] . '</p>';
            unset($_SESSION['success']);
        }
      ?>

      <form action="<?= BASE_URL ?>index.php/User/register" method="POST" style="width: 100%;">
        
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
          <input type="password" id="reg-password" name="password" placeholder="Nhập mật khẩu (ít nhất 6 ký tự)" required minlength="6">
        </div>
        
        <div class="input-group">
          <label for="reg-confirm-password">Xác nhận mật khẩu</label>
          <input type="password" id="reg-confirm-password" name="confirm_password" placeholder="Nhập lại mật khẩu lần nữa" required minlength="6">
        </div>
        
        <button type="submit" class="auth-button">ĐĂNG KÝ TÀI KHOẢN</button>
      </form>
      
      <div class="auth-footer-link">
        Bạn đã có tài khoản? <a href="<?= BASE_URL ?>index.php/User/login">Đăng nhập ngay</a>
      </div>
    </div>
  </main>

 