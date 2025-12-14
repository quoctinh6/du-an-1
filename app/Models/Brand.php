<?php
include_once "./Models/Database.php";
class Brand
{
    private $db;
    function __construct()
    {
        $this->db = new Database();
    }

    function getAll()
    {
        $sql = "SELECT * FROM brands WHERE status = 'published' ";
        return $this->db->query($sql);
    }

    function getNameById(int $id)
    {
        $sql = "SELECT * FROM brands where id = ?";
        return $this->db->queryOne($sql, $id);
    }
}
