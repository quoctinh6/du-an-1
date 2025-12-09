<!-- CỘT 2: NỘI DUNG CHÍNH (HEADER + CONTENT) -->
<main class="col-lg-10 col-md-9 ms-sm-auto px-0">
  <!-- HEADER (MENU NGANG) -->
  <nav class="navbar navbar-expand-lg admin-top-nav shadow-sm sticky-top"> <!-- Thêm sticky-top cho header luôn -->
    <div class="container-fluid">
      <!-- Nút bật/tắt sidebar trên Mobile -->
      <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#admin-sidebar"
        aria-controls="admin-sidebar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- SỬA: Đổi 'ms-md-4' (lệch) thành 'mx-auto' (ngay chính giữa) -->
      <form class="d-flex mx-auto" style="width: 400px">
        <input class="form-control me-2" type="search" placeholder="Tìm kiếm đơn hàng, sản phẩm..."
          aria-label="Search" />
      </form>

      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
            aria-expanded="false">
            <i class="bi bi-person-circle fs-4 align-middle"></i>
            <span class="d-none d-lg-inline align-middle ms-1">Chào, Admin</span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
            <li>
              <a class="dropdown-item" href="index.html">Xem trang khách</a>
            </li>
            <li>
              <hr class="dropdown-divider" />
            </li>
            <li>
              <a class="dropdown-item" href="index.html">Đăng xuất</a>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>

  <!-- NỘI DUNG CHÍNH CỦA TRANG -->
  <div class="admin-content">
    <!-- Tiêu đề trang -->
    <h1 class="h3 mb-4 fw-bold" style="color: var(--color-text-main)">
      Tổng quan
    </h1>

    <!-- 1. CÁC THẺ KPI -->
    <div class="row g-4 mb-4">
      <!-- Thẻ 1: Doanh thu -->
      <div class="col-lg-3 col-md-6">
        <div class="kpi-card d-flex align-items-center">
          <div class="flex-grow-1">
            <div class="card-title">DOANH THU HÔM NAY</div>
            <div class="card-value"><?= number_format($totalRevenue) ?>đ</div>
          </div>
          <!-- <i class="bi bi-cash-stack card-icon"></i> -->
        </div>
      </div>
      <!-- Thẻ 2: Đơn hàng mới -->
      <div class="col-lg-3 col-md-6">
        <div class="kpi-card d-flex align-items-center">
          <div class="flex-grow-1">
            <div class="card-title">ĐƠN HÀNG MỚI</div>
            <div class="card-value"><?= $pendingOrders ?></div>
          </div>
          <i class="bi bi-receipt card-icon"></i>
        </div>
      </div>
      <!-- Thẻ 3: Khách hàng mới -->
      <div class="col-lg-3 col-md-6">
        <div class="kpi-card d-flex align-items-center">
          <div class="flex-grow-1">
            <div class="card-title">KHÁCH HÀNG MỚI</div>
            <div class="card-value"><?= $newUsers ?></div>
          </div>
          <i class="bi bi-person-plus card-icon"></i>
        </div>
      </div>
      <!-- Thẻ 4: Bán chạy nhất -->
      <div class="col-lg-3 col-md-6">
        <div class="kpi-card d-flex align-items-center">
          <div class="flex-grow-1">
            <div class="card-title">BÁN CHẠY NHẤT</div>
            <div class="card-value fs-5"><?= $topSellerName ?></div>
          </div>
          <i class="bi bi-star card-icon"></i>
        </div>
      </div>
    </div>

    <!-- 2. BIỂU ĐỒ (2 CỘT) -->
    <div class="row g-4 mb-4">
      <!-- Cột trái: Biểu đồ đường (Doanh thu) -->
      <div class="col-lg-8">
        <div class="dashboard-chart-card">
          <h5 class="mb-3">Doanh thu 30 ngày qua</h5>
          <!-- Canvas cho biểu đồ đường -->
          <canvas id="revenueChart"></canvas>
        </div>
      </div>
      <!-- Cột phải: Biểu đồ tròn (Tỉ lệ) -->
      <div class="col-lg-4">
        <div class="dashboard-chart-card">
          <h5 class="mb-3">Tỉ lệ danh mục</h5>
          <!-- Canvas cho biểu đồ tròn -->
          <canvas id="categoryChart"></canvas>
        </div>
      </div>
    </div>

    <!-- 3. BẢNG (2 CỘT) -->
    <div class="row g-4">
      <!-- Cột trái: Đơn hàng mới nhất -->
      <div class="col-lg-8">
        <div class="dashboard-chart-card">
          <h5 class="mb-3">Đơn hàng mới nhất</h5>
          <table class="table table-hover align-middle admin-table">
            <thead class="table-light">
              <tr>
                <th scope="col">Mã Đơn</th>
                <th scope="col">Khách hàng</th>
                <th scope="col">Tổng tiền</th>
                <th scope="col">Trạng thái</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($latestOrders)): ?>
                <?php foreach ($latestOrders as $order): ?>
                  <tr>
                    <td><strong>#FS<?= $order['id'] ?></strong></td>
                    <td><?= $order['user_name'] ?></td>
                    <td><?= number_format($order['total_price']) ?>đ</td>
                    <td>
                      <?php
                      $badge = match ($order['status']) {
                        'completed' => 'bg-success',
                        'pending' => 'bg-warning text-dark',
                        'cancelled' => 'bg-danger',
                        default => 'bg-primary', // shipped/processing
                      };
                      ?>
                      <span class="badge <?= $badge ?>"><?= $order['status'] ?></span>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="4" class="text-center">Chưa có đơn hàng nào gần đây.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
      <!-- Cột phải: Sản phẩm bán chạy -->
      <div class="col-lg-4">
        <div class="dashboard-chart-card">
          <h5 class="mb-3">Sản phẩm bán chạy</h5>
          <ul class="list-group list-group-flush">
            <?php if (!empty($bestSellers)): ?>
              <?php foreach ($bestSellers as $index => $item): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                  <span class="product-info"><?= $index + 1 ?>. <?= $item['product_name'] ?></span>
                  <span class="product-sales"><?= $item['total_sold'] ?> sp</span>
                </li>
              <?php endforeach; ?>
            <?php else: ?>
              <li class="list-group-item px-0 text-center text-muted">Chưa có dữ liệu bán hàng.</li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </div>


  </div>
  <!-- end .admin-content -->
