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
        $this->UsersModel = new Users();
        $this->LessonModel = new Lesson();
        $this->EnrollmeintModel = new Enrollments();
    }
    public function login() {
        if(($_SERVER['REQUEST_METHOD']) == 'POST') {
            $username = $_POST['username_or_email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (($username == 'admin') && ($password == 123)) {
                header('localtion' . BASE_URL);
            }else{
                $error = "Login Failed";
            }
        }
    require_once './app/Views/login.php';
    }
}
?>