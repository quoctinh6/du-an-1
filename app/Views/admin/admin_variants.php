<div class="admin-content">
  <nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.php?act=products"
          style="color: var(--color-accent); text-decoration: none;">Sản phẩm</a></li>
      <li class="breadcrumb-item active" aria-current="page">Quản lý biến thể</li>
    </ol>
  </nav>

  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h1 class="h3 mb-1 fw-bold" style="color: var(--color-text-main)">
        Biến thể sản phẩm
      </h1>
      <p class="text-muted mb-0">Sản phẩm: <strong><?= htmlspecialchars($product['name']) ?></strong> (Mã ID: <?= $product['id'] ?>)</p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVariantModal">
      <i class="bi bi-plus-lg me-2"></i>Thêm biến thể mới
    </button>
  </div>

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
            <?php if (!empty($variants)): ?>
              <?php foreach ($variants as $variant): ?>
                <tr>
                  <td>
                    <?php
                    // Logic tương tự: Nối BASE_URL + đường dẫn ảnh variant
                    $varImg = !empty($variant['image']) ? BASE_URL . $variant['image'] : 'https://placehold.co/50x50?text=No+Img';
                    ?>
                    <img src="<?= $varImg ?>"
                      alt="Variant Img"
                      class="shadow-sm"
                      style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #eee;">
                  </td>
                  <td><strong><?= htmlspecialchars($variant['sku']) ?></strong></td>
                  <td>
                    <span class="variant-attribute me-2">Màu: <?= htmlspecialchars($variant['colors']) ?></span>
                    <span class="variant-attribute">Size: <?= htmlspecialchars($variant['sizes']) ?></span>
                  </td>
                  <td><?= number_format($variant['price'], 0, ',', '.') ?>đ</td>
                  <td>
                    <div class="d-flex align-items-center">
                      <span class="me-2 fw-bold"><?= $variant['quantity'] ?></span>
                      <?php if ($variant['quantity'] > 10): ?>
                        <span class="badge bg-success" style="font-size: 0.7em;">Nhiều</span>
                      <?php elseif ($variant['quantity'] > 0): ?>
                        <span class="badge bg-warning" style="font-size: 0.7em;">Sắp hết</span>
                      <?php else: ?>
                        <span class="badge bg-danger" style="font-size: 0.7em;">Hết hàng</span>
                      <?php endif; ?>
                    </div>
                  </td>
                  <td class="text-end">
                    <button class="btn btn-primary btn-sm" title="Cập nhật"
                      data-bs-toggle="modal"
                      data-bs-target="#editVariantModal_<?= $variant['id'] ?>">
                      <i class="bi bi-pencil"></i>
                    </button>
                    <a href="index.php?act=delete_variant&id=<?= $variant['id'] ?>&product_id=<?= $product['id'] ?>"
                      class="btn btn-danger btn-sm" onclick="return confirm('Bạn chắc chắn muốn xóa?')">
                      <i class="bi bi-trash"></i>
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="6" class="text-center py-4">Chưa có biến thể nào. Hãy thêm mới!</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="addVariantModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="index.php?act=add_variant" method="POST" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title fw-bold">Thêm biến thể mới</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="product_id" value="<?= $product['id'] ?>">

          <div class="mb-3">
            <label class="form-label">Mã SKU</label>
            <input type="text" name="sku" class="form-control" placeholder="VD: AK-RED-XL" required>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Màu sắc</label>
              <select class="form-select" name="color_id">
                <?php foreach ($colors as $color): ?>
                  <option value="<?= $color['id'] ?>"><?= $color['name'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Kích thước</label>
              <select class="form-select" name="size_id">
                <?php foreach ($sizes as $size): ?>
                  <option value="<?= $size['id'] ?>"><?= $size['name'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Giá bán</label>
              <input type="number" name="price" class="form-control" value="<?= $product['price'] ?? 0 ?>">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Số lượng</label>
              <input type="number" name="quantity" class="form-control" value="10">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Ảnh biến thể</label>

            <input type="file" name="image" class="form-control"
              onchange="previewImage(this, 'preview_img_variant')">

            <div class="mt-2 text-center">
              <img id="preview_img_variant" src="#" alt="Ảnh xem trước"
                style="display: none; width: 80px; height: 80px; object-fit: cover; border-radius: 5px; border: 1px solid #ccc;">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" name="btn_add_variant" class="btn btn-primary">Lưu biến thể</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php if (!empty($variants)): ?>
  <?php foreach ($variants as $variant): ?>
    <div class="modal fade" id="editVariantModal_<?= $variant['id'] ?>" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form action="index.php?act=update_variant" method="POST" enctype="multipart/form-data">
            <div class="modal-header">
              <h5 class="modal-title fw-bold">Cập nhật biến thể</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <input type="hidden" name="variant_id" value="<?= $variant['id'] ?>">
              <input type="hidden" name="product_id" value="<?= $product['id'] ?>">

              <div class="mb-3">
                <label class="form-label">Mã SKU</label>
                <input type="text" name="sku" class="form-control" value="<?= htmlspecialchars($variant['sku']) ?>">
              </div>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Màu sắc</label>
                  <select class="form-select" name="color_id">
                    <?php foreach ($colors as $color): ?>
                      <option value="<?= $color['id'] ?>" <?= $color['id'] == $variant['color_id'] ? 'selected' : '' ?>>
                        <?= $color['name'] ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Kích thước</label>
                  <select class="form-select" name="size_id">
                    <?php foreach ($sizes as $size): ?>
                      <option value="<?= $size['id'] ?>" <?= $size['id'] == $variant['size_id'] ? 'selected' : '' ?>>
                        <?= $size['name'] ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Giá bán</label>
                  <input type="number" name="price" class="form-control" value="<?= $variant['price'] ?>">
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Số lượng</label>
                  <input type="number" name="quantity" class="form-control" value="<?= $variant['quantity'] ?>">
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label">Ảnh (Chọn nếu muốn đổi)</label>
                <input type="file" name="image" class="form-control">
                <?php if (!empty($variant['image'])): ?>
                  <div class="mt-2">
                    <img src="<?= BASE_URL . $variant['image'] ?>"
                      style="width: 60px; height: 60px; object-fit: cover; border: 1px solid #ddd; border-radius: 4px;">
                    <span class="small text-muted ms-2">Ảnh hiện tại</span>
                  </div>
                <?php endif; ?>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
              <button type="submit" name="btn_update_variant" class="btn btn-primary">Cập nhật</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
<?php endif; ?>

<script>
  // Hàm hiển thị ảnh preview
  function previewImage(input, imgId) {
    const preview = document.getElementById(imgId);

    if (input.files && input.files[0]) {
      const reader = new FileReader();

      reader.onload = function(e) {
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