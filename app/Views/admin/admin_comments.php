<main class="col-lg-10 col-md-9 ms-sm-auto px-0">
  <!-- Top Navbar -->
  <nav class="navbar navbar-expand-lg admin-top-nav shadow-sm sticky-top">
    <div class="container-fluid">
      <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#admin-sidebar">
        <span class="navbar-toggler-icon"></span>
      </button>
      <form class="d-flex mx-auto" style="width: 400px" action="" method="GET">
        <input type="hidden" name="act" value="comments">
        <input class="form-control me-2" type="search" placeholder="Tìm nội dung bình luận..." />
      </form>

      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
            <i class="bi bi-person-circle fs-4 align-middle"></i>
            <span class="d-none d-lg-inline align-middle ms-1">Chào, Admin</span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="#">Đăng xuất</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>

  <!-- NỘI DUNG CHÍNH -->
  <div class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1 class="h3 mb-0 fw-bold" style="color: var(--color-text-main)">
        Quản lý Bình luận & Đánh giá
      </h1>
      <!-- Không có nút thêm vì bình luận do user tạo -->
    </div>

    <!-- Bộ lọc -->
    <div class="card shadow-sm mb-4">
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-4">
            <select class="form-select">
              <option selected>Lọc theo số sao</option>
              <option value="5">5 Sao (Tuyệt vời)</option>
              <option value="4">4 Sao (Tốt)</option>
              <option value="3">3 Sao (Bình thường)</option>
              <option value="2">2 Sao (Tệ)</option>
              <option value="1">1 Sao (Rất tệ)</option>
            </select>
          </div>
          <div class="col-md-4 text-md-end">
            <button class="btn btn-outline-secondary w-100 w-md-auto">
              <i class="bi bi-funnel me-1"></i> Lọc
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Bảng Bình luận -->
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover align-middle admin-table">
            <thead class="table-light">
              <tr>
                <th scope="col" style="width: 150px;">Người dùng</th>
                <th scope="col">Sản phẩm</th>
                <th scope="col" style="width: 40%;">Nội dung</th>
                <th scope="col">Đánh giá</th>
                <th scope="col">Ngày đăng</th>
                <th scope="col" class="text-end">Hành động</th>
              </tr>
            </thead>
            <tbody>
              <!-- Dữ liệu mẫu 1 -->
              <tr>
                <td>
                  <div class="fw-bold">Nguyễn Văn A</div>
                  <small class="text-muted">ID: 1</small>
                </td>
                <td>
                  <a href="#" class="text-decoration-none text-dark fw-bold">Rolex Submariner Date</a>
                </td>
                <td>
                  <p class="mb-0 text-truncate" style="max-width: 300px;">
                    Đồng hồ rất đẹp, giao hàng nhanh, đóng gói cẩn thận. Sẽ ủng hộ shop lần sau!
                  </p>
                </td>
                <td>
                  <div class="text-warning">
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                  </div>
                </td>
                <td>2025-11-10</td>
                <td class="text-end">
                  <button class="btn btn-sm btn-outline-danger" title="Xóa bình luận" onclick="return confirm('Xóa bình luận này?');">
                    <i class="bi bi-trash"></i>
                  </button>
                </td>
              </tr>

              <!-- Dữ liệu mẫu 2 -->
              <tr>
                <td>
                  <div class="fw-bold">Trần Thị B</div>
                  <small class="text-muted">ID: 2</small>
                </td>
                <td>
                  <a href="#" class="text-decoration-none text-dark fw-bold">Casio G-Shock</a>
                </td>
                <td>
                  <p class="mb-0 text-truncate" style="max-width: 300px;">
                    Hàng ổn trong tầm giá, nhưng dây đeo hơi cứng một chút.
                  </p>
                </td>
                <td>
                  <div class="text-warning">
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill text-muted opacity-25"></i> <!-- Sao xám -->
                    <i class="bi bi-star-fill text-muted opacity-25"></i>
                  </div>
                </td>
                <td>2025-11-12</td>
                <td class="text-end">
                  <button class="btn btn-sm btn-outline-danger" title="Xóa bình luận">
                    <i class="bi bi-trash"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    
    <!-- Pagination -->
    <nav aria-label="Comments pagination" class="d-flex justify-content-center mt-4">
      <ul class="pagination">
        <li class="page-item disabled"><a class="page-link" href="#">Trước</a></li>
        <li class="page-item active"><a class="page-link" href="#">1</a></li>
        <li class="page-item"><a class="page-link" href="#">Sau</a></li>
      </ul>
    </nav>
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>