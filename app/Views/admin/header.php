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
  <title>ZeroWatch - Admin Panel</title>
  <meta name="description" content="Khu vực quản trị website FashionShop." />

  <!-- Custom CSS -->
  <style>
    /* =================================================== */
    /* 1. CORE VARIABLES & RESET (DÙNG CHUNG) */
    /* =================================================== */
    :root {
      /* MÀU CHỦ ĐẠO: DEEP NAVY */
      --color-accent: #000f38;
      --color-accent-darker: #000826;
      --color-text-main: #1a1a1a;
      --color-text-subtle: #555555;
      --bg-admin: #f8f9fa;
      --sidebar-width: 240px;
      /* (Tham khảo nếu cần chỉnh độ rộng) */
    }

    body {
      font-family: "Segoe UI", sans-serif;
      color: var(--color-text-main);
      background-color: var(--bg-admin);
      overflow-x: hidden;
      /* Tránh thanh cuộn ngang ngoài ý muốn */
    }

    /* =================================================== */
    /* 2. LAYOUT ADMIN (SIDEBAR & HEADER) */
    /* =================================================== */

    /* --- Sidebar (Cột trái) --- */
    /* Lưu ý: id #admin-sidebar nằm trong header.php */
    #admin-sidebar {
      min-height: 100vh;
      background-color: #212529;
      /* Màu đen */

      /* Sticky giúp sidebar luôn cố định khi cuộn nội dung bên phải */
      position: -webkit-sticky;
      position: sticky;
      top: 0;
      height: 100vh;
      overflow-y: auto;
      /* Cho phép cuộn dọc trong menu nếu quá dài */
      z-index: 1000;
      box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
    }

    #admin-sidebar .nav-link {
      color: #adb5bd;
      /* Màu chữ xám */
      padding: 0.8rem 1.5rem;
      font-weight: 500;
      transition: all 0.2s;
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

    /* Style cho link đang Active (Trang hiện tại) */
    #admin-sidebar .nav-link.active {
      color: #ffffff;
      background-color: var(--color-accent);
      border-left: 4px solid #fff;
      /* Thêm điểm nhấn */
    }

    #admin-sidebar .sidebar-heading {
      font-size: 0.75rem;
      text-transform: uppercase;
      color: #6c757d;
      padding: 1rem 1.5rem 0.5rem;
      letter-spacing: 0.5px;
    }

    /* --- Main Content Area (Cột phải) --- */
    /* Class này nằm trong các file admin_*.php */
    .admin-content {
      padding: 2rem;
      min-height: calc(100vh - 60px);
      /* Đảm bảo full màn hình trừ header */
    }

    /* --- Header Top Nav --- */
    .admin-top-nav {
      background-color: #ffffff;
      border-bottom: 1px solid #dee2e6;
      height: 64px;
      /* Cố định chiều cao header để tránh nhảy layout */
    }

    /* Input tìm kiếm trên header */
    .admin-top-nav .form-control {
      border-color: var(--color-accent);
      border-radius: 20px;
      /* Bo tròn thanh tìm kiếm */
    }

    .admin-top-nav .form-control:focus {
      border-color: var(--color-accent-darker);
      box-shadow: 0 0 0 0.25rem rgba(0, 15, 56, 0.15);
    }

    /* =================================================== */
    /* 3. COMMON COMPONENTS (BẢNG, NÚT, BADGE) */
    /* =================================================== */

    /* --- Buttons --- */
    .btn-primary {
      background-color: var(--color-accent);
      border-color: var(--color-accent);
      color: #ffffff;
    }

    .btn-primary:hover,
    .btn-primary:focus {
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

    /* --- Data Tables --- */
    /* Mặc định ảnh trong bảng sẽ vuông 60px (cho Sản phẩm, Đơn hàng) */
    .admin-table img {
      width: 60px;
      height: 60px;
      object-fit: cover;
      border-radius: 6px;
      border: 1px solid #e9ecef;
    }

    /* Căn chỉnh badge trong bảng */
    .admin-table .badge {
      font-size: 0.85rem;
      padding: 0.5em 0.8em;
      font-weight: 500;
    }

    /* --- Pagination --- */
    .pagination .page-item.active .page-link {
      background-color: var(--color-accent);
      border-color: var(--color-accent);
      color: #fff;
    }

    .pagination .page-link {
      color: var(--color-accent);
      border: 1px solid #dee2e6;
    }

    .pagination .page-link:hover {
      background-color: #e9ecef;
      color: var(--color-accent-darker);
    }

    /* =================================================== */
    /* 4. PAGE SPECIFIC STYLES (STYLE RIÊNG TỪNG TRANG) */
    /* =================================================== */

    /* --- A. Dashboard (admin.php) --- */
    .kpi-card {
      background-color: #ffffff;
      border: 1px solid #dee2e6;
      border-radius: 0.5rem;
      padding: 1.5rem;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
      transition: transform 0.2s;
    }

    .kpi-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
    }

    .kpi-card .card-value {
      font-size: 2rem;
      font-weight: 700;
      color: var(--color-accent);
      line-height: 1.2;
    }

    .kpi-card .card-title {
      font-size: 0.85rem;
      font-weight: 700;
      color: #6c757d;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 0.5rem;
    }

    .kpi-card .card-icon,
    .kpi-card .kpi-icon {
      font-size: 2.5rem;
      color: var(--color-accent);
      opacity: 0.15;
      /* Làm mờ icon nền */
      margin-left: 1rem;
      flex-shrink: 0;
    }

    .dashboard-chart-card {
      background-color: #ffffff;
      border: 1px solid #dee2e6;
      border-radius: 0.5rem;
      padding: 1.5rem;
      height: 100%;
      /* Đảm bảo bằng nhau khi chia cột */
    }

    .dashboard-chart-card h5 {
      font-weight: 700;
      color: var(--color-text-main);
      text-align: center;
      margin-bottom: 1.5rem;
    }

    .list-group-item {
      border-left: none;
      border-right: none;
      padding: 1rem 0;
    }

    .list-group-item:first-child {
      border-top: none;
    }

    .list-group-item:last-child {
      border-bottom: none;
    }

    .list-group-item .product-info {
      font-weight: 500;
    }

    .list-group-item .product-sales {
      font-weight: 600;
      color: var(--color-accent);
    }


    /* --- B. Quản lý Đơn hàng (admin_orders.php) --- */
    .filter-buttons .btn {
      border-radius: 50px;
      /* Pill shape */
      padding: 0.375rem 1.2rem;
      font-weight: 500;
      font-size: 0.9rem;
    }

    .filter-buttons .btn-outline-secondary {
      color: var(--color-text-subtle);
      border-color: #ced4da;
      background-color: #fff;
    }

    .filter-buttons .btn-outline-secondary:hover {
      border-color: var(--color-accent);
      color: var(--color-accent);
      background-color: rgba(0, 15, 56, 0.05);
    }

    .filter-buttons .btn-primary {
      background-color: var(--color-accent);
      border-color: var(--color-accent);
    }


    /* --- C. Quản lý Sản phẩm (admin_products.php) --- */
    /* Cột Thương hiệu */
    .brand-badge {
      background-color: #f1f3f5;
      color: var(--color-text-subtle);
      font-size: 0.75rem;
      padding: 4px 8px;
      border-radius: 4px;
      border: 1px solid #dee2e6;
      font-weight: 600;
      white-space: nowrap;
    }


    /* --- D. Quản lý Danh mục (admin_category.php) --- */
    /* Override ảnh cho danh mục: nhỏ hơn và fit contain (vì là icon) */
    .admin-table img.category-icon {
      width: 40px;
      height: 40px;
      object-fit: contain;
      padding: 4px;
      background: #f8f9fa;
      border-radius: 4px;
      border: 1px solid #dee2e6;
    }


    /* --- E. Quản lý Tài khoản (admin_account.php) --- */
    /* Override ảnh cho tài khoản: tròn và nhỏ hơn */
    .admin-table img.avatar-img {
      width: 45px;
      height: 45px;
      object-fit: cover;
      border-radius: 50%;
      border: 2px solid #dee2e6;
    }

    /* Badge phân quyền */
    .badge-role-admin {
      background-color: var(--color-accent);
      color: #fff;
      border: 1px solid var(--color-accent);
    }

    .badge-role-client {
      background-color: #f8f9fa;
      color: var(--color-text-main);
      border: 1px solid #ced4da;
    }


    /* --- F. Quản lý Biến thể (admin_variants.php) --- */
    .variant-attribute {
      font-weight: 600;
      color: var(--color-accent);
      background-color: rgba(0, 15, 56, 0.08);
      padding: 2px 8px;
      border-radius: 4px;
      margin-right: 5px;
      font-size: 0.85rem;
      display: inline-block;
      margin-bottom: 2px;
    }


    /* --- G. Hồ sơ Cá nhân (user_profile.php) --- */
    .profile-card {
      background: #fff;
      border: 1px solid #dee2e6;
      border-radius: 0.5rem;
      box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.05);
      height: 100%;
    }

    .profile-sidebar .avatar-wrapper {
      text-align: center;
      padding: 2rem 1rem;
      border-bottom: 1px solid #dee2e6;
    }

    .profile-sidebar .avatar-img {
      width: 120px;
      /* To hơn avatar trong bảng */
      height: 120px;
      object-fit: cover;
      border-radius: 50%;
      border: 4px solid #f8f9fa;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      margin-bottom: 1rem;
    }

    .profile-sidebar .user-name {
      font-weight: 700;
      font-size: 1.2rem;
      margin-bottom: 0.2rem;
      color: var(--color-accent);
    }

    .profile-sidebar .user-role {
      color: var(--color-text-subtle);
      font-size: 0.9rem;
    }

    /* Menu dọc trong profile */
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
      margin-right: 12px;
      font-size: 1.1rem;
    }

    .profile-nav .nav-link:hover {
      background-color: #f8f9fa;
      color: var(--color-accent);
    }

    .profile-nav .nav-link.active {
      background-color: rgba(0, 15, 56, 0.05);
      color: var(--color-accent);
      border-left-color: var(--color-accent);
      font-weight: 600;
    }

    .profile-content {
      padding: 2rem;
    }

    .profile-content h4 {
      font-weight: 700;
      color: var(--color-accent);
      margin-bottom: 1.5rem;
      border-bottom: 2px solid #f1f1f1;
      padding-bottom: 0.5rem;
    }

    /* Timeline Hoạt động */
    .activity-timeline {
      border-left: 2px solid #e9ecef;
      padding-left: 25px;
      margin-left: 10px;
    }

    .timeline-item {
      position: relative;
      margin-bottom: 2rem;
    }

    .timeline-item:last-child {
      margin-bottom: 0;
    }

    .timeline-item::before {
      content: '';
      position: absolute;
      left: -31px;
      /* Căn chỉnh chấm tròn trùng với đường kẻ */
      top: 4px;
      width: 14px;
      height: 14px;
      border-radius: 50%;
      background-color: var(--color-accent);
      border: 3px solid #fff;
      box-shadow: 0 0 0 1px #e9ecef;
    }

    .timeline-date {
      font-size: 0.8rem;
      color: #999;
      margin-bottom: 0.2rem;
      font-weight: 600;
    }

    .timeline-content {
      font-size: 0.95rem;
      color: var(--color-text-main);
    }

    :root {
      --color-accent: #000f38;
      --color-accent-darker: #000826;
      --color-text-main: #1a1a1a;
      --color-text-subtle: #555555;
    }

    /* Style riêng cho badge biến thể */
    .variant-attribute {
      font-weight: 600;
      color: var(--color-accent);
      background-color: rgba(0, 15, 56, 0.05);
      padding: 2px 8px;
      border-radius: 4px;
      margin-right: 5px;
      font-size: 0.85rem;
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