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
            include_once("Views/detail.php");
        } else {
            echo "Sản phẩm không tồn tại";
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

        $products = $this->productModel->getProducts(16, $category, $brand, $search);
        include_once("Views/products.php");
    }
}