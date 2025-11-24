<?php
class ProductsCtrl
{
    private $CourseModel;
    private $productModel;
    private $CatModel;

    public function __construct()
    {
        include_once __DIR__ . "/../Models/Products.php";
        $this->productModel = new Products();
        include_once __DIR__ . "/../Models/Category.php";
        $catModel = new Category();
    }
    public function category($categories)
    {
        // Lấy sản phẩm, nếu $categories rỗng => lấy tất cả
        $products = $this->productModel->getProducts(0, [$categories]);

        // Hiển thị view danh sách sản phẩm (view có thể nhận $categoryName)
        include_once("Views/products-all.php");
    }
    public function detail($slug)
    {
            $product_base = $this->productModel->getProductBySlug($slug);
        
            // Kiểm tra nếu sản phẩm tồn tại mới lấy biến thể
            if($product_base) {
                $product_variants = $this->productModel->getVariantsById_product($product_base['id']);
                include_once("Views/detail.php");
            } else {
                echo "Sản phẩm không tồn tại";
            }
        
    }

    function all() {
        // 1. Lấy tham số từ URL (để bộ lọc bên View hoạt động)
        $search = $_GET['search'] ?? '';
        
        // Xử lý Brand (URL gửi lên string, Model cần array)
        $brand = [];
        if (isset($_GET['brand']) && $_GET['brand'] != 'all') {
            $brand = [$_GET['brand']];
        }

        // Xử lý Category
        $category = [];
        if (isset($_GET['category'])) {
            $category = is_array($_GET['category']) ? $_GET['category'] : [$_GET['category']];
        }

        // 2. Gọi Model để lấy dữ liệu
        $products = $this->productModel->getProducts(16, $category, $brand, $search);

        // 3. Lúc này biến $products đã có dữ liệu, include View vào nó mới hiện
        include_once("Views/products.php");

        include_once("Views/products.php");
    }
}
?>