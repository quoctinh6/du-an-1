<?php
include_once "./Models/Database.php";
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
  }

  function getProductBySlug(string $slug = '')
  {
    $sql = 'SELECT * FROM Products where slug = ? and status = "published"';
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

  // Lấy nhiều khóa học trong array id  
  function getProductsrray($array_Products)
  {

    if (empty($array_Products))
      return [];

    $placeholders = implode(',', array_fill(0, count($array_Products), '?'));

    $sql = "SELECT * FROM Products WHERE id IN ($placeholders) and status = 'published' ";

    return $this->db->query($sql, ...$array_Products);

  }

}
