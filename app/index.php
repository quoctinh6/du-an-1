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

} elseif (strtolower($parts[0]) == 'admin') {
    
    // 1. Nạp Header Admin
    include_once "./Views/admin/header.php";

    // 2. Xác định Controller con (Dựa vào phần tử thứ 2 của URL)
    // Nếu URL là /Admin -> Mặc định gọi Dashboard
    $subCtrl = $parts[1] ?? 'Dashboard'; 

    // 3. Ghép tên file: Admin + TênChứcNăng + Ctrl
    // Ví dụ: AdminProductCtrl, AdminDashboardCtrl
    $controllerName = "Admin" . ucfirst($subCtrl) . "Ctrl";

    // 4. Đường dẫn file (Lưu ý: Bạn để file trong Controllers hay Controllers/Admin?)
    // Dựa trên ảnh cũ, file của bạn nằm ngay trong Controllers/
    $controllerFile = "./Controllers/" . $controllerName . ".php";
    
    // Nếu bạn đã chuyển vào thư mục con 'Admin' thì dùng dòng dưới:
    // $controllerFile = "./Controllers/Admin/" . $controllerName . ".php";

    if (file_exists($controllerFile)) {
        include_once $controllerFile;
        $ctrl = new $controllerName();

        // 5. Xác định Action (Hàm) - Lùi xuống vị trí số 2
        $act = $parts[2] ?? 'index'; 
        
        // 6. Lấy tham số
        $params = array_slice($parts, 3);

        // 7. Gọi hàm
        if (method_exists($ctrl, $act)) {
            $ctrl->$act(...$params);
        } else {
            echo "Lỗi: Không tìm thấy hành động '$act'";
        }
    } else {
        echo "Lỗi 404: Không tìm thấy Controller Admin: $controllerName";
    }

}
    include_once "./Views/footer.php";

?>
<Script>
    const BASE_URL = <?= BASE_URL ?>    
</Script>