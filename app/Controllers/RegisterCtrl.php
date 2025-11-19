<?php
class RegisterCtrl
{
    private $CourseModel;

    private $CatModel;
    private $UserModel;

    public function __construct()
    {
        $this->UserModel = new Users();
    }
    public function index()
    {
        $password = password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT);

        if (isset($_POST['reg'])) {
            $this->UserModel->creUser($_POST['name'] ?? '', $_POST['email'] ?? '', $_POST['phone'] ?? '', $password);
        }
        include_once 'Views/user_register.php';
    }
}
?>