</main>
<!-- end .col-lg-10 -->
</div>
<!-- end .d-flex -->

<!-- Bootstrap 5.3.2 JS Bundle CDN -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
  xintegrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

<!-- JS Demo cho Biểu đồ -->
<script>
  // Dữ liệu mẫu
  const revenueData = {
    // LẤY TỪ CONTROLLER
    labels: <?= $revenueLabelsJson ?>,
    datasets: [{
      label: 'Doanh thu (VNĐ)',
      // LẤY TỪ CONTROLLER
      data: <?= $revenueDataJson ?>,
      fill: true,
      // Đã cập nhật sang màu #000f38 với độ trong suốt 0.1
      backgroundColor: 'rgba(0, 15, 56, 0.1)',
      // Đã cập nhật sang màu #000f38
      borderColor: '#000f38',
      tension: 0.1
    }]
  };

  const categoryData = {
    // Lấy tên danh mục từ PHP
    labels: <?= $categoryLabelsJson ?>,
    datasets: [{
      label: 'Tỉ lệ danh mục',
      // Lấy số lượng sản phẩm từ PHP
      data: <?= $categoryDataJson ?>,
      backgroundColor: [
        '#0d2d85ff',
        '#000826',
        '#6c757d',
        '#adb5bd'
        // Lưu ý: Nếu có nhiều hơn 4 danh mục, phải thêm màu vào đây
      ],
      hoverOffset: 4
    }]
  };

  // Khởi tạo biểu đồ khi DOM đã sẵn sàng
  document.addEventListener("DOMContentLoaded", function() {
    // Biểu đồ đường
    const ctxRevenue = document.getElementById('revenueChart');
    if (ctxRevenue) {
      new Chart(ctxRevenue, {
        type: 'line',
        data: revenueData,
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: 'top',
            },
            title: {
              display: false,
              text: 'Doanh thu 30 ngày qua'
            }
          }
        }
      });
    }

    // Biểu đồ tròn
    const ctxCategory = document.getElementById('categoryChart');
    if (ctxCategory) {
      new Chart(ctxCategory, {
        type: 'doughnut',
        data: categoryData,
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: 'bottom',
            },
            title: {
              display: false,
              text: 'Tỉ lệ danh mục'
            }
          }
        }
      });
    }
  });
</script>

</body>

</html>