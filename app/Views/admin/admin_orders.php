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
        
        <form action="" method="GET" class="mb-4">
            
            <input type="hidden" name="act" value="orders">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <h1 class="h3 mb-0 fw-bold" style="color: var(--color-text-main);">Quản lý Đơn hàng</h1>
                
                <div class="input-group" style="max-width: 400px;">
                    <input type="text" class="form-control" name="keyword" 
                           value="<?= htmlspecialchars($keyword ?? '') ?>" 
                           placeholder="Mã đơn, Tên khách, SĐT...">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2 mt-3 filter-buttons">
                <?php
                $filters = [
                    ''          => 'Tất cả',
                    'pending'   => 'Chờ xử lý',
                    'processing'=> 'Đang chuẩn bị',
                    'shipped'   => 'Đang giao',
                    'completed' => 'Đã giao',
                    'cancelled' => 'Đã hủy'
                ];
                ?>
                <?php foreach ($filters as $key => $label): ?>
                    <button type="submit" name="status" value="<?= $key ?>" 
                            class="btn <?= ($status == $key) ? 'btn-primary' : 'btn-outline-secondary' ?>">
                        <?= $label ?>
                    </button>
                <?php endforeach; ?>
                
                <?php if($status != '' || $keyword != ''): ?>
                    <a href="?act=orders" class="btn btn-link text-decoration-none">
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
                                    <tr>
                                        <td><strong>#FS<?= $order['id'] ?></strong></td>
                                        <td>
                                            <div class="fw-bold"><?= htmlspecialchars($order['user_name'] ?? 'Khách vãng lai') ?></div>
                                            <small class="text-muted"><?= htmlspecialchars($order['phone_number']) ?></small>
                                        </td>
                                        <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                                        <td class="fw-bold text-danger">
                                            <?= number_format($order['total_price'], 0, ',', '.') ?>đ
                                        </td>
                                        <td>
                                            <?php
                                            $badgeClass = 'bg-secondary';
                                            $statusText = $order['status'];
                                            switch ($order['status']) {
                                                case 'pending':
                                                    $badgeClass = 'bg-warning text-dark'; $statusText = 'Chờ xử lý'; break;
                                                case 'processing':
                                                    $badgeClass = 'bg-info text-dark'; $statusText = 'Đang chuẩn bị'; break;
                                                case 'shipped':
                                                    $badgeClass = 'bg-primary'; $statusText = 'Đang giao'; break;
                                                case 'completed':
                                                    $badgeClass = 'bg-success'; $statusText = 'Đã giao'; break;
                                                case 'cancelled':
                                                    $badgeClass = 'bg-danger'; $statusText = 'Đã hủy'; break;
                                            }
                                            ?>
                                            <span class="badge <?= $badgeClass ?>"><?= $statusText ?></span>
                                        </td>
                                        <td class="text-end">
                                            <a href="index.php?act=order_detail&id=<?= $order['id'] ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i> Xem
                                            </a>
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

        <?php if ($totalPages > 1): ?>
            <nav aria-label="Page navigation" class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php 
                        // Tạo chuỗi tham số hiện tại để nối vào link phân trang
                        $params = "&status=" . urlencode($status) . "&keyword=" . urlencode($keyword);
                    ?>
                    
                    <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                        <a class="page-link" href="index.php?act=orders<?= $params ?>&page=<?= $page - 1 ?>">Trước</a>
                    </li>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                            <a class="page-link" href="index.php?act=orders<?= $params ?>&page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                        <a class="page-link" href="index.php?act=orders<?= $params ?>&page=<?= $page + 1 ?>">Sau</a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>

    </div>
</main>