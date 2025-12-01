<div class="admin-content">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 fw-bold" style="color: var(--color-text-main)">
      Quản lý Sản phẩm
    </h1>
    <a href="admin-add-product.html" class="btn btn-primary">
      <i class="bi bi-plus-lg me-2"></i>Thêm sản phẩm mới
    </a>
  </div>

  <!-- BỘ LỌC NÂNG CAO -->
  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <div class="row g-3">
        <!-- Lọc Danh mục -->
        <div class="col-md-3">
          <label class="form-label small text-muted mb-1">Danh mục</label>
          <select class="form-select">
            <option selected>Tất cả danh mục</option>
            <option value="1">Áo Khoác</option>
            <option value="2">Quần Jeans</option>
            <option value="3">Giày</option>
          </select>
        </div>

        <!-- Lọc Thương hiệu (MỚI) -->
        <div class="col-md-3">
          <label class="form-label small text-muted mb-1">Thương hiệu</label>
          <select class="form-select">
            <option selected>Tất cả thương hiệu</option>
            <option value="nike">Nike</option>
            <option value="adidas">Adidas</option>
            <option value="levis">Levi's</option>
            <option value="local">ZeroWatch Original</option>
          </select>
        </div>

        <!-- Lọc Tồn kho (MỚI) -->
        <div class="col-md-3">
          <label class="form-label small text-muted mb-1">Tình trạng kho</label>
          <select class="form-select">
            <option selected>Tất cả</option>
            <option value="low">Sắp hết hàng (< 10)</option>
            <option value="out">Hết hàng (0)</option>
            <option value="stock">Còn hàng</option>
          </select>
        </div>

        <!-- Lọc Trạng thái hiển thị -->
        <div class="col-md-3">
          <label class="form-label small text-muted mb-1">Trạng thái bán</label>
          <div class="d-flex gap-2">
            <select class="form-select">
              <option selected>Tất cả</option>
              <option value="1">Đang bán</option>
              <option value="2">Ẩn</option>
            </select>
            <button class="btn btn-outline-secondary" title="Lọc">
              <i class="bi bi-funnel"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bảng Sản Phẩm -->
  <div class="card shadow-sm">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover align-middle admin-table">
          <thead class="table-light">
            <tr>
              <th scope="col" style="width: 80px;">Ảnh</th>
              <th scope="col">Tên sản phẩm</th>
              <th scope="col">Thương hiệu</th> <!-- CỘT MỚI -->
              <th scope="col">Giá bán</th>
              <th scope="col">Tổng Kho</th>
              <th scope="col">Trạng thái</th>
              <th scope="col" class="text-end">Hành động</th>
            </tr>
          </thead>
          <tbody>

            <!-- Sản phẩm 1 -->
            <tr>
              <td>
                <img src="https://picsum.photos/id/1005/100/100" alt="Áo Khoác Nam Dù">
              </td>
              <td>
                <div class="fw-bold">Áo Khoác Nam Dù</div>
                <small class="text-muted">Danh mục: Áo Khoác</small>
              </td>
              <td><span class="brand-badge">ZeroWatch</span></td>
              <td>450.000đ</td>
              <td>150</td>
              <td><span class="badge bg-success">Đang bán</span></td>
              <td class="text-end">
                <!-- NÚT LINK ĐẾN TRANG BIẾN THỂ -->
                <a href="admin_variants.html" class="btn btn-sm btn-outline-info me-1"
                  title="Quản lý biến thể (Size/Màu)">
                  <i class="bi bi-layers"></i>
                </a>
                <a href="admin-edit-product.html" class="btn btn-sm btn-outline-primary me-1"
                  title="Sửa thông tin chung">
                  <i class="bi bi-pencil"></i>
                </a>
                <button class="btn btn-sm btn-outline-danger" title="Xóa">
                  <i class="bi bi-trash"></i>
                </button>
              </td>
            </tr>

            <!-- Sản phẩm 2 -->
            <tr>
              <td>
                <img src="https://picsum.photos/id/1011/100/100" alt="Quần Jeans Levi's">
              </td>
              <td>
                <div class="fw-bold">Quần Jeans Levi's 511</div>
                <small class="text-muted">Danh mục: Quần Jeans</small>
              </td>
              <td><span class="brand-badge">Levi's</span></td>
              <td>650.000đ</td>
              <td>85</td>
              <td><span class="badge bg-success">Đang bán</span></td>
              <td class="text-end">
                <a href="admin_variants.html" class="btn btn-sm btn-outline-info me-1" title="Quản lý biến thể">
                  <i class="bi bi-layers"></i>
                </a>
                <a href="admin-edit-product.html" class="btn btn-sm btn-outline-primary me-1">
                  <i class="bi bi-pencil"></i>
                </a>
                <button class="btn btn-sm btn-outline-danger">
                  <i class="bi bi-trash"></i>
                </button>
              </td>
            </tr>

            <!-- Sản phẩm 3 -->
            <tr>
              <td>
                <img src="https://picsum.photos/id/1018/100/100" alt="Áo Hoodie Nike">
              </td>
              <td>
                <div class="fw-bold">Áo Hoodie Nike Sportswear</div>
                <small class="text-muted">Danh mục: Áo Hoodie</small>
              </td>
              <td><span class="brand-badge">Nike</span></td>
              <td>850.000đ</td>
              <td>0</td>
              <td><span class="badge bg-danger">Hết hàng</span></td>
              <td class="text-end">
                <a href="admin_variants.html" class="btn btn-sm btn-outline-info me-1">
                  <i class="bi bi-layers"></i>
                </a>
                <a href="admin-edit-product.html" class="btn btn-sm btn-outline-primary me-1">
                  <i class="bi bi-pencil"></i>
                </a>
                <button class="btn btn-sm btn-outline-danger">
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
  <nav aria-label="Products pagination" class="d-flex justify-content-center mt-4">
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
</main>
</div>

<!-- Bootstrap 5.3.2 JS Bundle CDN -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
  crossorigin="anonymous"></script>
</body>

</html>