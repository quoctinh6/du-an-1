<main class="col-lg-10 col-md-9 ms-sm-auto px-0">
  <nav class="navbar navbar-expand-lg admin-top-nav shadow-sm sticky-top">
    <div class="container-fluid">
      <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#admin-sidebar">
        <span class="navbar-toggler-icon"></span>
      </button>
      <form class="d-flex mx-auto" style="width: 400px" action="<?= BASE_URL ?>index.php/admin/products" method="GET">
        <input class="form-control me-2" type="search" name="search"
          value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" placeholder="Tìm kiếm sản phẩm..." />
        <button class="btn btn-outline-success" type="submit">
          <i class="bi bi-search"></i>
        </button>
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

  <div class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1 class="h3 mb-0 fw-bold" style="color: var(--color-text-main)">
        Quản lý Sản phẩm
      </h1>
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
        <i class="bi bi-plus-lg me-2"></i>Thêm sản phẩm mới
      </button>
    </div>

    <?php if (!empty($_SESSION['error_admin'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['error_admin']); unset($_SESSION['error_admin']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['success_admin'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['success_admin']); unset($_SESSION['success_admin']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm mb-4">
      <div class="card-body">
        <form action="<?= BASE_URL ?>index.php/admin/products" method="GET">

          <div class="row g-3">
            <div class="col-md-3">
              <label class="form-label small text-muted mb-1">Danh mục</label>
              <select class="form-select" name="cate_id">
                <option value="">Tất cả danh mục</option>
                <?php $currentCateId = $_GET['cate_id'] ?? ''; ?>
                <?php if (!empty($categoriesAll)): ?>
                  <?php foreach ($categoriesAll as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= ($currentCateId == $cat['id']) ? 'selected' : '' ?>>
                      <?= htmlspecialchars($cat['name']) ?>
                    </option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>

            <div class="col-md-3">
              <label class="form-label small text-muted mb-1">Thương hiệu</label>
              <select class="form-select" name="brand_id">
                <option value="">Tất cả thương hiệu</option>
                <?php $currentBrandId = $_GET['brand_id'] ?? ''; ?>
                <?php if (!empty($brandsAll)): ?>
                  <?php foreach ($brandsAll as $brand): ?>
                    <option value="<?= $brand['id'] ?>" <?= ($currentBrandId == $brand['id']) ? 'selected' : '' ?>>
                      <?= htmlspecialchars($brand['name']) ?>
                    </option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>

            <div class="col-md-3">
              <label class="form-label small text-muted mb-1">Tình trạng kho</label>
              <select class="form-select" name="stock">
                <option value="">Tất cả</option>
                <?php $currentStock = $_GET['stock'] ?? ''; ?>
                <option value="low" <?= ($currentStock == 'low') ? 'selected' : '' ?>>Sắp hết
                  hàng (< 10)</option>
                <option value="out" <?= ($currentStock == 'out') ? 'selected' : '' ?>>Hết hàng
                  (0)</option>
                <option value="in" <?= ($currentStock == 'in') ? 'selected' : '' ?>>Còn hàng
                  (>=10)</option>
              </select>
            </div>

            <div class="col-md-3">
              <label class="form-label small text-muted mb-1">Trạng thái bán</label>
              <div class="d-flex gap-2">
                <select class="form-select" name="status">
                  <option value="">Tất cả</option>
                  <?php $currentStatus = $_GET['status'] ?? ''; ?>
                  <option value="published" <?= ($currentStatus == 'published') ? 'selected' : '' ?>>
                    Đang bán</option>
                  <option value="draft" <?= ($currentStatus == 'draft') ? 'selected' : '' ?>>
                    Ẩn</option>
                </select>
                <button type="submit" class="btn btn-outline-secondary" title="Lọc">
                  <i class="bi bi-funnel"></i>
                </button>
                <a href="<?= BASE_URL ?>index.php/admin/products" class="btn btn-outline-danger" title="Xóa lọc">
                  <i class="bi bi-x-lg"></i>
                </a>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="card shadow-sm">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover align-middle admin-table">
            <thead class="table-light">
              <tr>
                <th scope="col" style="width: 80px;">Ảnh</th>
                <th scope="col">Tên sản phẩm</th>
                <th scope="col">Thương hiệu</th>
                <th scope="col">Giá thấp nhất</th>
                <th scope="col">Tổng Kho</th>
                <th scope="col">Trạng thái</th>
                <th scope="col" class="text-end">Hành động</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($products)): ?>
                <?php foreach ($products as $item): ?>
                  <tr>
                    <td>
                      <?php
                      $imgUrl = !empty($item['image_url']) ? BASE_URL . "uploads/products/" . $item['image_url'] : 'https://placehold.co/50x50?text=No+Img';
                      ?>
                      <img
                        src="<?= $imgUrl ?>"
                        alt="<?= htmlspecialchars($item['name']) ?>" class="shadow-sm"
                        style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px; border: 1px solid #eee;">
                    </td>
                    <td>
                      <div class="fw-bold"><?= htmlspecialchars($item['name']) ?></div>
                      <small class="text-muted">Danh mục: <?= htmlspecialchars($item['category_name'] ?? $item['category_id']) ?></small>
                    </td>
                    <td>
                      <span class="badge bg-light text-dark border">
                        <?= htmlspecialchars($item['brand_name'] ?? $item['brand_id']) ?>
                      </span>
                    </td>
                    <td class="text-danger fw-bold">
                      <?= number_format($item['price'] ?? 0, 0, ',', '.') ?>đ
                    </td>
                    <td>
                      <span class="badge bg-light text-dark border">
                        <?= !empty($item['total_stock']) ? $item['total_stock'] : 0 ?>
                      </span>
                    </td>
                    <td>
                      <?php if ($item['status'] == 'published'): ?>
                        <span class="badge bg-success">Đang bán</span>
                      <?php else: ?>
                        <span class="badge bg-secondary">Ẩn</span>
                      <?php endif; ?>
                    </td>
                    <td class="text-end">
                      <a href="<?= BASE_URL ?>index.php/admin/variants?product_id=<?= $item['id'] ?>"
                        class="btn btn-sm btn-outline-info me-1" title="Quản lý biến thể (Size/Màu)">
                        <i class="bi bi-layers"></i>
                      </a>

                      <button type="button" class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal"
                        data-bs-target="#editProductModal_<?= $item['id'] ?>" title="Sửa thông tin">
                        <i class="bi bi-pencil"></i>
                      </button>
                    </td>
                  </tr>

                  <div class="modal fade" id="editProductModal_<?= $item['id'] ?>" tabindex="-1" aria-hidden="true">
                      <div class="modal-dialog modal-lg">
                          <div class="modal-content">
                              <div class="modal-header">
                                  <h5 class="modal-title fw-bold">Sửa Sản phẩm: <?= htmlspecialchars($item['name']) ?></h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <form action="<?= BASE_URL ?>index.php/admin/updateProduct" method="POST" enctype="multipart/form-data">
                                  <div class="modal-body">
                                      <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                      
                                      <div class="row">
                                          <div class="col-md-8">
                                              <div class="mb-3">
                                                  <label class="form-label">Tên sản phẩm</label>
                                                  <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($item['name']) ?>" required>
                                              </div>
                                              <div class="mb-3">
                                                  <label class="form-label">Slug (Đường dẫn)</label>
                                                  <input type="text" class="form-control" name="slug" value="<?= htmlspecialchars($item['slug']) ?>" placeholder="Tự động tạo nếu để trống">
                                              </div>
                                              <div class="mb-3">
                                                  <label class="form-label">Mô tả sản phẩm</label>
                                                  <textarea class="form-control" name="description" rows="3"><?= htmlspecialchars($item['description'] ?? '') ?></textarea>
                                              </div>
                                          </div>
                                          <div class="col-md-4">
                                              <div class="mb-3">
                                                  <label class="form-label">Danh mục</label>
                                                  <select class="form-select" name="category_id">
                                                      <?php foreach ($categoriesAll as $cat): ?>
                                                          <option value="<?= $cat['id'] ?>" <?= ($item['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                                                              <?= htmlspecialchars($cat['name']) ?>
                                                          </option>
                                                      <?php endforeach; ?>
                                                  </select>
                                              </div>
                                              <div class="mb-3">
                                                  <label class="form-label">Thương hiệu</label>
                                                  <select class="form-select" name="brand_id">
                                                      <?php foreach ($brandsAll as $brand): ?>
                                                          <option value="<?= $brand['id'] ?>" <?= ($item['brand_id'] == $brand['id']) ? 'selected' : '' ?>>
                                                              <?= htmlspecialchars($brand['name']) ?>
                                                          </option>
                                                      <?php endforeach; ?>
                                                  </select>
                                              </div>
                                              <div class="mb-3">
                                                  <label class="form-label">Trạng thái bán</label>
                                                  <select class="form-select" name="status">
                                                      <option value="published" <?= ($item['status'] == 'published') ? 'selected' : '' ?>>Đang bán</option>
                                                      <option value="draft" <?= ($item['status'] == 'draft') ? 'selected' : '' ?>>Ẩn (Bản nháp)</option>
                                                  </select>
                                              </div>
                                              <div class="mb-3">
                                                  <label class="form-label">Ảnh đại diện (Đổi mới)</label>
                                                  <input type="file" class="form-control" name="image" accept="image/*">
                                                  <small class="text-muted d-block mt-1">Ảnh hiện tại: <a href="<?= $imgUrl ?>" target="_blank">Xem</a></small>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="modal-footer">
                                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                      <button type="submit" name="btn_update" class="btn btn-primary">Lưu Thay Đổi</button>
                                  </div>
                              </form>
                          </div>
                      </div>
                  </div>
                  <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="7" class="text-center py-4">Không tìm thấy sản phẩm nào.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <nav aria-label="Products pagination" class="d-flex justify-content-center mt-4">
    <ul class="pagination">
        <?php 
            $currentPage = $currentPage ?? 1;
            $totalPages = $totalPages ?? 1;
            
            // Lấy các tham số lọc/tìm kiếm hiện tại để truyền vào URL phân trang
            $currentParams = $_GET;
            unset($currentParams['page']);
            $queryString = http_build_query($currentParams);

            function getProductPaginationUrl($page, $queryString) {
                // Đảm bảo không có dấu & thừa nếu queryString rỗng
                return BASE_URL . "index.php/admin/products?page=" . $page . (empty($queryString) ? '' : '&' . $queryString);
            }
        ?>

        <?php if ($totalPages > 1): ?>
            <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                <a class="page-link" 
                   href="<?= getProductPaginationUrl(max(1, $currentPage - 1), $queryString) ?>">
                   Trước
                </a>
            </li>
            
            <?php 
                // Hiển thị tối đa 5 trang gần trang hiện tại
                $startPage = max(1, $currentPage - 2);
                $endPage = min($totalPages, $currentPage + 2);

                if ($startPage > 1) { echo '<li class="page-item disabled"><span class="page-link">...</span></li>'; }
            ?>

            <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                    <a class="page-link" 
                       href="<?= getProductPaginationUrl($i, $queryString) ?>">
                       <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>

            <?php 
                if ($endPage < $totalPages) { echo '<li class="page-item disabled"><span class="page-link">...</span></li>'; }
            ?>

            <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                <a class="page-link" 
                   href="<?= getProductPaginationUrl(min($totalPages, $currentPage + 1), $queryString) ?>">
                   Sau
                </a>
            </li>
        <?php endif; ?>
    </ul>
</nav>
</div>
</main>

<div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Thêm Sản phẩm mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?= BASE_URL ?>index.php/admin/addProduct" method="POST" enctype="multipart/form-data">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-8">
              <div class="mb-3">
                <label class="form-label">Tên sản phẩm</label>
                <input type="text" class="form-control" name="name" required placeholder="Ví dụ: Đồng hồ G-Shock GA-2100">
              </div>
              <div class="mb-3">
                <label class="form-label">Slug (Đường dẫn)</label>
                <input type="text" class="form-control" name="slug" placeholder="Tự động tạo nếu để trống">
              </div>
              <div class="mb-3">
                <label class="form-label">Mô tả sản phẩm</label>
                <textarea class="form-control" name="description" rows="3" placeholder="Chi tiết sản phẩm..."></textarea>
              </div>
            </div>
            <div class="col-md-4">
              <div class="mb-3">
                <label class="form-label">Danh mục</label>
                <select class="form-select" name="category_id" required>
                  <option value="" selected disabled>Chọn danh mục</option>
                  <?php if (!empty($categoriesAll)): ?>
                    <?php foreach ($categoriesAll as $cat): ?>
                      <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label">Thương hiệu</label>
                <select class="form-select" name="brand_id" required>
                  <option value="" selected disabled>Chọn thương hiệu</option>
                  <?php if (!empty($brandsAll)): ?>
                    <?php foreach ($brandsAll as $brand): ?>
                      <option value="<?= $brand['id'] ?>"><?= htmlspecialchars($brand['name']) ?></option>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label">Trạng thái bán</label>
                <select class="form-select" name="status">
                  <option value="published" selected>Đang bán</option>
                  <option value="draft">Ẩn (Bản nháp)</option>
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label">Ảnh đại diện</label>
                <input type="file" class="form-control" name="image" accept="image/*" required>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" name="btn_add" class="btn btn-primary">Thêm & Quản lý Biến thể</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>