<?php
$html_products_featured = '';

function render_product_item($item)
{

  // Định dạng giá tiền
  $formatted_price = number_format($item['price'], 0) . ' VND';

  // Bảo mật: Dùng htmlspecialchars để tránh lỗi XSS
  $image_url = htmlspecialchars($item['image_url']);
  $product_name = htmlspecialchars($item['name']);
  $detail_url = 'index.php/products/detail/' . htmlspecialchars($item['slug']);

  // Dùng Heredoc (<<<HTML) để viết code HTML dễ nhìn hơn
  return <<<HTML
    <div class="product-box" onclick="window.location.href= '$detail_url' ">
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
        <img src="$image_url" alt="$product_name" class="product-img">
        <div class="product-name">$product_name</div>
        <div class="product-price">$formatted_price</div>
        <button class="buy-btn">Buy Now</button>
    </div>
    HTML;
}
// (Đảm bảo bạn đã include file chứa hàm render_product_item)

$html_products_featured = '';
foreach ($productsFeatured as $item) {
  $html_products_featured .= render_product_item($item);
}

$html_products_trending = '';
foreach ($productsTrending as $item) {
  $html_products_trending .= render_product_item($item);
}

$html_products_collections = '';
foreach ($productsCollections as $item) {
  $html_products_collections .= render_product_item($item);
}



?>
<!-- Main Banner Full Screen -->
<section class="main-banner">
  <video autoplay muted loop playsinline src="<?php echo BASE_URL; ?>Views/assets/image/videobanner.mp4"></video>
  <div class="banner-content">

    <div class="banner-title">Elevate Your Time</div>
    <!-- <div class="banner-desc">
        Discover luxury and precision—shop our curated collection of modern watches. <br>
        <span style="color:var(--accent);"><b>Flash Sale</b></span>: Exclusive deals, limited-time only!
      </div>-->
    <button class="glass-button">Shop Now</button>
  </div>
</section>

<!-- Section 1: Product Grid -->
<section class="section scroll-reveal" id="featured-products">
  <div class="section-header">
    <div class="section-title">Featured Watches</div>
    <button class="section-action">All</button>
  </div>
  <div class="product-grid">
    <!-- Product Box 1 -->
    <?= $html_products_featured ?>
  </div>
</section>

<!-- Section 2: Trending Grid -->
<section class="section scroll-reveal" id="trending-products">
  <div class="section-header">
    <div class="section-title">Trending Now</div>
    <button class="section-action">All</button>
  </div>
  <div class="product-grid">
    <!-- Four Trending Product Boxes -->
    <?= $html_products_trending ?>
  </div>
</section>

<!-- Section Banner (Full Width) -->
<div class="mid-banner scroll-reveal dark-filter">
  <img src="<?php echo BASE_URL; ?>Views/assets/image/mainbanner1.png" alt="">
  <div class="mid-banner-title">Elevate Your Time</div>
  <button class="mid-banner-glass-button">Shop Now</button>
</div>

<!-- Section 3: Best Collection Grid -->
<section class="section scroll-reveal" id="best-collection">
  <div class="section-header">
    <div class="section-title">Best Collections</div>
    <button class="section-action">All</button>
  </div>
  <div class="product-grid">
    <!-- Four Best Collection Boxes -->
    <?= $html_products_Collections ?>
  </div>
</section>

<!-- Section 4: Blog/Article Grid -->
<section class="section scroll-reveal" id="watch-blog">
  <div class="section-header">
    <div class="section-title">Latest Articles</div>
  </div>
  <div class="blog-grid">
    <!-- Box 1: Large Feature -->
    <div class="blog-feature bloghover">
      <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80"
        alt="Watch Trends 2025" class="blog-feature-img">
      <div class="blog-feature-title">Watch Trends 2025</div>
      <div class="blog-feature-desc">
        Uncover the boldest looks and next-gen technologies for wristwatches in 2025: smart chronos, eco-friendly, and
        classic revivals.
      </div>
    </div>
    <!-- Box 2: Vertical List -->
    <div class="blog-list">
      <!-- Blog Item 1 -->
      <div class="blog-list-item bloghover">
        <img src="https://images.unsplash.com/photo-1522312346375-d1a52e2b99b3?auto=format&fit=crop&w=400&q=80"
          class="blog-list-img" alt="Wind Your Watch">
        <div class="blog-list-info">
          <div class="blog-list-title">How to Wind Your Watch</div>
          <div class="blog-list-desc">
            Learn proper winding techniques for mechanical and automatic watches.
          </div>
        </div>
      </div>
      <!-- Blog Item 2 -->
      <div class="blog-list-item bloghover">
        <img src="https://images.unsplash.com/photo-1465101162946-4377e57745c3?auto=format&fit=crop&w=400&q=80"
          class="blog-list-img" alt="Watch Care">
        <div class="blog-list-info">
          <div class="blog-list-title">Watch Care 101</div>
          <div class="blog-list-desc">
            Essential tips: cleaning, storage, servicing. Make your timepiece last.
          </div>
        </div>
      </div>
      <!-- Blog Item 3 -->
      <div class="blog-list-item bloghover">
        <img src="https://images.unsplash.com/photo-1491553895911-0055eca6402d?auto=format&fit=crop&w=400&q=80"
          class="blog-list-img" alt="Choosing the Right Fit">
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