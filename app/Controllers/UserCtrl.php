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
    public function index()
    {
        $user = $_SESSION['user'] ?? '';




        if ($_POST['update'] ?? '') {
            $a = $this->UsersModel->updateUser($user['id'], $_POST['up_name'], $_POST['up_email'], $_POST['up_phone'], );
            $_SESSION['user'] = $this->UsersModel->getById($user['id']);
        }

        if ($user) {
            $name = $user['name'];
            $email = $user['email'];
            $phone = $user['phone'];
            $id = $user['id'];
            $list_course = [];


            $idCourse = $this->EnrollmeintModel->listUserById($id);
            foreach ($idCourse as $items) {
                $list_course[] = $items['course_id'];
            }

            $list = $this->CourseModel->getCourseArrayId($list_course);

        }

        if ($_POST['confirm-password'] ?? '') {
            if ($_POST['up_password'] == $_POST['up_password_confirm']) {
                $this->UsersModel->updatePassword($id, $_POST['up_password']);
            } else {
                $_SESSION['error'] = "2 mật khẩu không khớp !";
            }
        }






        include_once 'Views/user.php';
    }
}
?>