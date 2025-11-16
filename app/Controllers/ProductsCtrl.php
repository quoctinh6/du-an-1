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
    }
    public function index()
    {


        // $produts = $this->productModel->getAll();
        // var_dump($produts);
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