<main class="col-lg-10 col-md-9 ms-sm-auto px-0">
    <nav class="navbar navbar-expand-lg admin-top-nav shadow-sm sticky-top">
        <div class="container-fluid">
            <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#admin-sidebar">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <form class="d-flex mx-auto" style="width: 400px" action="<?= BASE_URL ?>index.php/admin/comments" method="GET">
                <input type="search" name="search" class="form-control me-2" 
                       placeholder="Tìm nội dung, user, sản phẩm..." 
                       value="<?= htmlspecialchars($currentSearch ?? '') ?>" />
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
                Quản lý Bình luận & Đánh giá
            </h1>
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

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form action="<?= BASE_URL ?>index.php/admin/comments" method="GET" class="row g-3 align-items-end">
                    
                    <div class="col-md-4">
                        <label class="form-label small text-muted mb-1">Lọc theo số sao</label>
                        <select class="form-select" name="rating">
                            <option value="all" <?= ($currentRating ?? 'all') == 'all' ? 'selected' : '' ?>>Tất cả sao</option>
                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                <option value="<?= $i ?>" <?= ($currentRating ?? 0) == $i ? 'selected' : '' ?>><?= $i ?> Sao</option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-funnel me-1"></i> Áp dụng bộ lọc
                        </button>
                    </div>

                    <div class="col-md-4">
                        <a href="<?= BASE_URL ?>index.php/admin/comments" class="btn btn-outline-danger w-100">
                            <i class="bi bi-x-lg me-1"></i> Xóa lọc
                        </a>
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
                                <th scope="col" style="width: 150px;">Người dùng</th>
                                <th scope="col">Sản phẩm</th>
                                <th scope="col" style="width: 40%;">Nội dung</th>
                                <th scope="col">Đánh giá</th>
                                <th scope="col">Ngày đăng</th>
                                <th scope="col" class="text-end">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($comments)): ?>
                                <?php foreach ($comments as $item): ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold"><?= htmlspecialchars($item['user_name'] ?? 'Ẩn danh') ?></div>
                                            <small class="text-muted">ID: <?= $item['user_id'] ?></small>
                                        </td>
                                        <td>
                                            <span class="text-decoration-none text-dark fw-bold">
                                                <?= htmlspecialchars($item['product_name'] ?? 'Sản phẩm đã xóa') ?>
                                            </span>
                                        </td>
                                        <td>
                                            <p class="mb-0 text-truncate" style="max-width: 300px;">
                                                <?= htmlspecialchars($item['content']) ?>
                                            </p>
                                        </td>
                                        <td>
                                            <div class="text-warning">
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                    <?php if ($i <= $item['rating']): ?>
                                                        <i class="bi bi-star-fill"></i>
                                                    <?php else: ?>
                                                        <i class="bi bi-star-fill text-muted opacity-25"></i>
                                                    <?php endif; ?>
                                                <?php endfor; ?>
                                            </div>
                                        </td>
                                        <td><?= date('d/m/Y H:i', strtotime($item['created_at'])) ?></td>
                                        <td class="text-end">
                                            <a href="<?= BASE_URL ?>index.php/admin/deleteComment?id=<?= $item['id'] ?>"
                                                class="btn btn-sm btn-outline-danger" 
                                                title="Xóa bình luận" 
                                                onclick="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn bình luận này không?');">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        Không tìm thấy bình luận nào phù hợp với điều kiện tìm kiếm/lọc.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <nav aria-label="Comments pagination" class="d-flex justify-content-center mt-4">
            <ul class="pagination">
                <?php 
                    // Đảm bảo các biến có giá trị mặc định để tránh lỗi PHP
                    $totalPages = $totalPages ?? 1;
                    $currentPage = $currentPage ?? 1;
                    $currentRating = $currentRating ?? 'all';
                    $currentSearch = $currentSearch ?? '';
                ?>
                
                <?php if ($totalPages > 1): ?>
                    <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" 
                           href="<?= BASE_URL ?>index.php/admin/comments?page=<?= max(1, $currentPage - 1) ?>&rating=<?= $currentRating ?>&search=<?= $currentSearch ?>">
                           Trước
                        </a>
                    </li>
                    
                    <?php 
                        // Hiển thị tối đa 5 trang gần trang hiện tại để tránh thanh phân trang quá dài
                        $startPage = max(1, $currentPage - 2);
                        $endPage = min($totalPages, $currentPage + 2);

                        if ($startPage > 1) { echo '<li class="page-item disabled"><span class="page-link">...</span></li>'; }
                    ?>

                    <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                        <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                            <a class="page-link" 
                               href="<?= BASE_URL ?>index.php/admin/comments?page=<?= $i ?>&rating=<?= $currentRating ?>&search=<?= $currentSearch ?>">
                               <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <?php 
                        if ($endPage < $totalPages) { echo '<li class="page-item disabled"><span class="page-link">...</span></li>'; }
                    ?>

                    <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                        <a class="page-link" 
                           href="<?= BASE_URL ?>index.php/admin/comments?page=<?= min($totalPages, $currentPage + 1) ?>&rating=<?= $currentRating ?>&search=<?= $currentSearch ?>">
                           Sau
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>