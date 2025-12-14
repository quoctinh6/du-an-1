<main class="info-main">
    <div class="info-dashboard">

        <!-- BOX TRÁI: SIDEBAR MENU -->
        <aside class="dashboard-sidebar">
            <div class="user-summary">
                <div class="summary-avatar">
                    <!-- User Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor"
                        viewBox="0 0 24 24">
                        <path
                            d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                    </svg>
                </div>
                <div class="summary-info">
                    <div class="summary-name"><?php echo htmlspecialchars($user['name']); ?></div>
                    <div class="summary-email"><?php echo htmlspecialchars($user['email']); ?></div>
                </div>
            </div>

            <nav class="sidebar-menu">
                <button class="sidebar-link active" onclick="openTab(event, 'personal-info')">
                    Thông tin cá nhân
                </button>
                <button class="sidebar-link" onclick="openTab(event, 'order-history')">
                    Lịch sử đơn hàng
                </button>
                <button class="sidebar-link" onclick="openTab(event, 'change-address')">
                    Sổ địa chỉ
                </button>
                <button class="sidebar-link" onclick="openTab(event, 'change-password')">
                    Đổi mật khẩu
                </button>
                <button class="sidebar-link logout-link"
                    onclick="window.location.href='<?php echo BASE_URL; ?>index.php/User/logout'">
                    Đăng xuất
                </button>
            </nav>
        </aside>

        <!-- BOX PHẢI: CONTENT AREA -->
        <div class="dashboard-content">

            <!-- THÔNG BÁO LỖI/THÀNH CÔNG -->
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error"
                    style="margin-bottom: 20px; padding: 15px; background: #fee; border: 1px solid #fcc; border-radius: 8px; color: #c33;">
                    <?php echo htmlspecialchars($_SESSION['error']);
                    unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"
                    style="margin-bottom: 20px; padding: 15px; background: #efe; border: 1px solid #cfc; border-radius: 8px; color: #3c3;">
                    <?php echo htmlspecialchars($_SESSION['success']);
                    unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <!-- TAB 1: THÔNG TIN CÁ NHÂN -->
            <div id="personal-info" class="tab-content active-tab">
                <h2 class="tab-title">Hồ Sơ Của Tôi</h2>
                <div class="tab-desc">Quản lý thông tin hồ sơ để bảo mật tài khoản.</div>

                <form class="dashboard-form" method="POST">
                    <input type="hidden" name="action" value="update_profile">
                    <div class="form-grid">
                        <div class="input-group">
                            <label>Tên đăng nhập</label>
                            <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled
                                style="opacity: 0.7; cursor: not-allowed;">
                        </div>
                        <div class="input-group">
                            <label>Họ và Tên</label>
                            <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>"
                                required>
                        </div>
                        <div class="input-group">
                            <label>Email</label>
                            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>"
                                required>
                        </div>
                        <div class="input-group">
                            <label>Số điện thoại</label>
                            <input type="tel" name="phone_number"
                                value="<?php echo htmlspecialchars($user['phone_number'] ?? ''); ?>"
                                pattern="[0-9]{10,11}">
                        </div>
                    </div>
                    <button type="submit" class="save-btn">Lưu Thay Đổi</button>
                </form>
            </div>

            <!-- TAB 2: LỊCH SỬ ĐƠN HÀNG -->
            <div id="order-history" class="tab-content">
                <h2 class="tab-title">Lịch Sử Đơn Hàng</h2>
                <div class="tab-desc">Xem lại các đơn hàng bạn đã mua gần đây.</div>

                <div class="order-list">
                    <?php if (empty($orders)): ?>
                        <div style="text-align: center; padding: 40px; color: #999;">
                            <p>Bạn chưa có đơn hàng nào.</p>
                            <a href="<?php echo BASE_URL; ?>index.php/products/all" class="glass-button"
                                style="display: inline-block; margin-top: 15px; background: rgba(119, 119, 119, 0.15); backdrop-filter: blur(8px); border: 1px solid rgba(70, 70, 70, 0.2); padding: 12px 24px; border-radius: 8px; color: #fff; text-decoration: none; font-weight: 600;">
                                Bắt đầu mua sắm
                            </a>
                        </div>
                    <?php else: ?>
                        <?php foreach ($orders as $order):
                            $order_items = $OrderModel->getOrderItems($order['id']);
                            $status_class = '';
                            $status_label = '';

                            switch ($order['status']) {
                                case 'pending':
                                    $status_class = 'status-pending';
                                    $status_label = 'Chờ xác nhận';
                                    break;
                                case 'processing':
                                    $status_class = 'status-processing';
                                    $status_label = 'Đang xử lý';
                                    break;
                                case 'shipped':
                                    $status_class = 'status-shipped';
                                    $status_label = 'Đang giao hàng';
                                    break;
                                case 'completed':
                                    $status_class = 'status-success';
                                    $status_label = 'Giao thành công';
                                    break;
                                case 'cancelled':
                                    $status_class = 'status-cancelled';
                                    $status_label = 'Đã hủy';
                                    break;
                            }
                            ?>
                            <!-- Order Item -->
                            <div class="order-item">
                                <div class="order-header">
                                    <span class="order-id">#DH<?php echo str_pad($order['id'], 5, '0', STR_PAD_LEFT); ?></span>
                                    <span class="order-status <?php echo $status_class; ?>"><?php echo $status_label; ?></span>
                                    <div class="order-total">Ngày đặt: <span><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></span></div>
                                </div>
                                
                                <?php if (!empty($order_items)): ?>
                                    <?php foreach ($order_items as $item): 
                                        $variants = [];
                                        if ($item['color_name'])
                                            $variants[] = $item['color_name'];
                                        if ($item['size_name'])
                                            $variants[] = $item['size_name'];
                                    ?>
                                    <div class="order-body">
                                        <img src="<?php echo htmlspecialchars(BASE_URL . 'uploads/products/' . ($item['image_url'] ?? 'default.png')); ?>"
                                            alt="<?php echo htmlspecialchars($item['product_name']); ?>" class="order-img">
                                        <div class="order-details">
                                            <div class="order-product-name">
                                                <?php echo htmlspecialchars($item['product_name']); ?>
                                            </div>
                                            <div class="order-meta">
                                                Phân loại: <?php echo htmlspecialchars(implode(', ', $variants) ?: 'N/A'); ?>
                                            </div>
                                            <div class="order-qty">x<?php echo $item['quantity']; ?></div>
                                        </div>
                                        <div class="order-price" style="font-weight: 700;">
                                            <?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?> đ
                                        </div>
                                        <div class="order-actions">
                                            <a href="<?php echo BASE_URL; ?>index.php/Products/detail/<?php echo htmlspecialchars($item['slug'] ?? '#'); ?>"
                                                class="outline-btn">Chi Tiết</a>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>

                                <div class="order-footer">
                                    <div style="text-align: right; font-weight: 700; font-size: 1.1em;">
                                        Tổng: <span style="color: #ff9e00;"><?php echo number_format($order['total_price'], 0, ',', '.'); ?> đ</span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- TAB 3: SỔ ĐỊA CHỈ -->
            <div id="change-address" class="tab-content">
                <h2 class="tab-title">Địa Chỉ Giao Hàng</h2>
                <div class="tab-desc">Quản lý danh sách địa chỉ nhận hàng của bạn.</div>

                <!-- Danh sách địa chỉ hiện tại -->
                <div style="margin-bottom: 30px;">
                    <h3 style="color: #ff9e00; margin-bottom: 15px;">Địa chỉ của bạn</h3>
                    <?php if (empty($addresses)): ?>
                        <div style="padding: 20px; background: #111; border-radius: 8px; color: #999; text-align: center;">
                            Bạn chưa lưu địa chỉ nào. Hãy thêm địa chỉ đầu tiên.
                        </div>
                    <?php else: ?>
                        <div style="display: grid; gap: 15px;">
                            <?php foreach ($addresses as $address): ?>
                                <div style="padding: 15px; background: #111; border: 1px solid #333; border-radius: 8px;">
                                    <div
                                        style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                                        <div>
                                            <strong><?php echo htmlspecialchars($address['full_name']); ?></strong>
                                            <?php if ($address['is_default']): ?>
                                                <span
                                                    style="background: #ff9e00; color: #000; padding: 2px 8px; border-radius: 4px; font-size: 0.8em; margin-left: 10px;">Mặc
                                                    định</span>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <button class="btn-edit"
                                                onclick="editAddress(<?php echo $address['id']; ?>, '<?php echo htmlspecialchars($address['full_name'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($address['phone_number'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($address['address_line'], ENT_QUOTES); ?>', <?php echo $address['is_default']; ?>)"
                                                style="background: none; border: none; color: #ff9e00; cursor: pointer; margin-right: 10px;">Sửa</button>
                                            <button class="btn-delete" onclick="deleteAddress(<?php echo $address['id']; ?>)"
                                                style="background: none; border: none; color: #f44; cursor: pointer;">Xóa</button>
                                        </div>
                                    </div>
                                    <div style="font-size: 0.9em; color: #ccc;">
                                        📞 <?php echo htmlspecialchars($address['phone_number']); ?><br>
                                        📍 <?php echo htmlspecialchars($address['address_line']); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Form thêm/sửa địa chỉ -->
                <div id="address-form-container"
                    style="display: none; padding: 20px; background: #111; border-radius: 8px; margin-top: 20px;">
                    <h3 id="form-title" style="color: #ff9e00;">Thêm Địa Chỉ Mới</h3>
                    <form class="dashboard-form" method="POST">
                        <input type="hidden" name="action" id="form-action" value="add_address">
                        <input type="hidden" name="address_id" id="form-address-id" value="">

                        <div class="form-grid">
                            <div class="input-group">
                                <label>Họ và Tên</label>
                                <input type="text" name="full_name" id="form-full-name" required>
                            </div>
                            <div class="input-group">
                                <label>Số Điện Thoại</label>
                                <input type="tel" name="phone_number" id="form-phone" pattern="[0-9]{10,11}" required>
                            </div>
                        </div>

                        <div class="input-group">
                            <label>Địa Chỉ</label>
                            <textarea name="address_line" id="form-address" rows="3"
                                placeholder="Ví dụ: 123 Đường ABC, Phường X, Quận Y, TP.HCM" required></textarea>
                        </div>

                        <div class="input-group" style="display: flex; align-items: center; margin-bottom: 20px;">
                            <input type="checkbox" name="is_default" id="form-is-default"
                                style="width: auto; margin-right: 10px;">
                            <label style="margin: 0;">Đặt làm địa chỉ mặc định</label>
                        </div>

                        <div style="display: flex; gap: 10px;">
                            <button type="submit" class="save-btn">Lưu Địa Chỉ</button>
                            <button type="button" class="outline-btn" onclick="cancelAddressForm()">Hủy</button>
                        </div>
                    </form>
                </div>

                <!-- Nút thêm địa chỉ -->
                <div id="add-address-btn" style="margin-top: 20px;">
                    <button onclick="showAddressForm()" class="glass-button"
                        style="padding: 12px 24px; background: rgba(119, 119, 119, 0.15); backdrop-filter: blur(8px); border: 1px solid rgba(70, 70, 70, 0.2); color: #fff; cursor: pointer; font-weight: 600; border-radius: 8px;">
                        + Thêm Địa Chỉ Mới
                    </button>
                </div>
            </div>

            <!-- TAB 4: ĐỔI MẬT KHẨU -->
            <div id="change-password" class="tab-content">
                <h2 class="tab-title">Đổi Mật Khẩu</h2>
                <div class="tab-desc">Để bảo mật tài khoản, vui lòng không chia sẻ mật khẩu cho người khác.</div>

                <form class="dashboard-form" method="POST" style="max-width: 400px;">
                    <input type="hidden" name="action" value="change_password">
                    <div class="input-group">
                        <label>Mật khẩu hiện tại</label>
                        <input type="password" name="current_password" placeholder="••••••••" required>
                    </div>
                    <div class="input-group">
                        <label>Mật khẩu mới</label>
                        <input type="password" name="new_password" placeholder="Nhập mật khẩu mới" required>
                    </div>
                    <div class="input-group">
                        <label>Xác nhận mật khẩu mới</label>
                        <input type="password" name="confirm_password" placeholder="Nhập lại mật khẩu mới" required>
                    </div>
                    <button type="submit" class="save-btn">Lưu Mật Khẩu</button>
                </form>
            </div>

        </div>
    </div>
