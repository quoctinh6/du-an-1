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
    
    // HÀM MỚI: Đếm tổng số danh mục (Chỉ tính published và hidden)
    function countCategoriesAdmin($search = '', $status = '')
    {
        $sql = "SELECT COUNT(*) as total
                FROM categories c 
                WHERE 1=1 AND c.status IN ('published', 'hidden')";
        
        $params = [];

        if (!empty($search)) {
            $sql .= " AND c.name LIKE ?";
            $params[] = "%$search%";
        }

        if (!empty($status)) {
            $sql .= " AND c.status = ?";
            $params[] = $status;
        } 

        $result = $this->db->queryOne($sql, ...$params);
        return $result['total'] ?? 0;
    }

    // Lấy danh sách (Chỉ lấy published và hidden)
    function getCategoriesAdmin($search = '', $status = '', $page = 1, $limit = 10)
    {
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT c.*, 
                    (SELECT COUNT(*) FROM products p WHERE p.category_id = c.id) as product_count 
                FROM categories c 
                WHERE 1=1 AND c.status IN ('published', 'hidden')"; // ⚠️ Chỉ lấy 2 trạng thái chính
        
        $params = [];

        if (!empty($search)) {
            $sql .= " AND c.name LIKE ?";
            $params[] = "%$search%";
        }

        if (!empty($status)) {
            $sql .= " AND c.status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY c.id DESC LIMIT ? OFFSET ?";
        
        $params[] = $limit;
        $params[] = $offset;

        return $this->db->query($sql, ...$params);
    }

    function getCategoryById($id)
    {
        $sql = "SELECT * FROM categories WHERE id = ?";
        return $this->db->queryOne($sql, $id);
    }

    function createCategory($name, $slug, $description, $status, $icon)
    {
        $sql = "INSERT INTO categories (name, slug, description, status, icon) VALUES (?, ?, ?, ?, ?)";
        return $this->db->insert($sql, $name, $slug, $description, $status, $icon);
    }

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
}