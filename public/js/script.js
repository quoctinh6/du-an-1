  
    // Example: icon-button click logic, you can expand this for cart/wishlist features
    document.querySelectorAll('.icon-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        this.classList.toggle('active');
      });
    });

    // Optionally: smooth scroll for nav (demo only)
    document.querySelectorAll('nav a').forEach(link => {
      link.addEventListener('click', function(e) {
        if (this.getAttribute('href').startsWith("#")) {
          e.preventDefault();
          document.querySelector(this.getAttribute('href')).scrollIntoView({behavior:'smooth'});
        }
      });
    });
  

/**
 * Khởi tạo Intersection Observer để tạo hiệu ứng Reveal On Scroll.
 */
document.addEventListener('DOMContentLoaded', () => {
    // 1. Định nghĩa CSS Selector cho các phần tử cần hiệu ứng
    // Ví dụ: Sử dụng class 'scroll-reveal'
    const revealElements = document.querySelectorAll('.scroll-reveal');

    // 2. Định nghĩa cấu hình Observer
    const observerOptions = {
        root: null, // Quan sát dựa trên viewport
        rootMargin: '0px',
        // Kích hoạt khi 15% phần tử xuất hiện trong viewport
        threshold: 0.15 
    };

    // 3. Định nghĩa hàm xử lý khi phần tử giao nhau (intersect)
    const observerCallback = (entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Nếu phần tử xuất hiện trong viewport, thêm class 'is-visible'
                entry.target.classList.add('is-visible');
                
                // Ngừng quan sát sau khi đã xuất hiện (tối ưu hiệu suất)
                observer.unobserve(entry.target);
            }
        });
    };

    // 4. Tạo và chạy Observer
    const observer = new IntersectionObserver(observerCallback, observerOptions);

    revealElements.forEach(element => {
        observer.observe(element);
    });
});

 // Main image gallery logic
    const mainImage = document.getElementById('mainProductImage');
    const thumbnails = Array.from(document.querySelectorAll('.thumb-image-box'));
    const galleryImages = [
      "https://images.unsplash.com/photo-1518551937742-d7cb8f32a9b8?auto=format&fit=crop&w=600&q=80", // Black
      "https://images.unsplash.com/photo-1456086272139-7d6b1047d1a2?auto=format&fit=crop&w=600&q=80", // Silver
      "https://images.unsplash.com/photo-1518732714860-80c9b84c93fb?auto=format&fit=crop&w=600&q=80"  // Gold
    ];
    let currentIndex = 0;
    function updateMainImage(idx) {
      currentIndex = idx;
      mainImage.src = galleryImages[currentIndex];
      thumbnails.forEach((thumb, i) =>
        thumb.classList.toggle('active', i === currentIndex)
      );
    }
    thumbnails.forEach((thumb, i) =>
      thumb.addEventListener('click', () => updateMainImage(i))
    );
    document.querySelector('.gallery-nav-btn.left').addEventListener('click', () =>
      updateMainImage((currentIndex - 1 + galleryImages.length) % galleryImages.length)
    );
    document.querySelector('.gallery-nav-btn.right').addEventListener('click', () =>
      updateMainImage((currentIndex + 1) % galleryImages.length)
    );

    // Color select
    document.querySelectorAll('.color-option-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        document.querySelectorAll('.color-option-btn').forEach(b => b.classList.remove('selected'));
        btn.classList.add('selected');
      });
    });

    // Type select
    document.querySelectorAll('.type-option-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        document.querySelectorAll('.type-option-btn').forEach(b => b.classList.remove('selected'));
        btn.classList.add('selected');
      });
    });

    // Quantity logic
    const qtyInput = document.getElementById('quantityInput');
    document.getElementById('decrementQty').onclick = () => {
      let val = Math.max(1, parseInt(qtyInput.value)-1);
      qtyInput.value = val;
    };
    document.getElementById('incrementQty').onclick = () => {
      let val = Math.min(99, parseInt(qtyInput.value)+1);
      qtyInput.value = val;
    };

    