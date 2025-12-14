<?php
class ProductsCtrl
{
    private $productModel;
    private $categoryModel;
    private $brandModel;

    public function __construct()
    {
        // 1. Khởi động Session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 2. Include Models
        // Dùng __DIR__ . '/../Models/...' để trỏ đúng ra thư mục Models từ thư mục Controllers
        include_once __DIR__ . "/../Models/Products.php";
        $this->productModel = new Products();

        include_once __DIR__ . "/../Models/Category.php";
        $this->categoryModel = new Category();

        include_once __DIR__ . "/../Models/Brand.php";
        $this->brandModel = new Brand();
    }

    public function category($categories)
    {
        $products = $this->productModel->getProducts(0, [$categories]);
        include_once("Views/products-all.php");
    }

    public function detail($slug)
    {
        $product_base = $this->productModel->getProductBySlug($slug);
        if ($product_base) {
            $product_variants = $this->productModel->getVariantsById_product($product_base['id']);

            $product_category = $this->productModel->getProducts(4, [$product_base['category_id']]);

            // Lấy comments (3 đánh giá mới nhất)
            $product_comments = $this->productModel->getCommentsByProductId($product_base['id'], 3);

            // Kiểm tra user đã mua hàng hay chưa
            $current_user_id = $_SESSION['user']['id'] ?? 0;
            $user_bought = false;
            if ($current_user_id > 0) {
                $user_bought = $this->productModel->checkUserBoughtProduct($current_user_id, $product_base['id']);
            }

            include_once("Views/detail.php");
        } else {
            echo "Sản phẩm không tồn tại";
        }
    }

    /**
     * Xử lý thêm comment/đánh giá mới
     */
    public function addComment()
    {
        // Kiểm tra user đã login
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = 'Vui lòng đăng nhập để bình luận';
            header("Location: " . BASE_URL . "index.php/User/login");
            exit;
        }

        // Lấy dữ liệu từ POST
        $user_id = $_SESSION['user']['id'];
        $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
        $content = isset($_POST['content']) ? trim($_POST['content']) : '';

        // Validate
        if ($product_id <= 0) {
            $_SESSION['error'] = 'Sản phẩm không tồn tại';
            header("Location: " . BASE_URL . "index.php");
            exit;
        }

        if ($rating < 1 || $rating > 5) {
            $_SESSION['error'] = 'Đánh giá sao phải từ 1 đến 5';
            header("Location: " . BASE_URL . "index.php/products/detail/" . $this->productModel->getProductById($product_id)['slug']);
            exit;
        }

        if (empty($content) || strlen($content) < 5) {
            $_SESSION['error'] = 'Bình luận phải có ít nhất 5 ký tự';
            header("Location: " . BASE_URL . "index.php/products/detail/" . $this->productModel->getProductById($product_id)['slug']);
            exit;
        }

        // Kiểm tra user đã mua sản phẩm này chưa
        if (!$this->productModel->checkUserBoughtProduct($user_id, $product_id)) {
            $_SESSION['error'] = 'Bạn chỉ có thể bình luận sau khi mua sản phẩm';
            header("Location: " . BASE_URL . "index.php/products/detail/" . $this->productModel->getProductById($product_id)['slug']);
            exit;
        }

        // Thêm comment vào database
        if ($this->productModel->addComment($user_id, $product_id, $content, $rating)) {
            $_SESSION['success'] = 'Bình luận của bạn đã được gửi thành công!';
            $product = $this->productModel->getProductById($product_id);
            header("Location: " . BASE_URL . "index.php/products/detail/" . $product['slug']);
            exit;
        } else {
            $_SESSION['error'] = 'Có lỗi khi gửi bình luận';
            header("Location: " . BASE_URL . "index.php/products/detail/" . $this->productModel->getProductById($product_id)['slug']);
            exit;
        }
    }

    public function index()
    {
        $brands = $this->brandModel->getAll();

        $search = $_GET['search'] ?? '';
        $brand = (isset($_GET['brand']) && $_GET['brand'] != 'all') ? [$_GET['brand']] : [];

        $category = [];
        if (isset($_GET['category'])) {
            if (is_array($_GET['category'])) {
                $category = $_GET['category'];
            } else {
                $category = array_filter(array_map('trim', explode(',', $_GET['category'])));
            }
        }

        $category_all = $this->categoryModel->getAll();

        $products = $this->productModel->getProducts(16, $category, $brand, $search);
        include_once("Views/products.php");
    }
}