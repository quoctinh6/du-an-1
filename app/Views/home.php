<?php
$html_products_featured = '';

function render_product_item($item)
{
    $formatted_price = number_format($item['price'], 0, ',', '.') . ' VND';
    $product_id = htmlspecialchars($item['id']);
    $product_price = htmlspecialchars($item['price']);
    $image_url = htmlspecialchars($item['image_url']);
    
    if (!filter_var($image_url, FILTER_VALIDATE_URL)) {
        $image_url = BASE_URL . 'Views/assets/img/' . $image_url; 
    }

    $product_name = htmlspecialchars($item['name']);
    $detail_url = BASE_URL . 'index.php/products/detail/' . htmlspecialchars($item['slug'] ?? $item['id']);
    
    // --- ĐƯỜNG DẪN TRỎ VỀ FAVOR CONTROLLER ---
    $favor_add_url = BASE_URL . 'index.php/favor/add';

    $old_price_html = '';
    if (!empty($item['old_price']) && $item['old_price'] > $item['price']) {
        $formatted_old = number_format($item['old_price'], 0, ',', '.');
        $old_price_html = "<span class=\"product-old-price\">{$formatted_old}</span>";
    }

    return <<<HTML
    <div class="product-box">
        <div class="product-icons">
            <!-- Form Thêm Giỏ Hàng -->
            <form action="index.php/cart/add" method="POST" style="display:inline;" class="form-add-to-cart">
                <input type="hidden" name="id" value="$product_id">
                <input type="hidden" name="name" value="$product_name">
                <input type="hidden" name="price" value="$product_price">
                <input type="hidden" name="image" value="$image_url">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="icon-btn" aria-label="Add to cart">
                    <svg xmlns="http://www.w3.org/2000/svg" height="22" width="22" fill="currentColor" viewBox="0 0 24 24"><path d="M7 18c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm10 0c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm1.604-2.083l2.396-9.917h-16v-2h-3v2h1.604l3.452 13.917a2 2 0 0 0 1.944 1.25h10.192a2 2 0 0 0 1.944-1.25l.588-2.333zm-13.604-11.083v-2h16v2h-16z" /></svg>
                </button>
            </form>
            
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

$html_products_featured = '';
if(!empty($productsFeatured)) { foreach ($productsFeatured as $item) $html_products_featured .= render_product_item($item); }

$html_products_trending = '';
if(!empty($productsTrending)) { foreach ($productsTrending as $item) $html_products_trending .= render_product_item($item); }

$html_products_collections = '';
if(!empty($productsCollections)) { foreach ($productsCollections as $item) $html_products_collections .= render_product_item($item); }
?>

<main class="scroll-container">
  
  <section class="main-banner">
    <video autoplay muted loop playsinline src="<?= BASE_URL ?>Views/assets/image/videobanner.mp4"></video>
    <div class="banner-content">
      <div class="banner-title">Điều Khiển Thời Gian</div>
      <form class="search-box-glass" action="<?= BASE_URL ?>index.php/products/index" method="GET">
        <input type="text" name="search" placeholder="Tìm kiếm" class="search-input">
        <button type="submit" class="search-button">
            <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" fill="currentColor" viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19.5l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
        </button>
      </form>
    </div>
  </section>
      
  <section class="section scroll-reveal" id="featured-products">
    <div class="section-header">
      <div class="section-title">Đồng hồ bán chạy</div>
      <button class="section-action" onclick="window.location.href='<?= BASE_URL ?>index.php/products/index'">>></button>
    </div>
    <div class="product-grid">
      <?= $html_products_featured ?>
    </div>
  </section>

  <section class="section scroll-reveal" id="trending-products">
    <div class="section-header">
      <div class="section-title">Đồng hồ cơ</div>
      <button class="section-action" onclick="window.location.href='<?= BASE_URL ?>index.php/products/index'">>></button>
    </div>
    <div class="product-grid">
      <?= $html_products_trending ?>
    </div>
  </section>

  <div class="mid-banner scroll-reveal dark-filter">
    <img src="<?= BASE_URL ?>Views/assets/image/mainbanner1.png" alt="Rolex Banner">
    <div class="mid-banner-title">Đồng Hồ Rolex</div>
    <button class="mid-banner-glass-button" onclick="window.location.href='<?= BASE_URL ?>index.php/products/index'">Xem ngay</button>
  </div>

  <section class="section scroll-reveal" id="best-collection">
    <div class="section-header">
      <div class="section-title">Đồng hồ mới</div>
      <button class="section-action" onclick="window.location.href='<?= BASE_URL ?>index.php/products/index'">>></button>
    </div>
    <div class="product-grid">
      <?= $html_products_collections ?>
    </div>
  </section>

  <section class="section scroll-reveal" id="watch-blog">
    <div class="section-header">
      <div class="section-title">Tin tức</div>
    </div>
    <div class="blog-grid">
      <div class="blog-feature bloghover">
        <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80" alt="Watch Trends 2025" class="blog-feature-img">
        <div class="blog-feature-title">Watch Trends 2025</div>
        <div class="blog-feature-desc">
          Uncover the boldest looks and next-gen technologies for wristwatches in 2025: smart chronos, eco-friendly, and classic revivals.
        </div>
      </div>
      <div class="blog-list">
        <div class="blog-list-item bloghover">
          <img src="https://images.unsplash.com/photo-1522312346375-d1a52e2b99b3?auto=format&fit=crop&w=400&q=80" class="blog-list-img" alt="Wind Your Watch">
          <div class="blog-list-info">
            <div class="blog-list-title">How to Wind Your Watch</div>
            <div class="blog-list-desc">
              Learn proper winding techniques for mechanical and automatic watches.
            </div>
          </div>
        </div>
        <div class="blog-list-item bloghover">
          <img src="https://images.unsplash.com/photo-1465101162946-4377e57745c3?auto=format&fit=crop&w=400&q=80" class="blog-list-img" alt="Watch Care">
          <div class="blog-list-info">
            <div class="blog-list-title">Watch Care 101</div>
            <div class="blog-list-desc">
              Essential tips: cleaning, storage, servicing. Make your timepiece last.
            </div>
          </div>
        </div>
        <div class="blog-list-item bloghover">
          <img src="https://images.unsplash.com/photo-1491553895911-0055eca6402d?auto=format&fit=crop&w=400&q=80" class="blog-list-img" alt="Choosing the Right Fit">
          <div class="blog-list-info">
            <div class="blog-list-title">Choosing the Right Fit</div>
            <div class="blog-list-desc">
              Select a watch that suits your wrist, lifestyle, and wardrobe.
            </div>
          </div>
        </div>
        <button class="glass-button blog-list-btn">Xem Thêm</button>
      </div>
    </div>
  </section>

</main>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    
    // 1. ADD TO CART
    const cartForms = document.querySelectorAll(".form-add-to-cart");
    cartForms.forEach(form => {
      form.addEventListener('submit', function(e) {
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
      form.addEventListener('submit', function(e) {
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
            if(text.includes('404') || text.includes('Not Found')) {
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