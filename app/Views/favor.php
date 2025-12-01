<section class="section product-page-section" id="favor-list">
    <div class="section-header">
      <div class="section-title">Danh Sách Yêu Thích (4)</div>
    </div>

    <!-- Bố cục Grid: 1 cột Sidebar - 3 cột Sản phẩm -->
    <div class="product-page-container">
      
      <!-- Cột 1: Sidebar Bộ Lọc Đơn Giản -->
      <aside class="filter-sidebar favor-sidebar">
        <h3 class="filter-title">Quản Lý</h3>
        
        <!-- 1. Tìm kiếm trong yêu thích -->
        <div class="filter-group">
            <div class="filter-group-title">Tìm kiếm nhanh</div>
            <div class="sidebar-search-container">
                <input type="text" class="sidebar-search-input" placeholder="Tên sản phẩm...">
                <button class="sidebar-search-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </button>
            </div>
        </div>

        <!-- 2. Lọc theo Hãng -->
        <div class="filter-group">
            <div class="filter-group-title">Lọc theo Hãng</div>
            <label class="filter-checkbox-container">Tất cả
                <input type="radio" name="brand_filter" checked="checked">
                <span class="checkmark"></span>
            </label>
            <label class="filter-checkbox-container">Rolex
                <input type="radio" name="brand_filter">
                <span class="checkmark"></span>
            </label>
            <label class="filter-checkbox-container">Omega
                <input type="radio" name="brand_filter">
                <span class="checkmark"></span>
            </label>
            <label class="filter-checkbox-container">Casio
                <input type="radio" name="brand_filter">
                <span class="checkmark"></span>
            </label>
        </div>

        <!-- 3. Sắp xếp giá -->
        <div class="filter-group">
            <div class="filter-group-title">Sắp xếp giá</div>
            <select class="filter-select">
                <option value="default">Mặc định</option>
                <option value="asc">Thấp đến Cao</option>
                <option value="desc">Cao đến Thấp</option>
            </select>
        </div>

        <!-- Nút Reset -->
        <div class="filter-actions-bottom">
            <button class="btn-filter-action btn-apply" style="width: 100%;">Lọc danh sách</button>
        </div>
      </aside>

      <!-- Cột 2: Danh sách Sản phẩm (Sử dụng .product-result-grid để chia 3 cột) -->
      <div class="product-result-grid">
        
        <!-- Sản phẩm 1 -->
        <div class="product-box favor-product-box">
          <div class="product-icons">
            <!-- Nút thêm vào giỏ -->
            <button class="icon-btn" aria-label="Add to cart" title="Thêm vào giỏ">
              <svg xmlns="http://www.w3.org/2000/svg" height="22" width="22" fill="currentColor" viewBox="0 0 24 24"><path d="M7 18c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm10 0c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm1.604-2.083l2.396-9.917h-16v-2h-3v2h1.604l3.452 13.917a2 2 0 0 0 1.944 1.25h10.192a2 2 0 0 0 1.944-1.25l.588-2.333zm-13.604-11.083v-2h16v2h-16z"/></svg>
            </button>
            
            <!-- Nút Xóa khỏi yêu thích (Logic Heart -> X) -->
            <button class="icon-btn remove-favor-btn" aria-label="Remove from favorites" title="Xóa khỏi yêu thích">
              <!-- Icon Trái tim (Hiện mặc định) -->
              <svg class="icon-heart" xmlns="http://www.w3.org/2000/svg" fill="currentColor" height="22" width="22" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 1.01 4.5 2.09C13.09 4.01 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
              <!-- Icon X (Hiện khi hover) -->
              <svg class="icon-close" xmlns="http://www.w3.org/2000/svg" height="22" width="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
          </div>
          
          <img src="https://images.unsplash.com/photo-1523170335258-f5ed11844a49?auto=format&fit=crop&w=400&q=80" alt="Watch 1" class="product-img">
          <div class="product-name">Rolex Submariner Date</div>
          <div class="product-price">350.000.000 VND</div>
          <button class="buy-btn">Mua ngay</button>
        </div>

        <!-- Sản phẩm 2 -->
        <div class="product-box favor-product-box">
          <div class="product-icons">
            <button class="icon-btn" aria-label="Add to cart"><svg xmlns="http://www.w3.org/2000/svg" height="22" width="22" fill="currentColor" viewBox="0 0 24 24"><path d="M7 18c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm10 0c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm1.604-2.083l2.396-9.917h-16v-2h-3v2h1.604l3.452 13.917a2 2 0 0 0 1.944 1.25h10.192a2 2 0 0 0 1.944-1.25l.588-2.333zm-13.604-11.083v-2h16v2h-16z"/></svg></button>
            <button class="icon-btn remove-favor-btn" aria-label="Remove from favorites">
              <svg class="icon-heart" xmlns="http://www.w3.org/2000/svg" fill="currentColor" height="22" width="22" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 1.01 4.5 2.09C13.09 4.01 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
              <svg class="icon-close" xmlns="http://www.w3.org/2000/svg" height="22" width="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
          </div>
          <img src="https://images.unsplash.com/photo-1614164185128-e4ec99c436d7?auto=format&fit=crop&w=400&q=80" alt="Watch 2" class="product-img">
          <div class="product-name">Omega Speedmaster</div>
          <div class="product-price">180.000.000 VND</div>
          <button class="buy-btn">Mua ngay</button>
        </div>

        <!-- Sản phẩm 3 -->
        <div class="product-box favor-product-box">
          <div class="product-icons">
            <button class="icon-btn" aria-label="Add to cart"><svg xmlns="http://www.w3.org/2000/svg" height="22" width="22" fill="currentColor" viewBox="0 0 24 24"><path d="M7 18c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm10 0c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm1.604-2.083l2.396-9.917h-16v-2h-3v2h1.604l3.452 13.917a2 2 0 0 0 1.944 1.25h10.192a2 2 0 0 0 1.944-1.25l.588-2.333zm-13.604-11.083v-2h16v2h-16z"/></svg></button>
            <button class="icon-btn remove-favor-btn" aria-label="Remove from favorites">
              <svg class="icon-heart" xmlns="http://www.w3.org/2000/svg" fill="currentColor" height="22" width="22" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 1.01 4.5 2.09C13.09 4.01 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
              <svg class="icon-close" xmlns="http://www.w3.org/2000/svg" height="22" width="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
          </div>
          <img src="https://images.unsplash.com/photo-1524592094714-0f0654e20314?auto=format&fit=crop&w=400&q=80" alt="Watch 3" class="product-img">
          <div class="product-name">Casio G-Shock Red</div>
          <div class="product-price">3.200.000 VND</div>
          <button class="buy-btn">Mua ngay</button>
        </div>

        <!-- Sản phẩm 4 -->
        <div class="product-box favor-product-box">
          <div class="product-icons">
            <button class="icon-btn" aria-label="Add to cart"><svg xmlns="http://www.w3.org/2000/svg" height="22" width="22" fill="currentColor" viewBox="0 0 24 24"><path d="M7 18c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm10 0c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm1.604-2.083l2.396-9.917h-16v-2h-3v2h1.604l3.452 13.917a2 2 0 0 0 1.944 1.25h10.192a2 2 0 0 0 1.944-1.25l.588-2.333zm-13.604-11.083v-2h16v2h-16z"/></svg></button>
            <button class="icon-btn remove-favor-btn" aria-label="Remove from favorites">
              <svg class="icon-heart" xmlns="http://www.w3.org/2000/svg" fill="currentColor" height="22" width="22" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41 1.01 4.5 2.09C13.09 4.01 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
              <svg class="icon-close" xmlns="http://www.w3.org/2000/svg" height="22" width="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
          </div>
          <img src="https://images.unsplash.com/photo-1623998021450-85c29c644e0d?auto=format&fit=crop&w=400&q=80" alt="Watch 4" class="product-img">
          <div class="product-name">Seiko Presage</div>
          <div class="product-price">12.500.000 VND</div>
          <button class="buy-btn">Mua ngay</button>
        </div>

      </div>
    </div>
  </section>