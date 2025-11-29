<!-- Link CSS riêng cho trang checkout -->
<link rel="stylesheet" href="<?= BASE_URL ?>Views/assets/css/checkout.css" />

<main class="checkout-main">
    <div class="checkout-container">
      
      <!-- CỘT 1: THÔNG TIN GIAO HÀNG (2/3 CHIỀU RỘNG) -->
      <div class="checkout-info-wrapper">
        <div class="checkout-title">Thông tin giao hàng</div>
        
        <!-- FORM CHÍNH: Gửi dữ liệu về CheckoutCtrl -> process -->
        <form id="checkout-form" action="<?= BASE_URL ?>index.php/checkout/process" method="POST">
          
          <!-- Họ và tên -->
          <div class="form-group">
            <label for="full-name" class="form-label">Họ và tên</label>
            <!-- Tự động điền nếu đã đăng nhập -->
            <input type="text" id="full-name" name="fullname" class="form-input" 
                   value="<?= isset($_SESSION['user']) ? htmlspecialchars($_SESSION['user']['name']) : '' ?>" 
                   placeholder="Nhập họ tên" required>
          </div>

          <!-- Số điện thoại -->
          <div class="form-group">
            <label for="phone" class="form-label">Số điện thoại</label>
            <input type="tel" id="phone" name="phone" class="form-input" 
                   value="<?= isset($_SESSION['user']['phone_number']) ? htmlspecialchars($_SESSION['user']['phone_number']) : '' ?>"
                   placeholder="Nhập số điện thoại" required>
          </div>

          <!-- Địa chỉ -->
          <div class="form-group">
            <label for="address" class="form-label">Địa chỉ nhận hàng</label>
            <input type="text" id="address" name="address" class="form-input" placeholder="Số nhà, đường, phường/xã..." required>
          </div>

          <!-- Ghi chú (Tùy chọn) -->
          <div class="form-group">
            <label for="notes" class="form-label">Ghi chú đơn hàng</label>
            <textarea id="notes" name="note" class="form-textarea" placeholder="VD: Giao hàng giờ hành chính..."></textarea>
          </div>

        </form>
      </div>

      <!-- CỘT 2: TÓM TẮT ĐƠN HÀNG (1/3 CHIỀU RỘNG) -->
      <div class="order-summary-wrapper">
        <div class="summary-title">Tóm tắt đơn hàng</div>

        <!-- DANH SÁCH SẢN PHẨM TRONG GIỎ -->
        <div class="order-list" style="margin-bottom: 20px; max-height: 300px; overflow-y: auto;">
            <?php if (!empty($cartItems)): ?>
                <?php foreach ($cartItems as $item): ?>
                    <div class="summary-detail-row" style="justify-content: flex-start; gap: 10px; border-bottom: 1px dashed #eee; padding-bottom: 10px; margin-bottom: 10px;">
                        <!-- Ảnh nhỏ -->
                        <img src="<?= $item['image'] ?>" 
                             style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;"
                             onerror="this.src='https://placehold.co/50x50?text=IMG'">
                        
                        <div style="flex: 1;">
                            <div style="font-weight: 600; font-size: 0.9rem;"><?= htmlspecialchars($item['name']) ?></div>
                            <div style="font-size: 0.8rem; color: #666;">x<?= $item['quantity'] ?></div>
                        </div>
                        
                        <div style="font-weight: bold;">
                            <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?>đ
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <!-- Tạm tính -->
        <div class="summary-detail-row">
            <span class="label">Tổng tiền hàng</span>
            <span class="value"><?= number_format($subtotal, 0, ',', '.') ?>đ</span>
        </div>

        <!-- PHƯƠNG THỨC THANH TOÁN -->
        <div class="summary-option-group">
            <label for="payment-method" class="form-label">Phương thức thanh toán</label>
            <!-- Input nằm ngoài form chính nhưng dùng thuộc tính form="checkout-form" để liên kết -->
            <select id="payment-method" name="payment_method" class="option-select" form="checkout-form">
                <option value="cod">Thanh toán khi nhận hàng (COD)</option>
                <option value="banking">Chuyển khoản ngân hàng</option>
            </select>
        </div>
        
        <!-- Phí vận chuyển -->
        <div class="summary-detail-row" style="padding-top: 1rem;">
            <span class="label">Phí vận chuyển</span>
            <span class="value"><?= ($shipping > 0) ? number_format($shipping, 0, ',', '.') . 'đ' : 'Miễn phí' ?></span>
        </div>

        <!-- TỔNG CỘNG -->
        <div class="summary-final-total">
            <span class="label">Tổng thanh toán</span>
            <span class="value" style="color: #d4af37;"><?= number_format($totalPrice, 0, ',', '.') ?>đ</span>
        </div>

        <!-- Nút Đặt hàng -->
        <button type="submit" form="checkout-form" class="btn-place-order">XÁC NHẬN ĐẶT HÀNG</button>

      </div>
    </div>
  </main>