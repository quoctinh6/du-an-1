<section class="product-section">
  <!-- Section 1: Product Gallery and Information -->
  <section class="product-main-grid scroll-reveal">
    <!-- Left Box: Product Gallery -->
    <div class="product-gallery-box">
      <button class="gallery-nav-btn left" aria-label="Previous image">&lt;</button>
      <div class="main-image-area">
        <img src="https://images.unsplash.com/photo-1518551937742-d7cb8f32a9b8?auto=format&fit=crop&w=600&q=80"
          alt="Ảnh đồng hồ nhìn từ trên" id="mainProductImage">
      </div>
      <div class="thumb-gallery-area" id="productThumbnails">
        <div class="thumb-image-box active"><img
            src="https://images.unsplash.com/photo-1518551937742-d7cb8f32a9b8?auto=format&fit=crop&w=200&q=80"
            alt="Ảnh đồng hồ nhìn từ trên"></div>
        <div class="thumb-image-box"><img
            src="https://images.unsplash.com/photo-1456086272139-7d6b1047d1a2?auto=format&fit=crop&w=200&q=80"
            alt="Ảnh đồng hồ nhìn từ dưới"></div>
        <div class="thumb-image-box"><img
            src="https://images.unsplash.com/photo-1518732714860-80c9b84c93fb?auto=format&fit=crop&w=200&q=80"
            alt="Ảnh đồng hồ nhìn từ phải"></div>
      </div>
      <button class="gallery-nav-btn right" aria-label="Next image">&gt;</button>
    </div>
    <!-- Right Box: Product Details -->
    <div class="product-details-box">
      <div>
        <span class="product-dt-name">ChronoX Pro Watch</span>
        <div class="product-sku">SKU: CX2009-PRO</div>
        <div class="product-dt-price">5000000 VND</div>
      </div>
      <div class="attribute-group">
        <div class="attribute-label">Màu sắc</div>
        <div class="color-options" id="colorContainer">
        </div>
      </div>

      <div class="attribute-group">
        <div class="attribute-label">Kích thước</div>
        <div class="type-options" id="sizeContainer">
        </div>
      </div>
      <div class="quantity-group">
        <div class="attribute-label">Số lượng</div>
        <div class="quantity-selector">
          <button class="quantity-btn" aria-label="Decrease quantity" id="decrementQty">-</button>
          <input type="number" readonly class="quantity-value" id="quantityInput" value="1" min="1">
          <button class="quantity-btn" aria-label="Increase quantity" id="incrementQty">+</button>
        </div>
        <span id="outOfStockLabel" style="display:none;color:#f44336;font-weight:600;margin-left:8px;">Hết hàng</span>
      </div>
      <div class="action-group">
        <button class="glass-button">Mua ngay</button>
        <button class="cta-btn secondary">Thêm vào giỏ</button>
      </div>
    </div>
  </section>
  <!-- Section 2: Description and Specifications -->
  <section class="product-info-grid scroll-reveal">
    <!-- Left Box: Description -->
    <div class="description-box">
      <h2>Description</h2>
      <p class="description-content">
        The ChronoX Pro Watch elegantly combines advanced engineering and timeless design, making it a statement piece
        for any occasion. Featuring a robust casing, high-precision quartz movement, and an interchangeable strap
        system, this watch is crafted for those who demand both style and reliability. With 50m water resistance,
        glare-resistant sapphire glass, and a two-year warranty, ChronoX Pro is built to last—just like your ambitions.
      </p>
    </div>
    <!-- Right Box: Specifications -->
    <div class="specs-box">
      <h2>Specifications</h2>
      <table class="specs-table">
        <tr>
          <td>Case Diameter: 42 mm</td>
        </tr>
        <tr>
          <td>Movement: Quartz</td>
        </tr>
        <tr>
          <td>Water Resistance: 50 meters</td>
        </tr>
        <tr>
          <td>Glass: Sapphire Crystal</td>
        </tr>
        <tr>
          <td>Warranty: 2 Years</td>
        </tr>
      </table>
    </div>
  </section>


