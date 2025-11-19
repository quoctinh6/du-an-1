// Example: icon-button click logic, you can expand this for cart/wishlist features
document.querySelectorAll(".icon-btn").forEach((btn) => {
  btn.addEventListener("click", function () {
    this.classList.toggle("active");
  });
});

// Optionally: smooth scroll for nav (demo only)
document.querySelectorAll("nav a").forEach((link) => {
  link.addEventListener("click", function (e) {
    if (this.getAttribute("href").startsWith("#")) {
      e.preventDefault();
      document
        .querySelector(this.getAttribute("href"))
        .scrollIntoView({ behavior: "smooth" });
    }
  });
});

/**
 * Khởi tạo Intersection Observer để tạo hiệu ứng Reveal On Scroll.
 */
document.addEventListener("DOMContentLoaded", () => {
  // 1. Định nghĩa CSS Selector cho các phần tử cần hiệu ứng
  // Ví dụ: Sử dụng class 'scroll-reveal'
  const revealElements = document.querySelectorAll(".scroll-reveal");

  // 2. Định nghĩa cấu hình Observer
  const observerOptions = {
    root: null, // Quan sát dựa trên viewport
    rootMargin: "0px",
    // Kích hoạt khi 15% phần tử xuất hiện trong viewport
    threshold: 0.15,
  };

  // 3. Định nghĩa hàm xử lý khi phần tử giao nhau (intersect)
  const observerCallback = (entries, observer) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        // Nếu phần tử xuất hiện trong viewport, thêm class 'is-visible'
        entry.target.classList.add("is-visible");

        // Ngừng quan sát sau khi đã xuất hiện (tối ưu hiệu suất)
        observer.unobserve(entry.target);
      }
    });
  };

  // 4. Tạo và chạy Observer
  const observer = new IntersectionObserver(observerCallback, observerOptions);

  revealElements.forEach((element) => {
    observer.observe(element);
  });
});
