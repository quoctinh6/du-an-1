<?php

class PageCtrl
{
    // private $categoriesModel;

    private $productModel;
    private $categoryModel;
    public function __construct()
    {
        include_once("Models/Products.php");
        $this->productModel = new Products();
        include_once("Models/Category.php");
        $this->categoryModel = new Category();

    }
    public function home()
    {

        $productsFeatured = $this->productModel->getProducts(4, [1]);

        $productsTrending = $this->productModel->getProducts(4, [2]);

        $productsCollections = $this->productModel->getProducts(4, [3]);
        // Lấy danh sách categories để hiển thị nút lọc trên trang chủ
        $categories = $this->categoryModel->getAll();
        // var_dump($productsFeatured);
        include_once 'Views/home.php';
    }

    public function favorites() {
        $productsFavorits = $this->productModel->getProducts(8, [0]);
        
        include_once "Views/products-favorite.php";
    }
}