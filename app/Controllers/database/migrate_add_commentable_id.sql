-- Migration: Thêm column commentable_id vào bảng comments
-- Chạy file này trên database nếu muốn hỗ trợ đầy đủ cho feature comment
-- Command: mysql -u root -p du_an_1 < migrate_add_commentable_id.sql

ALTER TABLE `comments` ADD COLUMN `commentable_id` BIGINT NOT NULL DEFAULT 0 AFTER `user_id`;
ALTER TABLE `comments` ADD KEY `commentable_id` (`commentable_id`);

-- Sau khi thêm column, cập nhật logic ở Products Model:
-- Thay thế query getCommentsByProductId bằng:
/*
  function getCommentsByProductId($product_id, $limit = 3)
  {
    $sql = "SELECT c.id, c.user_id, c.content, c.rating, c.created_at, u.name, u.email
            FROM comments c
            INNER JOIN users u ON c.user_id = u.id
            WHERE c.commentable_type = 'product' AND c.commentable_id = ?
            ORDER BY c.created_at DESC
            LIMIT ?";
    return $this->db->query($sql, $product_id, $limit) ?? [];
  }
*/

-- Và addComment:
/*
  function addComment($user_id, $product_id, $content, $rating)
  {
    $sql = "INSERT INTO comments (user_id, commentable_id, content, rating, commentable_type, created_at, updated_at)
            VALUES (?, ?, ?, ?, 'product', NOW(), NOW())";
    return $this->db->insert($sql, $user_id, $product_id, $content, $rating);
  }
*/
