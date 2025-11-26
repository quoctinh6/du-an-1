<?php
class ProductsCtrl
{
    private $CourseModel;
    private $productModel;
    private $CatModel;
    private $BrandModel;


    public function __construct()
    {
        include_once __DIR__ . "/../Models/Products.php";
        $this->productModel = new Products();
        include_once __DIR__ . "/../Models/Category.php";
        $catModel = new Category();
        include_once __DIR__ . "/../Models/Brand.php";
        $this->BrandModel = new Brand();

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
        if ($product_base) {
            $product_variants = $this->productModel->getVariantsById_product($product_base['id']);
            include_once("Views/detail.php");
        } else {
            echo "Sản phẩm không tồn tại";
        }

    }

    function all()
    {

        $brands = $this->BrandModel->getAll();

        $search = $_GET['search'] ?? '';

        // Xử lý Brand (URL gửi lên string, Model cần array)
        $brand = [];
        if (isset($_GET['brand']) && $_GET['brand'] != 'all') {
            $brand = [$_GET['brand']];
        }

        // Xử lý Category
        $category = [];
        if (isset($_GET['category'])) {
            // Accept either array (category[]=1&category[]=2) or comma-separated string (category=1,2)
            if (is_array($_GET['category'])) {
                $category = $_GET['category'];
            } else {
                // Split by comma and trim spaces
                $category = array_filter(array_map('trim', explode(',', $_GET['category'])));
            }
        }

        $products = $this->productModel->getProducts(16, $category, $brand, $search);

        // 3. Lúc này biến $products đã có dữ liệu, include View vào nó mới hiện

        include_once("Views/products.php");
    }
    function cart()
    {
        include_once("Views/cart.php");
    }
}
?>