</section>
<!-- <div class="mid-banner scroll-reveal dark-filter">
    <img src="./image/mainbanner1.png" alt="">
    <div class="mid-banner-title">Elevate Your Time</div>
    <button class="mid-banner-glass-button">Shop Now</button>
  </div> -->

<section class="section scroll-reveal" id="trending-products">
  <div class="section-header">
    <div class="section-title">Sản phẩm liên quan</div>
    <a href="<?php echo BASE_URL; ?>index.php/products?category=<?= $product_base['id'] ?>"
      class="section-action">>></a>
  </div>
  <div class="product-grid">
    <?php
    // Kiểm tra xem có sản phẩm liên quan không
    if (!empty($products_similar)):
      // Lấy tối đa 4 sản phẩm
      $similar_count = 0;
      foreach ($products_similar as $product):
        if ($similar_count >= 4)
          break;
        $similar_count++;
        ?>
        <!-- Product Box -->
        <div class="product-box">
          <div class="product-icons">
            <button class="icon-btn add-to-cart-btn"
              onclick="addToCartAjax(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars($product['name']); ?>', <?php echo $product['price']; ?>, '<?php echo htmlspecialchars($product['image_url']); ?>');"
              aria-label="Add to cart">
              <svg xmlns="http://www.w3.org/2000/svg" height="22" width="22" fill="currentColor" viewBox="0 0 24 24">
                <path
                  d="M7 18c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm10 0c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm1.604-2.083l2.396-9.917h-16v-2h-3v2h1.604l3.452 13.917a2 2 0 0 0 1.944 1.25h10.192a2 2 0 0 0 1.944-1.25l.588-2.333zm-13.604-11.083v-2h16v2h-16z" />
              </svg>
            </button>
            <button class="icon-btn" aria-label="Add to favorites">
              <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" height="22" width="22" viewBox="0 0 24 24">
                <path
                  d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 1.01 4.5 2.09C13.09 4.01 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
              </svg>
            </button>
          </div>
          <img src="<?php echo htmlspecialchars($product['image_url']); ?>"
            alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-img">
          <div class="product-name"><?php echo htmlspecialchars($product['name']); ?></div>
          <div class="product-price">
            <?php echo number_format($product['price'], 0, ',', '.'); ?> đ
          </div>
          <a href="<?php echo BASE_URL; ?>index.php/Products/detail/<?php echo htmlspecialchars($product['slug']); ?>"
            class="buy-btn">Xem chi tiết</a>
        </div>
        <?php
      endforeach;
    else:
      ?>
      <!-- Thông báo không có sản phẩm liên quan -->
      <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #999;">
        <p>Không có sản phẩm liên quan</p>
      </div>
    <?php endif; ?>
  </div>
