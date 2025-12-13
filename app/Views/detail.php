<?php
// 1. Chuẩn bị dữ liệu từ PHP (Chuyển đổi để JS đọc được)
$variants_json = json_encode($product_variants ?? []);
$p_id = $product_base['id'] ?? 0;
$p_name = htmlspecialchars($product_base['name'] ?? '');
$p_sku = htmlspecialchars($product_base['slug'] ?? '');

$html_products_featured = '';

function render_product_item($item)
{
  $formatted_price = number_format($item['price'] ?? 0, 0, ',', '.') . ' VND';
  $product_id = htmlspecialchars($item['id'] ?? '');
  $product_price = htmlspecialchars($item['price'] ?? '');
  $image_url = htmlspecialchars($item['image_url'] ?? '');

  if (!filter_var($image_url, FILTER_VALIDATE_URL)) {
    $image_url = BASE_URL . 'uploads/products/' . $image_url;
  }

  $product_name = htmlspecialchars($item['name'] ?? '');
  $detail_url = BASE_URL . 'index.php/products/detail/' . htmlspecialchars($item['slug'] ?? $item['id']);
  $favor_add_url = BASE_URL . 'index.php/favor/add';
  $old_price_html = '';

  if (!empty($item['old_price']) && $item['old_price'] > $item['price']) {
    $formatted_old = number_format($item['old_price'] ?? 0, 0, ',', '.');
    $old_price_html = "<span class=\"product-old-price\">{$formatted_old}</span>";
  }

  return <<<HTML
    <div class="product-box">
        <div class="product-icons">          
            <!-- Form Thêm Yêu Thích -->
            <form action="{$favor_add_url}" method="POST" style="display:inline;" class="form-add-to-favor">
                <input type="hidden" name="id" value="$product_id">
                <button type="submit" class="icon-btn" aria-label="Add to favorites">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" height="22" width="22" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 1.01 4.5 2.09C13.09 4.01 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" /></svg>
                </button>
            </form>
        </div>
        
        <a href="$detail_url">
            <img src="$image_url" alt="$product_name" class="product-img">
        </a>
        
        <div class="product-name">$product_name</div>
        <div class="product-price">$old_price_html $formatted_price</div>
        
        <button class="buy-btn" onclick="window.location.href='$detail_url'">Xem chi tiết</button>
    </div>
HTML;
}

$html_products = '';
if (!empty($product_category)) {
  foreach ($product_category as $item)
    $html_products .= render_product_item($item);
}
?>

<!-- Link CSS riêng -->
<link rel="stylesheet" href="<?= BASE_URL ?>Views/assets/css/detail.css" />

