<main class="col-lg-10 col-md-9 ms-sm-auto px-0">
  <nav class="navbar navbar-expand-lg admin-top-nav shadow-sm sticky-top">
    <div class="container-fluid">
      <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#admin-sidebar">
        <span class="navbar-toggler-icon"></span>
      </button>
      <form class="d-flex mx-auto" style="width: 400px" action="" method="GET">
        <input type="hidden" name="act" value="products">
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

    <div class="card shadow-sm mb-4">
      <div class="card-body">
        <form action="" method="GET">
          <input type="hidden" name="act" value="products">

          <div class="row g-3">
            <div class="col-md-3">
              <label class="form-label small text-muted mb-1">Danh mục</label>
              <select class="form-select" name="cate_id">
                <option value="">Tất cả danh mục</option>
                <?php if (!empty($categoriesAll)): ?>
                  <?php foreach ($categoriesAll as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= (isset($_GET['cate_id']) && $_GET['cate_id'] == $cat['id']) ? 'selected' : '' ?>>
                      <?= $cat['name'] ?>
                    </option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>

            <div class="col-md-3">
              <label class="form-label small text-muted mb-1">Thương hiệu</label>
              <select class="form-select" name="brand_id">
                <option value="">Tất cả thương hiệu</option>
                <?php if (!empty($brandsAll)): ?>
                  <?php foreach ($brandsAll as $brand): ?>
                    <option value="<?= $brand['id'] ?>" <?= (isset($_GET['brand_id']) && $_GET['brand_id'] == $brand['id']) ? 'selected' : '' ?>>
                      <?= $brand['name'] ?>
                    </option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>

            <div class="col-md-3">
              <label class="form-label small text-muted mb-1">Tình trạng kho</label>
              <select class="form-select" name="stock">
                <option value="">Tất cả</option>
                <option value="low" <?= (isset($_GET['stock']) && $_GET['stock'] == 'low') ? 'selected' : '' ?>>Sắp hết
                  hàng (< 10)</option>
                <option value="out" <?= (isset($_GET['stock']) && $_GET['stock'] == 'out') ? 'selected' : '' ?>>Hết hàng
                  (0)</option>
                <option value="in" <?= (isset($_GET['stock']) && $_GET['stock'] == 'in') ? 'selected' : '' ?>>Còn hàng
                  (>=10)</option>
              </select>
            </div>

            <div class="col-md-3">
              <label class="form-label small text-muted mb-1">Trạng thái bán</label>
              <div class="d-flex gap-2">
                <select class="form-select" name="status">
                  <option value="">Tất cả</option>
                  <option value="published" <?= (isset($_GET['status']) && $_GET['status'] == 'published') ? 'selected' : '' ?>>
                    Đang bán</option>
                  <option value="draft" <?= (isset($_GET['status']) && $_GET['status'] == 'draft') ? 'selected' : '' ?>>
                    Ẩn</option>
                </select>
                <button type="submit" class="btn btn-outline-secondary" title="Lọc">
                  <i class="bi bi-funnel"></i>
                </button>
                <a href="?act=products" class="btn btn-outline-danger" title="Xóa lọc">
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
                      // ⚠️ FIX: Sử dụng đường dẫn đầy đủ từ BASE_URL + thư mục + tên file (Controller chỉ lưu tên file)
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
                return BASE_URL . "index.php/admin/products?page=" . $page . "&" . $queryString;
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