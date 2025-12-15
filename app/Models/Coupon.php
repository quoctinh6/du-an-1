<?php
    include_once __DIR__ . "/Database.php"; 

    class Coupon {
        private $db;

        public function __construct()
        {
            $this->db = new Database();
        }

        // --- CÁC HÀM QUẢN TRỊ (ADMIN) ---

        /**
         * Lấy danh sách mã giảm giá với đầy đủ bộ lọc logic
         * Logic trạng thái dựa trên ngày hết hạn (expires_at):
         * - Active (Chưa hết hạn): Còn hạn > 5 ngày VÀ còn lượt dùng.
         * - Expiring (Sắp hết hạn): Còn hạn <= 5 ngày (nhưng chưa hết) VÀ còn lượt dùng.
         * - Expired (Đã hết hạn): Đã qua ngày hết hạn HOẶC hết lượt dùng.
         */
        public function getCouponsAdmin($search = '', $type = 'all', $status = 'all', $page = 1, $limit = 10)
        {
            $params = [];
            $offset = ((int)$page - 1) * (int)$limit;

            // Câu truy vấn cơ bản
            $sql = "SELECT *, 
                    -- Tính toán trạng thái ảo để hiển thị (nếu cần dùng trong PHP)
                    CASE 
                        WHEN (usage_limit <= 0 OR expires_at <= NOW()) THEN 'expired'
                        WHEN (DATEDIFF(expires_at, NOW()) <= 5) THEN 'expiring'
                        ELSE 'active'
                    END as calculated_status
                    FROM coupons WHERE 1=1";

            // 1. Lọc theo Loại (Phần trăm/Cố định)
            if ($type !== 'all') {
                $sql .= " AND type = ?";
                $params[] = $type;
            }

            // 2. Lọc theo Trạng thái (Dựa trên logic ngày & lượt dùng)
            if ($status === 'active') {
                // Cách ngày hết hạn trên 5 ngày
                $sql .= " AND DATEDIFF(expires_at, NOW()) > 5 AND (usage_limit > 0 OR usage_limit IS NULL)";
            } elseif ($status === 'expiring') {
                // Cách ngày hết hạn dưới hoặc bằng 5 ngày (nhưng chưa hết)
                $sql .= " AND DATEDIFF(expires_at, NOW()) BETWEEN 0 AND 5 AND expires_at > NOW() AND (usage_limit > 0 OR usage_limit IS NULL)";
            } elseif ($status === 'expired') {
                // Đã quá hạn hoặc hết lượt
                $sql .= " AND (expires_at <= NOW() OR usage_limit <= 0)";
            }
            
            // 3. Tìm kiếm (Theo Mã code hoặc Giá trị)
            if (!empty($search)) {
                $search_param = '%' . $search . '%';
                $sql .= " AND (code LIKE ? OR value LIKE ?)";
                $params[] = $search_param;
                $params[] = $search_param;
            }

            $sql .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
            $params[] = $limit;
            $params[] = $offset;

            return $this->db->query($sql, ...$params);
        }

        /**
         * Đếm tổng số mã để phân trang (Logic WHERE giống hệt hàm trên)
         */
        public function countCouponsAdmin($search = '', $type = 'all', $status = 'all')
        {
            $params = [];
            $sql = "SELECT COUNT(id) AS total FROM coupons WHERE 1=1";
            
            if ($type !== 'all') {
                $sql .= " AND type = ?";
                $params[] = $type;
            }

            if ($status === 'active') {
                $sql .= " AND DATEDIFF(expires_at, NOW()) > 5 AND (usage_limit > 0 OR usage_limit IS NULL)";
            } elseif ($status === 'expiring') {
                $sql .= " AND DATEDIFF(expires_at, NOW()) BETWEEN 0 AND 5 AND expires_at > NOW() AND (usage_limit > 0 OR usage_limit IS NULL)";
            } elseif ($status === 'expired') {
                $sql .= " AND (expires_at <= NOW() OR usage_limit <= 0)";
            }
            
            if (!empty($search)) {
                $search_param = '%' . $search . '%';
                $sql .= " AND (code LIKE ? OR value LIKE ?)";
                $params[] = $search_param;
                $params[] = $search_param;
            }

            $result = $this->db->queryOne($sql, ...$params);
            return $result['total'] ?? 0;
        }

        /**
         * Tạo mã giảm giá mới
         */
        public function createCoupon($code, $type, $value, $usage_limit, $expires_at) {
            $sql = "INSERT INTO coupons (code, type, value, usage_limit, expires_at) VALUES (?, ?, ?, ?, ?)";
            return $this->db->insert($sql, $code, $type, $value, $usage_limit, $expires_at);
        }

        /**
         * Cập nhật mã giảm giá
         * Lưu ý: Để "thay đổi trạng thái tùy ý", bạn chỉ cần chỉnh sửa `expires_at`.
         * - Muốn thành 'active': Chỉnh ngày hết hạn xa hơn 5 ngày tới.
         * - Muốn thành 'expiring': Chỉnh ngày hết hạn trong vòng 5 ngày tới.
         * - Muốn thành 'expired': Chỉnh ngày hết hạn về quá khứ (ví dụ: hôm qua).
         */
        public function updateCoupon($id, $code, $type, $value, $usage_limit, $expires_at) {
            $sql = "UPDATE coupons SET code=?, type=?, value=?, usage_limit=?, expires_at=? WHERE id=?";
            return $this->db->update($sql, $code, $type, $value, $usage_limit, $expires_at, $id);
        }

        /**
         * Xóa mã giảm giá khỏi Database (Xóa cứng tại vị trí bấm)
         */
        public function deleteCoupon($id) {
            $sql = "DELETE FROM coupons WHERE id = ?";
            return $this->db->delete($sql, $id);
        }

        /**
         * Lấy chi tiết một mã theo ID
         */
        public function getCouponById($id) {
            $sql = "SELECT * FROM coupons WHERE id = ? LIMIT 1";
            return $this->db->queryOne($sql, $id);
        }

        // --- CÁC HÀM CLIENT (Dành cho người dùng áp mã) ---

        /**
         * Tìm mã theo code để áp dụng
         * Chỉ lấy mã còn hiệu lực (chưa hết hạn & còn lượt dùng)
         */
        public function getByCode($code) {
            $sql = "SELECT * FROM coupons 
                    WHERE code = ? 
                    AND (usage_limit > 0 OR usage_limit IS NULL) 
                    AND (expires_at > NOW() OR expires_at IS NULL) 
                    LIMIT 1";
            return $this->db->queryOne($sql, $code);
        }
        public function getRawByCode($code) {
            // CHỈ select theo code, KHÔNG check hạn sử dụng hay số lượng ở đây
            $sql = "SELECT * FROM coupons WHERE code = ? LIMIT 1"; 
            return $this->db->queryOne($sql, $code);
        }
        /**
         * Trừ số lượng sử dụng sau khi đơn hàng thành công
         */
        public function decrementUsage($coupon_id) {
            $sql = "UPDATE coupons SET usage_limit = usage_limit - 1 WHERE id = ?";
            return $this->db->update($sql, $coupon_id);
        }
    }