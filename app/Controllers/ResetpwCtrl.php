<?php
class ResetpwCtrl {
    private $UsersModel;

    public function __construct() {
        // Tận dụng Model User đã có
        include_once __DIR__ . "/../Models/User.php";
        $this->UsersModel = new Users();
    }

    public function index() {
        // Lấy thông báo lỗi/thành công từ Session (giống UserCtrl)
        $error = $_SESSION['error'] ?? null;
        $success = $_SESSION['success'] ?? null;

        // Xóa session sau khi lấy
        unset($_SESSION['error']);
        unset($_SESSION['success']);

        // Truyền biến sang View
        include_once 'Views/reset_password.php';
    }

    // Hàm xử lý gửi yêu cầu reset password
    public function send() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'] ?? '';

            // 1. Validate Email
            if (empty($email)) {
                $_SESSION['error'] = 'Vui lòng nhập địa chỉ Email.';
                header("Location: " . BASE_URL . "index.php/Resetpw/index");
                exit();
            }

            // 2. Kiểm tra Email có tồn tại trong hệ thống không
            // Sử dụng hàm getByEmail có sẵn trong Model User
            $user = $this->UsersModel->getByEmail($email);

            if ($user) {
                // 3. Tạo mật khẩu ngẫu nhiên 6 ký tự (chữ thường + số)
                $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
                $new_password = substr(str_shuffle($characters), 0, 6);
                
                // 4. Cập nhật mật khẩu mới vào Database
                // Hàm updatePassword trong Model User đã có sẵn chức năng mã hóa (password_hash)
                $this->UsersModel->updatePassword($user['id'], $new_password);

                // 5. Gửi Email thông báo
                $subject = "CẤP LẠI MẬT KHẨU MỚI - ZERO WATCH";
                $content = "Chào bạn,\n\n";
                $content .= "Mật khẩu của bạn đã được thay đổi theo yêu cầu.\n";
                $content .= "--------------------------------\n";
                $content .= "MẬT KHẨU MỚI: " . $new_password . "\n";
                $content .= "--------------------------------\n";
                $content .= "Vui lòng đăng nhập và đổi lại mật khẩu ngay để bảo mật tài khoản.\n";
                
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/plain;charset=UTF-8" . "\r\n";
                $headers .= "From: no-reply@zerowatch.com" . "\r\n";

                // --- LOGIC GHI FILE LOG (Hỗ trợ test trên Localhost) ---
                // Giúp bạn biết mật khẩu mới là gì mà không cần mail gửi về thật
                $logContent = "=== RESET PASSWORD LOG [" . date('Y-m-d H:i:s') . "] ===\n";
                $logContent .= "To: " . $email . "\n";
                $logContent .= "New Password: " . $new_password . "\n";
                $logContent .= "=================================================\n\n";
                file_put_contents('email_log.txt', $logContent, FILE_APPEND);
                // -------------------------------------------------------

                // Gửi mail (sử dụng @ để tránh lỗi nếu chưa cấu hình mail server)
                @mail($email, $subject, $content, $headers);

                $_SESSION['success'] = 'Thành công! Mật khẩu mới đã được gửi tới Email của bạn. (Kiểm tra file email_log.txt nếu dùng Localhost)';
            } else {
                $_SESSION['error'] = 'Email này chưa được đăng ký trong hệ thống.';
            }

            // Quay lại trang Reset password để hiện thông báo
            header("Location: " . BASE_URL . "index.php/Resetpw/index");
            exit();
        }
    }
}
?>