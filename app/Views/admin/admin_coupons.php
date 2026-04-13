<main class="col-lg-10 col-md-9 ms-sm-auto px-0">
  <!-- Top Navbar -->
  <nav class="navbar navbar-expand-lg admin-top-nav shadow-sm sticky-top">
    <div class="container-fluid">
      <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#admin-sidebar">
        <span class="navbar-toggler-icon"></span>
      </button>
      
      <!-- FORM TÌM KIẾM (Submit về trang hiện tại với act=coupons) -->
      <form class="d-flex mx-auto" style="width: 400px" action="" method="GET">
        <input type="hidden" name="act" value="coupons">
        <input class="form-control me-2" type="search" name="search" 
               value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" 
               placeholder="Tìm mã hoặc giá trị..." />
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

  <!-- NỘI DUNG CHÍNH -->
  <div class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1 class="h3 mb-0 fw-bold" style="color: var(--color-text-main)">
        Quản lý Mã giảm giá
      </h1>
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCouponModal">
        <i class="bi bi-ticket-perforated-fill me-2"></i>Tạo mã mới
      </button>
    </div>

    <!-- BỘ LỌC -->
    <div class="card shadow-sm mb-4">
      <div class="card-body">
        <form action="" method="GET">
            <input type="hidden" name="act" value="coupons">
            <!-- Giữ lại từ khóa tìm kiếm nếu có -->
            <?php if(isset($_GET['search'])): ?>
                <input type="hidden" name="search" value="<?= htmlspecialchars($_GET['search']) ?>">
            <?php endif; ?>

            <div class="row g-3">
              <div class="col-md-4">
                <label class="form-label small text-muted">Lọc theo loại:</label>
                <select class="form-select" name="type">
                  <option value="all">Tất cả loại</option>
                  <option value="percent" <?= (isset($_GET['type']) && $_GET['type'] == 'percent') ? 'selected' : '' ?>>Phần trăm (%)</option>
                  <option value="fixed" <?= (isset($_GET['type']) && $_GET['type'] == 'fixed') ? 'selected' : '' ?>>Số tiền cố định</option>
                </select>
              </div>
              
              <div class="col-md-4">
                <label class="form-label small text-muted">Lọc theo trạng thái:</label>
                <select class="form-select" name="status">
                  <option value="all">Tất cả trạng thái</option>
                  <option value="active" <?= (isset($_GET['status']) && $_GET['status'] == 'active') ? 'selected' : '' ?>>Đang hiệu lực</option>
                  <option value="expiring" <?= (isset($_GET['status']) && $_GET['status'] == 'expiring') ? 'selected' : '' ?>>Sắp hết hạn</option>
                  <option value="expired" <?= (isset($_GET['status']) && $_GET['status'] == 'expired') ? 'selected' : '' ?>>Đã hết hạn</option>
                </select>
              </div>
              
              <div class="col-md-4 text-md-end d-flex align-items-end">
                <button type="submit" class="btn btn-outline-secondary w-100 w-md-auto">
                  <i class="bi bi-funnel me-1"></i> Áp dụng lọc
                </button>
              </div>
            </div>
        </form>
      </div>
    </div>

    <!-- BẢNG DỮ LIỆU -->
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover align-middle admin-table">
            <thead class="table-light">
              <tr>
                <th scope="col">Mã Code</th>
                <th scope="col">Loại</th>
                <th scope="col">Giá trị</th>
                <th scope="col">Giới hạn dùng</th>
                <th scope="col">Ngày hết hạn</th>
                <th scope="col">Trạng thái</th>
                <th scope="col" class="text-end">Hành động</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($coupons)): ?>
                <?php foreach ($coupons as $c): ?>
                  <tr>
                    <td>
                      <span class="badge bg-light text-dark border border-secondary px-3 py-2 fw-bold">
                        <?= htmlspecialchars($c['code']) ?>
                      </span>
                    </td>
                    <td>
                        <?= ($c['type'] == 'percent') ? 'Phần trăm' : 'Số tiền cố định' ?>
                    </td>
                    <td class="text-danger fw-bold">
                        <?= ($c['type'] == 'percent') 
                            ? number_format($c['value']) . '%' 
                            : number_format($c['value'], 0, ',', '.') . 'đ' ?>
                    </td>
                    <td>
                        <?= isset($c['usage_limit']) ? number_format($c['usage_limit']) : 'Không giới hạn' ?>
                    </td>
                    <td>
                        <?= isset($c['expires_at']) ? date('d/m/Y', strtotime($c['expires_at'])) : 'Vĩnh viễn' ?>
                    </td>
                    <td>
                        <?php 
                            // Hiển thị Badge dựa trên trạng thái tính toán từ Model
                            if ($c['calculated_status'] == 'active') {
                                echo '<span class="badge bg-success">Đang hiệu lực</span>';
                            } elseif ($c['calculated_status'] == 'expiring') {
                                echo '<span class="badge bg-warning text-dark">Sắp hết hạn</span>';
                            } else {
                                echo '<span class="badge bg-secondary">Đã hết hạn</span>';
                            }
                        ?>
                    </td>
                    <td class="text-end">
                      <!-- Nút Sửa mở Modal riêng cho từng dòng -->
                      <button class="btn btn-sm btn-outline-primary me-1" title="Sửa" 
                              data-bs-toggle="modal" data-bs-target="#editCouponModal_<?= $c['id'] ?>">
                        <i class="bi bi-pencil"></i>
                      </button>
                      
                      <!-- Nút Xóa -->
                      <a href="<?= BASE_URL ?>index.php/admin/deleteCoupon?id=<?= $c['id'] ?>" 
                         class="btn btn-sm btn-outline-danger" 
                         title="Xóa"
                         onclick="return confirm('Bạn có chắc chắn muốn xóa mã này không? Hành động này không thể hoàn tác.');">
                        <i class="bi bi-trash"></i>
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">
                        Không tìm thấy mã giảm giá nào phù hợp.
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