<section class="product-section">
  <!-- KHỐI 1: HÌNH ẢNH & THÔNG TIN CHÍNH -->
  <section class="product-main-grid scroll-reveal">

    <!-- Cột Trái: Thư viện ảnh -->
    <div class="product-gallery-box">
      <button class="gallery-nav-btn left" aria-label="Previous image">&lt;</button>
      <div class="main-image-area">
        <!-- Ảnh chính sẽ được JS cập nhật -->
        <img src="" alt="<?= $p_name ?>" id="mainProductImage"
          onerror="this.src='https://placehold.co/600x600?text=No+Image'">
      </div>
      <div class="thumb-gallery-area" id="productThumbnails">
        <!-- JS sẽ tạo thumbnail ở đây -->
      </div>
      <button class="gallery-nav-btn right" aria-label="Next image">&gt;</button>
    </div>

    <!-- Cột Phải: Thông tin & Chọn biến thể -->
    <div class="product-details-box">
      <div>
        <span class="product-dt-name"><?= $p_name ?></span>
        <div class="product-sku">SKU: <?= $p_sku ?></div>
        <div class="product-dt-price">Loading...</div>
      </div>

      <!-- Chọn Màu -->
      <div class="attribute-group">
        <div class="attribute-label">Màu sắc</div>
        <div class="color-options" id="colorContainer"></div>
      </div>

      <!-- Chọn Size -->
      <div class="attribute-group">
        <div class="attribute-label">Kích thước</div>
        <div class="type-options" id="sizeContainer"></div>
      </div>

      <!-- Chọn Số lượng -->
      <div class="quantity-group">
        <div class="attribute-label">Số lượng</div>
        <div class="quantity-selector">
          <button type="button" class="quantity-btn" id="decrementQty">-</button>
          <input type="number" readonly class="quantity-value" id="quantityInput" value="1" min="1">
          <button type="button" class="quantity-btn" id="incrementQty">+</button>
        </div>
      </div>

      <!-- FORM ẨN (Gửi dữ liệu sang Giỏ hàng) -->
      <form id="addToCartForm" action="<?= BASE_URL ?>index.php/cart/add" method="POST">
        <!-- Các input này sẽ được JS điền giá trị vào -->
        <input type="hidden" name="id" value="<?= $p_id ?>">
        <input type="hidden" name="name" value="<?= $p_name ?>">
        <input type="hidden" name="price" id="form_price" value="">
        <input type="hidden" name="variant_id" id="form_variant_id" value="">
        <input type="hidden" name="image" id="form_image" value="">
        <input type="hidden" name="quantity" id="form_quantity" value="1">

        <!-- Biến này sẽ được JS bật lên 1 nếu dùng AJAX -->
        <input type="hidden" name="is_ajax" id="is_ajax_input" value="0">

        <div class="action-group">
          <!-- Nút Mua ngay: Submit thường (chuyển trang) -->
          <button type="submit" name="buy_now" value="1" class="glass-button" id="btnBuyNow">Mua ngay</button>

          <!-- Nút Thêm vào giỏ: Submit và chặn lại bằng JS để dùng AJAX -->
          <button type="submit" name="add_to_cart" value="1" class="cta-btn secondary" id="btnAddToCart">Thêm vào
            giỏ</button>
        </div>
      </form>

    </div>
  </section>

  <!-- KHỐI 2: MÔ TẢ CHI TIẾT -->
  <section class="product-info-grid scroll-reveal">
    <div class="description-box">
      <h2>Mô tả sản phẩm</h2>
      <div class="description-content">
        <?= $product_base['description'] ?? 'Chưa có mô tả.' ?>
      </div>
    </div>
    <div class="specs-box">
      <h2>Thông số kỹ thuật</h2>
      <table class="specs-table">
        <tr>
          <td>Thương hiệu: Zero Watch</td>
        </tr>
        <tr>
          <td>Bảo hành: 2 Năm chính hãng</td>
        </tr>
        <tr>
          <td>Chống nước: 5 ATM</td>
        </tr>
      </table>
    </div>
  </section>

  <!-- SECTION ĐÁNH GIÁ & BÌNH LUẬN MỚI -->
  <section class="reviews-section-container scroll-reveal">
    <div class="reviews-wrapper">
      <div class="reviews-header">Đánh giá & Bình luận</div>

      <!-- Danh sách đánh giá -->
      <div class="review-list" id="reviewList">
        <?php if (!empty($product_comments)): ?>
          <?php foreach ($product_comments as $comment): ?>
            <div class="review-item">
              <div class="review-content-area">
                <div class="review-meta">
                  <span class="review-author"><?= htmlspecialchars($comment['name']) ?></span>
                  <div class="star-display">
                    <?= str_repeat('★', $comment['rating']) . str_repeat('☆', 5 - $comment['rating']) ?>
                  </div>
                  <span class="review-date"><?= date('d/m/Y H:i', strtotime($comment['created_at'])) ?></span>
                </div>
                <div class="review-text"><?= htmlspecialchars($comment['content']) ?></div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p style="color: #999; text-align: center; padding: 20px;">Chưa có bình luận nào. Hãy là người đầu tiên bình
            luận!</p>
        <?php endif; ?>
      </div>

      <!-- Form viết đánh giá mới -->
      <div class="add-review-box">
        <div class="add-review-title">Viết đánh giá của bạn</div>
        <?php if (!isset($_SESSION['user'])): ?>
          <div style="padding: 20px; background: #f9f9f9; border-radius: 8px; color: #d9534f; text-align: center;">
            <p>Vui lòng <a href="<?= BASE_URL ?>index.php/User/login"
                style="color: #0066cc; text-decoration: underline;">đăng nhập</a> để bình luận</p>
          </div>
        <?php elseif (!$user_bought): ?>
          <div style="padding: 20px; background: #f9f9f9; border-radius: 8px; color: #d9534f; text-align: center;">
            <p>Bạn cần phải mua sản phẩm này để có thể bình luận</p>
          </div>
        <?php else: ?>
          <form method="POST" action="<?= BASE_URL ?>index.php/products/addComment">
            <input type="hidden" name="product_id" value="<?= $product_base['id'] ?>">

            <div class="review-form-group">
              <label class="review-form-label">Đánh giá sao:</label>
              <div class="star-rating-input">
                <!-- Lưu ý: CSS flex-direction: row-reverse để hover hoạt động đúng từ phải sang trái -->
                <input type="radio" id="star5" name="rating" value="5" required /><label for="star5"
                  title="5 stars">★</label>
                <input type="radio" id="star4" name="rating" value="4" /><label for="star4" title="4 stars">★</label>
                <input type="radio" id="star3" name="rating" value="3" /><label for="star3" title="3 stars">★</label>
                <input type="radio" id="star2" name="rating" value="2" /><label for="star2" title="2 stars">★</label>
                <input type="radio" id="star1" name="rating" value="1" /><label for="star1" title="1 star">★</label>
              </div>
            </div>
            <div class="review-form-group">
              <label class="review-form-label">Bình luận:</label>
              <textarea class="review-textarea" name="content" placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm..."
                required minlength="5"></textarea>
            </div>
            <button type="submit" class="submit-review-btn">Gửi đánh giá</button>
          </form>
        <?php endif; ?>
      </div>
    </div>
  </section>

