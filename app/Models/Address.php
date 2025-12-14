<?php
include_once "./Models/Database.php";

class Address
{
    private $db;

    function __construct()
    {
        $this->db = new Database();
    }

    // Lấy tất cả địa chỉ của một user
    
    function getAddressesByUserId($user_id)
    {
        $sql = "SELECT * FROM addresses WHERE user_id = ? ORDER BY is_default DESC, created_at DESC";
        return $this->db->query($sql, $user_id);
    }

    // Lấy địa chỉ mặc định
    function getDefaultAddress($user_id)
    {
        $sql = "SELECT * FROM addresses WHERE user_id = ? AND is_default = 1 LIMIT 1";
        return $this->db->queryOne($sql, $user_id);
    }

    // Lấy một địa chỉ theo ID
    function getAddressById($address_id)
    {
        $sql = "SELECT * FROM addresses WHERE id = ?";
        return $this->db->queryOne($sql, $address_id);
    }

    // Thêm địa chỉ mới
    function addAddress($user_id, $full_name, $phone_number, $address_line, $is_default = 0)
    {
        // Nếu là địa chỉ mặc định, bỏ default của các địa chỉ cũ
        if ($is_default == 1) {
            $sql_reset = "UPDATE addresses SET is_default = 0 WHERE user_id = ?";
            $this->db->update($sql_reset, $user_id);
        }

        $sql = "INSERT INTO addresses (user_id, full_name, phone_number, address_line, is_default)
                VALUES (?, ?, ?, ?, ?)";
        return $this->db->insert($sql, $user_id, $full_name, $phone_number, $address_line, $is_default);
    }

    // Cập nhật địa chỉ
    function updateAddress($address_id, $full_name, $phone_number, $address_line, $is_default = 0)
    {
        $address = $this->getAddressById($address_id);

        if ($is_default == 1) {
            $sql_reset = "UPDATE addresses SET is_default = 0 WHERE user_id = ?";
            $this->db->update($sql_reset, $address['user_id']);
        }

        $sql = "UPDATE addresses SET full_name = ?, phone_number = ?, address_line = ?, is_default = ? WHERE id = ?";
        return $this->db->update($sql, $full_name, $phone_number, $address_line, $is_default, $address_id);
    }

    // Xóa địa chỉ
    function deleteAddress($address_id)
    {
        $sql = "DELETE FROM addresses WHERE id = ?";
        return $this->db->delete($sql, $address_id);
    }

    // Đặt địa chỉ làm mặc định
    function setDefaultAddress($address_id, $user_id)
    {
        // Bỏ default của tất cả địa chỉ cũ
        $sql_reset = "UPDATE addresses SET is_default = 0 WHERE user_id = ?";
        $this->db->update($sql_reset, $user_id);

        // Đặt địa chỉ này làm default
        $sql = "UPDATE addresses SET is_default = 1 WHERE id = ?";
        return $this->db->update($sql, $address_id);
    }
}
