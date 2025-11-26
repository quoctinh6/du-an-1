<?php
include_once "./Models/Database.php";
class Users
{
    private $db;
    function __construct()
    {
        $this->db = new Database();
    }

    function getAll()
    {
        $sql = "SELECT * FROM users";
        return $this->db->query($sql);
    }
    function getAllTeacher()
    {
        $sql = "SELECT * FROM users where role = 'teacher' ";
        return $this->db->query($sql);
    }
    function getById($id)
    {
        $sql = "SELECT * FROM users where id = ?";
        return $this->db->queryOne($sql, $id);

    }
    function getbyarrrayId($id)
    {
        $placeholder = implode(',', array_fill(0, count($id), '?'));

        $sql = 'SELECT * FROM users where id in (' . $placeholder . ' ) ';
        return $this->db->queryOne($sql, ...$id);
    }
    function creUser($name, $email, $phone, $password, $confirm_password)
    {
        // 1. Validate dữ liệu
        if (empty($name) || empty($email) || empty($phone) || empty($password)) {
            $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin.';
            header("Location: /register");
            exit();
        }
        if ($password !== $confirm_password) {
            $_SESSION['error'] = 'Mật khẩu xác nhận không khớp.';
            header("Location: /register");
            exit();
        }
        if (strlen($phone) < 9) {
            $_SESSION['error'] = 'Số điện thoại không hợp lệ.';
            header("Location: /register");
            exit();
        }
        // Kiểm tra email đã tồn tại chưa
        $existingUser = $this->getByEmail($email);
        
        if ($existingUser) {
            $_SESSION['error'] = 'Email đã được sử dụng.';
            header("Location: " . BASE_URL . "index.php/User/login");
            exit();
        }

        // 2. Mã hóa mật khẩu
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = " INSERT INTO `users`( `name`, `email`, `password`, `phone_number`) VALUES (?,?,?,?) ";
        $this->db->query($sql, $name, $email, $hashed_password, $phone);

        $_SESSION['success'] = 'Đăng ký tài khoản thành công! Vui lòng đăng nhập.';
        header("Location:" . BASE_URL . "index.php/User/login");
        exit();
    }

    function login($user, $is_password_correct)
    {

        if ($user && password_verify($is_password_correct, $user['password'])) {
    $_SESSION['user'] = $user;
    unset($_SESSION['error']);
    var_dump(BASE_URL);
    // Đã sửa dòng này: Thêm dấu nháy bao quanh đường dẫn và nối chuỗi đúng
    header("Location: " . BASE_URL) ; 
    exit();
} else {
    $_SESSION['error'] = 'Sai email hoặc mật khẩu!';
}
    }

    function getByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = ?";
        return $this->db->queryOne($sql, $email);
    }

    function updateUser($id, $name, $email, $phone)
    {

        $sql = "UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ?";
        header("location:index.php?pg=user&tab=profile");
        return $this->db->update($sql, $name, $email, $phone, $id);
    }
    function updatePassword($id, $password)
    {
        $sql = "UPDATE users SET password = ? where id = ?";
        return $this->db->update($sql, password_hash($password, PASSWORD_DEFAULT), $id);
    }
}
