<!-- Link CSS riêng cho trang checkout -->
<link rel="stylesheet" href="<?= BASE_URL ?>Views/assets/css/checkout.css" />

<main class="checkout-main">
    <div class="checkout-container">
      
      <!-- CỘT 1: THÔNG TIN GIAO HÀNG (2/3 CHIỀU RỘNG) -->
      <div class="checkout-info-wrapper">
        <div class="checkout-title">Thông tin giao hàng</div>
        
        <!-- FORM CHÍNH: Gửi dữ liệu về CheckoutCtrl -> process -->
        <!-- ID="checkout-form" dùng để liên kết với nút Đặt hàng ở cột bên phải -->
        <form id="checkout-form" action="<?= BASE_URL ?>index.php/checkout/process" method="POST">
          
          <!-- Họ và tên -->
          <div class="form-group">
            <label for="full-name" class="form-label">Họ và tên</label>
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
        <div class="order-list" style="margin-bottom: 20px; max-height: 300px; overflow-y: auto; border-bottom: 1px dashed #eee;">
            <?php if (!empty($cartItems)): ?>
                <?php foreach ($cartItems as $item): ?>
                    <div class="summary-detail-row" style="justify-content: flex-start; gap: 10px; padding-bottom: 10px; margin-bottom: 10px;">
                        <!-- Ảnh nhỏ -->
                        <img src="<?= $item['image'] ?>" 
                             style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;"
                             onerror="this.src='https://placehold.co/50x50?text=IMG'">
                        
                        <div style="flex: 1;">
                            <div style="font-weight: 600; font-size: 0.9rem;"><?= htmlspecialchars($item['name']) ?></div>
                            <div style="font-size: 0.8rem; color: #666;">
                                SL: <?= $item['quantity'] ?>
                                <?php if(!empty($item['variant_meta'])): ?>
                                    <br><span style="font-size: 0.75rem; color: #999;">
                                        <?= htmlspecialchars($item['variant_meta']['size'] ?? '') ?> - <?= htmlspecialchars($item['variant_meta']['color'] ?? '') ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div style="font-weight: bold;">
                            <?= number_format(($item['display_price'] ?? $item['price']) * $item['quantity'], 0, ',', '.') ?>đ
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <!-- TÍNH TOÁN TIỀN -->
        <div class="summary-detail-row">
            <span class="label">Tạm tính</span>
            <span class="value"><?= number_format($subtotal, 0, ',', '.') ?>đ</span>
        </div>
        
        <div class="summary-detail-row">
            <span class="label">Phí vận chuyển</span>
            <span class="value"><?= ($shipping > 0) ? number_format($shipping, 0, ',', '.') . 'đ' : 'Miễn phí' ?></span>
        </div>
        <?php if (isset($discountApplied) && $discountApplied > 0): ?>
            <!-- Dòng hiển thị Mã Code -->
            <div class="summary-detail-row">
                <span class="label">Mã giảm giá đã sử dụng</span>
                <span class="value" style="color: #28a745; font-weight: 600;">
                    <?= htmlspecialchars($_SESSION['applied_coupon']['code'] ?? '') ?>
                </span>
            </div>
            
            <!-- Dòng hiển thị Số tiền giảm -->
            <div class="summary-detail-row" style="color: #d9534f;">
                <span class="label">Đã giảm</span>
                <span class="value">-<?= number_format($discountApplied, 0, ',', '.') ?>đ</span>
            </div>
        <?php endif; ?>

        <!-- HIỂN THỊ MÃ GIẢM GIÁ VÀ SỐ TIỀN GIẢM (Đã cập nhật theo yêu cầu) -->
        <?php if (isset($discount) && $discount > 0): ?>
            <!-- Dòng hiển thị Mã Code -->
            <div class="summary-detail-row">
                <span class="label">Mã giảm giá đã sử dụng</span>
                <span class="value" style="color: #28a745; font-weight: 600;">
                    <?= htmlspecialchars($_SESSION['applied_coupon']['code'] ?? '') ?>
                </span>
            </div>
            
            <!-- Dòng hiển thị Số tiền giảm -->
            <div class="summary-detail-row" style="color: #d9534f;">
                <span class="label">Đã giảm</span>
                <span class="value">-<?= number_format($discount, 0, ',', '.') ?>đ</span>
            </div>
        <?php endif; ?>

        <!-- TỔNG CỘNG -->
        <div class="summary-final-total" style="margin-top: 15px; border-top: 1px solid #444; padding-top: 15px;">
            <span class="label">Tổng thanh toán</span>
            <span class="value" style="color: #d4af37;"><?= number_format($totalPrice, 0, ',', '.') ?>đ</span>
        </div>

        <!-- PHƯƠNG THỨC THANH TOÁN -->
        <div class="summary-option-group" style="margin-top: 20px;">
            <label for="payment-method" class="form-label">Phương thức thanh toán</label>
            <!-- Input nằm ngoài form chính nhưng dùng thuộc tính form="checkout-form" để liên kết -->
            <select id="payment-method" name="payment_method" class="option-select" form="checkout-form">
                <option value="cod">Thanh toán khi nhận hàng (COD)</option>
                <option value="vnpay">Thanh toán Online (VNPAY)</option>
                <option value="banking">Chuyển khoản ngân hàng</option>
            </select>
        </div>

        <!-- Nút Đặt hàng -->
        <button type="submit" form="checkout-form" class="btn-place-order" style="margin-top: 20px;">XÁC NHẬN ĐẶT HÀNG</button>

      </div>
    </div>
</main>