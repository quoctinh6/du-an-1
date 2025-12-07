<?php
class FavorCtrl
{
    private $productModel;
    private $categoryModel;
    private $brandModel;

    public function __construct()
    {
        // Khởi động session nếu chưa có
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        include_once __DIR__ . "/../Models/Products.php";
        $this->productModel = new Products();
        include_once __DIR__ . "/../Models/Category.php";
        $this->categoryModel = new Category();
        include_once __DIR__ . "/../Models/Brand.php";
        $this->brandModel = new Brand();
    }

    // URL: index.php/favor hoặc index.php/favor/index
    public function index() {
        // Lấy dữ liệu lọc
        $search = $_GET['search'] ?? '';
        $brand = (isset($_GET['brand']) && $_GET['brand'] != 'all') ? [$_GET['brand']] : [];
        
        $category = [];
        if (isset($_GET['category'])) {
            $category = is_array($_GET['category']) ? $_GET['category'] : array_filter(array_map('trim', explode(',', $_GET['category'])));
        }

        // Lấy danh sách ID từ Session
        $favorites = isset($_SESSION['favorites']) ? $_SESSION['favorites'] : [];

        // Truy vấn sản phẩm
        $products = [];
        if (!empty($favorites)) {
            $products = $this->productModel->getFavorites($favorites, $category, $brand, $search);
        }

        $brands = $this->brandModel->getAll();
        include_once "Views/favor.php";
    }

    // URL: index.php/favor/add
    public function add() {
        ob_start(); // Bắt đầu buffer

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $added = false;

            if ($id) {
                if (!isset($_SESSION['favorites'])) {
                    $_SESSION['favorites'] = [];
                }
                if (!in_array($id, $_SESSION['favorites'])) {
                    $_SESSION['favorites'][] = $id;
                    $added = true;
                }
            }

            // Xử lý AJAX trả về JSON
            if (isset($_POST['is_ajax']) && $_POST['is_ajax'] == 1) {
                if (ob_get_length()) ob_clean(); // Xóa sạch output thừa (lỗi, warning)
                
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    'success' => true, 
                    'message' => $added ? 'Đã thêm vào yêu thích thành công!' : 'Sản phẩm đã có trong danh sách yêu thích!'
                ]);
                exit; 
            }
        }
        
        // Nếu không phải AJAX thì reload trang
        header("Location: " . BASE_URL . "index.php/favor");
        exit;
    }

    // URL: index.php/favor/remove
    public function remove() {
        $id = $_GET['id'] ?? null;
        if ($id && isset($_SESSION['favorites'])) {
            $key = array_search($id, $_SESSION['favorites']);
            if ($key !== false) {
                unset($_SESSION['favorites'][$key]);
                $_SESSION['favorites'] = array_values($_SESSION['favorites']); 
            }
        }
        header("Location: " . BASE_URL . "index.php/favor");
        exit;
    }
}