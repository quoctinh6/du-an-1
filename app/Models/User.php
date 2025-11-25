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
    function creUser($name, $email, $phone, $password, $role = 'student')
    {
        if (strlen($phone) < 9) {
            return $_SESSION['error'] = 'Số điện thoại chưa đúng';
        }

        $sql = " INSERT INTO `users`( `name`, `email`, `password`, `phone`, `role`) VALUES (?,?,?,?,?) ";
        return $this->db->query($sql, $name, $email, $password, $phone, $role);
    }

    function login($user, $password)
    {

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            unset($_SESSION['error']);
            header('Location: index.php');

            exit;
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
class login {
    
}
}