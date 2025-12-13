<main class="col-lg-10 col-md-9 ms-sm-auto px-0">
<div class="admin-content">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 fw-bold" style="color: var(--color-text-main)">
      Quản lý Tài khoản
    </h1>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
      <i class="bi bi-person-plus-fill me-2"></i>Thêm tài khoản mới
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

  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <form action="<?= BASE_URL ?>index.php/admin/account" method="GET" class="row g-3 align-items-end">
        
        <div class="col-md-4">
          <label class="form-label small text-muted">Tìm kiếm (Tên, Email, SĐT)</label>
          <input type="text" name="search" class="form-control" placeholder="Tìm kiếm tài khoản..." value="<?= htmlspecialchars($currentSearch) ?>">
        </div>

        <div class="col-md-3">
          <label class="form-label small text-muted">Lọc theo Quyền</label>
          <select class="form-select" name="role" aria-label="Lọc quyền">
            <option value="all" <?= $currentRole == 'all' ? 'selected' : '' ?>>Tất cả quyền</option>
            <option value="admin" <?= $currentRole == 'admin' ? 'selected' : '' ?>>Quản trị viên (Admin)</option>
            <option value="customer" <?= $currentRole == 'customer' ? 'selected' : '' ?>>Khách hàng (Client)</option>
          </select>
        </div>

        <div class="col-md-3">
          <label class="form-label small text-muted">Lọc trạng thái</label>
          <select class="form-select" name="status" aria-label="Lọc trạng thái">
            <option value="all" <?= $currentStatus == 'all' ? 'selected' : '' ?>>Tất cả trạng thái</option>
            <option value="active" <?= $currentStatus == 'active' ? 'selected' : '' ?>>Đang hoạt động</option>
            <option value="locked" <?= $currentStatus == 'locked' ? 'selected' : '' ?>>Đang bị khóa</option>
          </select>
        </div>
        
        <div class="col-md-2 d-flex align-items-end">
          <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-funnel me-1"></i> Lọc
          </button>
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
              <th scope="col" style="width: 70px;">Avatar</th>
              <th scope="col">Thông tin cá nhân</th>
              <th scope="col">Liên hệ</th>
              <th scope="col">Quyền hạn</th>
              <th scope="col">Trạng thái</th>
              <th scope="col" class="text-end">Hành động</th>
            </tr>
          </thead>
          <tbody>

            <?php if (!empty($users)): ?>
                <?php foreach ($users as $user): ?>
                    <?php 
                        $is_admin = $user['role'] == 'admin';
                        $is_active = $user['is_active'] == 1;
                        $bg_color = $is_admin ? '000f38' : 'random';
                        $is_self = ($_SESSION['user']['id'] ?? 0) == $user['id']; // Kiểm tra có phải user đang đăng nhập không
                    ?>
                    <tr>
                        <td>
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['name']) ?>&background=<?= $bg_color ?>&color=fff" class="avatar-img" alt="Avatar">
                        </td>
                        <td>
                            <div class="fw-bold"><?= htmlspecialchars($user['name']) ?></div>
                            <small class="text-muted">ID: #<?= $user['id'] ?></small>
                        </td>
                        <td>
                            <div><?= htmlspecialchars($user['email']) ?></div>
                            <small class="text-muted"><?= htmlspecialchars($user['phone_number']) ?></small>
                        </td>
                        <td>
                            <span class="badge <?= $is_admin ? 'badge-role-admin' : 'badge-role-client' ?> rounded-pill">
                                <?= $is_admin ? '<i class="bi bi-shield-lock-fill me-1"></i> Admin' : 'Client' ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge <?= $is_active ? 'bg-success bg-opacity-10 text-success' : 'bg-danger bg-opacity-10 text-danger' ?>">
                                <?= $is_active ? 'Hoạt động' : 'Đã khóa' ?>
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="<?= BASE_URL ?>index.php/admin/user?id=<?= $user['id'] ?>" class="btn btn-sm btn-outline-primary me-1" title="Sửa thông tin">
                                <i class="bi bi-pencil"></i>
                            </a>
                            
                            <?php if (!$is_self): // Không cho phép khóa chính mình ?>
                                <?php if ($is_active): ?>
                                    <a href="<?= BASE_URL ?>index.php/admin/updateStatus?id=<?= $user['id'] ?>&status=0" class="btn btn-sm btn-outline-danger" title="Khóa tài khoản" onclick="return confirm('Bạn có chắc chắn muốn khóa tài khoản <?= htmlspecialchars($user['name']) ?> không?');">
                                        <i class="bi bi-lock"></i>
                                    </a>
                                <?php else: ?>
                                    <a href="<?= BASE_URL ?>index.php/admin/updateStatus?id=<?= $user['id'] ?>&status=1" class="btn btn-sm btn-outline-success" title="Mở khóa" onclick="return confirm('Bạn có chắc chắn muốn mở khóa tài khoản <?= htmlspecialchars($user['name']) ?> không?');">
                                        <i class="bi bi-unlock"></i>
                                    </a>
                                <?php endif; ?>
                            <?php endif; ?>
                            
                            <?php if ($is_admin): // Nếu đang là Admin, hiện nút chuyển thành Client ?>
                                <a href="<?= BASE_URL ?>index.php/admin/updateRole?id=<?= $user['id'] ?>&role=customer" class="btn btn-sm btn-outline-secondary" title="Hạ cấp quyền" onclick="return confirm('Bạn có chắc chắn muốn hạ cấp tài khoản <?= htmlspecialchars($user['name']) ?> xuống Client không?');">
                                    <i class="bi bi-person-dash"></i>
                                </a>
                            <?php elseif (!$is_admin): // Nếu đang là Client, hiện nút chuyển thành Admin ?>
                                <a href="<?= BASE_URL ?>index.php/admin/updateRole?id=<?= $user['id'] ?>&role=admin" class="btn btn-sm btn-outline-secondary" title="Thăng cấp quyền" onclick="return confirm('Bạn có chắc chắn muốn thăng cấp tài khoản <?= htmlspecialchars($user['name']) ?> lên Admin không?');">
                                    <i class="bi bi-shield-plus"></i>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">Không tìm thấy tài khoản nào phù hợp với điều kiện tìm kiếm/lọc.</td>
                </tr>
            <?php endif; ?>

          </tbody>
        </table>
      </div>
    </div>
  </div>

  <nav aria-label="Account pagination" class="d-flex justify-content-center mt-4">
    <ul class="pagination">
      <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
        <a class="page-link" href="<?= BASE_URL ?>index.php/admin/account?page=<?= max(1, $currentPage - 1) ?>&role=<?= $currentRole ?>&status=<?= $currentStatus ?>&search=<?= $currentSearch ?>">Trước</a>
      </li>
      
      <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
          <a class="page-link" href="<?= BASE_URL ?>index.php/admin/account?page=<?= $i ?>&role=<?= $currentRole ?>&status=<?= $currentStatus ?>&search=<?= $currentSearch ?>"><?= $i ?></a>
        </li>
      <?php endfor; ?>

      <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
        <a class="page-link" href="<?= BASE_URL ?>index.php/admin/account?page=<?= min($totalPages, $currentPage + 1) ?>&role=<?= $currentRole ?>&status=<?= $currentStatus ?>&search=<?= $currentSearch ?>">Sau</a>
      </li>
    </ul>
  </nav>
