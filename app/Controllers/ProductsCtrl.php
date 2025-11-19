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
        // // Nếu truyền categoryId (từ URL), lọc theo category
        // $categories = [];
        // $categoryName = null;

        // if ($categoryId !== null && $categoryId !== '') {
        //     // nếu truyền slug thay vì id, user có thể truyền slug — simple numeric check
        //     if (is_numeric($categoryId)) {
        //         $categories = [(int) $categoryId];
        //         // load tên danh mục (nếu cần hiển thị)
        //         include_once __DIR__ . "/../Models/Category.php";
        //         $catModel = new Category();
        //         $allCats = $catModel->getAll();
        //         foreach ($allCats as $c) {
        //             if ((int) $c['id'] === (int) $categoryId) {
        //                 $categoryName = $c['name'];
        //                 break;
        //             }
        //         }
        //     } else {
        //         // nếu là slug: tìm id trong bảng categories
        //         include_once __DIR__ . "/../Models/Category.php";
        //         $catModel = new Category();
        //         $allCats = $catModel->getAll();
        //         foreach ($allCats as $c) {
        //             if ($c['slug'] === $categoryId) {
        //                 $categories = [(int) $c['id']];
        //                 $categoryName = $c['name'];
        //                 break;
        //             }
        //         }
        //     }
        // }

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