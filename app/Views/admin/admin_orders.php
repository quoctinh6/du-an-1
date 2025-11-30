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
          <div class="container-fluid">
            <!-- Nút bật/tắt sidebar trên Mobile -->
            <button
              class="navbar-toggler d-md-none"
              type="button"
              data-bs-toggle="collapse"
              data-bs-target="#admin-sidebar"
              aria-controls="admin-sidebar"
              aria-expanded="false"
              aria-label="Toggle navigation"
            >
              <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Form tìm kiếm nằm giữa -->
            <form class="d-flex mx-auto" style="width: 400px">
              <input
                class="form-control me-2"
                type="search"
                placeholder="Tìm kiếm đơn hàng..."
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
                  aria-expanded="false"
                >
                  <i class="bi bi-person-circle fs-4 align-middle"></i>
                  <span class="d-none d-lg-inline align-middle ms-1"
                    >Chào, Admin</span
                  >
                </a>
                <ul
                  class="dropdown-menu dropdown-menu-end"
                  aria-labelledby="navbarDropdown"
                >
                  <li>
                    <a class="dropdown-item" href="index.html"
                      >Xem trang khách</a
                    >
                  </li>
                  <li><hr class="dropdown-divider" /></li>
                  <li>
                    <a class="dropdown-item" href="index.html">Đăng xuất</a>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </nav>

        <!-- NỘI DUNG CHÍNH CỦA TRANG -->
        <div class="admin-content">
          <!-- Tiêu đề trang -->
           <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <h1 class="h3 mb-0 fw-bold" style="color: var(--color-text-main);">Quản lý Đơn hàng</h1>
             
             <!-- SỬA: THAY BỘ LỌC SELECT BẰNG DANH SÁCH NÚT (GIỐNG TRANG CHỦ) -->
             <div class="d-flex flex-wrap gap-2 filter-buttons">
                <!-- Nút Tất cả (Đang chọn) -->
                <button class="btn btn-primary">Tất cả</button>
                
                <!-- Các nút lọc khác -->
                <button class="btn btn-outline-secondary">Đang xử lý</button>
                <button class="btn btn-outline-secondary">Đang giao</button>
                <button class="btn btn-outline-secondary">Đã giao</button>
                <button class="btn btn-outline-secondary">Đã hủy</button>
                
                <!-- Nút Xuất Excel (Tách ra 1 chút bằng ms-2) -->
                <button class="btn btn-outline-dark ms-2">
                  <i class="bi bi-download me-1"></i> Xuất Excel
                </button>
             </div>
             
          </div>
          
          <!-- Bảng Đơn Hàng -->
          <div class="card shadow-sm">
            <div class="card-body">
               <div class="table-responsive">
                  <table class="table table-hover align-middle admin-table">
                    <thead class="table-light">
                      <tr>
                        <th scope="col">Mã đơn</th>
                        <th scope="col">Khách hàng</th>
                        <th scope="col">Ngày đặt</th>
                        <th scope="col">Tổng tiền</th>
                        <th scope="col">Trạng thái</th>
                        <th scope="col" class="text-end">Hành động</th>
                      </tr>
                    </thead>
                    <tbody>
                      
                      <!-- Đơn hàng 1 -->
                      <tr>
                        <td><strong>#FS1005</strong></td>
                        <td>
                          <div>Nguyễn Văn A</div>
                          <small class="text-muted">0901234567</small>
                        </td>
                        <td>15/11/2025</td>
                        <td>1.750.000đ</td>
                        <td><span class="badge bg-success">Đã giao</span></td>
                        <td class="text-end">
                           <button class="btn btn-sm btn-outline-primary">
                             <i class="bi bi-eye"></i> Xem
                           </button>
                        </td>
                      </tr>
                      
                      <!-- Đơn hàng 2 -->
                       <tr>
                        <td><strong>#FS1004</strong></td>
                        <td>
                          <div>Trần Thị B</div>
                          <small class="text-muted">0912345678</small>
                        </td>
                        <td>10/11/2025</td>
                        <td>1.200.000đ</td>
                        <td><span class="badge bg-warning text-dark">Đang xử lý</span></td>
                        <td class="text-end">
                           <button class="btn btn-sm btn-outline-primary">
                             <i class="bi bi-eye"></i> Xem
                           </button>
                        </td>
                      </tr>
                      
                      <!-- Đơn hàng 3 -->
                       <tr>
                        <td><strong>#FS1003</strong></td>
                        <td>
                          <div>Lê Văn C</div>
                          <small class="text-muted">0987654321</small>
                        </td>
                        <td>05/11/2025</td>
                        <td>380.000đ</td>
                        <td><span class="badge bg-danger">Đã hủy</span></td>
                        <td class="text-end">
                           <button class="btn btn-sm btn-outline-primary">
                             <i class="bi bi-eye"></i> Xem
                           </button>
                        </td>
                      </tr>
                      
                      <!-- Đơn hàng 4 -->
                       <tr>
                        <td><strong>#FS1002</strong></td>
                        <td>
                          <div>Phạm Thị D</div>
                          <small class="text-muted">0933333333</small>
                        </td>
                        <td>01/11/2025</td>
                        <td>850.000đ</td>
                        <td><span class="badge bg-primary">Đang giao</span></td>
                        <td class="text-end">
                           <button class="btn btn-sm btn-outline-primary">
                             <i class="bi bi-eye"></i> Xem
                           </button>
                        </td>
                      </tr>
                      
                      <!-- Đơn hàng 5 -->
                       <tr>
                        <td><strong>#FS1001</strong></td>
                        <td>
                          <div>Vũ Đình E</div>
                          <small class="text-muted">0909090909</small>
                        </td>
                        <td>28/10/2025</td>
                        <td>650.000đ</td>
                        <td><span class="badge bg-success">Đã giao</span></td>
                        <td class="text-end">
                           <button class="btn btn-sm btn-outline-primary">
                             <i class="bi bi-eye"></i> Xem
                           </button>
                        </td>
                      </tr>
                      
                    </tbody>
                  </table>
               </div>
            </div>
          </div>
          
          <!-- Phân trang -->
          <nav aria-label="Orders pagination" class="d-flex justify-content-center mt-4">
            <ul class="pagination">
              <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Trước</a>
              </li>
              <li class="page-item active" aria-current="page">
                <a class="page-link" href="#">1</a>
              </li>
              <li class="page-item"><a class="page-link" href="#">2</a></li>
              <li class="page-item"><a class="page-link" href="#">3</a></li>
              <li class="page-item">
                <a class="page-link" href="#">Sau</a>
              </li>
            </ul>
          </nav>

        </div>
        <!-- end .admin-content -->
      </main>
      <!-- end .col-lg-10 -->
    </div>
    <!-- end .d-flex -->

    <!-- Bootstrap 5.3.2 JS Bundle CDN -->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
      xintegrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
      crossorigin="anonymous"
    ></script>
  </body>
</html>