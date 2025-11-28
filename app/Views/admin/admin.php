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
          <div class="card-value">12.500.000đ</div>
        </div>
        <!-- <i class="bi bi-cash-stack card-icon"></i> -->
      </div>
    </div>
    <!-- Thẻ 2: Đơn hàng mới -->
    <div class="col-lg-3 col-md-6">
      <div class="kpi-card d-flex align-items-center">
        <div class="flex-grow-1">
          <div class="card-title">ĐƠN HÀNG MỚI</div>
          <div class="card-value">32</div>
        </div>
        <i class="bi bi-receipt card-icon"></i>
      </div>
    </div>
    <!-- Thẻ 3: Khách hàng mới -->
    <div class="col-lg-3 col-md-6">
      <div class="kpi-card d-flex align-items-center">
        <div class="flex-grow-1">
          <div class="card-title">KHÁCH HÀNG MỚI</div>
          <div class="card-value">15</div>
        </div>
        <i class="bi bi-person-plus card-icon"></i>
      </div>
    </div>
    <!-- Thẻ 4: Bán chạy nhất -->
    <div class="col-lg-3 col-md-6">
      <div class="kpi-card d-flex align-items-center">
        <div class="flex-grow-1">
          <div class="card-title">BÁN CHẠY NHẤT</div>
          <div class="card-value fs-5" style="color: var(--color-text-main);">Áo Khoác Nam Dù</div>
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
            <tr>
              <td><strong>#FS1005</strong></td>
              <td>Nguyễn Văn A</td>
              <td>1.750.000đ</td>
              <td><span class="badge bg-success">Đã giao</span></td>
            </tr>
            <tr>
              <td><strong>#FS1004</strong></td>
              <td>Trần Thị B</td>
              <td>1.200.000đ</td>
              <td><span class="badge bg-warning text-dark">Đang xử lý</span></td>
            </tr>
            <tr>
              <td><strong>#FS1003</strong></td>
              <td>Lê Văn C</td>
              <td>380.000đ</td>
              <td><span class="badge bg-danger">Đã hủy</span></td>
            </tr>
            <tr>
              <td><strong>#FS1002</strong></td>
              <td>Phạm Thị D</td>
              <td>850.000đ</td>
              <td><span class="badge bg-primary">Đang giao</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <!-- Cột phải: Sản phẩm bán chạy -->
    <div class="col-lg-4">
      <div class="dashboard-chart-card">
        <h5 class="mb-3">Sản phẩm bán chạy</h5>
        <ul class="list-group list-group-flush">
          <li class="list-group-item d-flex justify-content-between align-items-center px-0">
            <span class="product-info">1. Áo Khoác Nam Dù</span>
            <span class="product-sales">120 sp</span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center px-0">
            <span class="product-info">2. Giày Ultraboost Adidas</span>
            <span class="product-sales">95 sp</span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center px-0">
            <span class="product-info">3. Quần Jeans Levi's 511</span>
            <span class="product-sales">88 sp</span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center px-0">
            <span class="product-info">4. Áo Hoodie Nike</span>
            <span class="product-sales">70 sp</span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center px-0">
            <span class="product-info">5. Áo Sơ Mi Oxford</span>
            <span class="product-sales">55 sp</span>
          </li>
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
    labels: ["Ngày 1", "Ngày 5", "Ngày 10", "Ngày 15", "Ngày 20", "Ngày 25", "Ngày 30"],
    datasets: [{
      label: 'Doanh thu (VNĐ)',
      data: [1500000, 2000000, 1800000, 3000000, 2500000, 4000000, 4500000],
      fill: true,
      // Đã cập nhật sang màu #000f38 với độ trong suốt 0.1
      backgroundColor: 'rgba(0, 15, 56, 0.1)',
      // Đã cập nhật sang màu #000f38
      borderColor: '#000f38',
      tension: 0.1
    }]
  };

  const categoryData = {
    labels: [
      'Áo Khoác',
      'Quần Jeans',
      'Giày',
      'Áo Hoodie'
    ],
    datasets: [{
      label: 'Tỉ lệ danh mục',
      data: [40, 25, 20, 15],
      backgroundColor: [
        '#000f38', // Accent mới
        '#000826', // Accent Darker mới
        '#6c757d', // Xám
        '#adb5bd'  // Xám nhạt
      ],
      hoverOffset: 4
    }]
  };

  // Khởi tạo biểu đồ khi DOM đã sẵn sàng
  document.addEventListener("DOMContentLoaded", function () {
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