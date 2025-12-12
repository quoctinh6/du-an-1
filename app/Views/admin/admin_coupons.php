<main class="col-lg-10 col-md-9 ms-sm-auto px-0">
  <!-- Top Navbar -->
  <nav class="navbar navbar-expand-lg admin-top-nav shadow-sm sticky-top">
    <div class="container-fluid">
      <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#admin-sidebar">
        <span class="navbar-toggler-icon"></span>
      </button>
      <form class="d-flex mx-auto" style="width: 400px" action="" method="GET">
        <input type="hidden" name="act" value="coupons">
        <input class="form-control me-2" type="search" placeholder="Tìm mã giảm giá..." />
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
        Quản lý Mã giảm giá
      </h1>
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCouponModal">
        <i class="bi bi-ticket-perforated-fill me-2"></i>Tạo mã mới
      </button>
    </div>

    <!-- Bộ lọc -->
    <div class="card shadow-sm mb-4">
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-4">
            <select class="form-select">
              <option selected>Loại giảm giá</option>
              <option value="percent">Phần trăm (%)</option>
              <option value="fixed">Số tiền cố định</option>
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

    <!-- Bảng Coupon -->
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover align-middle admin-table">
            <thead class="table-light">
              <tr>
                <th scope="col">Mã Code</th>
                <th scope="col">Loại</th>
                <th scope="col">Giá trị</th>
                <th scope="col">Giới hạn dùng</th>
                <th scope="col">Ngày hết hạn</th>
                <th scope="col">Trạng thái</th>
                <th scope="col" class="text-end">Hành động</th>
              </tr>
            </thead>
            <tbody>
              <!-- Dữ liệu mẫu -->
              <tr>
                <td>
                  <span class="badge bg-light text-dark border border-secondary px-3 py-2 fw-bold">WELCOME2025</span>
                </td>
                <td>Phần trăm</td>
                <td class="text-danger fw-bold">10%</td>
                <td>100</td>
                <td>2025-12-31</td>
                <td><span class="badge bg-success">Đang hiệu lực</span></td>
                <td class="text-end">
                  <button class="btn btn-sm btn-outline-primary me-1" title="Sửa" data-bs-toggle="modal" data-bs-target="#editCouponModal">
                    <i class="bi bi-pencil"></i>
                  </button>
                  <button class="btn btn-sm btn-outline-danger" title="Xóa">
                    <i class="bi bi-trash"></i>
                  </button>
                </td>
              </tr>
              <tr>
                <td>
                  <span class="badge bg-light text-dark border border-secondary px-3 py-2 fw-bold">GIAM50K</span>
                </td>
                <td>Cố định</td>
                <td class="text-danger fw-bold">50.000đ</td>
                <td>50</td>
                <td>2025-06-01</td>
                <td><span class="badge bg-warning text-dark">Sắp hết hạn</span></td>
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
  </div>
</main>

<!-- MODAL THÊM COUPON -->
<div class="modal fade" id="addCouponModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Tạo mã giảm giá mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Mã Code (Viết liền, không dấu)</label>
            <input type="text" class="form-control text-uppercase" name="code" placeholder="VD: SALEHE2025" required>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Loại giảm giá</label>
              <select class="form-select" name="type">
                <option value="percent">Phần trăm (%)</option>
                <option value="fixed">Số tiền (VND)</option>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Giá trị giảm</label>
              <input type="number" class="form-control" name="value" placeholder="VD: 10 hoặc 50000" required>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Số lượng giới hạn</label>
              <input type="number" class="form-control" name="usage_limit" placeholder="VD: 100">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Ngày hết hạn</label>
              <input type="date" class="form-control" name="expires_at">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-primary">Tạo mã</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>