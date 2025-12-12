<?php
    include_once "Models/Database.php";

    class Coupon {
        private $db;

        public function __construct()
        {
            $this->db = new Database();
        }

        // Tim ma giam gia theo Code
        // Tra ve thong tin ma neu tim thay
        public function getByCode($code) {
            $sql = "SELECT * FROM coupons WHERE code = ? LIMIT 1";
            return $this->db->queryOne($sql, $code);
        }

        // Lay tat ca ma dang con hieu luc (de hien thi goi y neu muon)
        public function getAllAvailable() {
            $sql = "SELECT * FROM coupons WHERE usage_limit > 0 AND expires_at > NOW()";
            return $this->db->query($sql);
        }

        // Tru so luong ma sau khi dung thanh cong
        public function decrementUsage($coupon_id) {
            $sql = "UPDATE coupons SET usage_limit = usage_limit - 1 WHERE id = ?";
            return $this->db->update($sql, $coupon_id);
        }
    }
?>