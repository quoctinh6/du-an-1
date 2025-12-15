<?php
include_once "./Models/Database.php";
class Brand
{
    private $db;
    function __construct()
    {
        $this->db = new Database();
    }

    // 1. LẤY TẤT CẢ (Sử dụng cho form lọc/thêm sản phẩm)
    function getAll()
    {
        $sql = "SELECT * FROM brands WHERE status = 'published' ";
        return $this->db->query($sql);
    }
    
    // 2. LẤY DANH SÁCH CHO ADMIN (Có phân trang, tìm kiếm)
    function getBrandsAdmin($search = '', $page = 1, $limit = 10)
    {
        $offset = ($page - 1) * $limit;
        // Chỉ lấy các trạng thái được phép (published/hidden)
        $sql = "SELECT b.*, (SELECT COUNT(*) FROM products p WHERE p.brand_id = b.id) AS product_count
                FROM brands b
                WHERE b.status IN ('published', 'hidden')";
        
        $params = [];
        
        if (!empty($search)) {
            $sql .= " AND b.name LIKE ?";
            $params[] = "%$search%";
        }

        $sql .= " ORDER BY b.id DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;

        return $this->db->query($sql, ...$params);
    }

    // 3. ĐẾM TỔNG SỐ (Cho phân trang)
    function countBrandsAdmin($search = '')
    {
        $sql = "SELECT COUNT(id) AS total
                FROM brands b
                WHERE b.status IN ('published', 'hidden')";

        $params = [];
        if (!empty($search)) {
            $sql .= " AND b.name LIKE ?";
            $params[] = "%$search%";
        }
        
        $result = $this->db->queryOne($sql, ...$params);
        return $result['total'] ?? 0;
    }

    // 4. THÊM THƯƠNG HIỆU
    function createBrand($name, $slug, $status)
    {
        // Kiểm tra trùng Slug ở Controller
        $sql = "INSERT INTO brands (name, slug, status) VALUES (?, ?, ?)";
        return $this->db->insert($sql, $name, $slug, $status);
    }

    // 5. CẬP NHẬT THƯƠNG HIỆU
    function updateBrand($id, $name, $slug, $status)
    {
        // Kiểm tra trùng Slug ở Controller
        $sql = "UPDATE brands SET name=?, slug=?, status=? WHERE id=?";
        return $this->db->update($sql, $name, $slug, $status, $id);
    }
    
    // 6. LẤY THÔNG TIN THEO ID
    function getNameById(int $id)
    {
        $sql = "SELECT * FROM brands where id = ?";
        return $this->db->queryOne($sql, $id);
    }
}