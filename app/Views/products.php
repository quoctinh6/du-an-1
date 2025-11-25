<?php
// === HÀM RENDER HTML ===
function renderProductCard($item) {
    $base = defined('BASE_URL') ? BASE_URL : '';
    // Xử lý link: Ưu tiên slug, nếu ko có thì dùng id
    $slug = $item['slug'] ?? $item['id']; 
    $detailLink = $base . "product/detail/" . $slug;
    
    // Xử lý giá tiền
    $priceFormat = number_format($item['price'], 0, ',', '.') . ' VND';
    
    // SỬA 1: Dùng 'image_url' thay vì 'image' cho khớp với Model
    $imgSrc = $item['image_url'] ?? 'default.png'; 
    // Nếu ảnh chưa có đường dẫn đầy đủ (http...), nối thêm base url
    if (!filter_var($imgSrc, FILTER_VALIDATE_URL)) {
        $imgSrc = $base . "Views/assets/img/" . $imgSrc; 
    }

    // Xử lý giá cũ (chỉ hiện khi có giá trị và lớn hơn giá bán)
    $oldPriceHtml = '';
    if (!empty($item['old_price']) && $item['old_price'] > $item['price']) {
        $oldFormat = number_format($item['old_price'], 0, ',', '.');
        $oldPriceHtml = "<span class=\"product-old-price\">{$oldFormat}</span>";
    }

    return <<<HTML
    <div class="product-box">
        <div class="product-icons">
            <button class="icon-btn" aria-label="Add to cart">
                <svg xmlns="http://www.w3.org/2000/svg" height="22" width="22" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M7 18c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm10 0c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm1.604-2.083l2.396-9.917h-16v-2h-3v2h1.604l3.452 13.917a2 2 0 0 0 1.944 1.25h10.192a2 2 0 0 0 1.944-1.25l.588-2.333zm-13.604-11.083v-2h16v2h-16z" />
                </svg>
            </button>
            <button class="icon-btn" aria-label="Add to favorites">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" height="22" width="22" viewBox="0 0 24 24">
                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 1.01 4.5 2.09C13.09 4.01 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                </svg>
            </button>
        </div>
        <a href="{$detailLink}">
            <img src="{$imgSrc}" alt="{$item['name']}" class="product-img">
        </a>
        <div class="product-name">{$item['name']}</div>
        <div class="product-price">{$oldPriceHtml} {$priceFormat}</div>
        <button class="buy-btn" onclick="window.location.href='{$detailLink}'">Mua ngay</button>
    </div>
HTML;
}
?>

<!-- Product Page Main Content -->
<section class="section product-page-section scroll-reveal" id="product-list">
    <div class="section-header">
        <div class="section-title">Khám Phá Toàn Bộ Bộ Sưu Tập</div>
    </div>

    <div class="product-page-container">
        <!-- SỬA 2: Thêm thẻ FORM bao quanh Sidebar để bộ lọc hoạt động -->
        <form class="filter-sidebar" action="" method="GET">
            <h3 class="filter-title">Bộ Lọc Sản Phẩm</h3>
            
            <!-- 1. Lọc theo Danh mục -->
            <div class="filter-group">
                <div class="filter-group-title">Danh Mục</div>
                <!-- Thêm name="category[]" và value -->
                <label class="filter-checkbox-container">Đồng hồ cơ (ID:1)
                    <input type="checkbox" name="category[]" value="1" <?php echo (isset($_GET['category']) && in_array(1, $_GET['category'])) ? 'checked' : ''; ?>>
                    <span class="checkmark"></span>
                </label>
                <label class="filter-checkbox-container">Đồng hồ thông minh (ID:2)
                    <input type="checkbox" name="category[]" value="2" <?php echo (isset($_GET['category']) && in_array(2, $_GET['category'])) ? 'checked' : ''; ?>>
                    <span class="checkmark"></span>
                </label>
                <label class="filter-checkbox-container">Đồng hồ pin (ID:3)
                    <input type="checkbox" name="category[]" value="3" <?php echo (isset($_GET['category']) && in_array(3, $_GET['category'])) ? 'checked' : ''; ?>>
                    <span class="checkmark"></span>
                </label>
            </div>

            <!-- 2. Lọc theo Giá tiền -->
            <!-- Lưu ý: Range slider cần JS để update số tiền, ở đây làm cơ bản -->
            <div class="filter-group">
                <div class="filter-group-title">Khoảng Giá (VND)</div>
                <div class="price-range">
                    <input type="range" name="price_max" min="1000000" max="100000000" value="<?php echo $_GET['price_max'] ?? 50000000; ?>" class="price-slider" id="price-range-slider">
                    <div class="price-values">
                        <span>1 Triệu</span>
                        <span id="current-price-display">Max</span>
                        <span>100 Triệu</span>
                    </div>
                </div>
            </div>

            <!-- 3. Lọc theo Thương hiệu -->
            <div class="filter-group">
                <div class="filter-group-title">Thương Hiệu</div>
                <!-- Thêm name="brand" -->
                <select class="filter-select" name="brand">
                    <option value="all">Tất cả</option>
                    <option value="1" <?php echo (isset($_GET['brand']) && $_GET['brand'] == 1) ? 'selected' : ''; ?>>Rolex (ID:1)</option>
                    <option value="2" <?php echo (isset($_GET['brand']) && $_GET['brand'] == 2) ? 'selected' : ''; ?>>Omega (ID:2)</option>
                    <option value="3" <?php echo (isset($_GET['brand']) && $_GET['brand'] == 3) ? 'selected' : ''; ?>>Seiko (ID:3)</option>
                </select>
            </div>
            
            <!-- Input ẩn để giữ từ khóa tìm kiếm nếu có -->
            <?php if(isset($_GET['search'])): ?>
                <input type="hidden" name="search" value="<?php echo htmlspecialchars($_GET['search']); ?>">
            <?php endif; ?>

            <div class="btn">
                <button type="submit">Áp Dụng Lọc</button>
                <a href="?remove_filter=1" style="display:block; text-align:center; margin-top:10px; font-size:12px;">Xóa bộ lọc</a>
            </div>
        </form>

        <!-- Cột 2-4: Danh sách Sản phẩm -->
        <div class="product-result-grid">
            <?php 
            // === DEBUG CHECK: Nếu vẫn lỗi thì bỏ comment dòng dưới để kiểm tra ===
            // var_dump($products); 

            if (!empty($products)) {
                foreach ($products as $item) {
                    echo renderProductCard($item);
                }
            } else {
                echo "<div style='grid-column: 1/-1; text-align: center; padding: 50px;'>";
                echo "<h3>Không tìm thấy sản phẩm nào.</h3>";
                echo "<p>Vui lòng thử bỏ bớt bộ lọc hoặc kiểm tra lại kết nối Database.</p>";
                echo "</div>";
            }
            ?>
        </div>
    </div>
</section>