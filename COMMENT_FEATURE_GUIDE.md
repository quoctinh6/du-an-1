# Hướng Dẫn Tính Năng Bình Luận/Đánh Giá Sản Phẩm

## 📋 Tổng Quan

Tính năng bình luận/đánh giá sản phẩm được hoàn thiện với các yêu cầu sau:

- ✅ Hiển thị 3 đánh giá mới nhất của sản phẩm
- ✅ Kiểm tra người dùng đã mua hàng hay chưa trước khi cho phép bình luận
- ✅ Lưu bình luận vào database
- ✅ Giao diện thân thiện và responsive

---

## 🔧 Các Thay Đổi Đã Thực Hiện

### 1. **Models/Products.php** - Thêm 4 phương thức mới

#### `getCommentsByProductId($product_id, $limit = 3)`

- Lấy 3 đánh giá mới nhất của sản phẩm
- Chỉ lấy những comment từ người dùng đã mua hàng
- **Trả về:** Array chứa `id, user_id, content, rating, created_at, name, email`

#### `getAllCommentsByProductId($product_id)`

- Lấy tất cả các comment của sản phẩm
- Tiếp cận sử dụng trong tương lai (admin panel, phân trang, v.v.)

#### `addComment($user_id, $product_id, $content, $rating)`

- Thêm comment/đánh giá mới vào database
- **Tham số:**
  - `user_id`: ID của người dùng
  - `product_id`: ID của sản phẩm
  - `content`: Nội dung bình luận
  - `rating`: Số sao (1-5)

#### `checkUserBoughtProduct($user_id, $product_id)`

- Kiểm tra xem người dùng có mua sản phẩm này chưa
- **Trả về:** `true` nếu đã mua, `false` nếu chưa
- Cách hoạt động: Tìm trong `order_items` xem có `product_id` của user này không

---

### 2. **Controllers/ProductsCtrl.php** - Cập nhật 2 phương thức

#### `detail($slug)` - Cập nhật

```php
// Thêm 3 dòng code:
$product_comments = $this->productModel->getCommentsByProductId($product_base['id'], 3);
$current_user_id = $_SESSION['user_id'] ?? 0;
$user_bought = $this->productModel->checkUserBoughtProduct($current_user_id, $product_base['id']);
```

#### `addComment()` - Phương thức mới

- **Endpoint:** `POST /index.php/products/addComment`
- **Xử lý:**
  1. Kiểm tra user đã đăng nhập
  2. Validate dữ liệu (rating 1-5, content ≥ 5 ký tự)
  3. Kiểm tra user đã mua hàng
  4. Thêm vào database
  5. Trả về JSON response

---

### 3. **Views/detail.php** - Hiển thị & Xử lý Comment

#### Phần Hiển Thị Comments (HTML)

```php
<!-- Danh sách 3 đánh giá mới nhất -->
<?php foreach ($product_comments as $comment): ?>
  <div class="review-item">
    <!-- Hiển thị: Tên, Sao, Ngày, Nội dung -->
  </div>
<?php endforeach; ?>
```

#### Phần Form Bình Luận (HTML)

```php
<!-- 3 trường hợp:
1. Chưa đăng nhập → Hiển thị link "Đăng nhập"
2. Đã đăng nhập nhưng chưa mua → Hiển thị "Bạn cần phải mua sản phẩm này"
3. Đã mua → Hiển thị form để bình luận
-->
<?php if (!isset($_SESSION['user_id'])): ?>
  <!-- Case 1 -->
<?php elseif (!$user_bought): ?>
  <!-- Case 2 -->
<?php else: ?>
  <!-- Case 3 - Form -->
<?php endif; ?>
```

#### JavaScript Xử Lý Form

- **Sự kiện:** Submit form → AJAX
- **Validation:**
  - Kiểm tra rating có được chọn
  - Content ≥ 5 ký tự
