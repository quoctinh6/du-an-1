<main class="col-lg-10 col-md-9 ms-sm-auto px-0">
  <!-- Top Navbar -->
  <nav class="navbar navbar-expand-lg admin-top-nav shadow-sm sticky-top">
    <div class="container-fluid">
      <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#admin-sidebar">
        <span class="navbar-toggler-icon"></span>
      </button>
      <form class="d-flex mx-auto" style="width: 400px" action="" method="GET">
        <input type="hidden" name="act" value="account">
        <input class="form-control me-2" type="search" name="search" placeholder="Tìm kiếm tài khoản..." />
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

  <!-- NỘI DUNG CHÍNH CỦA TRANG -->
  <div class="admin-content">
    <!-- Tiêu đề trang & Nút Thêm -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1 class="h3 mb-0 fw-bold" style="color: var(--color-text-main)">
        Quản lý Tài khoản
      </h1>
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
        <i class="bi bi-person-plus-fill me-2"></i>Thêm tài khoản mới
      </button>
    </div>

    <!-- Bộ lọc nhanh -->
    <div class="card shadow-sm mb-4">
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label small text-muted">Lọc theo Quyền</label>
            <select class="form-select" aria-label="Lọc quyền">
              <option selected>Tất cả quyền</option>
              <option value="admin">Quản trị viên (Admin)</option>
              <option value="client">Khách hàng (Client)</option>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label small text-muted">Lọc trạng thái</label>
            <select class="form-select" aria-label="Lọc trạng thái">
              <option selected>Tất cả trạng thái</option>
              <option value="active">Đang hoạt động</option>
              <option value="locked">Đang bị khóa</option>
            </select>
          </div>
          <div class="col-md-4 d-flex align-items-end">
            <button class="btn btn-outline-secondary w-100">
              <i class="bi bi-funnel me-1"></i> Áp dụng bộ lọc
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Bảng Danh sách Tài khoản -->
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover align-middle admin-table">
            <thead class="table-light">
              <tr>
                <th scope="col" style="width: 70px;">Avatar</th>
                <th scope="col">Thông tin cá nhân</th>
                <th scope="col">Liên hệ</th>
                <th scope="col">Quyền hạn</th>
                <th scope="col">Trạng thái</th>
                <th scope="col" class="text-end">Hành động</th>
              </tr>
            </thead>
            <tbody>

              <!-- User 1: Admin -->
              <tr>
                <td>
                  <img src="https://ui-avatars.com/api/?name=Admin+User&background=000f38&color=fff" class="avatar-img"
                    alt="Avatar" style="width: 40px; height: 40px; border-radius: 50%;">
                </td>
                <td>
                  <div class="fw-bold">Lê Quản Trị</div>
                  <small class="text-muted">Username: admin_le</small>
                </td>
                <td>
                  <div>admin@zerowatch.com</div>
                  <small class="text-muted">0909.111.222</small>
                </td>
                <td>
                  <span class="badge badge-role-admin rounded-pill">
                    <i class="bi bi-shield-lock-fill me-1"></i> Admin
                  </span>
                </td>
                <td><span class="badge bg-success bg-opacity-10 text-success">Hoạt động</span></td>
                <td class="text-end">
                  <button class="btn btn-sm btn-outline-primary me-1" title="Sửa thông tin">
                    <i class="bi bi-pencil"></i>
                  </button>
                  <button class="btn btn-sm btn-outline-secondary" title="Đổi quyền">
                    <i class="bi bi-arrow-repeat"></i>
                  </button>
                </td>
              </tr>

              <!-- User 2: Client -->
              <tr>
                <td>
                  <img src="https://ui-avatars.com/api/?name=Nguyen+Van+A&background=random" class="avatar-img"
                    alt="Avatar" style="width: 40px; height: 40px; border-radius: 50%;">
                </td>
                <td>
                  <div class="fw-bold">Nguyễn Văn A</div>
                  <small class="text-muted">Username: anv_client</small>
                </td>
                <td>
                  <div>nguyenvana@gmail.com</div>
                  <small class="text-muted">0912.345.678</small>
                </td>
                <td>
                  <span class="badge badge-role-client rounded-pill">
                    Client
                  </span>
                </td>
                <td><span class="badge bg-success bg-opacity-10 text-success">Hoạt động</span></td>
                <td class="text-end">
                  <button class="btn btn-sm btn-outline-primary me-1" title="Sửa thông tin">
                    <i class="bi bi-pencil"></i>
                  </button>
                  <button class="btn btn-sm btn-outline-danger" title="Khóa tài khoản">
                    <i class="bi bi-lock"></i>
                  </button>
                </td>
              </tr>

              <!-- User 3: Client (Locked) -->
              <tr>
                <td>
                  <img src="https://ui-avatars.com/api/?name=Tran+Thi+B&background=random" class="avatar-img"
                    alt="Avatar" style="width: 40px; height: 40px; border-radius: 50%;">
                </td>
                <td>
                  <div class="fw-bold">Trần Thị B</div>
                  <small class="text-muted">Username: beb_tran</small>
                </td>
                <td>
                  <div>tranthib@yahoo.com</div>
                  <small class="text-muted">0988.777.666</small>
                </td>
                <td>
                  <span class="badge badge-role-client rounded-pill">
                    Client
                  </span>
                </td>
                <td><span class="badge bg-danger bg-opacity-10 text-danger">Đã khóa</span></td>
                <td class="text-end">
                  <button class="btn btn-sm btn-outline-primary me-1" title="Sửa thông tin">
                    <i class="bi bi-pencil"></i>
                  </button>
                  <button class="btn btn-sm btn-outline-success" title="Mở khóa">
                    <i class="bi bi-unlock"></i>
                  </button>
                </td>
              </tr>

              <!-- User 4: Admin (Khác) -->
              <tr>
                <td>
                  <img src="https://ui-avatars.com/api/?name=Hoang+Quan+Ly&background=000f38&color=fff" class="avatar-img"
                    alt="Avatar" style="width: 40px; height: 40px; border-radius: 50%;">
                </td>
                <td>
                  <div class="fw-bold">Hoàng Quản Lý</div>
                  <small class="text-muted">Username: hoang_mod</small>
                </td>
                <td>
                  <div>hoang@zerowatch.com</div>
                  <small class="text-muted">0901.000.999</small>
                </td>
                <td>
                  <span class="badge badge-role-admin rounded-pill">
                    <i class="bi bi-shield-lock-fill me-1"></i> Admin
                  </span>
                </td>
                <td><span class="badge bg-success bg-opacity-10 text-success">Hoạt động</span></td>
                <td class="text-end">
                  <button class="btn btn-sm btn-outline-primary me-1" title="Sửa thông tin">
                    <i class="bi bi-pencil"></i>
                  </button>
                  <button class="btn btn-sm btn-outline-secondary" title="Đổi quyền">
                    <i class="bi bi-arrow-repeat"></i>
                  </button>
                </td>
              </tr>

            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Phân trang -->
    <nav aria-label="Account pagination" class="d-flex justify-content-center mt-4">
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
<!-- MODAL THÊM TÀI KHOẢN -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold" id="addUserModalLabel">Thêm tài khoản mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          <div class="mb-3">
            <label for="userName" class="form-label">Họ và tên</label>
            <input type="text" class="form-control" id="userName" placeholder="Nhập họ tên đầy đủ">
          </div>
          <div class="mb-3">
            <label for="userEmail" class="form-label">Email</label>
            <input type="email" class="form-control" id="userEmail" placeholder="name@example.com">
          </div>
          <div class="mb-3">
            <label for="userPhone" class="form-label">Số điện thoại</label>
            <input type="tel" class="form-control" id="userPhone" placeholder="09xxxxxxxx">
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="userRole" class="form-label">Phân quyền</label>
              <select class="form-select" id="userRole">
                <option value="client" selected>Khách hàng (Client)</option>
                <option value="admin">Quản trị viên (Admin)</option>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label for="userStatus" class="form-label">Trạng thái</label>
              <select class="form-select" id="userStatus">
                <option value="active" selected>Hoạt động</option>
                <option value="locked">Khóa</option>
              </select>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
        <button type="button" class="btn btn-primary">Lưu tài khoản</button>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap 5.3.2 JS Bundle CDN -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
  xintegrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>