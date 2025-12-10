<!-- Cart Content -->
<main class="cart-main">
    <div class="cart-container">
        <!-- CỘT 1: DANH SÁCH SẢN PHẨM (2/3 CHIỀU RỘNG) -->
        <div class="cart-list-wrapper">
            <div class="cart-list-title">Giỏ hàng của bạn
        
            <?php if (!empty($cart)) : ?>

                <?php foreach ($cart as $id => $item) : ?>
                    <div class="cart-item">
                        <img src="<?= $item['image'] ?>" class="cart-item-img" alt="">

                        <div class="cart-item-details">
                            <div class="cart-item-name"><?= htmlspecialchars($item['name']) ?></div>

                            <!-- thêm hiển thị biến thể nếu có -->
                            <?php if (!empty($item['variant_meta'])): ?>
                                <div class="cart-item-meta">Kích thước: <?= htmlspecialchars($item['variant_meta']['size'] ?? '') ?> • Màu: <?= htmlspecialchars($item['variant_meta']['color'] ?? '') ?>
                                    <?php if(!empty($item['variant_meta']['sku'])): ?> • SKU: <?= htmlspecialchars($item['variant_meta']['sku']) ?><?php endif; ?></div>
                            <?php endif; ?>
                        </div>


                        <div class="cart-item-controls">
                            <!-- ==== Form cập nhật số lượng ở đây ==== -->
                            <div class="quantity-control">
                                <!-- Nút giảm -->
                                <form action="<?= BASE_URL ?>index.php/cart/update" method="POST" style="display: inline;">
                                    <input type="hidden" name="id" value="<?= $id ?>">
                                    <input type="hidden" name="type" value="dec">
                                    <button type="submit" class="qty-btn"> - </button>
                                </form>

                                <!-- Hiển thị số đang có -->
                                <input type="number" value="<?= $item['quantity'] ?>" readonly class="qty-input">


                                <!-- Nút tằng -->
                                <form action="<?= BASE_URL ?>index.php/cart/update" method="POST" style="display: inline;">
                                    <input type="hidden" name="id" value="<?= $id ?>">
                                    <input type="hidden" name="type" value="inc">
                                    <button type="submit" class="qty-btn"> + </button>
                                </form>
                            </div>
                            
                            
                            <!-- Xóa sản phẩm -->
                            <a href="<?= BASE_URL ?>index.php/cart/remove/<?= $id ?>" class="cart-item-remove">Xóa</a>

                            <!-- Thành tiền -->
                            <div class="cart-item-price">
                                <?php $linePrice = ($item['display_price'] ?? $item['price']) * ($item['quantity'] ?? 0); ?>
                                <?= number_format($linePrice) ?>đ
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <?php else :?>
                    <!-- Nếu hàm rỗng -->
                    <p>Giỏ hàng trống trơn!!</p>
                <?php endif;?>
            </div>
        </div>

        <!-- SẢN PHẨM 2: The Mirror
        <div class="cart-item">
          <!-- Sử dụng placeholder image -->
          <!-- <img src="https://placehold.co/100x100/111111/ff9e00?text=Watch+2" onerror="this.onerror=null;this.src='https://placehold.co/100x100/111111/ff9e00?text=Watch+2';" alt="The Mirror" class="cart-item-img">
          
          <div class="cart-item-details">
            <div class="cart-item-name">The Mirror</div>
            <div class="cart-item-meta">
              Size: 50ml • Màu: Trắng <br>
              Mã SP: SP-002
            </div>
          </div>
          
          <div class="cart-item-controls">
            <span class="cart-item-meta" style="margin-right: 1.5rem;">SL:</span>
            <div class="quantity-control">
              <button class="qty-btn" aria-label="Giảm số lượng">-</button>
              <input type="number" value="1" min="1" class="qty-input">
              <button class="qty-btn" aria-label="Tăng số lượng">+</button>
            </div>
            <button class="cart-item-remove" aria-label="Xóa sản phẩm">
              Icon Thùng rác (Delete)
              <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 24 24" fill="currentColor"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
            </button>
            <div class="cart-item-price">đ5.100.000</div>
          </div>
        </div>
        </div>-->

    <!-- CỘT 2: TẠM TÍNH (1/3 CHIỀU RỘNG) -->
    <div class="cart-summary-wrapper">
        <div class="cart-summary-title">Tạm tính</div>

        <!-- Chi tiết tạm tính -->
        <div class="summary-row">
            <span class="label">Tổng tiền hàng</span>
            <span class="value"><?= number_format($subtotal) ?>đ</span>
        </div>

        <!-- Voucher -->
        <div class="voucher-section">
            <form action="<?= BASE_URL ?>index.php/cart/applyCoupon" method="POST" style="display:flex; gap:8px;">
                <input type="text" name="coupon_code" id="coupon_code_cart" class="form-input" placeholder="Nhập mã hoặc chọn bên dưới" style="flex:1;">
                <button type="submit" class="apply-btn">Áp dụng</button>
            </form>

            <?php if (!empty($availableCoupons)): ?>
                <div style="margin-top:8px;">
                    <label class="form-label">Mã giảm giá có sẵn:</label>
                    <select onchange="document.getElementById('coupon_code_cart').value=this.value;" style="width:100%; margin-top:6px;">
                        <option value="">-- Chọn mã --</option>
                        <?php foreach ($availableCoupons as $c): ?>
                            <?php
                                $label = htmlspecialchars($c['code']) . ' - ' . ($c['type'] === 'percent' ? (float)$c['value'] . '%': number_format($c['value'],0,',','.').'đ');
                            ?>
                            <option value="<?= htmlspecialchars($c['code']) ?>"><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['coupon_error'])): ?>
                <div style="color: red; margin-top:6px;"><?php echo htmlspecialchars($_SESSION['coupon_error']); unset($_SESSION['coupon_error']); ?></div>
            <?php elseif (isset($_SESSION['coupon_success'])): ?>
                <div style="color: green; margin-top:6px;"><?php echo htmlspecialchars($_SESSION['coupon_success']); unset($_SESSION['coupon_success']); ?></div>
            <?php endif; ?>
        </div>
        
        <!-- Phí vận chuyển -->
        <div class="summary-row">
            <span class="label">Phí vận chuyển</span>
            <span class="value"><?= ($shipping > 0) ? number_format($shipping) . 'đ' : 'Miễn phí' ?></span>
        </div>

        <?php if (!empty($discountApplied) && $discountApplied > 0): ?>
            <div class="summary-row">
                <span class="label">Giảm giá (<?= htmlspecialchars($_SESSION['applied_coupon']['code'] ?? '') ?>)</span>
                <span class="value" style="color: #d9534f;">-<?= number_format($discountApplied) ?>đ</span>
            </div>
        <?php endif; ?>

        <!-- Tổng đơn hàng (Total) -->
        <div class="summary-total summary-row">
            <span class="label">Tổng đơn hàng</span>
            <span class="value"><?= number_format($total)?>đ</span>
        </div>

        <!-- Nút hành động -->
        <div class="cart-actions-group">
            <button class="btn-checkout"><a href="<?= BASE_URL ?>index.php/checkout">Tiếp tục thanh toán</a></button>
            <a href="<?= BASE_URL ?>" class="btn-continue" style="text-align: center;">Tiếp tục mua sắm</a>
        </div>
        
        <div class="cart-note">
            Chú ý: Giá trên chưa bao gồm VAT. <br>
            Có thể thay đổi số lượng hoặc xóa sản phẩm trước khi thanh toán.
        </div>
    </div>
</main>