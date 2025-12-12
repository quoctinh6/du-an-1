<?php
    class ContactCtrl{
        //Hàm mặc định khi vào trang contact
        public function index() {
            //có thể xử lý logic gửi mail tại đây 
            //hiện tại chỉ cần render giao diện

            include_once 'Views/contact.php';
        }

        //Hàm xử lý khi bấm nút Gửi
        //URL: index.php/contact/send
        public function send() {
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                //Lấy dữ liệu từ form
                $name = $_POST['name'] ?? '';
                $email = $_POST['email'] ?? '';
                $subject = $_POST['subject'] ?? '';
                $message = $_POST['message'] ?? '';

                // --- CODE MỚI: Logic Gửi Mail + Ghi Log ---
                
                // 1. Gọi Model User để lấy danh sách Admin
                include_once __DIR__ . '/../Models/User.php';
                $userModel = new Users();
                $admins = $userModel->getAdminEmails();

                // 2. Soạn nội dung tin nhắn
                $mailSubject = "KHÁCH HÀNG LIÊN HỆ: " . mb_strtoupper($name, 'UTF-8');
                $mailContent = "Bạn có một liên hệ mới từ khách hàng.\n\n";
                $mailContent .= "THÔNG TIN CHI TIẾT:\n";
                $mailContent .= "--------------------------------\n";
                $mailContent .= "Họ và tên: " . $name . "\n";
                $mailContent .= "Email: " . $email . "\n";
                $mailContent .= "Chủ đề: " . $subject . "\n";
                $mailContent .= "--------------------------------\n";
                $mailContent .= "NỘI DUNG TIN NHẮN:\n";
                $mailContent .= $message . "\n";
                $mailContent .= "--------------------------------\n";
                
                // 3. GHI LOG FILE (CÓ KIỂM TRA MẢNG ĐỂ TRÁNH LỖI)
                $adminListString = "";
                // Kiểm tra $admins có phải là mảng hợp lệ không trước khi dùng array_column
                if (is_array($admins) && !empty($admins)) {
                    $adminListString = implode(', ', array_column($admins, 'email'));
                } else {
                    $adminListString = "Không tìm thấy Admin hoặc lỗi truy vấn SQL";
                }

                $logContent = "=== LOG MAIL [" . date('Y-m-d H:i:s') . "] ===\n";
                $logContent .= "To Admins: " . $adminListString . "\n";
                $logContent .= "Subject: $mailSubject\n";
                $logContent .= "Content:\n$mailContent\n";
                $logContent .= "=================================================\n\n";

                // Ghi vào file email_log.txt ở thư mục gốc để kiểm tra
                file_put_contents('email_log.txt', $logContent, FILE_APPEND);

                // 4. Gửi mail thật (Chỉ chạy khi có danh sách admin hợp lệ)
                if (is_array($admins) && !empty($admins)) {
                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/plain;charset=UTF-8" . "\r\n";
                    $headers .= "From: no-reply@zerowatch.com" . "\r\n";
                    $headers .= "Reply-To: " . $email . "\r\n";

                    foreach ($admins as $admin) {
                        // Dùng @ để ẩn lỗi nếu server không có mail config
                        if (isset($admin['email']) && !empty($admin['email'])) {
                             @mail($admin['email'], $mailSubject, $mailContent, $headers);
                        }
                    }
                }
                // --- KẾT THÚC CODE MỚI ---

                //Tạm thời thông báo thành công
                echo 
                    "<script> 
                            alert('Gửi thành công! (Chế độ Test: Vui lòng kiểm tra file email_log.txt)');
                            window.location.href='../contact'; 
                    </script>"
                ;
            }
        }
    }
?>