- **Thành công:**
  - Thêm comment mới vào đầu danh sách
  - Reset form
  - Xóa message "Chưa có bình luận"

---

## 🚀 Cách Sử Dụng

### Để Người Dùng Bình Luận:

1. Người dùng phải **đăng nhập**
2. Người dùng phải **đã mua sản phẩm** (kiểm tra qua `order_items`)
3. Điền form:
   - Chọn số sao (1-5)
   - Viết bình luận (≥ 5 ký tự)
   - Nhấn "Gửi đánh giá"
4. Comment sẽ xuất hiện ngay trong danh sách

### Để Xem Comments:

- Trang chi tiết sản phẩm tự động hiển thị 3 comment mới nhất
- Không cần login để xem (chỉ cần mua để comment)

---

## 📊 Database Schema

```sql
-- Comments table (đã có trong du_an_1.sql)
CREATE TABLE `comments` (
  `id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `content` text NOT NULL,
  `rating` int NOT NULL,
  `commentable_type` varchar(50) NOT NULL,  -- 'product'
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Order items (được dùng để kiểm tra ai mua hàng)
CREATE TABLE `order_items` (
  `id` bigint NOT NULL,
  `order_id` bigint NOT NULL,
  `variant_id` bigint NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  -- ...
);
```

---

## 🔍 Kiểm Tra Chức Năng

### Test Case 1: Hiển thị comments

- [ ] Vào trang chi tiết sản phẩm → Xem 3 comment mới nhất

### Test Case 2: Người dùng chưa đăng nhập

- [ ] Vào trang sản phẩm → Form hiển thị "Vui lòng đăng nhập"

### Test Case 3: Người dùng chưa mua

- [ ] Đăng nhập bằng tài khoản chưa mua → Form hiển thị "Bạn cần phải mua sản phẩm"

### Test Case 4: Người dùng đã mua

- [ ] Đăng nhập bằng tài khoản đã mua → Form hiển thị bình thường
- [ ] Chọn sao, viết comment, nhấn "Gửi"
- [ ] Comment xuất hiện ngay ở đầu danh sách

---

## 💡 Lưu Ý

1. **Session Variable:** Cần có `$_SESSION['user_id']` và `$_SESSION['user_name']` khi user đăng nhập
2. **BASE_URL:** Đảm bảo `BASE_URL` constant được định nghĩa trong `index.php`
3. **Database:** Comment sẽ được lưu với `commentable_type = 'product'`
4. **Validation:** Server-side validation rất quan trọng

---

## 📝 Code Snippets Quan Trọng

### Lấy current user:

```php
$user_id = $_SESSION['user_id'] ?? 0;
$user_bought = $this->productModel->checkUserBoughtProduct($user_id, $product_id);
```

### Kiểm tra mua hàng (SQL):

```sql
SELECT COUNT(*) FROM order_items oi
INNER JOIN variants v ON oi.variant_id = v.id
INNER JOIN orders o ON oi.order_id = o.id
WHERE v.product_id = ? AND o.user_id = ?
```

### Submit comment (JavaScript):

```javascript
fetch(formAction, {
  method: "POST",
  body: new FormData(form),
})
  .then((res) => res.json())
  .then((data) => {
    // Xử lý response
  });
```

---

## ❓ Câu Hỏi Thường Gặp

**Q: Nếu user xóa order thì có mất comment không?**

- A: Hiện tại không. Comment được lưu độc lập. Nếu muốn xóa comment khi xóa order, cần thêm trigger SQL.

**Q: Có thể phân trang comments không?**

- A: Có. Phương thức `getAllCommentsByProductId()` lấy tất cả, có thể thêm LIMIT/OFFSET.

**Q: Có thể chỉnh sửa/xóa comment không?**

- A: Hiện tại chỉ có chức năng thêm. Cần thêm phương thức update/delete nếu cần.

---

**Hoàn thành: 14/12/2025** ✅
