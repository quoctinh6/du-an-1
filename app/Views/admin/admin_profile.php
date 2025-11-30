<?php include_once 'header.php' ?>

<body class="bg-light">
    <div class="d-flex">
        <!-- CỘT 1: SIDEBAR ADMIN (GIỮ NGUYÊN) -->
        <nav id="admin-sidebar" class="col-lg-2 col-md-3 d-none d-md-block p-0">
            <div class="position-sticky pt-3">
                <a
                    href="index.html"
                    class="d-block px-3 mb-3 text-white text-decoration-none fs-5">
                    <span><strong>ZEROWATCH</strong>Admin</span>
                </a>

                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>index.php/AdminDashBoard/index"><i class="bi bi-speedometer2"></i> Tổng quan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>index.php/AdminOrder/index"><i class="bi bi-receipt"></i> Quản lý Đơn hàng</a>
                    </li>
                    <li class="nav-item">
                        <!-- Giữ active để biết đang ở mục Sản phẩm -->
                        <a class="nav-link active" href="<?= BASE_URL ?>index.php/AdminProduct/index"><i class="bi bi-box-seam"></i> Quản lý Sản phẩm</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>index.php/AdminCategogy/index"><i class="bi bi-tags"></i> Quản lý Danh mục</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>index.php/AdminProfile/index"><i class="bi bi-people"></i> Quản lý Tài khoản</a>
                    </li>
                </ul>

                <h6 class="sidebar-heading px-3 mt-4 mb-1 text-muted">
                    Tài khoản
                </h6>
                <ul class="nav flex-column mb-2">
                    <li class="nav-item">
                        <!-- Active vì đang ở trang profile -->
                        <a class="nav-link active" href="admin_profile.html">
                            <i class="bi bi-person-circle"></i>
                            Admin (Bạn)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="d-n-1/public/index.html">
                            <i class="bi bi-box-arrow-left"></i>
                            Đăng xuất
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- CỘT 2: NỘI DUNG CHÍNH -->
        <main class="col-lg-10 col-md-9 ms-sm-auto px-0">
            <!-- HEADER -->
            <nav class="navbar navbar-expand-lg admin-top-nav shadow-sm sticky-top">
                <div class="container-fluid">
                    <button
                        class="navbar-toggler d-md-none"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#admin-sidebar">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <form class="d-flex mx-auto" style="width: 400px">
                        <input
                            class="form-control me-2"
                            type="search"
                            placeholder="Tìm kiếm..."
                            aria-label="Search" />
                    </form>

                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item dropdown">
                            <a
                                class="nav-link dropdown-toggle"
                                href="#"
                                id="navbarDropdown"
                                role="button"
                                data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle fs-4 align-middle"></i>
                                <span class="d-none d-lg-inline align-middle ms-1">Chào, Admin</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="index.html">Xem trang khách</a></li>
                                <li>
                                    <hr class="dropdown-divider" />
                                </li>
                                <li><a class="dropdown-item" href="index.html">Đăng xuất</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- NỘI DUNG PROFILE -->
            <div class="admin-content">
                <div class="row g-4">

                    <!-- CỘT TRÁI: MENU TAB (LOGIC GIỐNG INFO.HTML) -->
                    <div class="col-lg-3">
                        <div class="profile-card profile-sidebar h-100">
                            <div class="avatar-wrapper">
                                <img src="https://ui-avatars.com/api/?name=Admin+User&background=000f38&color=fff&size=200" alt="Admin Avatar" class="avatar-img">
                                <div class="user-name">Lê Quản Trị</div>
                                <div class="user-role">Super Administrator</div>
                                <button class="btn btn-sm btn-outline-secondary mt-2">
                                    <i class="bi bi-camera me-1"></i> Đổi Avatar
                                </button>
                            </div>

                            <!-- Nav Pills (Vertical) -->
                            <div class="nav flex-column nav-pills profile-nav" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                <button class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home" type="button" role="tab" aria-selected="true">
                                    <i class="bi bi-person-vcard"></i> Thông tin cá nhân
                                </button>
                                <button class="nav-link" id="v-pills-security-tab" data-bs-toggle="pill" data-bs-target="#v-pills-security" type="button" role="tab" aria-selected="false">
                                    <i class="bi bi-shield-lock"></i> Đổi mật khẩu
                                </button>
                                <button class="nav-link" id="v-pills-activity-tab" data-bs-toggle="pill" data-bs-target="#v-pills-activity" type="button" role="tab" aria-selected="false">
                                    <i class="bi bi-clock-history"></i> Nhật ký hoạt động
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- CỘT PHẢI: NỘI DUNG TAB -->
                    <div class="col-lg-9">
                        <div class="profile-card profile-content h-100">
                            <div class="tab-content" id="v-pills-tabContent">

                                <!-- TAB 1: THÔNG TIN CÁ NHÂN -->
                                <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel">
                                    <h4>Thông tin cá nhân</h4>
                                    <form>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Tên đăng nhập</label>
                                                <input type="text" class="form-control" value="admin_le" disabled>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Vai trò</label>
                                                <input type="text" class="form-control" value="Super Admin" disabled>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Họ và tên</label>
                                                <input type="text" class="form-control" value="Lê Quản Trị">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Email</label>
                                                <input type="email" class="form-control" value="admin@zerowatch.com">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Số điện thoại</label>
                                                <input type="tel" class="form-control" value="0909.111.222">
                                            </div>
                                            <div class="col-12 mt-4">
                                                <button type="button" class="btn btn-primary">
                                                    <i class="bi bi-save me-1"></i> Lưu thay đổi
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <!-- TAB 2: ĐỔI MẬT KHẨU -->
                                <div class="tab-pane fade" id="v-pills-security" role="tabpanel">
                                    <h4>Bảo mật & Mật khẩu</h4>
                                    <form style="max-width: 500px;">
                                        <div class="mb-3">
                                            <label class="form-label">Mật khẩu hiện tại</label>
                                            <input type="password" class="form-control" placeholder="••••••••">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Mật khẩu mới</label>
                                            <input type="password" class="form-control" placeholder="Nhập mật khẩu mới">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Xác nhận mật khẩu mới</label>
                                            <input type="password" class="form-control" placeholder="Nhập lại mật khẩu mới">
                                        </div>
                                        <div class="mt-4">
                                            <button type="button" class="btn btn-primary">
                                                <i class="bi bi-key me-1"></i> Cập nhật mật khẩu
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- TAB 3: NHẬT KÝ HOẠT ĐỘNG (THAY THẾ LỊCH SỬ MUA HÀNG) -->
                                <div class="tab-pane fade" id="v-pills-activity" role="tabpanel">
                                    <h4>Nhật ký hoạt động</h4>
                                    <p class="text-muted mb-4">Lịch sử các thao tác quản trị gần đây của bạn.</p>

                                    <div class="activity-timeline">
                                        <!-- Item 1 -->
                                        <div class="timeline-item">
                                            <div class="timeline-date">Vừa xong</div>
                                            <div class="timeline-content">
                                                Bạn đã cập nhật trạng thái đơn hàng <strong>#FS1005</strong> thành <span class="badge bg-success">Đã giao</span>.
                                            </div>
                                        </div>
                                        <!-- Item 2 -->
                                        <div class="timeline-item">
                                            <div class="timeline-date">2 giờ trước</div>
                                            <div class="timeline-content">
                                                Bạn đã thêm sản phẩm mới: <strong>Áo Khoác Nam Dù Cao Cấp</strong>.
                                            </div>
                                        </div>
                                        <!-- Item 3 -->
                                        <div class="timeline-item">
                                            <div class="timeline-date">Hôm qua, 15:30</div>
                                            <div class="timeline-content">
                                                Đăng nhập vào hệ thống từ IP <strong>192.168.1.1</strong>.
                                            </div>
                                        </div>
                                        <!-- Item 4 -->
                                        <div class="timeline-item">
                                            <div class="timeline-date">10/11/2025</div>
                                            <div class="timeline-content">
                                                Bạn đã thay đổi mật khẩu tài khoản.
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- end .admin-content -->
        </main>
    </div>

    <!-- Bootstrap 5.3.2 JS Bundle CDN -->
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        xintegrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
</body>

</html>