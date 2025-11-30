<?php include_once 'header.php' ?>

<body class="bg-light">
  <div class="d-flex">
    <!-- SIDEBAR -->
    <nav id="admin-sidebar" class="col-lg-2 col-md-3 d-none d-md-block p-0">
      <div class="position-sticky pt-3">
        <a href="index.html" class="d-block px-3 mb-3 text-white text-decoration-none fs-5">
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
      </div>
    </nav>

    <!-- CONTENT -->
    <main class="col-lg-10 col-md-9 ms-sm-auto px-0">
      <nav class="navbar navbar-expand-lg admin-top-nav shadow-sm sticky-top">
        <div class="container-fluid">
          <!-- Mobile Toggle -->
          <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#admin-sidebar">
            <span class="navbar-toggler-icon"></span>
          </button>
          <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle fs-4 align-middle"></i>
                <span class="d-none d-lg-inline align-middle ms-1">Chào, Admin</span>
              </a>
            </li>
          </ul>
        </div>
      </nav>

      <div class="admin-content">
        <!-- Breadcrumb điều hướng -->
        <nav aria-label="breadcrumb" class="mb-4">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="admin_products.html" style="color: var(--color-accent); text-decoration: none;">Sản phẩm</a></li>
            <li class="breadcrumb-item active" aria-current="page">Quản lý biến thể</li>
          </ol>
        </nav>

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
          <div>
            <h1 class="h3 mb-1 fw-bold" style="color: var(--color-text-main)">
              Biến thể sản phẩm
            </h1>
            <p class="text-muted mb-0">Sản phẩm: <strong>Áo Khoác Nam Dù (Mã: AK-001)</strong></p>
          </div>
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVariantModal">
            <i class="bi bi-plus-lg me-2"></i>Thêm biến thể mới
          </button>
        </div>

        <!-- Bảng Biến thể -->
        <div class="card shadow-sm">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover align-middle admin-table">
                <thead class="table-light">
                  <tr>
                    <th scope="col" style="width: 70px;">Ảnh</th>
                    <th scope="col">Mã SKU</th>
                    <th scope="col">Thuộc tính (Size / Màu)</th>
                    <th scope="col">Giá bán</th>
                    <th scope="col">Tồn kho</th>
                    <th scope="col" class="text-end">Hành động</th>
                  </tr>
                </thead>
                <tbody>

                  <!-- Biến thể 1 -->
                  <tr>
                    <td>
                      <img src="https://picsum.photos/id/1005/100/100" alt="Black M">
                    </td>
                    <td><strong>AK-001-BL-M</strong></td>
                    <td>
                      <span class="variant-attribute">Màu: Đen</span>
                      <span class="variant-attribute">Size: M</span>
                    </td>
                    <td>450.000đ</td>
                    <td>
                      <div class="d-flex align-items-center">
                        <span class="me-2">50</span>
                        <span class="badge bg-success" style="font-size: 0.7em;">Nhiều</span>
                      </div>
                    </td>
                    <td class="text-end">
                      <button class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></button>
                      <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </td>
                  </tr>

                  <!-- Biến thể 2 -->
                  <tr>
                    <td>
                      <img src="https://picsum.photos/id/1005/100/100" alt="Black L">
                    </td>
                    <td><strong>AK-001-BL-L</strong></td>
                    <td>
                      <span class="variant-attribute">Màu: Đen</span>
                      <span class="variant-attribute">Size: L</span>
                    </td>
                    <td>450.000đ</td>
                    <td>
                      <div class="d-flex align-items-center">
                        <span class="me-2">5</span>
                        <span class="badge bg-warning text-dark" style="font-size: 0.7em;">Sắp hết</span>
                      </div>
                    </td>
                    <td class="text-end">
                      <button class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></button>
                      <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </td>
                  </tr>

                  <!-- Biến thể 3 -->
                  <tr>
                    <td>
                      <img src="https://picsum.photos/id/1005/100/100" alt="Blue M">
                    </td>
                    <td><strong>AK-001-BU-M</strong></td>
                    <td>
                      <span class="variant-attribute">Màu: Xanh Than</span>
                      <span class="variant-attribute">Size: M</span>
                    </td>
                    <td>460.000đ <small class="text-muted">(+10k)</small></td>
                    <td>
                      <div class="d-flex align-items-center">
                        <span class="me-2">0</span>
                        <span class="badge bg-danger" style="font-size: 0.7em;">Hết hàng</span>
                      </div>
                    </td>
                    <td class="text-end">
                      <button class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></button>
                      <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </td>
                  </tr>

                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>

  <!-- MODAL THÊM BIẾN THỂ -->
  <div class="modal fade" id="addVariantModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title fw-bold">Thêm biến thể mới</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form>
            <div class="mb-3">
              <label class="form-label">Mã SKU Biến thể</label>
              <input type="text" class="form-control" placeholder="VD: AK-001-RED-XL">
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Màu sắc</label>
                <select class="form-select">
                  <option>Đen</option>
                  <option>Trắng</option>
                  <option>Xanh</option>
                  <option>Đỏ</option>
                </select>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Kích thước (Size)</label>
                <select class="form-select">
                  <option>S</option>
                  <option>M</option>
                  <option>L</option>
                  <option>XL</option>
                  <option>XXL</option>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Giá bán riêng (nếu có)</label>
                <input type="number" class="form-control" placeholder="450000">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Số lượng tồn kho</label>
                <input type="number" class="form-control" value="0">
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Ảnh đại diện biến thể</label>
              <input type="file" class="form-control">
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="button" class="btn btn-primary">Lưu biến thể</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>

</html>