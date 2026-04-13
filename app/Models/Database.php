<?php
class Database
{
  private $servername = "db";
  private $username = "root";
  private $password = "root";
  private $dbname = "duan1";
  private $conn;

  function __construct()
  {
    try {
      // 1. Thêm ';charset=utf8mb4' vào chuỗi DSN
      $dsn = "mysql:host=$this->servername;dbname=$this->dbname;charset=utf8mb4";
      
      // 2. Thêm mảng options để ép buộc UTF-8 ngay từ lúc kết nối
      $options = [
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
          PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
          PDO::ATTR_EMULATE_PREPARES => false, // dùng native prepares -> không quote số
      ];

      // 3. Kết nối với options vừa tạo
      $this->conn = new PDO($dsn, $this->username, $this->password, $options);
      
      // (Dòng này đã chuyển vào $options ở trên nên có thể bỏ hoặc giữ lại cũng được)
      // $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      
    } catch (PDOException $e) {
      echo "Connection failed: " . $e->getMessage();
    }
  }

  // ... (Giữ nguyên các hàm query, queryOne, insert, update, delete bên dưới của bạn) ...
  
  function query($sql, ...$args)
  {
    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->execute($args);
      // Dòng này hơi thừa nếu đã set FETCH_ASSOC ở __construct, nhưng giữ cũng không sao
      $stmt->setFetchMode(PDO::FETCH_ASSOC); 
      return $stmt->fetchAll();
    } catch (PDOException $e) {
      echo "Error: " . $e->getMessage();
    }
  }

  function queryOne($sql, ...$args)
  {
    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->execute($args);
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      return $stmt->fetch();
    } catch (PDOException $e) {
      echo "Error: " . $e->getMessage();
    }
  }
  
  // Hàm thực thi cho INSERT, UPDATE, DELETE (trả về true/false hoặc object)
    function execute($sql) {
        // Lấy tất cả tham số truyền vào trừ tham số đầu ($sql)
        $args = array_slice(func_get_args(), 1);
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($args);
            return $stmt;
        } catch (PDOException $e) {
            echo "Lỗi thực thi SQL: " . $e->getMessage();
            die();
        }
    }
  function insert($sql, ...$args)
  {
    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->execute($args);
      return $this->conn->lastInsertId();
    } catch (PDOException $e) {
      echo "Error: " . $e->getMessage();
    }
  }

  function update($sql, ...$args)
  {
    try {
      $stmt = $this->conn->prepare($sql);
      return $stmt->execute($args);
    } catch (PDOException $e) {
      echo $sql . "<br>" . $e->getMessage();
    }
  }

  function delete($sql, ...$args)
  {
    try {
      $stmt = $this->conn->prepare($sql);
      return $stmt->execute($args);
    } catch (PDOException $e) {
      echo $sql . "<br>" . $e->getMessage();
    }
  }
  
  // lọc tìm kiếm đảm bảo k bị hack
  function fil($str)
  {
    $str = trim($_GET['search'] ?? '');
    $str = strip_tags($str);
    $str = htmlspecialchars($str, ENT_QUOTES);
    return $str;
  }

  function __destruct()
  {
    $this->conn = null;
  }
}
