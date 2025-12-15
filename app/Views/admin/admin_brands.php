<main class="col-lg-10 col-md-9 ms-sm-auto px-0">
  <nav class="navbar navbar-expand-lg admin-top-nav shadow-sm sticky-top">
    <div class="container-fluid">
      <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#admin-sidebar">
        <span class="navbar-toggler-icon"></span>
      </button>
      
      <form class="d-flex mx-auto" style="width: 400px" action="<?= BASE_URL ?>index.php/admin/brands" method="GET">
        <input class="form-control me-2" type="search" name="search" 
               value="<?= htmlspecialchars($currentSearch ?? '') ?>" placeholder="Tìm kiếm thương hiệu..." />
        <button type="submit" class="btn btn-outline-secondary" title="Tìm kiếm">
            <i class="bi bi-search"></i>
        </button>
        <a href="<?= BASE_URL ?>index.php/admin/brands" class="btn btn-outline-danger ms-1" title="Đặt lại">
            <i class="bi bi-x-lg"></i>
        </a>
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
        Quản lý Thương hiệu
      </h1>
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBrandModal">
        <i class="bi bi-plus-lg me-2"></i>Thêm thương hiệu
      </button>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($error) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($success) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

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
                <th scope="col">Số SP</th>
                <th scope="col">Trạng thái</th>
                <th scope="col" class="text-end">Hành động</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($brands)): ?>
                <?php foreach ($brands as $brand): ?>
                  
                  <div class="modal fade" id="editBrandModal_<?= $brand['id'] ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title fw-bold">Cập nhật: <?= htmlspecialchars($brand['name']) ?></h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="POST" action="<?= BASE_URL ?>index.php/admin/updateBrand">
                          <div class="modal-body">
                            <input type="hidden" name="id" value="<?= $brand['id'] ?>">
                            <div class="mb-3">
                              <label class="form-label">Tên thương hiệu</label>
                              <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($brand['name']) ?>" required>
                            </div>
                            <div class="mb-3">
                              <label class="form-label">Slug</label>
                              <input type="text" class="form-control" name="slug" value="<?= htmlspecialchars($brand['slug']) ?>" placeholder="tự động tạo nếu để trống">
                            </div>
                            <div class="mb-3">
                              <label class="form-label">Trạng thái</label>
                              <select class="form-select" name="status">
                                <option value="published" <?= $brand['status'] == 'published' ? 'selected' : '' ?>>Hiển thị</option>
                                <option value="hidden" <?= $brand['status'] == 'hidden' ? 'selected' : '' ?>>Ẩn</option>
                              </select>
                            </div>
                            <p class="small text-muted mt-3 mb-0">Đang có <strong><?= $brand['product_count'] ?? 0 ?></strong> sản phẩm thuộc thương hiệu này.</p>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" name="btn_update_brand" class="btn btn-primary">Lưu thay đổi</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                  <tr>
                    <td>#<?= $brand['id'] ?></td>
                    <td>
                      <div class="fw-bold"><?= htmlspecialchars($brand['name']) ?></div>
                    </td>
                    <td><?= htmlspecialchars($brand['slug']) ?></td>
                    <td><?= date('Y-m-d', strtotime($brand['created_at'])) ?></td>
                    <td>
                        <?= $brand['product_count'] ?? 0 ?>
                    </td>
                    <td>
                      <span class="badge <?= $brand['status'] == 'published' ? 'bg-success' : 'bg-secondary' ?>">
                        <?= $brand['status'] == 'published' ? 'Hiển thị' : 'Đang ẩn' ?>
                      </span>
                    </td>
                    <td class="text-end">
                      <button class="btn btn-sm btn-outline-primary me-1" title="Sửa" 
                              data-bs-toggle="modal" data-bs-target="#editBrandModal_<?= $brand['id'] ?>">
                        <i class="bi bi-pencil"></i>
                      </button>
                      </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="7" class="text-center py-4 text-muted">
                      Không tìm thấy thương hiệu nào.
                      <?php if(!empty($currentSearch)): ?>
                          <br>Vui lòng thử tìm kiếm lại hoặc <a href="<?= BASE_URL ?>index.php/admin/brands">xóa bộ lọc</a>.
                      <?php endif; ?>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    
    <nav aria-label="Brands pagination" class="d-flex justify-content-center mt-4">
      <ul class="pagination">
        <?php 
            $totalPages = $totalPages ?? 1;
            $currentPage = $currentPage ?? 1;
            $currentSearch = $currentSearch ?? '';
        ?>
        
        <?php if ($totalPages > 1): ?>
            <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="<?= BASE_URL ?>index.php/admin/brands?page=<?= max(1, $currentPage - 1) ?>&search=<?= $currentSearch ?>">Trước</a>
            </li>
            
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                    <a class="page-link" href="<?= BASE_URL ?>index.php/admin/brands?page=<?= $i ?>&search=<?= $currentSearch ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                <a class="page-link" href="<?= BASE_URL ?>index.php/admin/brands?page=<?= min($totalPages, $currentPage + 1) ?>&search=<?= $currentSearch ?>">Sau</a>
            </li>
        <?php endif; ?>
      </ul>
    </nav>
  </div>
</main>

<div class="modal fade" id="addBrandModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Thêm thương hiệu mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" action="<?= BASE_URL ?>index.php/admin/addBrand">
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Tên thương hiệu</label>
            <input type="text" class="form-control" name="name" required placeholder="Ví dụ: Omega">
          </div>
          <div class="mb-3">
            <label class="form-label">Slug</label>
            <input type="text" class="form-control" name="slug" placeholder="omega (để trống tự tạo)">
          </div>
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
          <button type="submit" name="btn_add_brand" class="btn btn-primary">Lưu thương hiệu</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>