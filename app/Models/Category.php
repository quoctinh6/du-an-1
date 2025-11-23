<?php
include_once "./Models/Database.php";
class Category
{
  private $db;
  function __construct()
  {
    $this->db = new Database();
  }

  function getAll()
  {
    $sql = "SELECT * FROM categories";
    return $this->db->query($sql);
  }
}
