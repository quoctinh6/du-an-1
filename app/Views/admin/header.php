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
    </style>
</head>

<body class="bg-light">
    <div class="d-flex">
        <!-- CỘT 1: SIDEBAR (MENU TRÁI) -->
        <!-- ĐÃ THÊM STYLE STICKY VÀO CSS CỦA ID NÀY -->
        <nav id="admin-sidebar" class="col-lg-2 col-md-3 d-none d-md-block p-0">
            <div class="position-sticky pt-3">
                <!-- Logo/Brand Admin -->
                <a href="index.html" class="d-block px-3 mb-3 text-white text-decoration-none fs-5">

                    <span><strong>ZEROWATCH</strong>Admin</span>
                </a>

                <ul class="nav flex-column">
                    <li class="nav-item">
                        <!-- Đây là trang hiện tại, nên có class 'active' -->
                        <a class="nav-link active" aria-current="page" href="admin.html">
                            <i class="bi bi-speedometer2"></i>
                            Tổng quan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_orders.html">
                            <i class="bi bi-receipt"></i>
                            Quản lý Đơn hàng
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_products.html">
                            <i class="bi bi-box-seam"></i>
                            Quản lý Sản phẩm
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_account.html">
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
                        <a class="nav-link" href="user_profile.html">
                            <i class="bi bi-person-circle"></i>
                            Admin (Bạn)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.html">
                            <i class="bi bi-box-arrow-left"></i>
                            Đăng xuất
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- CỘT 2: NỘI DUNG CHÍNH (HEADER + CONTENT) -->
        <main class="col-lg-10 col-md-9 ms-sm-auto px-0">
            <!-- HEADER (MENU NGANG) -->
            <nav class="navbar navbar-expand-lg admin-top-nav shadow-sm sticky-top">
                <!-- Thêm sticky-top cho header luôn -->
                <div class="container-fluid">
                    <!-- Nút bật/tắt sidebar trên Mobile -->
                    <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="collapse"
                        data-bs-target="#admin-sidebar" aria-controls="admin-sidebar" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <!-- SỬA: Đổi 'ms-md-4' (lệch) thành 'mx-auto' (ngay chính giữa) -->
                    <form class="d-flex mx-auto" style="width: 400px">
                        <input class="form-control me-2" type="search" placeholder="Tìm kiếm đơn hàng, sản phẩm..."
                            aria-label="Search" />
                    </form>

                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle fs-4 align-middle"></i>
                                <span class="d-none d-lg-inline align-middle ms-1">Chào, Admin</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li>
                                    <a class="dropdown-item" href="index.html">Xem trang khách</a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider" />
                                </li>
                                <li>
                                    <a class="dropdown-item" href="index.html">Đăng xuất</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>