<?php
include_once __DIR__ . "/Database.php";

class Coupon
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    // Lấy coupon theo code
    public function getByCode($code)
    {
        $sql = "SELECT * FROM coupons WHERE code = ? LIMIT 1";
        return $this->db->queryOne($sql, $code);
    }

    // Giảm usage_limit (nếu không null)
    public function decrementUsage($id)
    {
        $sql = "UPDATE coupons SET usage_limit = usage_limit - 1 WHERE id = ? AND usage_limit IS NOT NULL AND usage_limit > 0";
        return $this->db->update($sql, $id);
    }

    // Lấy tất cả coupon đang hoạt động (chưa hết hạn và còn usage nếu có)
    public function getAllAvailable()
    {
        $sql = "SELECT * FROM coupons WHERE (expires_at IS NULL OR expires_at >= NOW()) AND (usage_limit IS NULL OR usage_limit > 0) ORDER BY created_at DESC";
        $res = $this->db->query($sql);
        return is_array($res) ? $res : [];
    }

    // Có thể thêm các hàm khác: isExpired, isUsable
}
