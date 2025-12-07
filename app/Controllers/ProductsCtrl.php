<?php
class ProductsCtrl
{
    private $productModel;
    private $categoryModel;
    private $brandModel;

    public function __construct()
    {
        // Sử dụng __DIR__ để đường dẫn tuyệt đối, tránh lỗi khi include
        include_once __DIR__ . "/../Models/Products.php";
        $this->productModel = new Products();
        
        include_once __DIR__ . "/../Models/Category.php";
        $this->categoryModel = new Category(); // Sửa $catModel thành property
        
        include_once __DIR__ . "/../Models/Brand.php";
        $this->BrandModel = new Brand();




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
        $brands = $this->brandModel->getAll(); // Dùng biến property đã sửa
        $search = $_GET['search'] ?? '';

        $brand = [];
        if (isset($_GET['brand']) && $_GET['brand'] != 'all') {
            $brand = [$_GET['brand']];
        }

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
?>