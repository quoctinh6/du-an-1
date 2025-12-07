<?php
include_once "./Models/Database.php";
class Products
{
  private $db;
  function __construct()
  {
    $this->db = new Database();
  }
  // Hiển thị tất cả sản phẩm
  function getAll()
  {
    $sql = "SELECT * FROM products";
    return $this->db->query($sql);
  }
  // Lấy nhiều sản phẩm với các điều kiện lọc
  function getProducts(int $limit = 16, array $categories_id = [], array $brand = [], string $search = '', string $status = '', string $stock = '')
  {
    $sql = 'SELECT products.*, 
      MIN(product_images.image_url) image_url, 
        MIN(variants.price) price, 
          SUM(variants.quantity) as total_stock,
            categories.name as cate_name,
             brands.name as brand_name
    FROM Products 
    LEFT JOIN product_images ON products.id = product_images.product_id
            LEFT JOIN variants ON products.id = variants.product_id
              LEFT JOIN categories ON products.category_id = categories.id
                LEFT JOIN brands ON products.brand_id = brands.id';
    // Đổi INNER JOIN thành LEFT JOIN để lỡ sản phẩm chưa có biến thể/ảnh nó vẫn hiện ra (tránh lỗi mất sp mới tạo)

    $params = [];
    $where = [];

    // 1. lọc theo danh mục
    if (!empty($categories_id)) {
      $placeholders = implode(',', array_fill(0, count($categories_id), '?'));
      $where[] = 'category_id IN (' . $placeholders . ')';
      $params = array_merge($params, $categories_id);
    }

    // 2. lọc theo thương hiệu
    if (!empty($brand)) {
      $placeholders = implode(',', array_fill(0, count($brand), '?'));
      $where[] = 'brand_id IN (' . $placeholders . ')';
      $params = array_merge($params, $brand);
    }
    // 3. Chức năng tìm kiếm
    if (!empty($search) || $search == 0) {
      $where[] = 'products.name LIKE ?';
      $params[] = '%' . $search . '%';
    }

    // 4. Lọc Trạng thái (Active/Hidden)
    if (!empty($status)) {
        if ($status == 'active') {
            $where[] = 'products.status = "published"';
        } elseif ($status == 'hidden') {
             // Trong SQL dump của bạn enum là 'draft', 'published', 'archived'
             // Nên 'hidden' ta sẽ map vào 'draft' hoặc 'archived' tùy bạn chọn. Ở đây tôi để draft
            $where[] = 'products.status = "draft"';
        }
    }

    // // Nối where (Nối các điều kiện lọc lại với nhau.)
    // if ($where) {
    //   $sql .= ' where ' . implode(' and ', $where) . ' AND status = "published" GROUP BY products.id ';
    // } else {
    //   $sql .= ' AND status = "published" GROUP BY products.id ';
    // }

    // Nối các điều kiện WHERE
    if ($where) {
        $sql .= ' WHERE ' . implode(' AND ', $where);
    }

    // GROUP BY trước khi HAVING
    $sql .= ' GROUP BY products.id ';

    // 5. lọc theo trạng thái (active, hidden)
    if (!empty($status)) {
      // BƯỚC 1: PHIÊN DỊCH (Mapping)
      // Nếu form gửi lên là 'active', ta hiểu là tìm 'published' trong DB
      // Nếu form gửi lên là 'hidden', ta hiểu là tìm 'draft' (hoặc 'hidden') trong DB

      $db_status = '';
      if ($status == 'active') {
        $db_status = 'published';
      } elseif ($status == 'hidden') {
        $db_status = 'draft'; // Hoặc 'hidden' tùy DB bạn quy định
      }
    }

    $sql .= " ORDER BY products.id DESC"; // Sắp xếp mới nhất lên đầu

    // 6. Phân trang và giới hạn hiển thị
    if ($limit) {
      $sql .= " LIMIT " . intval($limit);
    }
    return $this->db->query($sql, ...$params);

    // 7. Lọc Tồn kho (Phải dùng HAVING vì lọc trên cột tổng hợp SUM)
    if (!empty($stock)) {
        if ($stock == 'out') {
            // Hết hàng: Tổng = 0 hoặc NULL
            $sql .= ' HAVING total_stock = 0 OR total_stock IS NULL ';
        } elseif ($stock == 'low') {
            // Sắp hết: > 0 và < 10
            $sql .= ' HAVING total_stock > 0 AND total_stock < 10 ';
        } elseif ($stock == 'in') {
            // Còn hàng: >= 10
            $sql .= ' HAVING total_stock >= 10 ';
        }
    }

    $sql .= " ORDER BY products.id DESC"; // Sắp xếp mới nhất lên đầu
  }

  // 8. Lấy một sản phẩm theo slug (Làm chức năng chi tiết sản phẩm)
  function getProductBySlug(string $slug = '')
  {
    $sql = 'SELECT * FROM Products where slug = ? and status = "published"';
    return $this->db->queryOne($sql, $slug);
  }

