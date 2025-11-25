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
        if($_SERVER['REQUEST_METHOD'] == "POST"){
            $username = $_POST['username'];
            $password = $_POST['password'];

            $this->UsersModel->login($username, $password);
        }
            
            include_once 'Views/login.php';
    }
}
?>