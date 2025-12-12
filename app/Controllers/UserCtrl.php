<?php
class UserCtrl
{
    private $UsersModel;
    private $OrderModel;
    private $AddressModel;

    public function __construct()
    {
        include_once __DIR__ . "/../Models/User.php";
        include_once __DIR__ . "/../Models/Order.php";
        include_once __DIR__ . "/../Models/Address.php";
        $this->UsersModel = new Users();
        $this->OrderModel = new Order();
        $this->AddressModel = new Address();
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $email = $_POST['username'];
            $password = $_POST['password'];

            $user = $this->UsersModel->getByEmail($email);

            // Kiểm tra user có tồn tại và mật khẩu khớp không
            // Nếu thất bại, Model đã set $_SESSION['error'] và return;
            $this->UsersModel->login($user, $password);
        }

        // 1. Lấy thông báo lỗi/thành công từ Session
        $error = $_SESSION['error'] ?? null;
        $success = $_SESSION['success'] ?? null;

        // 2. Xóa thông báo khỏi Session ngay lập tức
        unset($_SESSION['error']);
        unset($_SESSION['success']);

        // 3. Truyền dữ liệu lỗi/thành công vào View
        // Sử dụng biến $data để truyền biến vào View
        $data = [
            'error' => $error,
            'success' => $success
        ];

        // Giả sử Controller của bạn có hàm loadView hoặc bạn xử lý truyền biến thủ công
        // Cách xử lý thủ công: extract($data) để biến $error và $success có sẵn trong View
        extract($data);

        include_once 'Views/login.php';
    }
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            // Gọi phương thức creUser từ model
            $this->UsersModel->creUser($name, $email, $phone, $password, $confirm_password);
        }
        // Hiển thị view đăng ký
        include_once 'Views/register.php';
    }

    public function info()
    {
        // Kiểm tra user đã đăng nhập chưa
        if (!isset($_SESSION['user'])) {
            header("Location: " . BASE_URL . "index.php/User/login");
            exit();
        }

        $user_id = $_SESSION['user']['id'];

        // Xử lý form cập nhật thông tin cá nhân
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
            $action = $_POST['action'];

            if ($action == 'update_profile') {
                $name = $_POST['name'] ?? '';
                $email = $_POST['email'] ?? '';
                $phone_number = $_POST['phone_number'] ?? '';

                // Validate dữ liệu
                if (empty($name) || empty($email) || empty($phone_number)) {
                    $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin.';
                } else {
                    // Kiểm tra email có bị trùng không (ngoại trừ email hiện tại)
                    $existing_user = $this->UsersModel->getByEmail($email);
                    if ($existing_user && $existing_user['id'] != $user_id) {
                        $_SESSION['error'] = 'Email này đã được sử dụng.';
                    } else {
                        $this->UsersModel->updateProfile($user_id, $name, $email, $phone_number);
                        $_SESSION['user'] = $this->UsersModel->getById($user_id);
                        $_SESSION['success'] = 'Cập nhật thông tin thành công!';
                    }
                }
            } elseif ($action == 'change_password') {
                $current_password = $_POST['current_password'] ?? '';
                $new_password = $_POST['new_password'] ?? '';
                $confirm_password = $_POST['confirm_password'] ?? '';

                if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
                    $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin.';
                } elseif (!$this->UsersModel->verifyPassword($user_id, $current_password)) {
                    $_SESSION['error'] = 'Mật khẩu hiện tại không đúng.';
                } elseif ($new_password !== $confirm_password) {
                    $_SESSION['error'] = 'Mật khẩu xác nhận không khớp.';
                } elseif (strlen($new_password) < 6) {
                    $_SESSION['error'] = 'Mật khẩu phải có ít nhất 6 ký tự.';
                } else {
                    $this->UsersModel->updatePassword($user_id, $new_password);
                    $_SESSION['success'] = 'Đổi mật khẩu thành công!';
                }
            } elseif ($action == 'add_address') {
                $full_name = $_POST['full_name'] ?? '';
                $phone_number = $_POST['phone_number'] ?? '';
                $address_line = $_POST['address_line'] ?? '';
                $is_default = isset($_POST['is_default']) ? 1 : 0;

                if (empty($full_name) || empty($phone_number) || empty($address_line)) {
                    $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin địa chỉ.';
                } else {
                    $this->AddressModel->addAddress($user_id, $full_name, $phone_number, $address_line, $is_default);
                    $_SESSION['success'] = 'Thêm địa chỉ thành công!';
                }
            } elseif ($action == 'update_address') {
                $address_id = $_POST['address_id'] ?? '';
                $full_name = $_POST['full_name'] ?? '';
                $phone_number = $_POST['phone_number'] ?? '';
                $address_line = $_POST['address_line'] ?? '';
                $is_default = isset($_POST['is_default']) ? 1 : 0;

                if (empty($full_name) || empty($phone_number) || empty($address_line)) {
                    $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin địa chỉ.';
                } else {
                    $this->AddressModel->updateAddress($address_id, $full_name, $phone_number, $address_line, $is_default);
                    $_SESSION['success'] = 'Cập nhật địa chỉ thành công!';
                }
            } elseif ($action == 'delete_address') {
                $address_id = $_POST['address_id'] ?? '';
                $this->AddressModel->deleteAddress($address_id);
                $_SESSION['success'] = 'Xóa địa chỉ thành công!';
            }

            // Redirect để tránh resubmit form
            header("Location: " . BASE_URL . "index.php/User/info");
            exit();
        }

        // Lấy dữ liệu để hiển thị
        $user = $this->UsersModel->getById($user_id);
        $orders = $this->OrderModel->getOrdersByUserId($user_id);
        $addresses = $this->AddressModel->getAddressesByUserId($user_id);
        $OrderModel = $this->OrderModel; // Pass model vào view

        include_once "Views/info.php";
    }

    public function logout()
    {
        session_destroy();
        header("Location: " . BASE_URL);
        exit();
    }
    public function reset()
    {
        include_once 'Views/reset_password.php';
    }
}
