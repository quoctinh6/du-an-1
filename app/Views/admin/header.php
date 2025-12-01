<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Bootstrap 5.3.2 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        xintegrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />

    <!-- Bootstrap Icons 1.11.3 CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />

    <!-- Chart.js CDN (Cho biểu đồ) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>


    <!-- SEO Tags -->
    <title>FashionShop - Admin Panel</title>
    <meta name="description" content="Khu vực quản trị website FashionShop." />

    <!-- Custom CSS -->
    <style>
        /* =================================================== */
        /* START: CSS KẾ THỪA TỪ TRANG KHÁCH HÀNG */
        /* =================================================== */
        :root {
            /* Đã thay đổi theo yêu cầu */
            --color-accent: #000f38;
            /* Tạo màu tối hơn một chút cho hiệu ứng hover dựa trên #000f38 */
            --color-accent-darker: #000826;
            --color-text-main: #1a1a1a;
            --color-text-subtle: #555555;
        }

        body {
            font-family: "Segoe UI", sans-serif;
            color: var(--color-text-main);
            /* Nền admin luôn là xám nhạt */
            background-color: #f8f9fa;
        }

        /* Kế thừa style nút */
        .btn-primary {
            background-color: var(--color-accent);
            border-color: var(--color-accent);
            color: #ffffff;
        }

        .btn-primary:hover {
            background-color: var(--color-accent-darker);
            border-color: var(--color-accent-darker);
            color: #ffffff;
        }

        .btn-outline-secondary {
            color: var(--color-accent);
            border-color: var(--color-accent);
        }

        .btn-outline-secondary:hover {
            background-color: var(--color-accent);
            color: #ffffff;
        }

        /* =================================================== */
        /* END: CSS KẾ THỪA */
        /* =================================================== */

        /* 14. CSS MỚI CHO LAYOUT ADMIN */

        /* SỬA: Sidebar cố định (Sticky Sidebar) */
        #admin-sidebar {
            min-height: 100vh;
            /* Chiều cao tối thiểu bằng màn hình */
            background-color: #212529;
            /* Màu đen */

            /* KỸ THUẬT STICKY */
            position: -webkit-sticky;
            /* Cho Safari */
            position: sticky;
            top: 0;
            /* Dính chặt vào đỉnh màn hình */
            height: 100vh;
            /* Chiều cao cố định bằng màn hình để scroll bên trong nếu cần */
            overflow-y: auto;
            /* Cho phép scroll dọc nếu menu quá dài */
            z-index: 1000;
            /* Nổi lên trên */
        }

        #admin-sidebar .nav-link {
            color: #adb5bd;
            /* Màu chữ xám */
            padding: 0.75rem 1.5rem;
            font-weight: 500;
        }

        #admin-sidebar .nav-link i {
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
        }

        #admin-sidebar .nav-link:hover {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.05);
        }

        /* Style cho link "active" (đang ở trang đó) */
        #admin-sidebar .nav-link.active {
            color: #ffffff;
            background-color: var(--color-accent);
            /* Dùng màu chủ đạo mới */
        }

        #admin-sidebar .sidebar-heading {
            font-size: 0.8rem;
            text-transform: uppercase;
            color: #6c757d;
            padding: 0.5rem 1.5rem;
            margin-top: 1rem;
        }

        .admin-top-nav {
            background-color: #ffffff;
            border-bottom: 1px solid #dee2e6;
        }

        .admin-content {
            padding: 2rem;
        }

        /* Style cho thanh search */
        .admin-top-nav .form-control {
            border-color: var(--color-accent);
        }

        .admin-top-nav .form-control:focus {
            border-color: var(--color-accent-darker);
            /* Đã cập nhật shadow sang hệ màu #000f38 (R0 G15 B56) */
            box-shadow: 0 0 0 0.25rem rgba(0, 15, 56, 0.25);
        }

        /* Style cho Bảng */
        .admin-table img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 0.25rem;
        }

        .admin-table .badge {
            font-size: 0.85rem;
        }

        /* 16. CSS MỚI CHO DASHBOARD */
        .kpi-card {
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 1.5rem;
        }

        .kpi-card .card-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--color-accent);
        }

        /* SỬA: Làm đậm và rõ tiêu đề KPI */
        .kpi-card .card-title {
            font-size: 0.9rem;
            font-weight: 700;
            /* Đậm hơn */
            color: #6c757d !important;
            /* Màu xám đậm hơn tí cho rõ */
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .kpi-card .kpi-icon {
            /* Đổi tên class cho khớp */
            font-size: 2.5rem;
            color: var(--color-accent);
            opacity: 0.5;
        }

        /* SỬA: Fix lỗi icon hiển thị */
        .kpi-card .card-icon {
            font-size: 2.5rem;
            color: var(--color-accent);
            opacity: 0.5;
        }

        .dashboard-chart-card {
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 1.5rem;
        }

        /* SỬA: Làm đậm tiêu đề các Card lớn (Biểu đồ, Bảng) */
        .dashboard-chart-card h5,
        .card-header {
            font-weight: 700;
            color: var(--color-text-main);
            text-align: center;
            /* THÊM MỚI: Canh giữa theo yêu cầu */
        }

        .list-group-item .product-info {
            font-weight: 500;
            color: var(--color-text-main);
        }

        .list-group-item .product-sales {
            font-weight: 600;
            color: var(--color-accent);
        }

        /* CSS RIÊNG CHO TRANG PROFILE (Logic giống info.html nhưng style Admin) */

        /* Card chứa profile */
        .profile-card {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        /* Cột trái: Menu Profile */
        .profile-sidebar .avatar-wrapper {
            text-align: center;
            padding: 2rem 1rem;
            border-bottom: 1px solid #dee2e6;
        }

        .profile-sidebar .avatar-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #f8f9fa;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
        }

        .profile-sidebar .user-name {
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 0.2rem;
        }

        .profile-sidebar .user-role {
            color: var(--color-text-subtle);
            font-size: 0.9rem;
        }

        /* Style cho Nav Pills (Menu dọc) */
        .profile-nav .nav-link {
            color: var(--color-text-subtle);
            padding: 1rem 1.5rem;
            border-radius: 0;
            border-left: 3px solid transparent;
            font-weight: 500;
            display: flex;
            align-items: center;
        }

        .profile-nav .nav-link i {
            margin-right: 10px;
            font-size: 1.1rem;
        }

        .profile-nav .nav-link:hover {
            background-color: #f8f9fa;
            color: var(--color-accent);
        }

        .profile-nav .nav-link.active {
            background-color: rgba(0, 15, 56, 0.05);
            /* Màu accent nhạt */
            color: var(--color-accent);
            border-left-color: var(--color-accent);
        }

        /* Cột phải: Nội dung Form */
        .profile-content {
            padding: 2rem;
        }

        .profile-content h4 {
            font-weight: 700;
            color: var(--color-accent);
            margin-bottom: 1.5rem;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 1rem;
        }

        .form-label {
            font-weight: 500;
            color: var(--color-text-subtle);
        }

        .form-control:disabled {
            background-color: #e9ecef;
            opacity: 1;
        }

        /* Timeline cho Nhật ký hoạt động */
        .activity-timeline {
            border-left: 2px solid #e9ecef;
            padding-left: 20px;
            margin-left: 10px;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -26px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: var(--color-accent);
            border: 2px solid #fff;
            box-shadow: 0 0 0 2px #e9ecef;
        }

        .timeline-date {
            font-size: 0.8rem;
            color: #999;
            margin-bottom: 0.2rem;
        }

        .timeline-content {
            font-size: 0.95rem;
        }

        /* Style riêng cho biến thể */
        .variant-attribute {
            font-weight: 600;
            color: var(--color-accent);
            background-color: rgba(0, 15, 56, 0.05);
            padding: 2px 8px;
            border-radius: 4px;
            margin-right: 5px;
            font-size: 0.85rem;
        }

        /* product css */
        .pagination .page-item.active .page-link {
            background-color: var(--color-accent);
            border-color: var(--color-accent);
            color: #fff;
        }

        .pagination .page-link {
            color: var(--color-accent);
        }

        .pagination .page-link:hover {
            color: var(--color-accent-darker);
        }

        /* Style Badge Thương hiệu */
        .brand-badge {
            background-color: #e9ecef;
            color: var(--color-text-subtle);
            font-size: 0.75rem;
            padding: 4px 8px;
            border-radius: 4px;
            border: 1px solid #dee2e6;
            font-weight: 600;
        }

        /* 17. CSS MỚI CHO QUẢN LÝ ĐƠN HÀNG */
        .order-status-select {
            min-width: 150px;
        }

        /* Phân trang (Kế thừa từ categories.html) */
        .pagination .page-item.active .page-link {
            background-color: var(--color-accent);
            border-color: var(--color-accent);
            color: #fff;
        }

        .pagination .page-link {
            color: var(--color-accent);
        }

        .pagination .page-link:hover {
            color: var(--color-accent-darker);
        }

        .pagination>.page-item:not(:first-child) .page-link {
            margin-left: -1px;
        }

        .pagination .page-item:first-child .page-link {
            border-top-left-radius: 0.375rem;
            border-bottom-left-radius: 0.375rem;
        }

        .pagination .page-item:last-child .page-link {
            border-top-right-radius: 0.375rem;
            border-bottom-right-radius: 0.375rem;
        }

        /* Style cho Bảng Danh mục */
        .admin-table img.category-icon {
            width: 40px;
            height: 40px;
            object-fit: contain;
            padding: 5px;
            background: #f8f9fa;
            border-radius: 0.25rem;
            border: 1px solid #dee2e6;
        }

        .admin-table .badge {
            font-size: 0.85rem;
        }

        /* Phân trang */
        .pagination .page-item.active .page-link {
            background-color: var(--color-accent);
            border-color: var(--color-accent);
            color: #fff;
        }

        .pagination .page-link {
            color: var(--color-accent);
        }

        .pagination .page-link:hover {
            color: var(--color-accent-darker);
        }

        /* Style cho thanh search "màu nâu" */
        .admin-top-nav .form-control {
            border-color: var(--color-accent);
        }

        .admin-top-nav .form-control:focus {
            border-color: var(--color-accent-darker);
            /* CẬP NHẬT SHADOW MÀU MỚI */
            box-shadow: 0 0 0 0.25rem rgba(0, 15, 56, 0.25);
        }

        /* Style cho Bảng Tài khoản */
        .admin-table img.avatar-img {
            width: 45px;
            height: 45px;
            object-fit: cover;
            border-radius: 50%;
            /* Bo tròn avatar */
            border: 2px solid #e9ecef;
        }

        .admin-table .badge {
            font-size: 0.8rem;
            font-weight: 500;
            padding: 0.5em 0.8em;
        }

        /* Style riêng cho Role badge để dễ phân biệt */
        .badge-role-admin {
            background-color: var(--color-accent);
            color: #fff;
        }

        .badge-role-client {
            background-color: #e9ecef;
            color: var(--color-text-main);
            border: 1px solid #ced4da;
        }

        /* Phân trang (Kế thừa) */
        .pagination .page-item.active .page-link {
            background-color: var(--color-accent);
            border-color: var(--color-accent);
            color: #fff;
        }

        .pagination .page-link {
            color: var(--color-accent);
        }

        .pagination .page-link:hover {
            color: var(--color-accent-darker);
        }

        .pagination>.page-item:not(:first-child) .page-link {
            margin-left: -1px;
        }

        .pagination .page-item:first-child .page-link {
            border-top-left-radius: 0.375rem;
            border-bottom-left-radius: 0.375rem;
        }

        .pagination .page-item:last-child .page-link {
            border-top-right-radius: 0.375rem;
            border-bottom-right-radius: 0.375rem;
        }
    </style>
</head>

<body class="bg-light">
    <div class="d-flex">
        <!-- CỘT 1: SIDEBAR (MENU TRÁI) -->
        <!-- ĐÃ THÊM STYLE STICKY VÀO CSS CỦA ID NÀY -->
        <nav id="admin-sidebar" class="col-lg-2 col-md-3 d-none d-md-block p-0">
            <div class="position-sticky pt-3">
                <!-- Logo/Brand Admin -->
                <a href="<?= BASE_URL ?>index.php/admin/index"
                    class="d-block px-3 mb-3 text-white text-decoration-none fs-5">
                    <span><strong>ZEROWATCH</strong>Admin</span>
                </a>

                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="<?= BASE_URL ?>index.php/admin/index">
                            <i class="bi bi-speedometer2"></i>
                            Tổng quan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>index.php/admin/orders">
                            <i class="bi bi-receipt"></i>
                            Quản lý Đơn hàng
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>index.php/admin/products">
                            <i class="bi bi-box-seam"></i>
                            Quản lý Sản phẩm
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>index.php/admin/categories">
                            <i class="bi bi-tags"></i>
                            Quản lý Danh mục
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>index.php/admin/account">
                            <i class="bi bi-people"></i>
                            Tài khoản
                        </a>
                    </li>
                </ul>

                <h6 class="sidebar-heading px-3 mt-4 mb-1 text-muted">
                    Tài khoản
                </h6>
                <ul class="nav flex-column mb-2">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>index.php/admin/user">
                            <i class="bi bi-person-circle"></i>
                            Admin (Bạn)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>index.php/user/logout">
                            <i class="bi bi-box-arrow-left"></i>
                            Đăng xuất
                        </a>
                    </li>
                </ul>
            </div>
        </nav>