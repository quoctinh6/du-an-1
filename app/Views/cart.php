<main class="cart-main">
    <div class="cart-container">
        <!-- CỘT 1: DANH SÁCH SẢN PHẨM (2/3 CHIỀU RỘNG) -->
        <div class="cart-list-wrapper">
            <div class="cart-list-title">Giỏ hàng của bạn</div>

            <!-- Cart items will be rendered here from localStorage -->
            <div id="cart-items"></div>
        </div>

        <!-- CỘT 2: TẠM TÍNH (1/3 CHIỀU RỘNG) -->
        <div class="cart-summary-wrapper">
            <div class="cart-summary-title">Tạm tính</div>

            <!-- Chi tiết tạm tính -->
            <div class="summary-row">
                <span class="label">Tổng tiền hàng</span>
                <span class="value" id="subtotal-value">đ0</span>
            </div>

            <!-- Voucher -->
            <div class="voucher-section">
                <select class="voucher-select">
                    <option value="">-- Chọn voucher --</option>
                    <option value="GIAMGIA10">GIAMGIA10 (Giảm 10%)</option>
                    <option value="FREESHIP">FREESHIP (Miễn phí vận chuyển)</option>
                </select>
                <button class="apply-btn">Áp dụng</button>
            </div>

            <!-- Phí vận chuyển -->
            <div class="summary-row">
                <span class="label">Phí vận chuyển</span>
                <span class="value" id="shipping-value">đ0</span>
            </div>

            <!-- Tổng đơn hàng (Total) -->
            <div class="summary-total summary-row">
                <span class="label">Tổng đơn hàng</span>
                <span class="value" id="total-value">đ0</span>
            </div>

            <!-- Nút hành động -->
            <div class="cart-actions-group">
                <button class="btn-checkout"><a id="checkout-link" href="checkout.html">Tiếp tục thanh toán</a></button>
                <a href="index.html" class="btn-continue" style="text-align: center;">Tiếp tục mua sắm</a>
            </div>

            <div class="cart-note">
                Chú ý: Giá trên chưa bao gồm VAT. <br>
                Có thể thay đổi số lượng hoặc xóa sản phẩm trước khi thanh toán.
            </div>
        </div>
    </div>
</main>
<script>
    // Cart render + behavior using localStorage 'cart' key
    (function () {
        function formatVND(num) {
            if (isNaN(num)) num = 0;
            return 'đ' + new Intl.NumberFormat('vi-VN').format(num);
        }

        function loadCart() {
            try {
                return JSON.parse(localStorage.getItem('cart')) || [];
            } catch (e) {
                return [];
            }
        }

        function saveCart(cart) {
            localStorage.setItem('cart', JSON.stringify(cart));
            // update global cart count if element exists
            const el = document.getElementById('cart-count');
            if (el) el.innerText = cart.reduce((s, i) => s + (i.quantity || 0), 0);
        }

        function calcTotals(cart) {
            const subtotal = cart.reduce((s, item) => s + (Number(item.price) || 0) * (Number(item.quantity) || 0), 0);
            // simple shipping rule: free over 1,000,000 else 30000
            const shipping = subtotal > 1000000 ? 0 : 30000;
            return { subtotal, shipping, total: subtotal + shipping };
        }

        function renderEmpty() {
            const container = document.getElementById('cart-items');
            container.innerHTML = '<div class="empty-cart" style="padding:2rem;text-align:center;">Giỏ hàng trống. <a href="<?php echo BASE_URL; ?>">Tiếp tục mua sắm</a></div>';
        }

        function renderCart() {
            const cart = loadCart();
            const container = document.getElementById('cart-items');
            if (!container) return;
            container.innerHTML = '';
            if (!cart || cart.length === 0) { renderEmpty(); updateSummary(0, 0, 0); return; }

            cart.forEach((item, idx) => {
                const itemTotal = (Number(item.price) || 0) * (Number(item.quantity) || 0);
                const div = document.createElement('div');
                div.className = 'cart-item';
                div.dataset.index = idx;
                div.innerHTML = `
                    <img src="${item.image || 'https://placehold.co/100x100/111111/ff9e00?text=No+Image'}" alt="${escapeHtml(item.name || '')}" class="cart-item-img" onerror="this.onerror=null;this.src='https://placehold.co/100x100/111111/ff9e00?text=No+Image';" />
                    <div class="cart-item-details">
                        <div class="cart-item-name">${escapeHtml(item.name || '')}</div>
                        <div class="cart-item-meta">Mã SP: ${escapeHtml(item.id || '')}</div>
                    </div>
                    <div class="cart-item-controls">
                        <span class="cart-item-meta" style="margin-right: 1.5rem;">SL:</span>
                        <div class="quantity-control">
                            <button class="qty-btn" data-action="dec" aria-label="Giảm số lượng">-</button>
                            <input type="number" value="${Number(item.quantity || 1)}" min="1" class="qty-input" />
                            <button class="qty-btn" data-action="inc" aria-label="Tăng số lượng">+</button>
                        </div>
                        <button class="cart-item-remove" aria-label="Xóa sản phẩm">🗑</button>
                        <div class="cart-item-price">${formatVND(itemTotal)}</div>
                    </div>
                `;

                // bind events
                const decBtn = div.querySelector('button[data-action="dec"]');
                const incBtn = div.querySelector('button[data-action="inc"]');
                const qtyInput = div.querySelector('.qty-input');
                const removeBtn = div.querySelector('.cart-item-remove');

                decBtn.addEventListener('click', () => {
                    let q = Number(qtyInput.value) || 1; q = Math.max(1, q - 1); qtyInput.value = q; updateQty(idx, q);
                });
                incBtn.addEventListener('click', () => {
                    let q = Number(qtyInput.value) || 1; q = q + 1; qtyInput.value = q; updateQty(idx, q);
                });
                qtyInput.addEventListener('change', () => {
                    let q = Number(qtyInput.value) || 1; if (q < 1) q = 1; qtyInput.value = q; updateQty(idx, q);
                });

                removeBtn.addEventListener('click', () => {
                    removeItem(idx);
                });

                container.appendChild(div);
            });

            const totals = calcTotals(cart);
            updateSummary(totals.subtotal, totals.shipping, totals.total);
            saveCart(cart); // persist any normalization
        }

        function updateQty(index, quantity) {
            const cart = loadCart();
            if (!cart[index]) return;
            cart[index].quantity = Number(quantity);
            saveCart(cart);
            renderCart();
        }

        function removeItem(index) {
            const cart = loadCart();
            if (!cart[index]) return;
            cart.splice(index, 1);
            saveCart(cart);
            renderCart();
        }

        function updateSummary(subtotal, shipping, total) {
            const elSub = document.getElementById('subtotal-value');
            const elShip = document.getElementById('shipping-value');
            const elTotal = document.getElementById('total-value');
            if (elSub) elSub.innerText = formatVND(subtotal);
            if (elShip) elShip.innerText = formatVND(shipping);
            if (elTotal) elTotal.innerText = formatVND(total);
        }

        function escapeHtml(str) {
            return String(str).replace(/[&<>\"']/g, function (m) { return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', '\'': '&#39;' })[m]; });
        }

        // init
        document.addEventListener('DOMContentLoaded', function () {
            renderCart();

            // Wire apply voucher button (basic demo logic)
            const applyBtn = document.querySelector('.apply-btn');
            if (applyBtn) {
                applyBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    alert('Voucher áp dụng tạm thời (demo)');
                });
            }
        });

    })();
</script>