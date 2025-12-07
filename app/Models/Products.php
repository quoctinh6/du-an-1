<?php
include_once __DIR__ . "/Database.php"; // Sử dụng đường dẫn tương đối an toàn

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
    $sql = 'SELECT products.*, MIN(product_images.image_url) image_url, MIN(variants.price) price
    FROM Products 
    INNER JOIN product_images ON products.id = product_images.product_id
    INNER JOIN variants ON products.id = variants.product_id';
    $params = [];
    $where = [];

    //lọc thư mục 
    if (!empty($categories_id)) {
      $placeholders = implode(',', array_fill(0, count($categories_id), '?'));
      $where[] = 'category_id IN (' . $placeholders . ')';
      $params = array_merge($params, $categories_id);
    }

    // lọc hảng
    if (!empty($brand)) {
      $placeholders = implode(',', array_fill(0, count($brand), '?'));
      $where[] = 'brand_id IN (' . $placeholders . ')';
      $params = array_merge($params, $brand);
    }
    // tìm kiếm
    if (!empty($search) || $search == 0) {
      $where[] = 'products.name LIKE ?';
      $params[] = '%' . $search . '%';
    }

    // nối where
    if ($where) {
      $sql .= ' where ' . implode(' and ', $where) . ' AND status = "published" GROUP BY products.id ';
    } else {
      $sql .= ' AND status = "published" GROUP BY products.id ';
    }

    // Giới hạn số lượng
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
    $sql = 'SELECT * FROM products WHERE slug = ? AND status = "published"';
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

  /**
   * Lấy một variant theo variant id
   */
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
    $placeholders = implode(',', array_fill(0, count($id), '?'));
    $sql = 'SELECT products.*, variants.color_id
    FROM products
    INNER JOIN variants ON products.id  = variants.product_id
    WHERE variants.color_id in ( ' . $placeholders . ' )';
    return $this->db->query($sql, ...$id);
  }
  function getProductsBySize_id($id)
  {
    $placeholders = implode(',', array_fill(0, count($id), '?'));
    $sql = 'SELECT products.*, variants.size_id
    FROM products
    INNER JOIN variants ON products.id  = variants.product_id
    WHERE variants.size_id in ( ' . $placeholders . ' )';
    return $this->db->query($sql, ...$id);
  }

  // Lấy nhiều khóa học trong array id  
  function getProductsrray($array_Products)
  {
    if (empty($array_Products)) return [];
    $placeholders = implode(',', array_fill(0, count($array_Products), '?'));
    $sql = "SELECT * FROM products WHERE id IN ($placeholders) AND status = 'published' ";
    return $this->db->query($sql, ...$array_Products);

  }
  
}
