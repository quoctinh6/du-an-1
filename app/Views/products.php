<?php
// === HÀM RENDER HTML ===
function renderProducts($item)
{
    $formatted_price = number_format($item['price'], 0) . ' VND';
    $product_id = htmlspecialchars($item['id']);
    $product_price = htmlspecialchars($item['price']);
    // Bảo mật: Dùng htmlspecialchars để tránh lỗi XSS
    $image_url = htmlspecialchars($item['image_url']);
    $product_name = htmlspecialchars($item['name']);
    $detail_url = 'index.php/products/detail/' . htmlspecialchars($item['slug']);
    $slug = $item['slug'] ?? $item['id'];
    $detailLink = BASE_URL . "index.php/products/detail/" . $slug;

    $priceFormat = number_format($item['price'], 0, ',', '.') . ' VND';

    $imgSrc = $item['image_url'] ?? 'default.png';
    if (!filter_var($imgSrc, FILTER_VALIDATE_URL)) {
        $imgSrc = BASE_URL . "Views/assets/img/" . $imgSrc;
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
            <button class="icon-btn btn-add-to-cart" 
        aria-label="Add to cart"
        data-id="$product_id;" 
        data-name="$product_name;" 
        data-price="$product_price" 
        data-image="$image_url">
        
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
function renderBrands($item)
{
    $currentBrand = $_GET['brand'] ?? '';

    $isSelected = ($currentBrand == $item['id']) ? 'selected' : '';

    return <<<HTML
    <option value="{$item['id']}" {$isSelected}>
        {$item['name']}
    </option>
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
        <?php
        // Chuẩn hoá selected categories từ GET: có thể là array hoặc chuỗi '1,2'
        $selected_categories = [];
        if (isset($_GET['category'])) {
            if (is_array($_GET['category'])) {
                $selected_categories = $_GET['category'];
            } else {
                $selected_categories = array_filter(array_map('trim', explode(',', $_GET['category'])));
            }
        }
        ?>
        <form class="filter-sidebar" action="" method="GET">
            <h3 class="filter-title">Bộ Lọc Sản Phẩm</h3>
            <div class="sidebar-search-container"><input type="text" name="search" class="sidebar-search-input"
                    value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                    placeholder="Tên sản phẩm..."></div>



            <!-- 1. Lọc theo Danh mục -->
            <div class="filter-group">
                <div class="filter-group-title">Danh Mục</div>
                <!-- Thêm name="category[]" và value -->
                <label class="filter-checkbox-container">Đồng hồ cơ (ID:1)
                    <input type="checkbox" class="category-checkbox" value="1" <?php echo in_array('1', $selected_categories) ? 'checked' : ''; ?>>
                    <span class="checkmark"></span>
                </label>
                <label class="filter-checkbox-container">Đồng hồ thông minh (ID:2)
                    <input type="checkbox" class="category-checkbox" value="2" <?php echo in_array('2', $selected_categories) ? 'checked' : ''; ?>>
                    <span class="checkmark"></span>
                </label>
                <label class="filter-checkbox-container">Đồng hồ pin (ID:3)
                    <input type="checkbox" class="category-checkbox" value="3" <?php echo in_array('3', $selected_categories) ? 'checked' : ''; ?>>
                    <span class="checkmark"></span>
                </label>
            </div>

            <!-- 2. Lọc theo Giá tiền -->
            <!-- Lưu ý: Range slider cần JS để update số tiền, ở đây làm cơ bản -->
            <div class="filter-group price-filter-modern">
                <!-- <div class="filter-group-title">Khoảng Giá</div>

                <div class="price-inputs">
                    <div class="input-wrapper">
                        <span class="currency">₫</span>
                        <input type="number" id="price-min-input" value="1000000" min="1000000" class="input-price">
                    </div>
                    <span class="separator">-</span>
                    <div class="input-wrapper">
                        <span class="currency">₫</span>
                        <input type="number" id="price-max-input" name="price_max"
                            value="<?php echo $_GET['price_max'] ?? 50000000; ?>" max="100000000" class="input-price">
                    </div>
                </div> -->




            </div>

            <!-- 3. Lọc theo Thương hiệu -->
            <div class="filter-group">
                <div class="filter-group-title">Thương Hiệu</div>
                <!-- Thêm name="brand" -->
                <select class="filter-select" name="brand">
                    <option value="all">Tất cả</option>
                    <?php
                    if ($brands) {
                        foreach ($brands as $item) {
                            echo renderBrands($item);
                        }
                    }
                    ?>
                </select>
            </div>

            <!-- Hidden consolidated category input (will receive comma-separated ids) -->
            <input type="hidden" name="category" id="category-hidden" value="">

            <div class="filter-actions-bottom">
                <button class="btn-filter-action btn-reset"> <a href="?">Thiết lập lại</a></button>
                <button class="btn-filter-action btn-apply" type="submit">Áp dụng</button>
            </div>
        </form>

        <!-- Cột 2-4: Danh sách Sản phẩm -->
        <div class="product-result-grid">
            <?php
            // === DEBUG CHECK: Nếu vẫn lỗi thì bỏ comment dòng dưới để kiểm tra ===
            // var_dump($products); 
            
            if (!empty($products)) {
                foreach ($products as $item) {
                    echo renderProducts($item);
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