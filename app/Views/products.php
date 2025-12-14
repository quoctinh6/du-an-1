<?php
// === HÀM RENDER HTML ===
function renderProducts($item)
{
    $slug = $item['slug'] ?? $item['id'];
    $detailLink = BASE_URL . "index.php/products/detail/" . $slug;
    $product_id = htmlspecialchars($item['id']);

    // Format giá sản phẩm
    $priceFormat = number_format($item['price'] ?? 0, 0, ',', '.') . ' VNĐ';

    $imgSrc = $item['image_url'] ?? 'default.png';
    if (!filter_var($imgSrc, FILTER_VALIDATE_URL)) {
        $imgSrc = BASE_URL . "uploads/products/" . $imgSrc;
    }
    $favor_add_url = BASE_URL . 'index.php/favor/add';

    // Xử lý giá cũ (chỉ hiện khi có giá trị và lớn hơn giá bán)
    $oldPriceHtml = '';
    if (!empty($item['old_price']) && $item['old_price'] > $item['price']) {
        $oldFormat = number_format($item['old_price'], 0, ',', '.');
        $oldPriceHtml = "<span class=\"product-old-price\">{$oldFormat}</span>";
    }

    return <<<HTML
    <div class="product-box">
        <div class="product-icons">
            <form action="{$favor_add_url}" method="POST" style="display:inline;" class="form-add-to-favor">
                <input type="hidden" name="id" value="$product_id">
                <button type="submit" class="icon-btn" aria-label="Add to favorites">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" height="22" width="22" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 1.01 4.5 2.09C13.09 4.01 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" /></svg>
                </button>
        </div>
        <a href="{$detailLink}">
            <img src="{$imgSrc}" alt="{$item['name']}" class="product-img">
        </a>
        <div class="product-name">{$item['name']}</div>
        <div class="product-price">{$oldPriceHtml} {$priceFormat}</div>
        <button class="buy-btn" type="button" onclick="window.location.href='{$detailLink}'">Xem Chi Tiết</button>
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
            <div class="filter-group">
                <div class="filter-group-title">Tìm kiếm</div>
                <div class="sidebar-search-container">
                    <input type="text" name="search"
                        value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                        class="sidebar-search-input" placeholder="Tên sản phẩm...">
                </div>
            </div>


            <!-- 1. Lọc theo Danh mục -->
            <div class="filter-group">
                <div class="filter-group-title">Danh Mục</div>
                <?php
                if (!empty($category_all)) {
                    foreach ($category_all as $cat) {
                        $cat_id = htmlspecialchars($cat['id']);
                        $cat_name = htmlspecialchars($cat['name']);
                        $is_checked = in_array($cat_id, $selected_categories) ? 'checked' : '';
                        echo <<<HTML
                        <label class="filter-checkbox-container">{$cat_name}
                            <input type="checkbox" name="category[]" class="category-checkbox" value="{$cat_id}" {$is_checked}>
                            <span class="checkmark"></span>
                        </label>
HTML;
                    }
                }
                ?>
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var form = document.querySelector('.filter-sidebar');
            if (!form) return;
            var hidden = document.getElementById('category-hidden');
            var checkboxes = form.querySelectorAll('input.category-checkbox[name="category[]"]');

            function syncHidden() {
                var vals = Array.prototype.slice.call(checkboxes).filter(function (c) {
                    return c.checked;
                }).map(function (c) {
                    return c.value;
                });
                hidden.value = vals.join(',');
            }

            checkboxes.forEach(function (ch) {
                ch.addEventListener('change', syncHidden);
            });
            // initialize on load
            syncHidden();
        });
    </script>

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
                        try {
                            payload = JSON.parse(text);
                        } catch (err) {
                            console.error('CART JSON ERROR:', text);
                            alert('Có lỗi xảy ra khi thêm giỏ hàng!');
                            return;
                        }

                        if (!payload || !payload.success) {
                            alert(payload?.message || 'Không thể thêm vào giỏ');
                            return;
                        }
                        alert('Đã thêm vào giỏ hàng thành công!');
                        const headerCount = document.getElementById('header-cart-count');
                        if (headerCount) headerCount.innerText = '(' + (payload.totalQty ?? 0) + ')';
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Lỗi kết nối giỏ hàng!');
                    });
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