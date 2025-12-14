<?php
include_once __DIR__ . "/Database.php";

class Comments
{
    private $db;
    
    function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Lấy danh sách bình luận (có lọc, tìm kiếm, và phân trang)
     * @return array
     */
    function getCommentsAdmin($rating = 'all', $search = '', $page = 1, $limit = 10)
    {
        $sql = 'SELECT 
                    comment.id, 
                    comment.content, 
                    comment.rating, 
                    comment.created_at,
                    product.name AS product_name, 
                    user.name AS user_name, 
                    user.id AS user_id
                FROM comments comment
                INNER JOIN users user ON comment.user_id = user.id
                LEFT JOIN products product ON comment.product_id = product.id 
                WHERE 1=1'; 

        $params = [];

        // 1. Lọc theo Rating (Số sao)
        if ($rating != 'all' && is_numeric($rating)) {
            $sql .= ' AND comment.rating = ?';
            $params[] = (int)$rating;
        }

        // 2. Tìm kiếm (Nội dung, Tên User, Tên Sản phẩm)
        if (!empty($search)) {
            $search_param = '%' . $search . '%';
            // Tìm kiếm trong cột content, tên user, tên sản phẩm
            $sql .= ' AND (comment.content LIKE ? OR user.name LIKE ? OR product.name LIKE ?)';
            $params[] = $search_param;
            $params[] = $search_param;
            $params[] = $search_param;
        }
        
        // Sắp xếp và Phân trang
        $sql .= ' ORDER BY comment.created_at DESC';
        
        // Xử lý Phân trang: Sử dụng nối chuỗi với ép kiểu INT để tránh lỗi cú pháp 1064
        $offset = ((int)$page - 1) * (int)$limit;
        $sql .= " LIMIT " . (int)$limit . " OFFSET " . (int)$offset;

        return $this->db->query($sql, ...$params);
    }
    
    /**
     * Đếm tổng số bình luận cho phân trang
     */
    function countCommentsAdmin($rating = 'all', $search = '')
    {
        $sql = 'SELECT COUNT(comment.id) AS total 
                FROM comments comment
                INNER JOIN users user ON comment.user_id = user.id
                LEFT JOIN products product ON comment.product_id = product.id 
                WHERE 1=1';

        $params = [];
        
        // 1. Lọc theo Rating
        if ($rating != 'all' && is_numeric($rating)) {
            $sql .= ' AND comment.rating = ?';
            $params[] = (int)$rating;
        }

        // 2. Tìm kiếm
        if (!empty($search)) {
            $search_param = '%' . $search . '%';
            $sql .= ' AND (comment.content LIKE ? OR user.name LIKE ? OR product.name LIKE ?)';
            $params[] = $search_param;
            $params[] = $search_param;
            $params[] = $search_param;
        }
        
        $result = $this->db->queryOne($sql, ...$params);
        return $result['total'] ?? 0;
    }

    /**
     * Xóa bình luận theo ID
     */
    function deleteComment($id)
    {
        $sql = "DELETE FROM comments WHERE id = ?";
        return $this->db->delete($sql, $id);
    }
}