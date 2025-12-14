<?php
// === HÀM RENDER HTML RIÊNG CHO TRANG FAVOR ===
function renderFavorProducts($item)
{
  $slug = $item['slug'] ?? $item['id'];
  $detailLink = BASE_URL . "index.php/products/detail/" . $slug;

  // Sửa đường dẫn Xóa để trỏ về FavorCtrl
  $removeLink = BASE_URL . "index.php/favor/remove?id=" . $item['id'];

  $priceFormat = number_format($item['price'], 0, ',', '.') . ' VND';

  $imgSrc = $item['image_url'] ?? 'default.png';
  if (!filter_var($imgSrc, FILTER_VALIDATE_URL)) {
    $imgSrc = BASE_URL . "uploads/products/" . $imgSrc;
  }

  $product_id = $item['id'];
  $product_name = htmlspecialchars($item['name']);
  $product_price = $item['price'];

  return <<<HTML
    <div class="product-box favor-product-box">
        <div class="product-icons">
            <button class="icon-btn remove-favor-btn" onclick="window.location.href='{$removeLink}'" aria-label="Remove from favorites" title="Xóa khỏi yêu thích">
              <svg class="icon-heart" xmlns="http://www.w3.org/2000/svg" fill="currentColor" height="22" width="22" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 1.01 4.5 2.09C13.09 4.01 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
              <svg class="icon-close" xmlns="http://www.w3.org/2000/svg" height="22" width="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <a href="{$detailLink}">
            <img src="{$imgSrc}" alt="{$item['name']}" class="product-img">
        </a>
        <div class="product-name">{$item['name']}</div>
        <div class="product-price">{$priceFormat}</div>
        <button class="buy-btn" onclick="window.location.href='{$detailLink}'">Mua ngay</button>
    </div>
HTML;
}

function renderFavorBrands($item)
{
  $currentBrand = $_GET['brand'] ?? '';
  $isSelected = ($currentBrand == $item['id']) ? 'selected' : '';
  return "<option value=\"{$item['id']}\" {$isSelected}>{$item['name']}</option>";
}
?>

<section class="section product-page-section" id="favor-list">
  <div class="section-header">
    <div class="section-title">Danh Sách Yêu Thích</div>
  </div>

  <div class="product-page-container">

    <?php
    $selected_categories = [];
    if (isset($_GET['category'])) {
      if (is_array($_GET['category'])) {
        $selected_categories = $_GET['category'];
      } else {
        $selected_categories = array_filter(array_map('trim', explode(',', $_GET['category'])));
      }
    }
    ?>
    <form class="filter-sidebar favor-sidebar" action="" method="GET">
      <h3 class="filter-title">Bộ Lọc Yêu Thích</h3>

      <div class="sidebar-search-container">
        <input type="text" name="search" class="sidebar-search-input"
          value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
          placeholder="Tên sản phẩm...">
      </div>

      <div class="filter-group">
        <div class="filter-group-title">Danh Mục</div>
        <label class="filter-checkbox-container">Đồng hồ cơ (ID:1)
          <input type="checkbox" name="category[]" value="1" <?php echo in_array('1', $selected_categories) ? 'checked' : ''; ?>>
          <span class="checkmark"></span>
        </label>
        <label class="filter-checkbox-container">Đồng hồ thông minh (ID:2)
          <input type="checkbox" name="category[]" value="2" <?php echo in_array('2', $selected_categories) ? 'checked' : ''; ?>>
          <span class="checkmark"></span>
        </label>
        <label class="filter-checkbox-container">Đồng hồ pin (ID:3)
          <input type="checkbox" name="category[]" value="3" <?php echo in_array('3', $selected_categories) ? 'checked' : ''; ?>>
          <span class="checkmark"></span>
        </label>
      </div>

      <div class="filter-group">
        <div class="filter-group-title">Lọc theo Hãng</div>
        <select class="filter-select" name="brand">
          <option value="all">Tất cả</option>
          <?php
          if (!empty($brands)) {
            foreach ($brands as $item) {
              echo renderFavorBrands($item);
            }
          }
          ?>
        </select>
      </div>

      <div class="filter-actions-bottom">
        <!-- Sửa đường dẫn Reset về FavorCtrl -->
        <button class="btn-filter-action btn-reset" type="button" onclick="window.location.href='index.php/favor'">Thiết
          lập lại</button>
        <button class="btn-filter-action btn-apply" type="submit">Áp dụng</button>
      </div>
    </form>

    <div class="product-result-grid">
      <?php
      if (!empty($products)) {
        foreach ($products as $item) {
          echo renderFavorProducts($item);
        }
      } else {
        echo "<div style='grid-column: 1/-1; text-align: center; padding: 50px;'>";
        echo "<h3>Danh sách yêu thích trống hoặc không tìm thấy sản phẩm phù hợp.</h3>";
        echo "</div>";
      }
      ?>
    </div>
  </div>
</section>
<script>
  document.addEventListener('DOMContentLoaded', function () {

    // 1. ADD TO CART
    const cartForms = document.querySelectorAll(".form-add-to-cart");
    cartForms.forEach(form => {
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(form);
        formData.append('is_ajax', '1');

        fetch(form.action, {
          method: 'POST',
          body: formData
        })
          .then(response => response.text())
          .then(text => {
            let payload;
            try { payload = JSON.parse(text); }
            catch (err) { console.error('CART JSON ERROR:', text); alert('Có lỗi xảy ra khi thêm giỏ hàng!'); return; }

            if (!payload || !payload.success) {
              alert(payload?.message || 'Không thể thêm vào giỏ');
              return;
            }
            alert('Đã thêm vào giỏ hàng thành công!');
            const headerCount = document.getElementById('header-cart-count');
            if (headerCount) headerCount.innerText = '(' + (payload.totalQty ?? 0) + ')';
          })
          .catch(err => { console.error(err); alert('Lỗi kết nối giỏ hàng!'); });
      });
    });

    // 2. ADD TO FAVORITES
    const favorForms = document.querySelectorAll(".form-add-to-favor");
    favorForms.forEach(form => {
      form.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(form);
        formData.append('is_ajax', '1');

        fetch(form.action, {
          method: 'POST',
          body: formData
        })
          .then(response => response.text())
          .then(text => {
            let payload;
            try {
              payload = JSON.parse(text);
            } catch (err) {
              console.error('FAVOR JSON ERROR:', text);
              // Check lỗi 404
              if (text.includes('404') || text.includes('Not Found')) {
                alert('Lỗi 404: Đường dẫn không tồn tại. Kiểm tra Router.');
              } else {
                alert('Có lỗi hệ thống khi thêm yêu thích!');
              }
              return;
            }

            if (payload && payload.success) {
              alert(payload.message);
            } else {
              alert('Không thể thêm vào yêu thích: ' + (payload?.message || 'Lỗi lạ'));
            }
          })
          .catch(err => {
            console.error(err);
            alert('Lỗi kết nối mạng!');
          });
      });
    });

  });
</script>