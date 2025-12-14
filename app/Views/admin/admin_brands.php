<main class="col-lg-10 col-md-9 ms-sm-auto px-0">
  <!-- Top Navbar -->
  <nav class="navbar navbar-expand-lg admin-top-nav shadow-sm sticky-top">
    <div class="container-fluid">
      <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#admin-sidebar">
        <span class="navbar-toggler-icon"></span>
      </button>
      <form class="d-flex mx-auto" style="width: 400px" action="" method="GET">
        <input type="hidden" name="act" value="brands">
        <input class="form-control me-2" type="search" placeholder="Tìm kiếm thương hiệu..." />
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
        Quản lý Thương hiệu
      </h1>
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBrandModal">
        <i class="bi bi-plus-lg me-2"></i>Thêm thương hiệu
      </button>
    </div>

    <!-- Bảng Thương hiệu -->
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover align-middle admin-table">
            <thead class="table-light">
              <tr>
                <th scope="col" style="width: 50px;">ID</th>
                <th scope="col">Tên thương hiệu</th>
                <th scope="col">Slug (Đường dẫn)</th>
                <th scope="col">Ngày tạo</th>
                <th scope="col" class="text-end">Hành động</th>
              </tr>
            </thead>
            <tbody>
              <!-- Dữ liệu mẫu tĩnh -->
              <tr>
                <td>#1</td>
                <td>
                  <div class="fw-bold">Rolex</div>
                </td>
                <td>rolex</td>
                <td>2025-11-10</td>
                <td class="text-end">
                  <button class="btn btn-sm btn-outline-primary me-1" title="Sửa" data-bs-toggle="modal" data-bs-target="#editBrandModal">
                    <i class="bi bi-pencil"></i>
                  </button>
                  <button class="btn btn-sm btn-outline-danger" title="Xóa" onclick="return confirm('Xóa thương hiệu này?');">
                    <i class="bi bi-trash"></i>
                  </button>
                </td>
              </tr>
              <tr>
                <td>#2</td>
                <td>
                  <div class="fw-bold">Casio</div>
                </td>
                <td>casio</td>
                <td>2025-11-10</td>
                <td class="text-end">
                  <button class="btn btn-sm btn-outline-primary me-1" title="Sửa">
                    <i class="bi bi-pencil"></i>
                  </button>
                  <button class="btn btn-sm btn-outline-danger" title="Xóa">
                    <i class="bi bi-trash"></i>
                  </button>
                </td>
              </tr>
              <tr>
                <td>#3</td>
                <td>
                  <div class="fw-bold">Seiko</div>
                </td>
                <td>seiko</td>
                <td>2025-11-10</td>
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

<!-- MODAL THÊM THƯƠNG HIỆU -->
<div class="modal fade" id="addBrandModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Thêm thương hiệu mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Tên thương hiệu</label>
            <input type="text" class="form-control" name="name" required placeholder="Ví dụ: Omega">
          </div>
          <div class="mb-3">
            <label class="form-label">Slug</label>
            <input type="text" class="form-control" name="slug" placeholder="omega (để trống tự tạo)">
          </div>
          <!-- THÊM MỤC TRẠNG THÁI (Ẩn/Hiện) -->
          <div class="mb-3">
            <label class="form-label">Trạng thái</label>
            <select class="form-select" name="status">
              <option value="published" selected>Hiển thị</option>
              <option value="hidden">Ẩn</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-primary">Lưu thương hiệu</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL SỬA THƯƠNG HIỆU (Mẫu) -->
<div class="modal fade" id="editBrandModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Cập nhật thương hiệu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form>
        <div class="modal-body">
          <input type="hidden" name="id" value="1">
          <div class="mb-3">
            <label class="form-label">Tên thương hiệu</label>
            <input type="text" class="form-control" name="name" value="Rolex" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Slug</label>
            <input type="text" class="form-control" name="slug" value="rolex">
          </div>
          <!-- THÊM MỤC TRẠNG THÁI (Ẩn/Hiện) -->
          <div class="mb-3">
            <label class="form-label">Trạng thái</label>
            <select class="form-select" name="status">
              <option value="published" selected>Hiển thị</option>
              <option value="hidden">Ẩn</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>