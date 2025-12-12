<!-- Cart Content -->
<main class="cart-main">
    <div class="cart-container">
        
        <!-- HIỂN THỊ THÔNG BÁO LỖI/THÀNH CÔNG TỪ SESSION -->
        <?php if (isset($_SESSION['coupon_error'])): ?>
            <div style="background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
                <?= $_SESSION['coupon_error']; unset($_SESSION['coupon_error']); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['coupon_success'])): ?>
            <div style="background: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
                <?= $_SESSION['coupon_success']; unset($_SESSION['coupon_success']); ?>
            </div>
        <?php endif; ?>

        <!-- CỘT 1: DANH SÁCH SẢN PHẨM (2/3 CHIỀU RỘNG) -->
        <div class="cart-list-wrapper">
            <div class="cart-list-title">Giỏ hàng của bạn</div>
        
            <?php if (!empty($cart)) : ?>
                <?php foreach ($cart as $key => $item) : ?>
                    <div class="cart-item">
                        <img src="<?= $item['image'] ?>" class="cart-item-img" onerror="this.src='https://placehold.co/100x100?text=IMG'">

                        <div class="cart-item-details">
                            <div class="cart-item-name"><?= htmlspecialchars($item['name']) ?></div>

                            <!-- Hiển thị biến thể nếu có -->
                            <?php if (!empty($item['variant_meta'])): ?>
                                <div class="cart-item-meta">
                                    Size: <?= htmlspecialchars($item['variant_meta']['size'] ?? '-') ?> • 
                                    Màu: <?= htmlspecialchars($item['variant_meta']['color'] ?? '-') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="cart-item-controls">
                            <!-- Form cập nhật số lượng -->
                            <div class="quantity-control">
                                <!-- Nút giảm -->
                                <form action="<?= BASE_URL ?>index.php/cart/update" method="POST" style="display: inline;">
                                    <input type="hidden" name="id" value="<?= $key ?>">
                                    <input type="hidden" name="type" value="dec">
                                    <button type="submit" class="qty-btn"> - </button>
                                </form>

                                <input type="number" value="<?= $item['quantity'] ?>" readonly class="qty-input">

                                <!-- Nút tăng -->
                                <form action="<?= BASE_URL ?>index.php/cart/update" method="POST" style="display: inline;">
                                    <input type="hidden" name="id" value="<?= $key ?>">
                                    <input type="hidden" name="type" value="inc">
                                    <button type="submit" class="qty-btn"> + </button>
                                </form>
                            </div>
                            
                            <!-- Xóa sản phẩm -->
                            <a href="<?= BASE_URL ?>index.php/cart/remove/<?= $key ?>" 
                               class="cart-item-remove"
                               onclick="return confirm('Bạn chắc chắn muốn xóa sản phẩm này?')">Xóa</a>

                            <!-- Thành tiền -->
                            <div class="cart-item-price">
                                <?php 
                                    $displayPrice = $item['display_price'] ?? $item['price'];
                                    $linePrice = $displayPrice * ($item['quantity'] ?? 0); 
                                ?>
                                <?= number_format($linePrice) ?>đ
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else :?>
                <div style="text-align: center; padding: 40px;">
                    <p style="font-size: 1.2rem; color: #666;">Giỏ hàng trống trơn!!</p>
                    <a href="<?= BASE_URL ?>index.php/products/all" class="btn-continue" style="margin-top: 15px; display: inline-block;">Quay lại mua sắm</a>
                </div>
            <?php endif;?>
        </div>

        <!-- CỘT 2: TẠM TÍNH (1/3 CHIỀU RỘNG) -->
        <div class="cart-summary-wrapper">
            <div class="cart-summary-title">Tạm tính</div>

            <!-- Tổng tiền hàng -->
            <div class="summary-row">
                <span class="label">Tổng tiền hàng</span>
                <span class="value"><?= number_format($subtotal) ?>đ</span>
            </div>

            <!-- LOGIC MÃ GIẢM GIÁ (VOUCHER) -->
            <div class="voucher-section">
                <?php if (isset($_SESSION['applied_coupon'])): ?>
                    <!-- Nếu đã áp mã: Hiện thông tin và nút Hủy -->
                    <div style="display: flex; justify-content: space-between; align-items: center; background: #e8f5e9; padding: 10px; border-radius: 5px; border: 1px solid #c3e6cb;">
                        <span style="color: #155724; font-weight: 600;">
                            Mã: <?= htmlspecialchars($_SESSION['applied_coupon']['code']) ?>
                        </span>
                        <a href="<?= BASE_URL ?>index.php/cart/removeCoupon" style="color: #dc3545; font-size: 0.9rem; text-decoration: underline;">Hủy</a>
                    </div>
                <?php else: ?>
                    <!-- Nếu chưa áp mã: Hiện Form nhập -->
                    <form action="<?= BASE_URL ?>index.php/cart/applyCoupon" method="POST" style="display: flex; gap: 5px;">
                        <input type="text" name="coupon_code" placeholder="Nhập mã giảm giá" class="form-input" style="flex: 1; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                        <button type="submit" name="apply_coupon" class="apply-btn" style="white-space: nowrap;">Áp dụng</button>
                    </form>
                <?php endif; ?>
            </div>
            
            <!-- Phí vận chuyển -->
            <div class="summary-row">
                <span class="label">Phí vận chuyển</span>
                <span class="value"><?= ($shipping > 0) ? number_format($shipping) . 'đ' : 'Miễn phí' ?></span>
            </div>

            <!-- HIỂN THỊ DÒNG GIẢM GIÁ NẾU CÓ -->
            <?php if (isset($discount) && $discount > 0): ?>
                <div class="summary-row" style="color: #dc3545; font-weight: bold;">
                    <span class="label">Giảm giá</span>
                    <span class="value">-<?= number_format($discount) ?>đ</span>
                </div>
            <?php endif; ?>

            <!-- Tổng đơn hàng (Total) -->
            <div class="summary-total summary-row" style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #eee;">
                <span class="label">Tổng thanh toán</span>
                <span class="value" style="font-size: 1.4rem; color: #d4af37;"><?= number_format($total)?>đ</span>
            </div>

            <!-- Nút hành động -->
            <div class="cart-actions-group">
                <?php if (!empty($cart)): ?>
                    <a href="<?= BASE_URL ?>index.php/checkout" class="btn-checkout" style="display:block; text-align:center; text-decoration:none;">Tiếp tục thanh toán</a>
                <?php else: ?>
                    <button class="btn-checkout" disabled style="background-color: #ccc; cursor: not-allowed;">Giỏ hàng trống</button>
                <?php endif; ?>
                
                <a href="<?= BASE_URL ?>index.php/products/all" class="btn-continue" style="text-align: center;">Tiếp tục mua sắm</a>
            </div>
            
            <div class="cart-note">
                Chú ý: Giá trên chưa bao gồm VAT. <br>
                Có thể thay đổi số lượng hoặc xóa sản phẩm trước khi thanh toán.
            </div>
        </div>
    </div>
</main>