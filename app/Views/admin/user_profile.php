<?php
// Lấy Flash Message (Lỗi/Thành công)
// Các biến này được truyền từ AdminCtrl::user() hoặc AdminCtrl::updateUserFromAdmin()
$error = $error ?? ($_SESSION['error'] ?? null);
$success = $success ?? ($_SESSION['success'] ?? null);
unset($_SESSION['error']);
unset($_SESSION['success']);
// Vì AdminCtrl đang dùng $_SESSION['error_admin'] và $_SESSION['success_admin'], 
// ta cần chỉnh lại cách lấy biến này cho khớp nếu cần.
// Giả sử các biến $error và $success đã được extract() từ AdminCtrl::user()

// Người dùng hiện tại cần hiển thị (Có thể là người đang đăng nhập, hoặc người Admin đang sửa)
// Biến $currentUser được truyền từ AdminCtrl::user() nếu có ID trên URL
$profileUser = $currentUser ?? ($_SESSION['user'] ?? []);

// Nếu không tìm thấy người dùng, hiển thị thông báo lỗi và dừng
if (empty($profileUser) || !isset($profileUser['id'])) {
    // Nếu bạn đang dùng template Admin, hãy đảm bảo rằng file này nằm trong thẻ <main>
    echo "<div class='container mt-5'><div class='alert alert-danger'>Không tìm thấy thông tin tài khoản. Vui lòng kiểm tra lại ID.</div></div>";
    // Nếu có thể, chuyển hướng về trang account
    // header("Location: " . BASE_URL . "index.php/admin/account");
    return;
}

// Đặt biến tiện ích
$is_admin = $profileUser['role'] === 'admin';
$is_self = ($profileUser['id'] ?? 0) === ($_SESSION['user']['id'] ?? 0);
// Nếu $currentUser tồn tại (do Admin gọi) VÀ không phải là Admin đang sửa chính mình
$is_admin_editing_other = isset($currentUser) && !$is_self;

// Xác định Action Form (Nếu là Admin sửa người khác, dùng hàm AdminCtrl::updateUserFromAdmin)
$formAction = BASE_URL . "index.php/admin/updateUserFromAdmin";
?>

