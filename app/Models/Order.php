<?php
include_once __DIR__ . "/Database.php";

class Order
{
    private $db;

    function __construct()
    {
        $this->db = new Database();
    }

    // --- CÁC HÀM CLIENT/CƠ SỞ (Giữ nguyên) ---

    // Lấy tất cả đơn hàng của một user
    function getOrdersByUserId($user_id)
    {
        $sql = "SELECT o.*, pm.name as payment_method_name, sm.name as shipping_method_name
                FROM orders o
                LEFT JOIN payment_methods pm ON o.payment_method_id = pm.id
                LEFT JOIN shipping_methods sm ON o.shipping_method_id = sm.id
                WHERE o.user_id = ?
                ORDER BY o.created_at DESC";
        return $this->db->query($sql, $user_id);
    }

    // Lấy chi tiết một đơn hàng
    function getOrderDetail($order_id)
    {
        $sql = "SELECT o.*, pm.name as payment_method_name, sm.name as shipping_method_name
                FROM orders o
                LEFT JOIN payment_methods pm ON o.payment_method_id = pm.id
                LEFT JOIN shipping_methods sm ON o.shipping_method_id = sm.id
                WHERE o.id = ?";
        return $this->db->queryOne($sql, $order_id);
    }

    // Lấy các item trong đơn hàng
    function getOrderItems($order_id)
    {
        $sql = "SELECT oi.*, p.name as product_name, p.slug, MIN(pi.image_url) as image_url, 
                        sz.name as size_name, c.name as color_name
                FROM order_items oi
                INNER JOIN variants v ON oi.variant_id = v.id
                INNER JOIN products p ON v.product_id = p.id
                LEFT JOIN product_images pi ON p.id = pi.product_id
                LEFT JOIN sizes sz ON v.size_id = sz.id
                LEFT JOIN colors c ON v.color_id = c.id
                WHERE oi.order_id = ?
                GROUP BY oi.id";
        return $this->db->query($sql, $order_id);
    }

    // Tạo đơn hàng mới
    function createOrder($user_id, $total_price, $shipping_address, $phone_number, $payment_method_id, $shipping_method_id, $coupon_id = null)
    {
        $sql = "INSERT INTO orders (user_id, total_price, shipping_address, phone_number, payment_method_id, shipping_method_id, coupon_id, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')";
        return $this->db->insert($sql, $user_id, $total_price, $shipping_address, $phone_number, $payment_method_id, $shipping_method_id, $coupon_id);
    }

    // Thêm item vào đơn hàng
    function addOrderItem($order_id, $variant_id, $quantity, $price)
    {
        $sql = "INSERT INTO order_items (order_id, variant_id, quantity, price)
                VALUES (?, ?, ?, ?)";
        return $this->db->insert($sql, $order_id, $variant_id, $quantity, $price);
    }

    // Cập nhật status đơn hàng
    function updateOrderStatus($order_id, $status)
    {
        $sql = "UPDATE orders SET status = ? WHERE id = ?";
        return $this->db->update($sql, $status, $order_id);
    }

    // Lấy tổng số đơn hàng của user
    function getOrderCount($user_id)
    {
        $sql = "SELECT COUNT(*) as total FROM orders WHERE user_id = ?";
        $result = $this->db->queryOne($sql, $user_id);
        return $result['total'] ?? 0;
    }

    // Lấy tổng chi tiêu của user
    function getTotalSpent($user_id)
    {
        $sql = "SELECT SUM(total_price) as total FROM orders WHERE user_id = ? AND status IN ('completed', 'shipped')";
        $result = $this->db->queryOne($sql, $user_id);
        return $result['total'] ?? 0;
    }
    
    // Lấy chi tiết đơn hàng theo ID (Dùng cho AdminCtrl)
    public function getOrderById($orderId)
    {
        $sql = "SELECT * FROM orders WHERE id = ?";
        return $this->db->queryOne($sql, $orderId);
    }


    // --- HÀM ADMIN BỔ SUNG ---

    /**
     * Lấy danh sách đơn hàng cho Admin (có lọc và phân trang)
     * Đã fix lỗi tìm kiếm theo Mã đơn hàng (FS<ID>)
     * @return array
     */
    function getAllOrdersAdmin($status = '', $keyword = '', $page = 1, $limit = 10)
    {
        $offset = ($page - 1) * $limit;
        $params = [];

        $sql = "SELECT o.*, u.name as user_name, u.email 
                FROM orders o
                LEFT JOIN users u ON o.user_id = u.id
                WHERE 1=1";

        // 🛑 LOGIC XỬ LÝ TÌM KIẾM MÃ ĐƠN HÀNG (FS<ID>)
        $search_id = null;
        $search_term = "%" . $keyword . "%";

        if (!empty($keyword) && preg_match('/^FS(\d+)$/i', $keyword, $matches)) {
            $search_id = (int)$matches[1]; // Lấy số ID từ 'FS123'
        }

        // 1. Lọc theo trạng thái
        if (!empty($status)) {
            $sql .= " AND o.status = ?";
            $params[] = $status;
        }

        // 2. Lọc theo từ khóa
        if (!empty($keyword)) {
            // Nếu tìm theo FS<ID>, chỉ tìm chính xác ID đó
            if ($search_id !== null) {
                $sql .= " AND o.id = ?";
                $params[] = $search_id;
            } else {
                // Ngược lại, tìm theo Tên khách, SĐT
                $sql .= " AND (u.name LIKE ? OR o.phone_number LIKE ?)";
                $params[] = $search_term;
                $params[] = $search_term;
            }
        }

        $sql .= " ORDER BY o.created_at DESC";
        $sql .= " LIMIT " . intval($limit) . " OFFSET " . intval($offset);

        $result = $this->db->query($sql, ...$params);
        return $result ? $result : [];
    }

    /**
     * Đếm tổng số đơn để phân trang (Đã fix lỗi tìm kiếm theo Mã đơn hàng)
     * @return int
     */
    function countOrdersAdmin($status = '', $keyword = '')
    {
        $params = [];

        $sql = "SELECT COUNT(*) as total 
                FROM orders o 
                LEFT JOIN users u ON o.user_id = u.id 
                WHERE 1=1";
        
        // 🛑 LOGIC XỬ LÝ TÌM KIẾM MÃ ĐƠN HÀNG (FS<ID>)
        $search_id = null;
        $search_term = "%" . $keyword . "%";
        if (!empty($keyword) && preg_match('/^FS(\d+)$/i', $keyword, $matches)) {
            $search_id = (int)$matches[1];
        }

        // 1. Lọc theo trạng thái
        if (!empty($status)) {
            $sql .= " AND o.status = ?";
            $params[] = $status;
        }

        // 2. Lọc theo từ khóa
        if (!empty($keyword)) {
            if ($search_id !== null) {
                $sql .= " AND o.id = ?";
                $params[] = $search_id;
            } else {
                $sql .= " AND (u.name LIKE ? OR o.phone_number LIKE ?)";
                $params[] = $search_term;
                $params[] = $search_term;
            }
        }

        $result = $this->db->queryOne($sql, ...$params);
        return $result['total'] ?? 0;
    }
}