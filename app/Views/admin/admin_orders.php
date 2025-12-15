<main class="col-lg-10 col-md-9 ms-sm-auto px-0">
    <nav class="navbar navbar-expand-lg admin-top-nav shadow-sm sticky-top">
        <div class="container-fluid">
            <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#admin-sidebar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <span class="navbar-brand d-md-none ms-2">Quản lý Đơn hàng</span>

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

        <form action="<?= BASE_URL ?>index.php/admin/orders" method="GET" class="mb-4">
            
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <h1 class="h3 mb-0 fw-bold" style="color: var(--color-text-main);">Quản lý Đơn hàng</h1>
                
                <div class="input-group" style="max-width: 400px;">
                    <input type="text" class="form-control" name="keyword" 
                           value="<?= htmlspecialchars($keyword ?? '') ?>" 
                           placeholder="Mã đơn hàng (FS...), Tên khách, SĐT...">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2 mt-3 filter-buttons">
                <?php
                $status = $status ?? '';
                $keyword = $keyword ?? '';

                // 🛑 ĐÃ LOẠI BỎ 'cancelled' khỏi danh sách Bộ lọc
                $filters = [
                    ''          => 'Tất cả',
                    'pending'   => 'Chờ xử lý',
                    'processing'=> 'Đang chuẩn bị',
                    'shipped'   => 'Đang giao',
                    'completed' => 'Đã giao',
                    // 'cancelled' => 'Đã hủy' <-- Đã loại bỏ
                ];
                ?>
                <?php foreach ($filters as $key => $label): ?>
                    <button type="submit" name="status" value="<?= $key ?>" 
                            class="btn <?= ($status == $key) ? 'btn-primary' : 'btn-outline-secondary' ?>">
                        <?= $label ?>
                    </button>
                <?php endforeach; ?>
                
                <?php if($status != '' || $keyword != ''): ?>
                    <a href="<?= BASE_URL ?>index.php/admin/orders" class="btn btn-link text-decoration-none">
                        <i class="bi bi-arrow-counterclockwise"></i> Đặt lại
                    </a>
                <?php endif; ?>
            </div>
        </form>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle admin-table">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Mã đơn</th>
                                <th scope="col">Khách hàng</th>
                                <th scope="col">Ngày đặt</th>
                                <th scope="col">Tổng tiền</th>
                                <th scope="col">Trạng thái</th>
                                <th scope="col" class="text-end">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($orders)): ?>
                                <?php foreach ($orders as $order): ?>
                                    <?php
                                        $badgeClass = 'bg-secondary';
                                        $statusText = $order['status'];
                                        switch ($order['status']) {
                                            case 'pending': $badgeClass = 'bg-warning text-dark'; $statusText = 'Chờ xử lý'; break;
                                            case 'processing': $badgeClass = 'bg-info text-dark'; $statusText = 'Đang chuẩn bị'; break;
                                            case 'shipped': $badgeClass = 'bg-primary'; $statusText = 'Đang giao'; break;
                                            case 'completed': $badgeClass = 'bg-success'; $statusText = 'Đã giao'; break;
                                            case 'cancelled': $badgeClass = 'bg-danger'; $statusText = 'Đã hủy'; break; // Giữ lại để hiển thị đơn hàng cũ nếu có
                                        }
                                    ?>
                                    <tr>
                                        <td><strong>#FS<?= $order['id'] ?></strong></td>
                                        <td>
                                            <div class="fw-bold"><?= htmlspecialchars($order['user_name'] ?? 'Khách vãng lai') ?></div>
                                            <small class="text-muted"><?= htmlspecialchars($order['phone_number'] ?? 'N/A') ?></small>
                                        </td>
                                        <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                                        <td class="fw-bold text-danger">
                                            <?= number_format($order['total_price'], 0, ',', '.') ?>đ
                                        </td>
                                        <td>
                                            <span class="badge <?= $badgeClass ?>"><?= $statusText ?></span>
                                        </td>
                                        <td class="text-end">
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-primary"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#orderDetailModal_<?= $order['id'] ?>"
                                                    title="Xem chi tiết đơn hàng">
                                                <i class="bi bi-eye"></i> Xem
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="bi bi-inbox fs-1 text-muted"></i>
                                        <p class="mt-2 text-muted">Không tìm thấy đơn hàng nào.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <?php if (($totalPages ?? 1) > 1): ?>
            <nav aria-label="Page navigation" class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php 
                        $page = $currentPage ?? 1;
                        $totalPages = $totalPages ?? 1;
                        $status = $status ?? '';
                        $keyword = $keyword ?? '';

                        $currentParams = $_GET;
                        unset($currentParams['page']);
                        $paramsCleaned = array_filter($currentParams, function($value, $key) {
                            return $value !== '' && $key !== 'act';
                        }, ARRAY_FILTER_USE_BOTH);

                        $queryString = http_build_query($paramsCleaned);
                        
                        function getOrderPaginationUrl($page, $queryString) {
                            return BASE_URL . "index.php/admin/orders?page=" . $page . (empty($queryString) ? '' : '&' . $queryString);
                        }
                    ?>
                    
                    <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= getOrderPaginationUrl($page - 1, $queryString) ?>">Trước</a>
                    </li>

                    <?php 
                        $startPage = max(1, $page - 2);
                        $endPage = min($totalPages, $page + 2);

                        if ($startPage > 1) { echo '<li class="page-item disabled"><span class="page-link">...</span></li>'; }
                    ?>

                    <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                        <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                            <a class="page-link" href="<?= getOrderPaginationUrl($i, $queryString) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    
                    <?php 
                        if ($endPage < $totalPages) { echo '<li class="page-item disabled"><span class="page-link">...</span></li>'; }
                    ?>

                    <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= getOrderPaginationUrl($page + 1, $queryString) ?>">Sau</a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>

    </div>
