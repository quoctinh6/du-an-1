<?php
include_once "./Models/Database.php";
class Users
{
    private $db;
    function __construct()
    {
        $this->db = new Database();
    }
    // Lấy tất cả người dùng
    function getAll()
    {
        $sql = "SELECT id, name, email, phone_number, role, is_active FROM users";
        return $this->db->query($sql);
    }

    // Hàm cập nhật trạng thái hoạt động (1=Mở khóa, 0=Khóa)
    public function updateActiveStatus($user_id, $status)
    {
        $sql = "UPDATE users SET is_active = ? WHERE id = ?";
        return $this->db->update($sql, $status, $user_id);
    }

    function getByArrayId($id)
    {
        $placeholder = implode(',', array_fill(0, count($id), '?'));
        $sql = 'SELECT * FROM users where id in (' . $placeholder . ' ) ';
        // Sửa từ queryOne() thành query() vì SQL đang tìm kiếm nhiều ID, phải trả về nhiều kết quả
        return $this->db->query($sql, ...$id);
    }
    // Hàm tạo tài khoản người dùng mới
    function creUser($name, $email, $phone, $password, $confirm_password)
    {
        // 1. Validate dữ liệu
        if (empty($name) || empty($email) || empty($phone) || empty($password)) {
            $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin.';
            header("Location: " . BASE_URL . "index.php/User/register");
            exit();
        }
        if ($password !== $confirm_password) {
            $_SESSION['error'] = 'Mật khẩu xác nhận không khớp.';
            header("Location: " . BASE_URL . "index.php/User/register");
            exit();
        }
        if (strlen($phone) < 9) {
            $_SESSION['error'] = 'Số điện thoại không hợp lệ.';
            header("Location: " . BASE_URL . "index.php/User/register");
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

    function getById($id)
    {
        $sql = "SELECT * FROM users where id = ?";
        return $this->db->queryOne($sql, $id);
    }

    function login($user, $is_password_correct)
    {
        if ($user && password_verify($is_password_correct, $user['password'])) {
            // BỔ SUNG: Kiểm tra trạng thái hoạt động (Nếu đã bị khóa)
            if (isset($user['is_active']) && $user['is_active'] == 0) {
                $_SESSION['error'] = 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ Admin!';
                return; // Dừng hàm khi tài khoản bị khóa
            }
            $_SESSION['user'] = $user;
            unset($_SESSION['error']);
            header("Location: " . BASE_URL);
            exit();
        } else {
            $_SESSION['error'] = 'Sai email hoặc mật khẩu!';
            return; // BỔ SUNG: Đảm bảo dừng hàm khi đăng nhập thất bại
        }
    }

    function getByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = ?";
        return $this->db->queryOne($sql, $email);
    }

    function updateUser($id, $name, $email, $phone)
    {
        // Bổ sung updated_at = NOW() vào câu SQL
        $sql = "UPDATE users SET name = ?, email = ?, phone_number = ?, updated_at = NOW() WHERE id = ?";
        return $this->db->update($sql, $name, $email, $phone, $id);
    }

    function updatePassword($id, $password)
    {
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        return $this->db->update($sql, password_hash($password, PASSWORD_DEFAULT), $id);
    }

    // Verify mật khẩu hiện tại
    function verifyPassword($id, $password)
    {
        $user = $this->getById($id);
        if ($user) {
            return password_verify($password, $user['password']);
        }
        return false;
    }

    // Cập nhật thông tin người dùng
    function updateProfile($id, $name, $email, $phone_number)
    {
        $sql = "UPDATE users SET name = ?, email = ?, phone_number = ?, updated_at = NOW() WHERE id = ?";
        return $this->db->update($sql, $name, $email, $phone_number, $id);
    }

    function deleteUser($id)
    {
        // Thực hiện khóa tài khoản (Soft Delete) bằng cách đặt is_active = 0
        return $this->updateActiveStatus($id, 0);
    }

    function countFilteredUsersAdmin($role, $status, $search)
    {
        $sql = "SELECT COUNT(id) AS total FROM users WHERE 1=1";
        $params = [];

        // Lọc theo Quyền
        if ($role != 'all') {
            $sql .= " AND role = ?";
            $params[] = $role;
        }

        // Lọc theo Trạng thái
        if ($status == 'active') {
            $sql .= " AND is_active = 1";
        } elseif ($status == 'locked') {
            $sql .= " AND is_active = 0";
        }

        // Lọc theo Tìm kiếm
        if (!empty($search)) {
            $search_param = "%$search%";
            $sql .= " AND (name LIKE ? OR email LIKE ? OR phone_number LIKE ?)";
            $params[] = $search_param;
            $params[] = $search_param;
            $params[] = $search_param;
        }

        // queryOne trả về 1 dòng. Lấy giá trị cột 'total'
        $result = $this->db->queryOne($sql, ...$params);
        return $result['total'] ?? 0;
    }


    function getFilteredUsersAdmin($role, $status, $search, $page, $limit)
    {
        $sql = "SELECT id, name, email, phone_number, role, is_active FROM users WHERE 1=1";
        $params = [];
        $offset = ($page - 1) * $limit;

        // Lọc theo Quyền
        if ($role != 'all') {
            $sql .= " AND role = ?";
            $params[] = $role;
        }

        // Lọc theo Trạng thái
        if ($status == 'active') {
            $sql .= " AND is_active = 1";
        } elseif ($status == 'locked') {
            $sql .= " AND is_active = 0";
        }

        // Lọc theo Tìm kiếm
        if (!empty($search)) {
            $search_param = "%$search%";
            $sql .= " AND (name LIKE ? OR email LIKE ? OR phone_number LIKE ?)";
            $params[] = $search_param;
            $params[] = $search_param;
            $params[] = $search_param;
        }

        $sql .= " LIMIT " . intval($limit) . " OFFSET " . intval($offset);
        return $this->db->query($sql, ...$params);
    }
    
    // [BỔ SUNG 2]: Tạo người dùng từ Admin Panel (có thể đặt role và status)
    function createAdminUser($name, $email, $phone, $password, $role, $is_active)
    {
        // 1. Kiểm tra email đã tồn tại (nên kiểm tra ở Controller, nhưng Model vẫn nên có)
        if ($this->getByEmail($email)) {
            // Có thể dùng Session hoặc throw Exception để Controller xử lý
            return false;
        }
        
        // 2. Mã hóa mật khẩu
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO `users` (`name`, `email`, `password`, `phone_number`, `role`, `is_active`) VALUES (?,?,?,?,?,?)";
        
        // Sử dụng insert() để lấy ID hoặc update() nếu bạn không cần ID trả về
        return $this->db->insert($sql, $name, $email, $hashed_password, $phone, $role, $is_active);
    }
    
    // [BỔ SUNG 3]: Cập nhật quyền hạn (Role)
    function updateUserRole($id, $new_role)
    {
        $sql = "UPDATE users SET role = ?, updated_at = NOW() WHERE id = ?";
        return $this->db->update($sql, $new_role, $id);
    }

    // [CODE MỚI]: Lấy danh sách email Admin
    function getAdminEmails() {
        // Chỉ lấy theo role admin, KHÔNG check is_active để tránh lỗi SQL
        $sql = "SELECT email FROM users WHERE role = 'admin'";
        return $this->db->query($sql);
    }
    
}