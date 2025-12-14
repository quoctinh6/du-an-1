<?php
// Đảm bảo đường dẫn đến Database.php chính xác
include_once __DIR__ . "/Database.php"; 

class Comments
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Lấy danh sách bình luận (có join user và product), có lọc và phân trang.
     */
    function getCommentsAdmin($search, $rating, $page, $limit)
    {
        // Sử dụng tên cột id_product theo cấu trúc DB thực tế của bạn
        $sql = 'SELECT 
            comment.id, comment.content, comment.rating, comment.created_at,
            user.id as user_id, user.name as user_name,
            product.id as product_id, product.name as product_name
        FROM comments comment
        INNER JOIN users user ON comment.user_id = user.id
        LEFT JOIN products product ON comment.id_product = product.id 
        WHERE 1=1'; 

        $params = [];

        // 1. Lọc theo số sao
        if ($rating !== 'all' && is_numeric($rating)) {
            $sql .= " AND comment.rating = ?";
            $params[] = (int)$rating;
        }

        // 2. Tìm kiếm (theo content, user name hoặc product name)
        if (!empty($search)) {
            $search_param = '%' . $search . '%';
            $sql .= " AND (
                comment.content LIKE ? 
                OR user.name LIKE ? 
                OR product.name LIKE ?
            )";
            $params[] = $search_param;
            $params[] = $search_param;
            $params[] = $search_param;
        }

        // 3. Phân trang
        $sql .= " ORDER BY comment.created_at DESC";
        $offset = ((int)$page - 1) * (int)$limit;
        
        // 🛑 Sử dụng tham số $limit và $offset
        $sql .= " LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;

        return $this->db->query($sql, ...$params);
    }

    /**
     * Đếm tổng số bình luận cho phân trang (có lọc)
     */
    function countCommentsAdmin($search, $rating)
    {
        // Sử dụng tên cột id_product theo cấu trúc DB thực tế của bạn
        $sql = 'SELECT COUNT(comment.id) as total_comments
        FROM comments comment
        INNER JOIN users user ON comment.user_id = user.id
        LEFT JOIN products product ON comment.id_product = product.id 
        WHERE 1=1';

        $params = [];

        // 1. Lọc theo số sao
        if ($rating !== 'all' && is_numeric($rating)) {
            $sql .= " AND comment.rating = ?";
            $params[] = (int)$rating;
        }

        // 2. Tìm kiếm
        if (!empty($search)) {
            $search_param = '%' . $search . '%';
            $sql .= " AND (
                comment.content LIKE ? 
                OR user.name LIKE ? 
                OR product.name LIKE ?
            )";
            $params[] = $search_param;
            $params[] = $search_param;
            $params[] = $search_param;
        }
        
        $result = $this->db->queryOne($sql, ...$params);
        return (int)($result['total_comments'] ?? 0);
    }

    /**
     * Xóa bình luận
     */
    function deleteComment($comment_id)
    {
        $sql = "DELETE FROM comments WHERE id = ?";
        return $this->db->delete($sql, $comment_id);
    }
}