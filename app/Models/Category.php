<?php
include_once __DIR__ . "/Database.php";

class Category
{
    private $db;
    function __construct()
    {
        $this->db = new Database();
    }

    function getAll()
    {
        $sql = "SELECT * FROM categories ORDER BY id DESC";
        return $this->db->query($sql);
    }

    // Lấy danh sách có tìm kiếm và đếm sản phẩm (Bỏ lọc status vì DB không có)
    function getCategoriesAdmin($search = '')
    {
        $sql = "SELECT c.*, (SELECT COUNT(*) FROM products p WHERE p.category_id = c.id) as product_count 
                FROM categories c 
                WHERE 1=1";
        
        $params = [];

        if (!empty($search)) {
            $sql .= " AND c.name LIKE ?";
            $params[] = "%$search%";
        }

        $sql .= " ORDER BY c.id DESC";

        return $this->db->query($sql, ...$params);
    }

    function getCategoryById($id)
    {
        $sql = "SELECT * FROM categories WHERE id = ?";
        return $this->db->queryOne($sql, $id);
    }

    // Thêm danh mục (Chỉ có name, slug)
    function createCategory($name, $slug)
    {
        $sql = "INSERT INTO categories (name, slug) VALUES (?, ?)";
        return $this->db->insert($sql, $name, $slug);
    }

    // Cập nhật danh mục (Chỉ cập nhật name, slug)
    function updateCategory($id, $name, $slug)
    {
        $sql = "UPDATE categories SET name=?, slug=? WHERE id=?";
        return $this->db->update($sql, $name, $slug, $id);
    }

    function deleteCategory($id)
    {
        $check = $this->db->queryOne("SELECT count(*) as total FROM products WHERE category_id = ?", $id);
        if ($check['total'] > 0) {
            return false;
        }
        $sql = "DELETE FROM categories WHERE id=?";
        return $this->db->delete($sql, $id);
    }
}