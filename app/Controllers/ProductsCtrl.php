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
        // lấy miêu tả cơ bảng của sảng phẩm
        $product_base = $this->productModel->getProductBySlug($slug);

        // lấy ảnh,giá của từng biến thể
        $product_variants = $this->productModel->getVariantsById_product($product_base['id']);

        // var_dump($product_variants);

        include_once("Views/detail.php");
    }
}
?>