<div class="admin-content">
    <div class="row g-4">

        <div class="col-lg-3">
            <div class="profile-card profile-sidebar h-100">
                <div class="avatar-wrapper">
                    <img
                        src="https://ui-avatars.com/api/?name=<?= urlencode($profileUser['name'] ?? 'User') ?>&background=<?= $is_admin ? '000f38' : '00bcd4' ?>&color=fff&size=200"
                        alt="User Avatar" class="avatar-img">
                    <div class="user-name"><?= htmlspecialchars($profileUser['name'] ?? 'Người dùng') ?></div>
                    <div class="user-role"><?= $is_admin ? 'Quản trị viên' : 'Khách hàng' ?></div>
                    <button class="btn btn-sm btn-outline-secondary mt-2">
                        <i class="bi bi-camera me-1"></i> Đổi Avatar
                    </button>
                </div>

                <div class="nav flex-column nav-pills profile-nav" id="v-pills-tab" role="tablist"
                    aria-orientation="vertical">
                    <button class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill"
                        data-bs-target="#v-pills-home" type="button" role="tab" aria-selected="true">
                        <i class="bi bi-person-vcard"></i> Thông tin cá nhân
                    </button>
                    <?php if ($is_self): ?>
                    <button class="nav-link" id="v-pills-security-tab" data-bs-toggle="pill"
                        data-bs-target="#v-pills-security" type="button" role="tab" aria-selected="false">
                        <i class="bi bi-shield-lock"></i> Đổi mật khẩu
                    </button>
                    <?php endif; ?>

                    <?php if ($is_admin): ?>
                    <button class="nav-link" id="v-pills-activity-tab" data-bs-toggle="pill"
                        data-bs-target="#v-pills-activity" type="button" role="tab" aria-selected="false">
                        <i class="bi bi-clock-history"></i> Nhật ký hoạt động
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="profile-card profile-content h-100">

                <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($error) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>
                <?php if (!empty($success)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($success) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>
                <div class="tab-content" id="v-pills-tabContent">

                    <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel">
                        <h4>Thông tin cá nhân</h4>
                        <form action="<?= $formAction ?>" method="POST">
                            <input type="hidden" name="id" value="<?= $profileUser['id'] ?>">
                            <input type="hidden" name="current_email"
                                value="<?= htmlspecialchars($profileUser['email'] ?? '') ?>">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Email (Tên đăng nhập)</label>
                                    <input type="text" class="form-control"
                                        value="<?= htmlspecialchars($profileUser['email'] ?? '') ?>" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Vai trò</label>
                                    <input type="text" class="form-control"
                                        value="<?= $is_admin ? 'Quản trị viên' : 'Khách hàng' ?>" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Họ và tên</label>
                                    <input type="text" class="form-control" name="name"
                                        value="<?= htmlspecialchars($profileUser['name'] ?? '') ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email"
                                        value="<?= htmlspecialchars($profileUser['email'] ?? '') ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Số điện thoại</label>
                                    <input type="tel" class="form-control" name="phone_number"
                                        value="<?= htmlspecialchars($profileUser['phone_number'] ?? '') ?>">
                                </div>
                                <div class="col-12 mt-4">
                                    <button type="submit" name="btn_update_profile" class="btn btn-primary">
                                        <i class="bi bi-save me-1"></i> Lưu thay đổi
                                    </button>
                                    <?php if ($is_admin_editing_other): ?>
                                    <a href="<?= BASE_URL ?>index.php/admin/account" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-left me-1"></i> Quay lại QL Tài khoản
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </form>
                    </div>

                    <?php if ($is_self): ?>
                    <div class="tab-pane fade" id="v-pills-security" role="tabpanel">
                        <h4>Bảo mật & Mật khẩu</h4>
                        <form action="<?= BASE_URL ?>index.php/admin/updatePassword" method="POST"
                            style="max-width: 500px;">
                            <input type="hidden" name="id" value="<?= $profileUser['id'] ?>">

                            <div class="mb-3">
                                <label class="form-label">Mật khẩu hiện tại</label>
                                <input type="password" class="form-control" name="old_password" placeholder="••••••••"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mật khẩu mới</label>
                                <input type="password" class="form-control" name="new_password"
                                    placeholder="Nhập mật khẩu mới" required minlength="6">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Xác nhận mật khẩu mới</label>
                                <input type="password" class="form-control" name="confirm_password"
                                    placeholder="Nhập lại mật khẩu mới" required>
                            </div>
                            <div class="mt-4">
                                <button type="submit" name="btn_update_password" class="btn btn-primary">
                                    <i class="bi bi-key me-1"></i> Cập nhật mật khẩu
                                </button>
                            </div>
                        </form>
                    </div>
                    <?php endif; ?>

                    <?php if ($is_admin): ?>
                    <div class="tab-pane fade" id="v-pills-activity" role="tabpanel">
                        <h4>Nhật ký hoạt động</h4>
                        <p class="text-muted mb-4">Lịch sử các thao tác quản trị gần đây của bạn.</p>

                        <div class="activity-timeline">
                            <div class="timeline-item">
                                <div class="timeline-date">Vừa xong</div>
                                <div class="timeline-content">
                                    Bạn đã cập nhật trạng thái đơn hàng <strong>#FS1005</strong> thành <span
                                        class="badge bg-success">Đã giao</span>.
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-date">2 giờ trước</div>
                                <div class="timeline-content">
                                    Bạn đã thêm sản phẩm mới: <strong>Áo Khoác Nam Dù Cao Cấp</strong>.
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-date">Hôm qua, 15:30</div>
                                <div class="timeline-content">
                                    Đăng nhập vào hệ thống từ IP <strong>192.168.1.1</strong>.
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-date">10/11/2025</div>
                                <div class="timeline-content">
                                    Bạn đã thay đổi mật khẩu tài khoản.
                                </div>
                            </div>
                        </div>

                    </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>

    </div>
</div>
</main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
    crossorigin="anonymous"></script>
</body>

</html>