  // 9. Lấy tất cả biến thể của một sản phẩm theo product_id
  function getVariantsById_product(int $id_product)
  {
    $sql = 'SELECT variants.*, sizes.name sizes, colors.name colors
    FROM variants 
    INNER JOIN sizes ON variants.size_id = sizes.id
    INNER JOIN colors ON variants.color_id = colors.id
    WHERE product_id = ?;';
    return $this->db->query($sql, $id_product);
  }

  // 10. Lấy một biến thể theo variant_id
  function getVariantById(int $variant_id)
  {
    $sql = 'SELECT variants.*, sizes.name as size_name, colors.name as color_name, products.name as product_name, products.slug
            FROM variants
            INNER JOIN sizes ON variants.size_id = sizes.id
            INNER JOIN colors ON variants.color_id = colors.id
            INNER JOIN products ON variants.product_id = products.id
            WHERE variants.id = ? LIMIT 1';
    return $this->db->queryOne($sql, $variant_id);
  }

  // 11. Lấy sản phẩm theo color_id (Cho chức năng trang chi tiết sản phẩm - lọc theo màu sắc)
  function getProductsByColor_id($id)
  {
    $params = [];
    $sql = 'SELECT products.*, variants.color_id
    FROM products
    INNER JOIN variants ON products.id  = variants.product_id';

    $placeholders = implode(',', array_fill(0, count($id), '?'));
    $sql .= " WHERE variants.color_id in ( $placeholders );";
    return $this->db->query($sql, ...$id);
  }

  // 12. Lấy sản phẩm theo size_id (Cho chức năng trang chi tiết sản phẩm - lọc theo kích thước)
  function getProductsBySize_id($id)
  {
    $params = [];
    $sql = 'SELECT products.*, variants.size_id
    FROM products
    INNER JOIN variants ON products.id  = variants.product_id';

    $placeholders = implode(',', array_fill(0, count($id), '?'));
    $sql .= " WHERE variants.size_id in ( $placeholders );";
    return $this->db->query($sql, ...$id);
  }

  // 13. Lấy nhiều sản phẩm theo mảng id sản phẩm (Cho trang giỏ hàng và thanh toán)
  function getProductsrray($array_Products)
  {

    if (empty($array_Products))
      return [];

    $placeholders = implode(',', array_fill(0, count($array_Products), '?'));

    $sql = "SELECT * FROM Products WHERE id IN ($placeholders) and status = 'published' ";

    return $this->db->query($sql, ...$array_Products);
  }

  // --- BỔ SUNG CÁC HÀM CÒN THIẾU CHO ADMIN VARIANTS ---

  // 14. Lấy thông tin chi tiết 1 sản phẩm theo ID (Để hiển thị tên SP cha ở tiêu đề trang Admin)
  function getProductById($id)
  {
      $sql = "SELECT * FROM products WHERE id = ?";
      return $this->db->queryOne($sql, $id);
  }

  // 15. Lấy danh sách tất cả Màu sắc (Để đổ dữ liệu vào Dropdown chọn màu)
  function getAllColors() {
      return $this->db->query("SELECT * FROM colors");
  }

  // 16. Lấy danh sách tất cả Kích thước (Để đổ dữ liệu vào Dropdown chọn size)
  function getAllSizes() {
      return $this->db->query("SELECT * FROM sizes");
  }

  // 17. Thêm biến thể mới (Create)
  function addVariant($product_id, $color_id, $size_id, $price, $quantity, $sku, $image) {
      $sql = "INSERT INTO variants (product_id, color_id, size_id, price, quantity, sku, image) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";
      // Lưu ý: Hàm execute của DB bên ông phải hỗ trợ trả về true/false hoặc id
      return $this->db->execute($sql, $product_id, $color_id, $size_id, $price, $quantity, $sku, $image);
  }

  // 18. Cập nhật biến thể (Update)
  function updateVariant($id, $color_id, $size_id, $price, $quantity, $sku, $image = null) {
      if ($image) {
          // Nếu có chọn ảnh mới thì cập nhật cả ảnh
          $sql = "UPDATE variants 
                  SET color_id = ?, size_id = ?, price = ?, quantity = ?, sku = ?, image = ? 
                  WHERE id = ?";
          return $this->db->execute($sql, $color_id, $size_id, $price, $quantity, $sku, $image, $id);
      } else {
          // Nếu không chọn ảnh mới thì giữ nguyên ảnh cũ
          $sql = "UPDATE variants 
                  SET color_id = ?, size_id = ?, price = ?, quantity = ?, sku = ? 
                  WHERE id = ?";
          return $this->db->execute($sql, $color_id, $size_id, $price, $quantity, $sku, $id);
      }
  }

  // 19. Xóa biến thể (Delete)
  function deleteVariant($id) {
      $sql = "DELETE FROM variants WHERE id = ?";
      return $this->db->execute($sql, $id);
  }
  
}
