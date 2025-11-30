<?php include_once 'header.php'?>

<body class="bg-light">
    <div class="d-flex">
      <!-- CỘT 1: SIDEBAR (MENU TRÁI) -->
      <nav id="admin-sidebar" class="col-lg-2 col-md-3 d-none d-md-block p-0">
        <div class="position-sticky pt-3">
          <!-- Logo/Brand Admin -->
          <a
            href="index.html"
            class="d-block px-3 mb-3 text-white text-decoration-none fs-5"
          >
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
              <a class="nav-link" href="admin_profile.html">
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
          <div class="container-fluid">
            <!-- Nút bật/tắt sidebar trên Mobile -->
            <button
              class="navbar-toggler d-md-none"
              type="button"
              data-bs-toggle="collapse"
              data-bs-target="#admin-sidebar"
            >
              <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Form tìm kiếm -->
            <form class="d-flex mx-auto" style="width: 400px">
              <input
                class="form-control me-2"
                type="search"
                placeholder="Tìm kiếm danh mục..."
                aria-label="Search"
              />
            </form>

            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
              <li class="nav-item dropdown">
                <a
                  class="nav-link dropdown-toggle"
                  href="#"
                  id="navbarDropdown"
                  role="button"
                  data-bs-toggle="dropdown"
                >
                  <i class="bi bi-person-circle fs-4 align-middle"></i>
                  <span class="d-none d-lg-inline align-middle ms-1"
                    >Chào, Admin</span
                  >
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li><a class="dropdown-item" href="index.html">Xem trang khách</a></li>
                  <li><hr class="dropdown-divider" /></li>
                  <li><a class="dropdown-item" href="index.html">Đăng xuất</a></li>
                </ul>
              </li>
            </ul>
          </div>
        </nav>

        <!-- NỘI DUNG CHÍNH CỦA TRANG -->
        <div class="admin-content">
          <!-- Tiêu đề trang & Nút Thêm -->
          <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 fw-bold" style="color: var(--color-text-main)">
              Quản lý Danh mục
            </h1>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
               <i class="bi bi-plus-lg me-2"></i>Thêm danh mục mới
            </button>
          </div>
          
          <!-- Bộ lọc nhanh -->
           <div class="card shadow-sm mb-4">
             <div class="card-body">
               <div class="row g-3">
                 <div class="col-md-6">
                    <select class="form-select" aria-label="Lọc trạng thái">
                      <option selected>Tất cả trạng thái</option>
                      <option value="1">Đang hiển thị</option>
                      <option value="2">Đang ẩn</option>
                    </select>
                 </div>
                  <div class="col-md-6 text-md-end">
                    <button class="btn btn-outline-secondary w-100 w-md-auto">
                      <i class="bi bi-funnel me-1"></i> Lọc danh sách
                    </button>
                 </div>
               </div>
             </div>
           </div>

          <!-- Bảng Danh mục -->
          <div class="card shadow-sm">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-hover align-middle admin-table">
                  <thead class="table-light">
                    <tr>
                      <th scope="col" style="width: 80px;">Icon</th>
                      <th scope="col">Tên danh mục</th>
                      <th scope="col">Mô tả</th>
                      <th scope="col" class="text-center">Số lượng SP</th>
                      <th scope="col">Trạng thái</th>
                      <th scope="col" class="text-end">Hành động</th>
                    </tr>
                  </thead>
                  <tbody>
                    
                    <!-- Danh mục 1 -->
                    <tr>
                      <td>
                        <img src="https://cdn-icons-png.flaticon.com/512/2652/2652218.png" alt="Icon" class="category-icon">
                      </td>
                      <td>
                        <div class="fw-bold">Áo Khoác Nam</div>
                        <small class="text-muted">ID: CAT001</small>
                      </td>
                      <td>Áo khoác dù, kaki, bomber các loại</td>
                      <td class="text-center"><span class="badge bg-secondary rounded-pill">120</span></td>
                      <td><span class="badge bg-success">Hiển thị</span></td>
                      <td class="text-end">
                         <button class="btn btn-sm btn-outline-primary me-1" title="Sửa">
                           <i class="bi bi-pencil"></i>
                         </button>
                         <button class="btn btn-sm btn-outline-danger" title="Xóa">
                           <i class="bi bi-trash"></i>
                         </button>
                      </td>
                    </tr>
                    
                    <!-- Danh mục 2 -->
                    <tr>
                      <td>
                         <img src="https://cdn-icons-png.flaticon.com/512/599/599388.png" alt="Icon" class="category-icon">
                      </td>
                      <td>
                        <div class="fw-bold">Quần Jeans</div>
                        <small class="text-muted">ID: CAT002</small>
                      </td>
                      <td>Quần dài, quần short, denim</td>
                      <td class="text-center"><span class="badge bg-secondary rounded-pill">88</span></td>
                      <td><span class="badge bg-success">Hiển thị</span></td>
                      <td class="text-end">
                         <button class="btn btn-sm btn-outline-primary me-1" title="Sửa">
                           <i class="bi bi-pencil"></i>
                         </button>
                         <button class="btn btn-sm btn-outline-danger" title="Xóa">
                           <i class="bi bi-trash"></i>
                         </button>
                      </td>
                    </tr>
                    
                    <!-- Danh mục 3 -->
                    <tr>
                      <td>
                        <img src="https://cdn-icons-png.flaticon.com/512/2589/2589903.png" alt="Icon" class="category-icon">
                      </td>
                      <td>
                        <div class="fw-bold">Giày Thể Thao</div>
                        <small class="text-muted">ID: CAT003</small>
                      </td>
                      <td>Sneaker, giày chạy bộ, giày tập</td>
                      <td class="text-center"><span class="badge bg-secondary rounded-pill">95</span></td>
                      <td><span class="badge bg-success">Hiển thị</span></td>
                      <td class="text-end">
                         <button class="btn btn-sm btn-outline-primary me-1" title="Sửa">
                           <i class="bi bi-pencil"></i>
                         </button>
                         <button class="btn btn-sm btn-outline-danger" title="Xóa">
                           <i class="bi bi-trash"></i>
                         </button>
                      </td>
                    </tr>
                    
                    <!-- Danh mục 4 -->
                    <tr>
                       <td>
                        <img src="https://cdn-icons-png.flaticon.com/512/88/88746.png" alt="Icon" class="category-icon">
                      </td>
                      <td>
                        <div class="fw-bold">Phụ Kiện</div>
                        <small class="text-muted">ID: CAT004</small>
                      </td>
                      <td>Mũ, nón, thắt lưng, ví da</td>
                      <td class="text-center"><span class="badge bg-secondary rounded-pill">45</span></td>
                      <td><span class="badge bg-secondary">Đang ẩn</span></td>
                      <td class="text-end">
                         <button class="btn btn-sm btn-outline-primary me-1" title="Sửa">
                           <i class="bi bi-pencil"></i>
                         </button>
                         <button class="btn btn-sm btn-outline-danger" title="Xóa">
                           <i class="bi bi-trash"></i>
                         </button>
                      </td>
                    </tr>

                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Phân trang -->
          <nav aria-label="Category pagination" class="d-flex justify-content-center mt-4">
            <ul class="pagination">
              <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Trước</a>
              </li>
              <li class="page-item active" aria-current="page">
                <a class="page-link" href="#">1</a>
              </li>
              <li class="page-item"><a class="page-link" href="#">2</a></li>
              <li class="page-item">
                <a class="page-link" href="#">Sau</a>
              </li>
            </ul>
          </nav>
        </div>
        <!-- end .admin-content -->
      </main>
    </div>
    
    <!-- MODAL THÊM DANH MỤC -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title fw-bold">Thêm danh mục mới</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form>
              <div class="mb-3">
                <label class="form-label">Tên danh mục</label>
                <input type="text" class="form-control" placeholder="Ví dụ: Áo Vest">
              </div>
              <div class="mb-3">
                <label class="form-label">Mô tả ngắn</label>
                <textarea class="form-control" rows="3" placeholder="Mô tả danh mục..."></textarea>
              </div>
              <div class="mb-3">
                <label class="form-label">Icon / Ảnh đại diện (URL)</label>
                <input type="text" class="form-control" placeholder="https://...">
              </div>
              <div class="mb-3">
                <label class="form-label">Trạng thái</label>
                <select class="form-select">
                  <option value="active" selected>Hiển thị</option>
                  <option value="hidden">Ẩn</option>
                </select>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
            <button type="button" class="btn btn-primary">Lưu danh mục</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap 5.3.2 JS Bundle CDN -->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
      xintegrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
      crossorigin="anonymous"
    ></script>
  </body>
</html>