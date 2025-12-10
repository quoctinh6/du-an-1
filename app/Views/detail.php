<?php
// 1. Chuẩn bị dữ liệu từ PHP (Chuyển đổi để JS đọc được)
$variants_json = json_encode($product_variants ?? []);
var_dump($product_variants);
$p_id = $product_base['id'] ?? 0;
$p_name = htmlspecialchars($product_base['name'] ?? '');
$p_sku = htmlspecialchars($product_base['slug'] ?? '');
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