</main>

<?php if (!empty($orders)): ?>
  <?php foreach ($orders as $order): ?>
    <?php
        $badgeClass = 'bg-secondary';
        $statusText = $order['status'];
        switch ($order['status']) {
            case 'pending': $badgeClass = 'bg-warning text-dark'; $statusText = 'Chờ xử lý'; break;
            case 'processing': $badgeClass = 'bg-info text-dark'; $statusText = 'Đang chuẩn bị'; break;
            case 'shipped': $badgeClass = 'bg-primary'; $statusText = 'Đang giao'; break;
            case 'completed': $badgeClass = 'bg-success'; $statusText = 'Đã giao'; break;
            case 'cancelled': $badgeClass = 'bg-danger'; $statusText = 'Đã hủy'; break;
        }
    ?>

    <div class="modal fade" id="orderDetailModal_<?= $order['id'] ?>" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-xl"> 
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title fw-bold">Chi tiết Đơn hàng #FS<?= $order['id'] ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          
          <div class="modal-body">
            
            <div class="row">
                <div class="col-md-7 border-end">
                    <h6><i class="bi bi-info-circle me-2"></i>Thông tin chung</h6>
                    <table class="table table-borderless table-sm small">
                        <tr>
                            <td><strong>Mã đơn:</strong></td>
                            <td class="fw-bold">#FS<?= $order['id'] ?></td>
                        </tr>
                        <tr>
                            <td><strong>Ngày đặt:</strong></td>
                            <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Trạng thái:</strong></td>
                            <td><span class="badge <?= $badgeClass ?>"><?= $statusText ?></span></td>
                        </tr>
                        <tr>
                            <td><strong>Tổng thanh toán:</strong></td>
                            <td class="fw-bold text-danger"><?= number_format($order['total_price'], 0, ',', '.') ?>đ</td>
                        </tr>
                        <tr>
                            <td><strong>Phương thức TT:</strong></td>
                            <td><?= htmlspecialchars($order['payment_method'] ?? 'N/A') ?></td>
                        </tr>
                        <tr>
                            <td><strong>Giao hàng:</strong></td>
                            <td><?= htmlspecialchars($order['shipping_method'] ?? 'N/A') ?></td>
                        </tr>
                    </table>

                    <h6 class="mt-4"><i class="bi bi-person-fill me-2"></i>Thông tin người nhận</h6>
                    <p class="mb-1"><strong>Tên:</strong> <?= htmlspecialchars($order['user_name'] ?? 'Khách vãng lai') ?></p>
                    <p class="mb-1"><strong>SĐT:</strong> <?= htmlspecialchars($order['phone_number'] ?? 'N/A') ?></p>
                    <p class="mb-1"><strong>Địa chỉ:</strong> <?= htmlspecialchars($order['shipping_address'] ?? 'N/A') ?></p>
                    
                    <h6 class="mt-4"><i class="bi bi-list-check me-2"></i>Danh sách Sản phẩm</h6>
                    
                    <div class="table-responsive">
                        <table class="table table-sm small">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;">Ảnh</th>
                                    <th>Sản phẩm & Biến thể</th>
                                    <th>SL</th>
                                    <th class="text-end">Giá</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($order['items'])): ?>
                                    <?php foreach ($order['items'] as $item): ?>
                                        <tr>
                                            <td>
                                                <?php 
                                                    $isExternalUrl = filter_var($item['image_url'], FILTER_VALIDATE_URL);
                                                    $imgUrl = $isExternalUrl 
                                                        ? $item['image_url'] 
                                                        : BASE_URL . "uploads/products/" . ($item['image_url'] ?? 'placeholder.png'); 
                                                ?>
                                                <img src="<?= $imgUrl ?>" alt="<?= $item['product_name'] ?>" style="width: 40px; height: 40px; object-fit: cover; border-radius: 3px;">
                                            </td>
                                            <td>
                                                <?= htmlspecialchars($item['product_name'] ?? 'N/A') ?>
                                                <div class="text-muted fst-italic">
                                                    (<?= htmlspecialchars($item['size_name'] ?? '') ?> / <?= htmlspecialchars($item['color_name'] ?? '') ?>)
                                                </div>
                                            </td>
                                            <td><?= $item['quantity'] ?></td>
                                            <td class="text-end"><?= number_format($item['price'], 0, ',', '.') ?>đ</td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr>
                                        <td colspan="3" class="fw-bold text-end">TỔNG CỘNG:</td>
                                        <td class="fw-bold text-danger text-end"><?= number_format($order['total_price'], 0, ',', '.') ?>đ</td>
                                    </tr>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">Đơn hàng không có sản phẩm nào.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    </div>
                
                <div class="col-md-5">
                    <h6 class="mb-3"><i class="bi bi-pencil-square me-2"></i>Cập nhật Trạng thái</h6>
                    <form action="<?= BASE_URL ?>index.php/admin/updateOrderStatus" method="POST">
                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                        
                        <div class="mb-3">
                            <label for="orderStatus_<?= $order['id'] ?>" class="form-label">Chọn Trạng thái mới</label>
                            <select class="form-select" name="new_status" id="orderStatus_<?= $order['id'] ?>" required>
                                <option value="pending" <?= ($order['status'] == 'pending') ? 'selected' : '' ?>>Chờ xử lý</option>
                                <option value="processing" <?= ($order['status'] == 'processing') ? 'selected' : '' ?>>Đang chuẩn bị</option>
                                <option value="shipped" <?= ($order['status'] == 'shipped') ? 'selected' : '' ?>>Đang giao</option>
                                <option value="completed" <?= ($order['status'] == 'completed') ? 'selected' : '' ?>>Đã giao (Hoàn thành)</option>
                                </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="note_<?= $order['id'] ?>" class="form-label">Ghi chú (Tùy chọn)</label>
                            <textarea class="form-control" name="note" id="note_<?= $order['id'] ?>" rows="2" placeholder="Thêm ghi chú nội bộ..."></textarea>
                        </div>
                        
                        <button type="submit" name="btn_update_status" class="btn btn-primary w-100">
                            Cập nhật Trạng thái
                        </button>
                    </form>
                </div>
            </div>

          </div>
          
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>