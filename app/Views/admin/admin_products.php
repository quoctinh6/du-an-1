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
                  <option value="active" <?= (isset($_GET['status']) && $_GET['status'] == 'active') ? 'selected' : '' ?>>
                    Đang bán</option>
                  <option value="hidden" <?= (isset($_GET['status']) && $_GET['status'] == 'hidden') ? 'selected' : '' ?>>
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
                      // Kiểm tra: Nếu có link ảnh thì nối BASE_URL vào, nếu không thì dùng ảnh mặc định
                      $imgUrl = !empty($item['image_url']) ? BASE_URL . $item['image_url'] : 'https://placehold.co/50x50?text=No+Img';
                      ?>
                      <img src="<?= $imgUrl ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="shadow-sm"
                        style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px; border: 1px solid #eee;">
                    </td>
                    <td>
                      <div class="fw-bold"><?= htmlspecialchars($item['name']) ?></div>
                      <small class="text-muted">Danh mục: <?= htmlspecialchars($item['cate_name']) ?></small>
                    </td>
                    <td>
                      <span class="badge bg-light text-dark border">
                        <?= htmlspecialchars($item['brand_name']) ?>
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
        <li class="page-item disabled"><a class="page-link" href="#">Trước</a></li>
        <li class="page-item active"><a class="page-link" href="#">1</a></li>
        <li class="page-item"><a class="page-link" href="#">2</a></li>
        <li class="page-item"><a class="page-link" href="#">Sau</a></li>
      </ul>
    </nav>
  </div>
</main>
<!-- FORM THÊM SẢN PHẨM -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Thêm sản phẩm mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" enctype="multipart/form-data" action="<?= BASE_URL ?>index.php/admin/addProduct">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Tên sản phẩm</label>
                <input type="text" class="form-control" name="name" required placeholder="Nhập tên sản phẩm">
              </div>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Danh mục</label>
                  <select class="form-select" name="category_id">
                    <?php foreach ($categoriesAll as $cat): ?>
                      <option value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Thương hiệu</label>
                  <select class="form-select" name="brand_id">
                    <?php foreach ($brandsAll as $brand): ?>
                      <option value="<?= $brand['id'] ?>"><?= $brand['name'] ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label">Mô tả ngắn</label>
                <textarea class="form-control" name="description" rows="3"></textarea>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Ảnh đại diện</label>

                <input type="file" class="form-control" name="image" accept="image/png, image/jpeg, image/jpg"
                  onchange="previewImage(this, 'preview_img_product')">

                <div class="mt-2 text-center">
                  <img id="preview_img_product" src="#" alt="Ảnh xem trước"
                    style="display: none; width: 100px; height: 100px; object-fit: cover; border-radius: 5px; border: 1px solid #ccc;">
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label">Trạng thái</label>
                <select class="form-select" name="status">
                  <option value="published">Đang bán</option>
                  <option value="draft">Ẩn (Nháp)</option>
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label">Slug (Đường dẫn SEO - Tự động tạo nếu để trống)</label>
                <input type="text" class="form-control" name="slug" placeholder="ao-khoac-nam">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" name="btn_add" class="btn btn-primary">Lưu sản phẩm</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- FORM SỬA SẢN PHẨM -->

<?php if (!empty($products)): ?>
  <?php foreach ($products as $item): ?>

    <div class="modal fade" id="editProductModal_<?= $item['id'] ?>" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title fw-bold">Cập nhật: <?= htmlspecialchars($item['name']) ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <form method="POST" enctype="multipart/form-data" action="<?= BASE_URL ?>index.php/admin/updateProduct">
            <div class="modal-body">
              <input type="hidden" name="id" value="<?= $item['id'] ?>">

              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label">Tên sản phẩm</label>
                    <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($item['name']) ?>"
                      required>
                  </div>

                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label class="form-label">Danh mục</label>
                      <select class="form-select" name="category_id">
                        <?php foreach ($categoriesAll as $cat): ?>
                          <option value="<?= $cat['id'] ?>" <?= ($item['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                            <?= $cat['name'] ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label class="form-label">Thương hiệu</label>
                      <select class="form-select" name="brand_id">
                        <?php foreach ($brandsAll as $brand): ?>
                          <option value="<?= $brand['id'] ?>" <?= ($item['brand_id'] == $brand['id']) ? 'selected' : '' ?>>
                            <?= $brand['name'] ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label">Ảnh đại diện (Chọn mới để thay đổi)</label>
                    <input type="file" class="form-control" name="image">
                    <?php if (!empty($item['image_url'])): ?>
                      <div class="mt-2">
                        <img src="<?= BASE_URL . $item['image_url'] ?>" alt="Ảnh hiện tại"
                          style="width: 80px; height: 80px; object-fit: cover; border: 1px solid #ddd; border-radius: 5px;">
                        <span class="small text-muted ms-2 align-middle">Ảnh hiện tại</span>
                      </div>
                    <?php endif; ?>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Trạng thái</label>
                    <select class="form-select" name="status">
                      <option value="published" <?= ($item['status'] == 'published') ? 'selected' : '' ?>>Đang bán</option>
                      <option value="draft" <?= ($item['status'] == 'draft' || $item['status'] == 'hidden') ? 'selected' : '' ?>>
                        Ẩn</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label">Mô tả sản phẩm</label>
                <textarea class="form-control" name="description"
                  rows="3"><?= htmlspecialchars($item['description'] ?? '') ?></textarea>
              </div>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
              <button type="submit" name="btn_update" class="btn btn-primary">Lưu thay đổi</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
  // Hàm hiển thị ảnh preview
  function previewImage(input, imgId) {
    const preview = document.getElementById(imgId);

    if (input.files && input.files[0]) {
      const reader = new FileReader();

      reader.onload = function (e) {
        preview.src = e.target.result;
        preview.style.display = 'block'; // Hiện ảnh lên
      }

      reader.readAsDataURL(input.files[0]);
    } else {
      preview.src = '#';
      preview.style.display = 'none'; // Ẩn đi nếu bỏ chọn
    }
  }
</script>