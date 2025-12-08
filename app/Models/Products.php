<?php
include_once __DIR__ . "/Database.php";

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

  /**
   * Lấy danh sách sản phẩm
   * @return array
   */
  function getProducts($limit = 16, $categories_id = [], $brand = [], $search = '', $status = '', $stock = '')
  {
    $sql = 'SELECT products.*, MIN(product_images.image_url) image_url, MIN(variants.price) price, SUM(variants.quantity) as total_stock
        FROM products 
        LEFT JOIN product_images ON products.id = product_images.product_id
        LEFT JOIN variants ON products.id = variants.product_id';

    $params = [];
    $where = [];

    if (!empty($categories_id)) {
      $placeholders = implode(',', array_fill(0, count($categories_id), '?'));
      $where[] = 'products.category_id IN (' . $placeholders . ')';
      $params = array_merge($params, $categories_id);
    }

    if (!empty($brand)) {
      $placeholders = implode(',', array_fill(0, count($brand), '?'));
      $where[] = 'products.brand_id IN (' . $placeholders . ')';
      $params = array_merge($params, $brand);
    }

    if (!empty($search)) {
      $where[] = 'products.name LIKE ?';
      $params[] = '%' . $search . '%';
    }

    if (!empty($status)) {
      $where[] = 'products.status = ?';
      $params[] = $status;
    }

    if ($where) {
      $sql .= ' WHERE ' . implode(' AND ', $where);
    }

    $sql .= ' GROUP BY products.id';

    if ($stock === 'in_stock') {
      $sql .= ' HAVING total_stock > 0';
    } elseif ($stock === 'out_of_stock') {
      $sql .= ' HAVING total_stock <= 0 OR total_stock IS NULL';
    }

    $sql .= ' ORDER BY products.id DESC';

    if ($limit > 0) {
      $sql .= " LIMIT " . intval($limit);
    }

    $result = $this->db->query($sql, ...$params);
    // Đảm bảo luôn trả về mảng
    return is_array($result) ? $result : [];
  }

  function getProductBySlug($slug = '')
  {
    $sql = 'SELECT * FROM products WHERE slug = ?';
    return $this->db->queryOne($sql, $slug);
  }

  function getProductById($id)
  {
    $sql = "SELECT * FROM products WHERE id = ?";
    return $this->db->queryOne($sql, $id);
  }

  // 9. Lấy tất cả biến thể của một sản phẩm theo product_id
  function getVariantsById_product(int $id_product)
  {
    $sql = 'SELECT variants.*, sizes.name sizes, colors.name colors, colors.code code
    FROM variants 
    INNER JOIN sizes ON variants.size_id = sizes.id
    INNER JOIN colors ON variants.color_id = colors.id
    WHERE product_id = ?;';
    return $this->db->query($sql, $id_product);
  }

  function getVariantById($variant_id)
  {
    $sql = 'SELECT variants.*, sizes.name as size_name, colors.name as color_name, products.name as product_name, products.slug
                FROM variants
                LEFT JOIN sizes ON variants.size_id = sizes.id
                LEFT JOIN colors ON variants.color_id = colors.id
                LEFT JOIN products ON variants.product_id = products.id
                WHERE variants.id = ? LIMIT 1';
    return $this->db->queryOne($sql, $variant_id);
  }

  // --- HÀM YÊU THÍCH ---
  /**
   * @return array
   */
  function getFavorites(array $ids, array $categories_id = [], array $brand = [], string $search = '')
  {
    if (empty($ids))
      return [];

    $sql = 'SELECT products.*, MIN(product_images.image_url) image_url, MIN(variants.price) price
        FROM products 
        LEFT JOIN product_images ON products.id = product_images.product_id
        LEFT JOIN variants ON products.id = variants.product_id';

    $params = [];
    $where = [];

    $placeholders_ids = implode(',', array_fill(0, count($ids), '?'));
    $where[] = 'products.id IN (' . $placeholders_ids . ')';
    $params = array_merge($params, $ids);

    if (!empty($categories_id)) {
      $placeholders = implode(',', array_fill(0, count($categories_id), '?'));
      $where[] = 'products.category_id IN (' . $placeholders . ')';
      $params = array_merge($params, $categories_id);
    }

    if (!empty($brand)) {
      $placeholders = implode(',', array_fill(0, count($brand), '?'));
      $where[] = 'products.brand_id IN (' . $placeholders . ')';
      $params = array_merge($params, $brand);
    }

    if (!empty($search)) {
      $where[] = 'products.name LIKE ?';
      $params[] = '%' . $search . '%';
    }

    $sql .= ' WHERE ' . implode(' AND ', $where) . ' AND status = "published" GROUP BY products.id';

    $result = $this->db->query($sql, ...$params);
    return is_array($result) ? $result : [];
  }

  // --- CÁC HÀM ADMIN ---

  function createProduct($name, $cate_id, $brand_id, $desc, $status, $slug)
  {
    $sql = "INSERT INTO products (name, category_id, brand_id, description, status, slug) VALUES (?, ?, ?, ?, ?, ?)";
    return $this->db->insert($sql, $name, $cate_id, $brand_id, $desc, $status, $slug);
  }

  function updateProduct($id, $name, $cate_id, $brand_id, $desc, $status, $slug)
  {
    $sql = "UPDATE products SET name=?, category_id=?, brand_id=?, description=?, status=?, slug=? WHERE id=?";
    return $this->db->update($sql, $name, $cate_id, $brand_id, $desc, $status, $slug, $id);
  }

  function addProductImage($product_id, $image_url)
  {
    $sql = "INSERT INTO product_images (product_id, image_url) VALUES (?, ?)";
    return $this->db->insert($sql, $product_id, $image_url);
  }

  function updateProductImage($product_id, $image_url)
  {
    $check = $this->db->queryOne("SELECT id FROM product_images WHERE product_id = ?", $product_id);
    if ($check) {
      $sql = "UPDATE product_images SET image_url=? WHERE product_id=?";
      return $this->db->update($sql, $image_url, $product_id);
    } else {
      return $this->addProductImage($product_id, $image_url);
    }
  }

  /**
   * @return array
   */
  function getAllSizes()
  {
    $res = $this->db->query("SELECT * FROM sizes");
    return is_array($res) ? $res : [];
  }

  /**
   * @return array
   */
  function getAllColors()
  {
    $res = $this->db->query("SELECT * FROM colors");
    return is_array($res) ? $res : [];
  }

  function addVariant($product_id, $color_id, $size_id, $price, $quantity, $sku, $image_path)
  {
    $sql = "INSERT INTO variants (product_id, color_id, size_id, price, quantity, sku, image) VALUES (?, ?, ?, ?, ?, ?, ?)";
    return $this->db->insert($sql, $product_id, $color_id, $size_id, $price, $quantity, $sku, $image_path);
  }

  function updateVariant($variant_id, $color_id, $size_id, $price, $quantity, $sku, $image_path = null)
  {
    if ($image_path) {
      $sql = "UPDATE variants SET color_id=?, size_id=?, price=?, quantity=?, sku=?, image=? WHERE id=?";
      return $this->db->update($sql, $color_id, $size_id, $price, $quantity, $sku, $image_path, $variant_id);
    } else {
      $sql = "UPDATE variants SET color_id=?, size_id=?, price=?, quantity=?, sku=? WHERE id=?";
      return $this->db->update($sql, $color_id, $size_id, $price, $quantity, $sku, $variant_id);
    }
  }
}
