<main class="col-lg-10 col-md-9 ms-sm-auto px-0">
    <div class="admin-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 fw-bold" style="color: var(--color-text-main)">
                Quản lý Danh mục
            </h1>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                <i class="bi bi-plus-lg me-2"></i>Thêm danh mục mới
            </button>
        </div>
        
        <?php 
            // Đảm bảo các biến này được extract từ Controller
            $error = $error ?? null;
            $success = $success ?? null;
            $categories = $categories ?? [];
            $currentStatus = $currentStatus ?? '';
        ?>
        
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
        
        <div class="card shadow-sm mb-4">
          <div class="card-body">
            <form action="" method="GET">
                <input type="hidden" name="act" value="categories">
                <div class="row g-3 align-items-end"> 
                  <div class="col-md-6">
                    <label class="form-label small text-muted mb-1">Trạng thái danh mục</label>
                    <select class="form-select" name="status" aria-label="Lọc trạng thái">
                      <option value="">Tất cả trạng thái</option>
                      <option value="published" <?= $currentStatus == 'published' ? 'selected' : '' ?>>Đang hiển thị</option>
                      <option value="hidden" <?= $currentStatus == 'hidden' ? 'selected' : '' ?>>Đang ẩn</option>
                    </select>
                  </div>
                  
                  <div class="col-md-6 d-flex gap-2"> 
                    <button class="btn btn-outline-secondary flex-fill" type="submit">
                      <i class="bi bi-funnel me-1"></i> Lọc danh sách
                    </button>
                    <a href="?act=categories" class="btn btn-outline-danger flex-fill">
                      <i class="bi bi-x-lg me-1"></i> Xóa lọc
                    </a>
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
                    <th scope="col" style="width: 80px;">Icon</th>
                    <th scope="col">Tên danh mục</th>
                    <th scope="col">Mô tả</th>
                    <th scope="col" class="text-center">Số lượng SP</th>
                    <th scope="col">Trạng thái</th>
                    <th scope="col" class="text-end">Hành động</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $cat): ?>
                      <tr>
                        <td>
                          <?php 
                            $iconUrl = !empty($cat['icon']) ? BASE_URL . $cat['icon'] : 'https://cdn-icons-png.flaticon.com/512/2652/2652218.png';
                          ?>
                          <img src="<?= $iconUrl ?>" alt="Icon" class="category-icon" style="width: 40px; height: 40px; object-fit: contain;">
                        </td>
                        <td>
                          <div class="fw-bold"><?= htmlspecialchars($cat['name']) ?></div>
                          <small class="text-muted">ID: CAT<?= $cat['id'] ?></small>
                        </td>
                        <td class="text-truncate" style="max-width: 250px;">
                            <?= htmlspecialchars($cat['description'] ?? 'Chưa có mô tả') ?>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-secondary rounded-pill">
                                <?= $cat['product_count'] ?? 0 ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($cat['status'] == 'published'): ?>
                                <span class="badge bg-success">Hiển thị</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Đang ẩn</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                          <button class="btn btn-sm btn-outline-primary" title="Sửa"
                                  data-bs-toggle="modal" data-bs-target="#editCategoryModal_<?= $cat['id'] ?>">
                            <i class="bi bi-pencil"></i>
                          </button>
                          </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-4">Không tìm thấy danh mục nào.</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>

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
                    
                    <?php 
                        $startPage = max(1, $currentPage - 2);
                        $endPage = min($totalPages, $currentPage + 2);

                        if ($startPage > 1) { echo '<li class="page-item disabled"><span class="page-link">...</span></li>'; }
                    ?>

                    <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                        <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                            <a class="page-link" 
                               href="<?= getPaginationUrl($i, $currentStatus, $currentSearch) ?>">
                               <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <?php 
                        if ($endPage < $totalPages) { echo '<li class="page-item disabled"><span class="page-link">...</span></li>'; }
                    ?>

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
</main>


<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Thêm danh mục mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" enctype="multipart/form-data" action="<?= BASE_URL ?>index.php/admin/addCategory">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tên danh mục</label>
                        <input type="text" class="form-control" name="name" required placeholder="Nhập tên danh mục">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Slug (Đường dẫn SEO - Tùy chọn)</label>
                        <input type="text" class="form-control" name="slug" placeholder="ao-khoac-nam">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô tả ngắn</label>
                        <textarea class="form-control" name="description" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Icon (Tùy chọn)</label>
                        <input type="file" class="form-control" name="icon" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Trạng thái</label>
                        <select class="form-select" name="status">
                            <option value="published">Hiển thị</option>
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

<?php if (!empty($categories)): ?>
    <?php foreach ($categories as $cat): ?>
        <div class="modal fade" id="editCategoryModal_<?= $cat['id'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Cập nhật Danh mục: <?= htmlspecialchars($cat['name']) ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" enctype="multipart/form-data" action="<?= BASE_URL ?>index.php/admin/updateCategory">
                        <div class="modal-body">
                            <input type="hidden" name="id" value="<?= $cat['id'] ?>">

                            <div class="mb-3">
                                <label class="form-label">Tên danh mục</label>
                                <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($cat['name']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Slug (Đường dẫn)</label>
                                <input type="text" class="form-control" name="slug" value="<?= htmlspecialchars($cat['slug']) ?>" placeholder="danh-muc-moi">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mô tả ngắn</label>
                                <textarea class="form-control" name="description" rows="2"><?= htmlspecialchars($cat['description'] ?? '') ?></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Icon (Chọn mới để thay đổi)</label>
                                <input type="file" class="form-control" name="icon" accept="image/*">
                                <?php if (!empty($cat['icon'])): ?>
                                    <div class="mt-2 d-flex align-items-center">
                                        <img src="<?= BASE_URL . $cat['icon'] ?>" alt="Icon hiện tại"
                                            style="width: 30px; height: 30px; object-fit: contain; border: 1px solid #ddd; border-radius: 3px;">
                                        <span class="small text-muted ms-2">Icon hiện tại</span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Trạng thái</label>
                                <select class="form-select" name="status">
                                    <option value="published" <?= ($cat['status'] == 'published') ? 'selected' : '' ?>>Hiển thị</option>
                                    <option value="hidden" <?= ($cat['status'] != 'published') ? 'selected' : '' ?>>Ẩn</option>
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