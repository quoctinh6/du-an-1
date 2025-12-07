<?php
include_once __DIR__ . "/Database.php"; // Sử dụng đường dẫn tương đối an toàn

class Products
{
  private $db;
  function __construct()
  {
    $this->db = new Database();
  }

  function getAll()
  {
    $sql = "SELECT * FROM products";
    return $this->db->query($sql);
  }

  function getProducts(int $limit = 16, array $categories_id = [], array $brand = [], string $search = '')
  {
    // Dùng chữ thường cho tên bảng: products, product_images, variants
    $sql = 'SELECT products.*, MIN(product_images.image_url) image_url, MIN(variants.price) price
    FROM products 
    INNER JOIN product_images ON products.id = product_images.product_id
    INNER JOIN variants ON products.id = variants.product_id';
    $params = [];
    $where = [];

    if (!empty($categories_id)) {
      $placeholders = implode(',', array_fill(0, count($categories_id), '?'));
      $where[] = 'category_id IN (' . $placeholders . ')';
      $params = array_merge($params, $categories_id);
    }

    if (!empty($brand)) {
      $placeholders = implode(',', array_fill(0, count($brand), '?'));
      $where[] = 'brand_id IN (' . $placeholders . ')';
      $params = array_merge($params, $brand);
    }

    if (!empty($search) || $search == 0) {
      $where[] = 'products.name LIKE ?';
      $params[] = '%' . $search . '%';
    }

    if ($where) {
      $sql .= ' WHERE ' . implode(' AND ', $where) . ' AND status = "published" GROUP BY products.id ';
    } else {
      $sql .= ' WHERE status = "published" GROUP BY products.id ';
    }

    if ($limit) {
      $sql .= " LIMIT " . intval($limit);
    }

    return $this->db->query($sql, ...$params);
  }

  function getProductBySlug(string $slug = '')
  {
    $sql = 'SELECT * FROM products WHERE slug = ? AND status = "published"';
    return $this->db->queryOne($sql, $slug);
  }

  function getVariantsById_product(int $id_product)
  {
    $sql = 'SELECT variants.*, sizes.name sizes, colors.name colors
    FROM variants 
    INNER JOIN sizes ON variants.size_id = sizes.id
    INNER JOIN colors ON variants.color_id = colors.id
    WHERE product_id = ?;';
    return $this->db->query($sql, $id_product);
  }

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

  function getProductsrray($array_Products)
  {
    if (empty($array_Products)) return [];
    $placeholders = implode(',', array_fill(0, count($array_Products), '?'));
    $sql = "SELECT * FROM products WHERE id IN ($placeholders) AND status = 'published' ";
    return $this->db->query($sql, ...$array_Products);
  }

  // === HÀM LẤY SẢN PHẨM YÊU THÍCH ===
  function getFavorites(array $ids, array $categories_id = [], array $brand = [], string $search = '')
  {
      if (empty($ids)) return [];

      // Dùng chữ thường cho tên bảng
      $sql = 'SELECT products.*, MIN(product_images.image_url) image_url, MIN(variants.price) price
      FROM products 
      INNER JOIN product_images ON products.id = product_images.product_id
      INNER JOIN variants ON products.id = variants.product_id';
      
      $params = [];
      $where = [];

      // 1. Lọc theo danh sách ID (WHERE IN)
      $placeholders_ids = implode(',', array_fill(0, count($ids), '?'));
      $where[] = 'products.id IN (' . $placeholders_ids . ')';
      $params = array_merge($params, $ids);

      // 2. Các bộ lọc khác
      if (!empty($categories_id)) {
        $placeholders = implode(',', array_fill(0, count($categories_id), '?'));
        $where[] = 'category_id IN (' . $placeholders . ')';
        $params = array_merge($params, $categories_id);
      }

      if (!empty($brand)) {
        $placeholders = implode(',', array_fill(0, count($brand), '?'));
        $where[] = 'brand_id IN (' . $placeholders . ')';
        $params = array_merge($params, $brand);
      }
      
      if (!empty($search)) {
        $where[] = 'products.name LIKE ?';
        $params[] = '%' . $search . '%';
      }

      // Luôn có ít nhất 1 điều kiện (ID IN) nên dùng WHERE an toàn
      $sql .= ' WHERE ' . implode(' AND ', $where) . ' AND status = "published" GROUP BY products.id';
      
      return $this->db->query($sql, ...$params);
  }
}