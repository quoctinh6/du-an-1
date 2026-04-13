<main class="col-lg-10 col-md-9 ms-sm-auto px-0">
  <nav class="navbar navbar-expand-lg admin-top-nav shadow-sm sticky-top">
    <div class="container-fluid">
      <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#admin-sidebar">
        <span class="navbar-toggler-icon"></span>
      </button>
      <form class="d-flex mx-auto" style="width: 400px" action="" method="GET">
        <input type="hidden" name="act" value="categories">
        <input class="form-control me-2" type="search" name="search" 
               value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" 
               placeholder="Tìm kiếm danh mục..." />
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
        Quản lý Danh mục
      </h1>
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
        <i class="bi bi-plus-lg me-2"></i>Thêm danh mục mới
      </button>
    </div>

    <!-- Bảng Danh mục -->
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover align-middle admin-table">
            <thead class="table-light">
              <tr>
                <th scope="col">ID</th>
                <th scope="col">Tên danh mục</th>
                <th scope="col">Slug</th>
                <th scope="col" class="text-center">Số lượng SP</th>
                <th scope="col">Trạng thái</th>
                <th scope="col" class="text-end">Hành động</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $cat): ?>
                  <tr>
                    <td>CAT<?= $cat['id'] ?></td>
                    <td class="fw-bold"><?= htmlspecialchars($cat['name']) ?></td>
                    <td><?= htmlspecialchars($cat['slug']) ?></td>
                    <td class="text-center">
                        <span class="badge bg-secondary rounded-pill">
                            <?= $cat['product_count'] ?? 0 ?>
                        </span>
                    </td>
                    <td>
                        <?php if (isset($cat['status']) && $cat['status'] == 'published'): ?>
                            <span class="badge bg-success">Hiển thị</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Ẩn</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-end">
                      <button class="btn btn-sm btn-outline-primary me-1" title="Sửa"
                              data-bs-toggle="modal" data-bs-target="#editCategoryModal_<?= $cat['id'] ?>">
                        <i class="bi bi-pencil"></i>
                      </button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center py-4">Chưa có danh mục nào.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <!-- Phân trang -->
        <nav aria-label="Category pagination" class="d-flex justify-content-center mt-4">
            <ul class="pagination">
                <?php 
                    $currentPage = $currentPage ?? 1;
                    $totalPages = $totalPages ?? 1;
                    $currentSearch = $currentSearch ?? '';
                    $currentStatus = $currentStatus ?? '';

                    function getPaginationUrl($page, $status, $search) {
                        $url = BASE_URL . "index.php/admin/categories?page=" . $page;
                        if (!empty($status)) $url .= "&status=" . $status;
                        if (!empty($search)) $url .= "&search=" . urlencode($search);
                        return $url;
                    }
                ?>

                <?php if ($totalPages > 1): ?>
                    <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" 
                           href="<?= getPaginationUrl(max(1, $currentPage - 1), $currentStatus, $currentSearch) ?>">
                           Trước
                        </a>
                    </li>
                    
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                            <a class="page-link" 
                               href="<?= getPaginationUrl($i, $currentStatus, $currentSearch) ?>">
                               <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                        <a class="page-link" 
                           href="<?= getPaginationUrl(min($totalPages, $currentPage + 1), $currentStatus, $currentSearch) ?>">
                           Sau
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
      </div>
    </div>
  </div>
</main>

<!-- MODAL THÊM DANH MỤC -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Thêm danh mục mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" action="<?= BASE_URL ?>index.php/admin/addCategory">
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Tên danh mục</label>
            <input type="text" class="form-control" name="name" required placeholder="Ví dụ: Áo Vest">
          </div>
          <div class="mb-3">
            <label class="form-label">Slug (Đường dẫn)</label>
            <input type="text" class="form-control" name="slug" placeholder="ao-vest (để trống tự tạo)">
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
          <button type="submit" name="btn_add_category" class="btn btn-primary">Lưu danh mục</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL SỬA DANH MỤC (VÒNG LẶP) -->
<?php if (!empty($categories)): ?>
  <?php foreach ($categories as $cat): ?>
    <div class="modal fade" id="editCategoryModal_<?= $cat['id'] ?>" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title fw-bold">Sửa danh mục: <?= htmlspecialchars($cat['name']) ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form method="POST" action="<?= BASE_URL ?>index.php/admin/updateCategory">
            <div class="modal-body">
              <input type="hidden" name="id" value="<?= $cat['id'] ?>">
              
              <div class="mb-3">
                <label class="form-label">Tên danh mục</label>
                <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($cat['name']) ?>" required>
              </div>
              
              <div class="mb-3">
                <label class="form-label">Slug</label>
                <input type="text" class="form-control" name="slug" value="<?= htmlspecialchars($cat['slug']) ?>">
              </div>

              <div class="mb-3">
                <label class="form-label">Trạng thái</label>
                <select class="form-select" name="status">
                  <option value="published" <?= (isset($cat['status']) && $cat['status'] == 'published') ? 'selected' : '' ?>>Hiển thị</option>
                  <option value="hidden" <?= (isset($cat['status']) && $cat['status'] == 'hidden') ? 'selected' : '' ?>>Ẩn</option>
                </select>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
              <button type="submit" name="btn_update_category" class="btn btn-primary">Lưu thay đổi</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>