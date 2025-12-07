<main class="col-lg-10 col-md-9 ms-sm-auto px-0">

    <nav class="navbar navbar-expand-lg admin-top-nav shadow-sm sticky-top">
        <div class="container-fluid">
            <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#admin-sidebar">
                <span class="navbar-toggler-icon"></span>
            </button>
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
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="?act=products" style="color: var(--color-accent); text-decoration: none;">Sản phẩm</a></li>
                <li class="breadcrumb-item active" aria-current="page">Quản lý biến thể</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1 fw-bold" style="color: var(--color-text-main)">
                    Biến thể sản phẩm
                </h1>
                <p class="text-muted mb-0">
                    Sản phẩm: <strong><?= htmlspecialchars($product['name'] ?? 'Không rõ tên') ?></strong>
                    <?php if(!empty($product['id'])): ?>
                        (ID: <?= $product['id'] ?>)
                    <?php endif; ?>
                </p>
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
                                <?php foreach ($variants as $item): ?>
                                    <tr>
                                        <td>
                                            <?php
                                            // Xử lý ảnh: Nếu có ảnh riêng thì dùng, không thì dùng ảnh placeholder
                                            // Lưu ý: BASE_URL phải được định nghĩa trong file config/index.php
                                            $imgUrl = !empty($item['image']) ? BASE_URL . $item['image'] : 'https://placehold.co/100x100?text=No+Img';
                                            ?>
                                            <img src="<?= $imgUrl ?>" alt="<?= htmlspecialchars($item['sku']) ?>" 
                                                 style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #dee2e6;">
                                        </td>
                                        <td><strong><?= htmlspecialchars($item['sku']) ?></strong></td>
                                        <td>
                                            <span class="variant-attribute">Màu: <?= htmlspecialchars($item['color_name'] ?? $item['color_id']) ?></span>
                                            <span class="variant-attribute">Size: <?= htmlspecialchars($item['size_name'] ?? $item['size_id']) ?></span>
                                        </td>
                                        <td>
                                            <?= number_format($item['price'], 0, ',', '.') ?>đ
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="me-2"><?= $item['quantity'] ?></span>
                                                
                                                <?php if ($item['quantity'] == 0): ?>
                                                    <span class="badge bg-danger" style="font-size: 0.7em;">Hết hàng</span>
                                                <?php elseif ($item['quantity'] < 10): ?>
                                                    <span class="badge bg-warning text-dark" style="font-size: 0.7em;">Sắp hết</span>
                                                <?php else: ?>
                                                    <span class="badge bg-success" style="font-size: 0.7em;">Nhiều</span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <button class="btn btn-sm btn-outline-primary me-1"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editVariantModal_<?= $item['id'] ?>"
                                                title="Sửa biến thể">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            
                                      
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        Chưa có biến thể nào cho sản phẩm này.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<div class="modal fade" id="addVariantModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Thêm biến thể mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" enctype="multipart/form-data" action="<?= BASE_URL ?>index.php/admin/variants">
                <div class="modal-body">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">

                    <div class="mb-3">
                        <label class="form-label">Mã SKU Biến thể</label>
                        <input type="text" class="form-control" name="sku" required placeholder="VD: AK-001-RED-XL">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Màu sắc</label>
                            <select class="form-select" name="color_id">
                                <?php foreach ($colors as $c): ?>
                                    <option value="<?= $c['id'] ?>"><?= $c['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kích thước (Size)</label>
                            <select class="form-select" name="size_id">
                                <?php foreach ($sizes as $s): ?>
                                    <option value="<?= $s['id'] ?>"><?= $s['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Giá bán riêng</label>
                            <input type="number" class="form-control" name="price" required value="<?= $product['price'] ?? 0 ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Số lượng tồn kho</label>
                            <input type="number" class="form-control" name="quantity" required value="0">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ảnh đại diện biến thể</label>
                        <input type="file" class="form-control" name="image">
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
    <?php foreach ($variants as $item): ?>
        <div class="modal fade" id="editVariantModal_<?= $item['id'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Cập nhật: <?= htmlspecialchars($item['sku']) ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" enctype="multipart/form-data" action="<?= BASE_URL ?>index.php/admin/updateVariant">
                        <div class="modal-body">
                            <input type="hidden" name="variant_id" value="<?= $item['id'] ?>">
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">

                            <div class="mb-3">
                                <label class="form-label">Mã SKU Biến thể</label>
                                <input type="text" class="form-control" name="sku" value="<?= htmlspecialchars($item['sku']) ?>" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Màu sắc</label>
                                    <select class="form-select" name="color_id">
                                        <?php foreach ($colors as $c): ?>
                                            <option value="<?= $c['id'] ?>" <?= ($item['color_id'] == $c['id']) ? 'selected' : '' ?>>
                                                <?= $c['name'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Kích thước (Size)</label>
                                    <select class="form-select" name="size_id">
                                        <?php foreach ($sizes as $s): ?>
                                            <option value="<?= $s['id'] ?>" <?= ($item['size_id'] == $s['id']) ? 'selected' : '' ?>>
                                                <?= $s['name'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Giá bán riêng</label>
                                    <input type="number" class="form-control" name="price" value="<?= $item['price'] ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Số lượng tồn kho</label>
                                    <input type="number" class="form-control" name="quantity" value="<?= $item['quantity'] ?>" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ảnh đại diện (Chọn nếu muốn thay đổi)</label>
                                <input type="file" class="form-control" name="image">
                                <?php if (!empty($item['image'])): ?>
                                    <div class="mt-2">
                                        <img src="<?= BASE_URL . $item['image'] ?>" width="60" class="border rounded">
                                        <small class="text-muted ms-2">Ảnh hiện tại</small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" name="btn_update_variant" class="btn btn-primary">Lưu thay đổi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>