</section>
<section class="section scroll-reveal" id="trending-products">
  <div class="section-header">
    <div class="section-title">Sản phẩm liên quan</div>
    <button class="section-action"
      onclick="window.location.href='<?= BASE_URL ?>index.php/products/?category=<?= $product_base['category_id'] ?>'">>></button>
  </div>
  <div class="product-grid">
    <!-- Four Trending Product Boxes -->
    <?= $html_products ?>
    <div class="product-box">

    </div>
</section>

<!-- SCRIPT XỬ LÝ LOGIC (Màu, Size, Giá) -->
<script>
  // Nhận dữ liệu JSON từ PHP
  const rawData = <?= $variants_json ?>;

  // Bảng mã màu
  const colorHexMap = {
    'Black': '#000000', 'Silver': '#C0C0C0', 'Gold': '#FFD700',
    'Rose Gold': '#B76E79', 'Blue': '#001f9f', 'White': '#FFFFFF'
  };

  // DOM Elements
  const dom = {
    mainImg: document.getElementById('mainProductImage'),
    thumbArea: document.getElementById('productThumbnails'),
    colorContainer: document.getElementById('colorContainer'),
    sizeContainer: document.getElementById('sizeContainer'),
    price: document.querySelector('.product-dt-price'),
    sku: document.querySelector('.product-sku'),
    qtyInput: document.getElementById('quantityInput'),
    formPrice: document.getElementById('form_price'),
    formVariantId: document.getElementById('form_variant_id'),
    formImage: document.getElementById('form_image'),
    formQty: document.getElementById('form_quantity'),
    isAjaxInput: document.getElementById('is_ajax_input')
  };

  let productData = [];
  let state = { colorIndex: 0, sizeIndex: 0, imgIndex: 0 };
  const formatMoney = (amount) => new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);

  // Hàm xử lý dữ liệu
  function processData(list) {
    const groups = {};
    list.forEach(item => {
      const cName = item.colors || 'Default';
      const colorCode = item.code || '#ccc'
      if (!groups[cName]) groups[cName] = { colorName: cName, colorCode: colorCode, images: [], variants: [] };

      if (item.image && !groups[cName].images.includes(item.image)) groups[cName].images.push(item.image);

      groups[cName].variants.push({
        id: item.id,
        size: item.sizes,
        price: parseFloat(item.price),
        quantity: parseInt(item.quantity),
        sku: item.sku
      });
    });
    Object.values(groups).forEach(g => g.variants.sort((a, b) => parseInt(a.size) - parseInt(b.size)));
    return Object.values(groups);
  }

  // Khởi chạy
  function init() {
    if (!rawData || rawData.length === 0) {
      document.querySelector('.product-details-box').innerHTML = '<p>Sản phẩm tạm hết hàng.</p>';
      return;
    }
    productData = processData(rawData);
    renderColors();
    selectColor(0);
  }

  function renderColors() {
    dom.colorContainer.innerHTML = '';
    productData.forEach((item, index) => {
      const btn = document.createElement('button');
      btn.className = 'color-option-btn';
      const border = item.colorName === 'White' ? 'border:1px solid #999' : '';
      btn.innerHTML = `<span class="color-swatch" style="background-color: ${item.colorCode}; ${border}"></span>`;
      btn.onclick = () => selectColor(index);
      dom.colorContainer.appendChild(btn);
    });
  }

  function selectColor(index) {
    state.colorIndex = index;
    state.sizeIndex = 0;
    state.imgIndex = 0;

    const btns = dom.colorContainer.querySelectorAll('.color-option-btn');
    btns.forEach((b, i) => b.classList.toggle('selected', i === index));

    renderGallery(productData[index].images);
    renderSizes(productData[index].variants);
    updateInfo();
  }

  function renderSizes(variants) {
    dom.sizeContainer.innerHTML = '';
    variants.forEach((v, index) => {
      const btn = document.createElement('button');
      btn.className = 'type-option-btn';
      btn.innerText = v.size;
      btn.onclick = () => { state.sizeIndex = index; updateInfo(); };
      dom.sizeContainer.appendChild(btn);
    });
  }

  function updateInfo() {
    const group = productData[state.colorIndex];
    const variant = group.variants[state.sizeIndex];

    const btns = dom.sizeContainer.querySelectorAll('.type-option-btn');
    btns.forEach((b, i) => b.classList.toggle('selected', i === state.sizeIndex));

    dom.price.innerText = formatMoney(variant.price);
    dom.sku.innerText = `SKU: ${variant.sku || 'N/A'}`;

    dom.formPrice.value = variant.price;
    // set selected variant id so cart and server know exactly which variant
    dom.formVariantId.value = variant.id;
    dom.formQty.value = dom.qtyInput.value;

    const btnBuy = document.getElementById('btnBuyNow');
    const btnAdd = document.getElementById('btnAddToCart');

    if (variant.quantity > 0) {
      dom.qtyInput.max = variant.quantity;
      btnBuy.disabled = false; btnBuy.innerText = "Mua ngay"; btnBuy.style.opacity = 1;
      btnAdd.disabled = false;
    } else {
      dom.qtyInput.value = 0;
      btnBuy.disabled = true; btnBuy.innerText = "Hết hàng"; btnBuy.style.opacity = 0.5;
      btnAdd.disabled = true;
    }
  }

  function renderGallery(images) {
    dom.thumbArea.innerHTML = '';
    const processedImgs = images.map(img => img.startsWith('http') ? img : '<?= BASE_URL ?>uploads/variants/' + img);
    if (processedImgs.length === 0) processedImgs.push('https://placehold.co/600x600?text=No+Image');

    productData[state.colorIndex].processedImages = processedImgs;
    dom.formImage.value = processedImgs[0];

    dom.mainImg.src = processedImgs[state.imgIndex];

    processedImgs.forEach((src, idx) => {
      const div = document.createElement('div');
      div.className = 'thumb-image-box';
      div.onclick = () => {
        state.imgIndex = idx;
        dom.mainImg.src = src;
        highlightThumb();
      };
      div.innerHTML = `<img src="${src}">`;
      dom.thumbArea.appendChild(div);
    });
    highlightThumb();
  }

  function highlightThumb() {
    const thumbs = dom.thumbArea.querySelectorAll('.thumb-image-box');
    thumbs.forEach((t, i) => t.classList.toggle('active', i === state.imgIndex));
  }

  // Tăng giảm số lượng (Sửa lỗi type="button" để không submit form)
  document.getElementById('decrementQty').onclick = (e) => {
    let val = parseInt(dom.qtyInput.value);
    if (val > 1) { dom.qtyInput.value = --val; dom.formQty.value = val; }
  };
  document.getElementById('incrementQty').onclick = (e) => {
    let val = parseInt(dom.qtyInput.value);
    if (val < parseInt(dom.qtyInput.max)) { dom.qtyInput.value = ++val; dom.formQty.value = val; }
  };

  // === XỬ LÝ SỰ KIỆN SUBMIT FORM ===
  document.getElementById('addToCartForm').addEventListener('submit', function (e) {
    // Kiểm tra xem nút nào được bấm
    const submitter = e.submitter;

    // Nếu là nút "Thêm vào giỏ" -> Dùng AJAX
    if (submitter && submitter.id === 'btnAddToCart') {
      e.preventDefault(); // Chặn chuyển trang

      // Bật cờ AJAX lên
      dom.isAjaxInput.value = '1';

      const formData = new FormData(this);

      fetch(this.action, {
        method: 'POST',
        body: formData
      })
        .then(res => res.text())
        .then(text => {
          // try to parse JSON but show server response on console if invalid
          let payload;
          try {
            payload = JSON.parse(text);
          } catch (err) {
            console.error('Invalid JSON response when adding to cart:', text);
            alert('Có lỗi xảy ra!');
            return;
          }

          if (!payload || !payload.success) {
            alert(payload?.message || 'Lỗi khi thêm vào giỏ hàng');
            return;
          }

          alert('Đã thêm sản phẩm vào giỏ hàng thành công!');

          // Cập nhật header nếu có (payload.totalQty)
          const badge = document.getElementById('header-cart-count');
          if (badge) badge.innerText = '(' + payload.totalQty + ')';
        })
        .catch(err => {
          console.error(err);
          alert('Có lỗi xảy ra!');
        });
    }
    // Nếu là nút "Mua ngay" -> Để form submit bình thường (is_ajax = 0)
    else {
      dom.isAjaxInput.value = '0';
    }
  });

  init();
</script>