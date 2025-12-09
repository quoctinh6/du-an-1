<?php
// Đảm bảo file Database.php đã được include ở nơi khác hoặc StatsModel extends Database

class StatsModel extends Database
{

    // KPI 1: TÍNH TỔNG DOANH THU HÔM NAY (Đã hoàn thành)
    function getTotalRevenueToday()
    {
        $sql = "SELECT SUM(total_price) AS total FROM orders 
                WHERE status = 'completed' AND DATE(created_at) = CURDATE()";

        return $this->queryOne($sql)['total'] ?? 0;
    }

    // KPI 2: ĐẾM ĐƠN HÀNG MỚI (Đang chờ xử lý)
    function countPendingOrders()
    {
        $sql = "SELECT COUNT(id) AS count FROM orders WHERE status = 'pending'";
        return $this->queryOne($sql)['count'] ?? 0;
    }

    // KPI 3: ĐẾM KHÁCH HÀNG MỚI (7 ngày gần nhất)
    function countNewUsersLast7Days()
    {
        $sql = "SELECT COUNT(id) AS count 
                FROM users 
                WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
        return $this->queryOne($sql)['count'] ?? 0;
    }

    // Bảng 1: LẤY ĐƠN HÀNG MỚI NHẤT (JOIN users để lấy tên)
    function getLatestOrders($limit = 4)
    {
        $sql = "SELECT o.id, u.name AS user_name, o.total_price, o.status 
                FROM orders o 
                JOIN users u ON o.user_id = u.id
                ORDER BY o.created_at DESC 
                LIMIT " . intval($limit);
        return $this->query($sql);
    }

    // Bảng 2: LẤY SẢN PHẨM BÁN CHẠY NHẤT (Top 5)
    function getTopSellingProducts($limit = 5)
    {
        $sql = "SELECT p.name AS product_name, SUM(oi.quantity) AS total_sold
                FROM order_items oi
                JOIN variants v ON oi.variant_id = v.id
                JOIN products p ON v.product_id = p.id
                GROUP BY p.id, p.name
                ORDER BY total_sold DESC
                LIMIT " . intval($limit);
        return $this->query($sql);
    }
    // Đếm số lượng sản phẩm trong từng danh mục để vẽ biểu đồ tròn.
    function countProductsByCategory()
    {
        $sql = "SELECT c.name, COUNT(p.id) AS product_count
            FROM products p
            JOIN categories c ON p.category_id = c.id
            WHERE p.status = 'published' -- Chỉ tính sản phẩm đang bán
            GROUP BY c.id, c.name
            ORDER BY product_count DESC";

        return $this->query($sql);
    }
    
    function getRevenueLast30Days()
    {
        // Lấy ngày và tổng doanh thu (total_price) của các đơn hàng đã 'completed'
        // trong 30 ngày qua (INTERVAL 30 DAY)
        $sql = "SELECT DATE(created_at) AS order_date, SUM(total_price) AS total_daily_revenue
            FROM orders
            WHERE status = 'completed' AND created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
            GROUP BY order_date -- Nhóm theo ngày để tính tổng từng ngày
            ORDER BY order_date ASC"; // Sắp xếp theo ngày tăng dần
        return $this->query($sql);
    }
}
