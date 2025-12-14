<?php
    include_once __DIR__ . "/Database.php"; 

    class Coupon {
        private $db;

        public function __construct()
        {
            $this->db = new Database();
        }

        // --- HÀM ADMIN ---

        /**
         * Lấy danh sách mã giảm giá (có lọc, tìm kiếm, phân trang)
         */
        public function getCouponsAdmin($search = '', $type = 'all', $status = 'active', $page = 1, $limit = 10)
        {
            // ... (Hàm này giữ nguyên) ...
            $params = [];
            $offset = ((int)$page - 1) * (int)$limit;

            $sql = "SELECT * FROM coupons WHERE is_deleted = 0";

            // 1. Lọc theo Loại (Phần trăm/Cố định)
            if ($type !== 'all') {
                $sql .= " AND type = ?";
                $params[] = $type;
            }

            // 2. Lọc theo Trạng thái (Active/Expired)
            if ($status === 'active') {
                $sql .= " AND (usage_limit > 0 OR usage_limit IS NULL) AND (expires_at > NOW() OR expires_at IS NULL)";
            } elseif ($status === 'expired') {
                $sql .= " AND (usage_limit <= 0 OR expires_at <= NOW())";
            }
            
            // 3. Tìm kiếm (Code hoặc Value)
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
         * Đếm tổng số mã giảm giá (cho phân trang)
         */
        public function countCouponsAdmin($search = '', $type = 'all', $status = 'active')
        {
            // ... (Hàm này giữ nguyên) ...
            $params = [];
            $sql = "SELECT COUNT(id) AS total FROM coupons WHERE is_deleted = 0";
            
            // 1. Lọc theo Loại
            if ($type !== 'all') {
                $sql .= " AND type = ?";
                $params[] = $type;
            }

            // 2. Lọc theo Trạng thái
            if ($status === 'active') {
                $sql .= " AND (usage_limit > 0 OR usage_limit IS NULL) AND (expires_at > NOW() OR expires_at IS NULL)";
            } elseif ($status === 'expired') {
                $sql .= " AND (usage_limit <= 0 OR expires_at <= NOW())";
            }
            
            // 3. Tìm kiếm
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
         * Thêm mã giảm giá mới
         */
        public function createCoupon($code, $type, $value, $usage_limit, $expires_at) {
            // ... (Hàm này giữ nguyên) ...
            $sql = "INSERT INTO coupons (code, type, value, usage_limit, expires_at) VALUES (?, ?, ?, ?, ?)";
            return $this->db->insert($sql, $code, $type, $value, $usage_limit, $expires_at);
        }

        /**
         * Cập nhật mã giảm giá
         */
        public function updateCoupon($id, $code, $type, $value, $usage_limit, $expires_at) {
            // ... (Hàm này giữ nguyên) ...
            $sql = "UPDATE coupons SET code=?, type=?, value=?, usage_limit=?, expires_at=? WHERE id=?";
            return $this->db->update($sql, $code, $type, $value, $usage_limit, $expires_at, $id);
        }

        /**
         * Xóa mềm mã giảm giá (Đặt is_deleted = 1)
         */
        public function deleteCoupon($id) {
            // ... (Hàm này giữ nguyên) ...
            $sql = "UPDATE coupons SET is_deleted = 1 WHERE id = ?";
            return $this->db->update($sql, $id);
        }

        // 🛑 HÀM MỚI: Lấy thông tin chi tiết một mã giảm giá theo ID
        public function getCouponById($id) {
            $sql = "SELECT * FROM coupons WHERE id = ? AND is_deleted = 0 LIMIT 1";
            return $this->db->queryOne($sql, $id);
        }

        // --- HÀM CLIENT (Giữ nguyên) ---
        public function getByCode($code) {
            // ... (Hàm này giữ nguyên) ...
            $sql = "SELECT * FROM coupons WHERE code = ? AND is_deleted = 0 AND (usage_limit > 0 OR usage_limit IS NULL) AND (expires_at > NOW() OR expires_at IS NULL) LIMIT 1";
            return $this->db->queryOne($sql, $code);
        }
        public function getAllAvailable() {
            // ... (Hàm này giữ nguyên) ...
            $sql = "SELECT * FROM coupons WHERE is_deleted = 0 AND (usage_limit > 0 OR usage_limit IS NULL) AND (expires_at > NOW() OR expires_at IS NULL)";
            return $this->db->query($sql);
        }
        public function decrementUsage($coupon_id) {
            // ... (Hàm này giữ nguyên) ...
            $sql = "UPDATE coupons SET usage_limit = usage_limit - 1 WHERE id = ?";
            return $this->db->update($sql, $coupon_id);
        }
    }