</main>

<style>
    .alert {
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        animation: slideDown 0.3s ease-out;
    }

    .alert-error {
        background: #fee;
        border: 1px solid #fcc;
        color: #c33;
    }

    .alert-success {
        background: #efe;
        border: 1px solid #cfc;
        color: #3c3;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .outline-btn {
        background: transparent;
        border: 1px solid #ff9e00;
        color: #ff9e00;
        padding: 10px 20px;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .outline-btn:hover {
        background: #ff9e00;
        color: #000;
    }

    .status-pending {
        background: #ff9e00 !important;
        color: #000 !important;
    }

    .status-processing {
        background: #0099ff !important;
        color: #fff !important;
    }

    .status-shipped {
        background: #9966ff !important;
        color: #fff !important;
    }

    .status-success {
        background: #33cc33 !important;
        color: #000 !important;
    }

    .status-cancelled {
        background: #ff4444 !important;
        color: #fff !important;
    }
</style>
<script>
    function openTab(evt, tabName) {
        // 1. Ẩn tất cả các tab content
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tab-content");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].classList.remove("active-tab");
        }

        // 2. Bỏ active class khỏi tất cả các nút sidebar
        tablinks = document.getElementsByClassName("sidebar-link");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].classList.remove("active");
        }

        // 3. Hiển thị tab hiện tại và thêm class active cho nút bấm
        document.getElementById(tabName).classList.add("active-tab");
        evt.currentTarget.classList.add("active");
    }

    function showAddressForm() {
        document.getElementById('address-form-container').style.display = 'block';
        document.getElementById('add-address-btn').style.display = 'none';
        document.getElementById('form-action').value = 'add_address';
        document.getElementById('form-title').textContent = 'Thêm Địa Chỉ Mới';
        document.getElementById('form-address-id').value = '';
        document.getElementById('form-full-name').value = '';
        document.getElementById('form-phone').value = '';
        document.getElementById('form-address').value = '';
        document.getElementById('form-is-default').checked = false;
    }

    function editAddress(id, fullName, phone, address, isDefault) {
        document.getElementById('address-form-container').style.display = 'block';
        document.getElementById('add-address-btn').style.display = 'none';
        document.getElementById('form-action').value = 'update_address';
        document.getElementById('form-title').textContent = 'Sửa Địa Chỉ';
        document.getElementById('form-address-id').value = id;
        document.getElementById('form-full-name').value = fullName;
        document.getElementById('form-phone').value = phone;
        document.getElementById('form-address').value = address;
        document.getElementById('form-is-default').checked = isDefault == 1;
    }

    function cancelAddressForm() {
        document.getElementById('address-form-container').style.display = 'none';
        document.getElementById('add-address-btn').style.display = 'block';
    }

    function deleteAddress(addressId) {
        if (confirm('Bạn có chắc chắn muốn xóa địa chỉ này?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `
                <input type="hidden" name="action" value="delete_address">
                <input type="hidden" name="address_id" value="${addressId}">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>