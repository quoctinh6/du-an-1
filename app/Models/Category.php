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
        $sql = "SELECT * FROM categories WHERE status = 'published' ORDER BY id DESC ";
        return $this->db->query($sql);
    }

    // Lấy danh sách có tìm kiếm, lọc trạng thái và đếm sản phẩm
    function getCategoriesAdmin($search = '', $status = '')
    {
        $sql = "SELECT c.*, (SELECT COUNT(*) FROM products p WHERE p.category_id = c.id) as product_count 
                FROM categories c 
                WHERE 1=1";
        
        $params = [];

        if (!empty($search)) {
            $sql .= " AND c.name LIKE ?";
            $params[] = "%$search%";
        }

        if (!empty($status)) {
            $sql .= " AND c.status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY c.id DESC";

        return $this->db->query($sql, ...$params);
    }

    function getCategoryById($id)
    {
        $sql = "SELECT * FROM categories WHERE id = ?";
        return $this->db->queryOne($sql, $id);
    }

    // Thêm danh mục (Có icon, mô tả, trạng thái)
    function createCategory($name, $slug, $description, $status, $icon)
    {
        $sql = "INSERT INTO categories (name, slug, description, status, icon) VALUES (?, ?, ?, ?, ?)";
        return $this->db->insert($sql, $name, $slug, $description, $status, $icon);
    }

    // Cập nhật danh mục
    function updateCategory($id, $name, $slug, $description, $status, $icon = null)
    {
        if ($icon) {
            $sql = "UPDATE categories SET name=?, slug=?, description=?, status=?, icon=? WHERE id=?";
            return $this->db->update($sql, $name, $slug, $description, $status, $icon, $id);
        } else {
            $sql = "UPDATE categories SET name=?, slug=?, description=?, status=? WHERE id=?";
            return $this->db->update($sql, $name, $slug, $description, $status, $id);
        }
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