// Example: icon-button click logic, you can expand this for cart/wishlist features
// 2. BIẾN TOÀN CỤC
const dom = {
  mainImg: document.getElementById("mainProductImage"),
  thumbArea: document.getElementById("productThumbnails"),
  colorContainer: document.getElementById("colorContainer"),
  sizeContainer: document.getElementById("sizeContainer"),
  price: document.querySelector(".product-dt-price"),
  sku: document.querySelector(".product-sku"),
  qtyInput: document.getElementById("quantityInput"),
  outOfStockLabel: document.getElementById("outOfStockLabel"),
  btnBuy: document.querySelector(".glass-button"),
  btnAddToCart: document.querySelector(".cta-btn.secondary"),
  btnNavLeft: document.querySelector(".gallery-nav-btn.left"),
  btnNavRight: document.querySelector(".gallery-nav-btn.right"),
};

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

document.addEventListener("DOMContentLoaded", function () {
  // 1. Lấy tất cả các nút có class .btn-add-to-cart
  const buttons = document.querySelectorAll(".btn-add-to-cart");

  buttons.forEach(function (btn) {
    btn.addEventListener("click", function () {
      // 2. Lấy thông tin từ data attribute của nút vừa bấm
      const newItem = {
        id: this.dataset.id,
        name: this.dataset.name,
        price: Number(this.dataset.price), // Ép kiểu về số
        image: this.dataset.image,
        quantity: 1, // Mặc định số lượng là 1
      };

      // 3. Gọi hàm xử lý thêm vào LocalStorage
      addToCart(newItem);
    });
  });

  // Hàm xử lý logic chính
  function addToCart(product) {
    // Lấy giỏ hàng từ LocalStorage, nếu chưa có thì tạo mảng rỗng []
    let cart = JSON.parse(localStorage.getItem("cart")) || [];

    // Kiểm tra xem sản phẩm này đã tồn tại trong giỏ chưa (so sánh theo ID)
    let existingProduct = cart.find((item) => item.id === product.id);

    if (existingProduct) {
      // TRƯỜNG HỢP 1: Sản phẩm ĐÃ CÓ -> Tăng số lượng lên 1
      existingProduct.quantity += 1;
      console.log(
        `Đã tăng số lượng sản phẩm ID ${product.id} lên ${existingProduct.quantity}`
      );
    } else {
      // TRƯỜNG HỢP 2: Sản phẩm CHƯA CÓ -> Thêm mới vào mảng
      cart.push(product);
      console.log(`Đã thêm mới sản phẩm ID ${product.id}`);
    }

    // 4. Lưu lại mảng cart mới vào LocalStorage
    localStorage.setItem("cart", JSON.stringify(cart));

    updateCartCount();
    // 5. Thông báo cho người dùng (hoặc cập nhật icon giỏ hàng)j
    alert("Đã thêm vào giỏ hàng thành công!");
  }
});

// Consolidate category checkboxes into a single hidden input "category" as comma-separated list
document.addEventListener("DOMContentLoaded", function () {
  const filterForm = document.querySelector(".filter-sidebar");
  if (!filterForm) return;

  filterForm.addEventListener("submit", function (e) {
    // Before submit, collect checked category-checkbox values
    const checked = Array.from(
      filterForm.querySelectorAll(".category-checkbox")
    )
      .filter((ch) => ch.checked)
      .map((ch) => ch.value);

    const hidden = filterForm.querySelector("#category-hidden");
    if (hidden) {
      hidden.value = checked.join(",");
    }

    // Let the form submit normally after setting hidden input
  });

  // Optional: when page loads, ensure hidden input reflects current checked boxes
  const hiddenInit = filterForm.querySelector("#category-hidden");
  if (hiddenInit) {
    const checkedInit = Array.from(
      filterForm.querySelectorAll(".category-checkbox")
    )
      .filter((ch) => ch.checked)
      .map((ch) => ch.value);
    hiddenInit.value = checkedInit.join(",");
  }
});

function updateCartCount() {
  const cart = JSON.parse(localStorage.getItem("cart")) || [];

  // 2. Tính tổng số lượng (Cộng dồn cột quantity)
  // reduce là hàm chạy vòng lặp để cộng dồn siêu nhanh
  const totalQuantity = cart.reduce((total, item) => total + item.quantity, 0);

  // 3. Hiển thị lên HTML
  const countElement = document.getElementById("cart-count");
  if (countElement) {
    countElement.innerText = totalQuantity;
  }
}

// Gọi hàm ngay khi vào trang để cập nhật số cũ
document.addEventListener("DOMContentLoaded", updateCartCount);
