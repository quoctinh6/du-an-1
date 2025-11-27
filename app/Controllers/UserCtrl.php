<?php
class UserCtrl
{
    private $CourseModel;
    private $EnrollmeintModel;
    private $CatModel;

    private $UsersModel;

    private $LessonModel;

    public function __construct()
    {
        include_once __DIR__ . "/../Models/User.php";
        $this->UsersModel = new Users();
    }
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = $this->UsersModel->getByEmail($email);

            // Kiểm tra user có tồn tại và mật khẩu khớp không
            if ($user && password_verify($password, $user['password'])) {
                $this->UsersModel->login($user, true);
            } else {
                $this->UsersModel->login(null, false);
            }
        }
            
        include_once 'Views/login.php';
    }

    public function register() {
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
}
?>