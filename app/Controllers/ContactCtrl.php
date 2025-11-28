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
                $message = $_POST['message'] ?? '';

                //Có thể gọi Models để lưu CSDL
                //Hoặc gửi email về cho admin

                //Tạm thời thông báo thành công
                echo 
                    "<script> 
                            alert('Cam on vi dong gop cua ban, chung toi se som co phan hoi cho ban trong thoi gian som nhat');
                            window.location.href='../contact'; 
                    </script>"
                ;
            }
        }
    }
?>