<!-- MODAL THÊM COUPON MỚI -->
<div class="modal fade" id="addCouponModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Tạo mã giảm giá mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?= BASE_URL ?>index.php/admin/addCoupon" method="POST">
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Mã Code (Viết liền, không dấu)</label>
            <input type="text" class="form-control text-uppercase" name="code" placeholder="VD: SALEHE2025" required>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Loại giảm giá</label>
              <select class="form-select" name="type">
                <option value="percent">Phần trăm (%)</option>
                <option value="fixed">Số tiền (VND)</option>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Giá trị giảm</label>
              <input type="number" class="form-control" name="value" placeholder="VD: 10 hoặc 50000" required>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Số lượng giới hạn</label>
              <input type="number" class="form-control" name="usage_limit" placeholder="Để trống nếu không giới hạn">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Ngày hết hạn</label>
              <input type="date" class="form-control" name="expires_at">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-primary">Tạo mã</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL SỬA COUPON (TẠO VÒNG LẶP MODAL CHO TỪNG ITEM) -->
<?php if (!empty($coupons)): ?>
  <?php foreach ($coupons as $c): ?>
    <div class="modal fade" id="editCouponModal_<?= $c['id'] ?>" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title fw-bold">Cập nhật mã: <?= htmlspecialchars($c['code']) ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="<?= BASE_URL ?>index.php/admin/updateCoupon" method="POST">
            <div class="modal-body">
              <input type="hidden" name="id" value="<?= $c['id'] ?>">
              
              <div class="mb-3">
                <label class="form-label">Mã Code</label>
                <input type="text" class="form-control text-uppercase" name="code" value="<?= htmlspecialchars($c['code']) ?>" required>
              </div>
              
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Loại giảm giá</label>
                  <select class="form-select" name="type">
                    <option value="percent" <?= ($c['type'] == 'percent') ? 'selected' : '' ?>>Phần trăm (%)</option>
                    <option value="fixed" <?= ($c['type'] == 'fixed') ? 'selected' : '' ?>>Số tiền (VND)</option>
                  </select>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Giá trị giảm</label>
                  <input type="number" class="form-control" name="value" value="<?= floatval($c['value']) ?>" required>
                </div>
              </div>
              
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Số lượng giới hạn</label>
                  <input type="number" class="form-control" name="usage_limit" value="<?= $c['usage_limit'] ?>" placeholder="Không giới hạn">
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Ngày hết hạn</label>
                  <!-- Chuyển đổi datetime từ DB sang định dạng Y-m-d cho input date -->
                  <input type="date" class="form-control" name="expires_at" 
                         value="<?= !empty($c['expires_at']) ? date('Y-m-d', strtotime($c['expires_at'])) : '' ?>">
                  <div class="form-text text-muted">Thay đổi ngày hết hạn để cập nhật trạng thái.</div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
              <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
      // Hàm dùng chung để cài đặt logic cho một modal (Thêm hoặc Sửa)
      function setupCouponValidation(modal) {
          const typeSelect = modal.querySelector('select[name="type"]');
          const valueInput = modal.querySelector('input[name="value"]');

          if (!typeSelect || !valueInput) return;

          function updateLimit() {
              if (typeSelect.value === 'percent') {
                  // Nếu là phần trăm, set max = 100
                  valueInput.setAttribute('max', '100');
                  valueInput.setAttribute('placeholder', 'VD: 10 (tối đa 100)');
                  
                  // Nếu giá trị đang nhập > 100 thì reset về 100
                  if (valueInput.value && parseFloat(valueInput.value) > 100) {
                      valueInput.value = 100;
                  }
              } else {
                  // Nếu là tiền cố định, bỏ max
                  valueInput.removeAttribute('max');
                  valueInput.setAttribute('placeholder', 'VD: 50000');
              }
          }

          // Lắng nghe sự kiện khi thay đổi loại giảm giá
          typeSelect.addEventListener('change', updateLimit);
          
          // Lắng nghe sự kiện khi nhập liệu để chặn ngay lập tức
          valueInput.addEventListener('input', function() {
              if (typeSelect.value === 'percent' && parseFloat(this.value) > 100) {
                  this.value = 100;
              }
          });

          // Chạy 1 lần lúc khởi tạo để set đúng trạng thái ban đầu
          updateLimit();
      }

      // 1. Áp dụng cho Modal Thêm mới
      const addModal = document.getElementById('addCouponModal');
      if (addModal) {
          setupCouponValidation(addModal);
      }

      // 2. Áp dụng cho tất cả các Modal Sửa (lấy theo class modal và id bắt đầu bằng editCouponModal_)
      const editModals = document.querySelectorAll('.modal[id^="editCouponModal_"]');
      editModals.forEach(modal => {
          setupCouponValidation(modal);
      });
  });
</script>