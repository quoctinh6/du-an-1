<?php
session_start();
$url = $_SERVER['PATH_INFO'] ?? '';

// Xóa dấu / ở đầu (nếu có)
$url = ltrim($url, '/');

// Xóa dấu / ở cuối URL nếu có
$url = rtrim($url, '/');

// Tách URL thành một mảng dựa trên dấu /
$parts = explode('/', $url);

// Tự động lấy đường dẫn gốc, kể cả 'du an 1'
define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));

// === LOGIC ĐIỀU HƯỚNG MỚI ==

if (empty($parts[0])) {
    // TRƯỜNG HỢP 1: TRANG CHỦ
    // Nếu $url rỗng, $parts[0] cũng rỗng
    include_once "./Views/header.php";
    include_once "./Controllers/PageController.php";
    $ctrl = new PageCtrl();
    $ctrl->home();
    include_once "./Views/footer.php";

} elseif ($parts[0] == 'admin') {

    include_once "./Views/admin/header.php";
    include_once "./Controllers/AdminCtrl.php";
    $ctrl = new AdminCtrl();
    $act = $parts[1] ?? 'index';
    $params = array_slice($parts, 2);
    // Gọi action với các tham số
    $ctrl->$act(...$params);

} else {
    // TRƯỜNG HỢP 2: CÓ CONTROLLER
    include_once "./Views/header.php";
    $controllerName = ucfirst($parts[0]) . "Ctrl"; // Vd: "ProductCtrl"
    $controllerFile = "./Controllers/" . $controllerName . ".php";

    if (file_exists($controllerFile)) {

        include_once $controllerFile;
        $ctrl = new $controllerName();

        // Lấy action (phần tử [1]), mặc định là 'index' nếu không có
        $act = $parts[1] ?? 'index';

        if (method_exists($ctrl, $act)) {
            // Lấy các tham số (từ phần tử 2] trở đi)
            $params = array_slice($parts, 2);

            // Gọi action với các tham số
            $ctrl->$act(...$params);
        } else {
            // Xử lý lỗi 404 - Không tìm thấy action
            echo "Error 404: Action '$act' not found in $controllerName";
        }
    } else {
        // Xử lý lỗi 404 - Không tìm thấy controller
        echo "Error 404: Controller '$controllerName' not found";
        exit;
    }
    include_once "./Views/footer.php";
}



?>
<Script>
    const BASE_URL = <?= BASE_URL ?>
</Script>