</div>
</main>
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold" id="addUserModalLabel">Thêm tài khoản mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="<?= BASE_URL ?>index.php/admin/addUser" method="POST"> 
          
          <div class="mb-3">
            <label for="userName" class="form-label">Họ và tên</label>
            <input type="text" class="form-control" name="name" id="userName" placeholder="Nhập họ tên đầy đủ" required>
          </div>
          <div class="mb-3">
            <label for="userEmail" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" id="userEmail" placeholder="name@example.com" required>
          </div>
          <div class="mb-3">
            <label for="userPassword" class="form-label">Mật khẩu</label>
            <input type="password" class="form-control" name="password" id="userPassword" placeholder="Đặt mật khẩu ban đầu" required minlength="6">
          </div>
          <div class="mb-3">
            <label for="userPhone" class="form-label">Số điện thoại</label>
            <input type="tel" class="form-control" name="phone" id="userPhone" placeholder="09xxxxxxxx" required>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="userRole" class="form-label">Phân quyền</label>
              <select class="form-select" name="role" id="userRole">
                <option value="customer" selected>Khách hàng (Client)</option>
                <option value="admin">Quản trị viên (Admin)</option>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label for="userStatus" class="form-label">Trạng thái</label>
              <select class="form-select" name="status" id="userStatus">
                <option value="active" selected>Hoạt động</option>
                <option value="locked">Khóa</option>
              </select>
            </div>
          </div>
          
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
            <button type="submit" name="btn_add_user" class="btn btn-primary">Lưu tài khoản</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
  xintegrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>