</section>
<script>
  // 1. NHẬN DỮ LIỆU TỪ PHP VÀ CẤU HÌNH MÀU SẮC
  const rawData = <?= json_encode($product_variants) ?>;

  // Vì dữ liệu trả về chỉ có Tên màu (ví dụ "Black") mà thiếu Mã màu Hex (ví dụ #000000)
  // Ta cần một bảng map thủ công để hiển thị màu sắc đẹp mắt


  let productData = []; // Dữ liệu sau khi đã nhóm
  let state = {
    colorIndex: 0,
    sizeIndex: 0,
    imgIndex: 0
  };

  // Định dạng tiền tệ
  const formatMoney = (amount) => new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);

  // 3. HÀM XỬ LÝ DỮ LIỆU (QUAN TRỌNG NHẤT)
  function processData(flatList) {
    // Simplified: don't group by color — just map each variant item to a simple structure
    // Use `item.codecoler` if present (fallbacks to common keys), and don't merge items
    const keys = ['codecoler', 'color_code', 'code', 'colorCode', 'hex', 'color_hex', 'colorHex', 'colors_code', 'colors_hex'];

    function pickColorCode(item) {
      for (const k of keys) {
        if (item[k] && typeof item[k] === 'string' && item[k].trim().startsWith('#')) return item[k].trim();
      }
      return '#cccccc';
    }

    return flatList.map(item => ({
      colorName: item.colors || item.color || '',
      colorCode: pickColorCode(item),
      images: item.image ? [item.image] : [],
      variants: [{
        id: item.id,
        size: item.sizes,
        price: parseFloat(item.price),
        quantity: parseInt(item.quantity),
        sku: item.sku
      }]
    }));
  }

  // 4. LOGIC HIỂN THỊ GIAO DIỆN

  function init() {
    if (!rawData || rawData.length === 0) return;

    // Bước 1: Chuyển dữ liệu phẳng thành dữ liệu phân cấp
    productData = processData(rawData);

    // Bước 2: Vẽ các nút màu
    renderColors();

    // Bước 3: Chọn mặc định màu đầu tiên
    selectColor(0);
  }

  function renderColors() {
    dom.colorContainer.innerHTML = '';
    productData.forEach((item, index) => {
      const btn = document.createElement('button');
      btn.className = 'color-option-btn';
      // Nếu màu là trắng, thêm viền xám để dễ nhìn
      const borderStyle = item.colorName === 'White' ? 'border:1px solid #999' : 'border:1px solid transparent';

      btn.innerHTML = `<span class="color-swatch" style="background-color: ${item.colorCode}; ${borderStyle}"></span>`;
      btn.setAttribute('title', item.colorName); // Hover hiện tên màu
      btn.onclick = () => selectColor(index);
      dom.colorContainer.appendChild(btn);
    });
  }

  function selectColor(index) {
    state.colorIndex = index;
    state.sizeIndex = 0;
    state.imgIndex = 0;

    // Highlight nút màu
    const allColorBtns = dom.colorContainer.querySelectorAll('.color-option-btn');
    allColorBtns.forEach(b => b.classList.remove('selected'));
    if (allColorBtns[index]) allColorBtns[index].classList.add('selected');

    // Load Gallery ảnh
    renderGallery(productData[index].images);

    // Load danh sách Size
    renderSizes(productData[index].variants);

    // Update thông tin chi tiết
    updateProductInfo();
  }

  function renderSizes(variants) {
    dom.sizeContainer.innerHTML = '';
    variants.forEach((variant, index) => {
      const btn = document.createElement('button');
      btn.className = 'type-option-btn';
      btn.innerText = variant.size;
      btn.onclick = () => selectSize(index);
      dom.sizeContainer.appendChild(btn);
    });
  }

  function selectSize(index) {
    state.sizeIndex = index;
    updateProductInfo();
  }

  function updateProductInfo() {
    const currentGroup = productData[state.colorIndex];
    const currentVariant = currentGroup.variants[state.sizeIndex];

    // Highlight nút Size
    const allSizeBtns = dom.sizeContainer.querySelectorAll('.type-option-btn');
    allSizeBtns.forEach(b => b.classList.remove('selected'));
    if (allSizeBtns[state.sizeIndex]) allSizeBtns[state.sizeIndex].classList.add('selected');

    // Cập nhật Giá & SKU
    dom.price.innerText = formatMoney(currentVariant.price);
    dom.sku.innerText = `SKU: ${currentVariant.sku}`;

    // Cập nhật Tồn kho & Nút Mua
    const maxStock = currentVariant.quantity;

    if (maxStock > 0) {
      dom.btnBuy.innerText = "Mua ngay";
      dom.btnBuy.disabled = false;
      dom.btnBuy.style.opacity = "1";
      dom.btnBuy.style.cursor = "pointer";
      dom.qtyInput.value = 1;
      dom.qtyInput.max = maxStock;
      // Show quantity selector, hide out-of-stock label
      const qtySel = document.querySelector('.quantity-selector');
      const oos = dom.outOfStockLabel;
      if (qtySel) qtySel.style.display = 'flex';
      if (oos) oos.style.display = 'none';
      if (dom.btnAddToCart) {
        dom.btnAddToCart.disabled = false;
        dom.btnAddToCart.style.opacity = '1';
        dom.btnAddToCart.style.cursor = 'pointer';
      }
    } else {
      dom.btnBuy.innerText = "Hết hàng";
      dom.btnBuy.disabled = true;
      dom.btnBuy.style.opacity = "0.5";
      dom.btnBuy.style.cursor = "not-allowed";
      // Hide quantity selector and show an explicit "Hết hàng" label
      const qtySel = document.querySelector('.quantity-selector');
      const oos = dom.outOfStockLabel;
      if (qtySel) qtySel.style.display = 'none';
      if (oos) oos.style.display = 'inline-block';
      if (dom.btnAddToCart) {
        dom.btnAddToCart.disabled = true;
        dom.btnAddToCart.style.opacity = '0.5';
        dom.btnAddToCart.style.cursor = 'not-allowed';
      }
      dom.qtyInput.value = 0;
    }
  }

  // 5. LOGIC GALLERY ẢNH
  function renderGallery(images) {
    dom.thumbArea.innerHTML = '';

    // Nếu không có ảnh nào, dùng ảnh placeholder
    if (images.length === 0) images = ["https://via.placeholder.com/600"];

    updateMainImageDisplay();

    images.forEach((url, index) => {
      const thumbBox = document.createElement('div');
      thumbBox.className = 'thumb-image-box';

      const img = document.createElement('img');
      img.src = url;
      thumbBox.onclick = () => {
        state.imgIndex = index;
        updateMainImageDisplay();
      };

      thumbBox.appendChild(img);
      dom.thumbArea.appendChild(thumbBox);
    });
    highlightThumbnail();
  }

  function updateMainImageDisplay() {
    const images = productData[state.colorIndex].images;
    if (images.length > 0) {
      dom.mainImg.src = images[state.imgIndex];
    }
    highlightThumbnail();
  }

  function highlightThumbnail() {
    const thumbs = dom.thumbArea.querySelectorAll('.thumb-image-box');
    thumbs.forEach((t, i) => {
      if (i === state.imgIndex) t.classList.add('active');
      else t.classList.remove('active');
    });
  }

  // Next/Prev Buttons
  dom.btnNavLeft.onclick = () => {
    const images = productData[state.colorIndex].images;
    if (images.length > 1) {
      state.imgIndex = (state.imgIndex - 1 + images.length) % images.length;
      updateMainImageDisplay();
    }
  };

  dom.btnNavRight.onclick = () => {
    const images = productData[state.colorIndex].images;
    if (images.length > 1) {
      state.imgIndex = (state.imgIndex + 1) % images.length;
      updateMainImageDisplay();
    }
  };

  // 6. TĂNG GIẢM SỐ LƯỢNG
  document.getElementById('decrementQty').onclick = () => {
    const currentMax = parseInt(dom.qtyInput.max);
    if (currentMax === 0) return;
    let val = parseInt(dom.qtyInput.value);
    if (val > 1) dom.qtyInput.value = val - 1;
  };

  document.getElementById('incrementQty').onclick = () => {
    const currentMax = parseInt(dom.qtyInput.max);
    if (currentMax === 0) return;
    let val = parseInt(dom.qtyInput.value);
    if (val < currentMax) {
      dom.qtyInput.value = val + 1;
    } else {
      alert(`Kho chỉ còn ${currentMax} sản phẩm cho phiên bản này.`);
    }
  };

  // Chạy ứng dụng
  init();

  // Cart URL for redirect when clicking "Mua ngay"
  const CART_URL = '<?php echo BASE_URL; ?>index.php/Products/cart';

  // ✅ AJAX Add to Cart Function
  function addToCartAjax(productId, productName, price, image, quantity = 1) {
    // Tạo object cart item
    const cartItem = {
      id: productId,
      name: productName,
      price: price,
      image: image,
      quantity: quantity
    };

    // Lấy cart từ localStorage
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    // Kiểm tra sản phẩm đã tồn tại chưa
    const existingItem = cart.find(item => item.id == productId);
    if (existingItem) {
      existingItem.quantity += quantity;
      showToast("✅ Tăng số lượng trong giỏ hàng!", "success");
    } else {
      cart.push(cartItem);
      showToast("✅ Đã thêm vào giỏ hàng!", "success");
    }

    // Lưu vào localStorage
    localStorage.setItem('cart', JSON.stringify(cart));

    // Cập nhật header cart count
    const headerCount = document.getElementById('header-cart-count');
    if (headerCount) {
      const currentCount = parseInt(headerCount.innerText.replace(/\D/g, '')) || 0;
      headerCount.innerText = '(' + (currentCount + 1) + ')';
    }
  }

  // Wire up main-detail Add to Cart button (uses selected variant + quantity)
  if (dom.btnAddToCart) {
    dom.btnAddToCart.addEventListener('click', function (e) {
      // If out of stock, do nothing
      const qtySel = document.querySelector('.quantity-selector');
      if (!qtySel || qtySel.style.display === 'none') {
        showToast('Sản phẩm đã hết hàng', 'error');
        return;
      }

      const currentGroup = productData[state.colorIndex];
      const currentVariant = currentGroup.variants[state.sizeIndex];
      const qty = parseInt(dom.qtyInput.value) || 1;
      addToCartAjax(currentVariant.id, '<?php echo addslashes($product_base['name'] ?? '') ?>', currentVariant.price, currentGroup.images && currentGroup.images[0] ? currentGroup.images[0] : '<?php echo addslashes($product_base['image_url'] ?? '') ?>', qty);
    });
  }

  // Wire up Buy Now (add then redirect to cart)
  if (dom.btnBuy) {
    dom.btnBuy.addEventListener('click', function (e) {
      // If button disabled or out of stock, don't proceed
      if (dom.btnBuy.disabled) return;
      const qtySel = document.querySelector('.quantity-selector');
      if (!qtySel || qtySel.style.display === 'none') {
        showToast('Sản phẩm đã hết hàng', 'error');
        return;
      }
      const currentGroup = productData[state.colorIndex];
      const currentVariant = currentGroup.variants[state.sizeIndex];
      const qty = parseInt(dom.qtyInput.value) || 1;
      addToCartAjax(currentVariant.id, '<?php echo addslashes($product_base['name'] ?? '') ?>', currentVariant.price, currentGroup.images && currentGroup.images[0] ? currentGroup.images[0] : '<?php echo addslashes($product_base['image_url'] ?? '') ?>', qty);
      // Redirect to cart page
      window.location.href = CART_URL;
    });
  }

  // ✅ Hàm hiển thị thông báo Toast
  function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.textContent = message;
    toast.style.cssText = `
      position: fixed;
      bottom: 20px;
      right: 20px;
      background: ${type === 'success' ? '#4CAF50' : type === 'error' ? '#f44336' : '#2196F3'};
      color: white;
      padding: 15px 20px;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.2);
      z-index: 9999;
      animation: slideIn 0.3s ease-out;
    `;
    document.body.appendChild(toast);

    setTimeout(() => toast.remove(), 3000);
  }
</script>

<!-- ✅ CSS Animation cho Toast -->
<style>
  @keyframes slideIn {
    from {
      transform: translateX(400px);
      opacity: 0;
    }

    to {
      transform: translateX(0);
      opacity: 1;
    }
  }

  .toast {
    animation: slideIn 0.3s ease-out !important;
  }
</style>