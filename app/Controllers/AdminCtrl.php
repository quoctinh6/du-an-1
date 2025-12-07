<?php
class AdminCtrl
{
    private $CategoryModel;
    private $productModel;
    private $BrandModel;
    private $OrderModel;

    public function __construct()
    {
        include_once __DIR__ . "/../Models/Products.php";
        $this->productModel = new Products();

        include_once __DIR__ . "/../Models/Brand.php";
        $this->BrandModel = new Brand();

        include_once __DIR__ . "/../Models/Category.php";
        $this->CategoryModel = new Category();

        include_once __DIR__ . "/../Models/Order.php";
        $this->OrderModel = new Order();
    }

    public function index() {
        include_once 'Views/admin/admin.php';
    }

    // --- QUẢN LÝ DANH MỤC ---
    public function categories() {
        $search = $_GET['search'] ?? '';
        $status = $_GET['status'] ?? ''; // Nhận biến lọc trạng thái
        
        $categories = $this->CategoryModel->getCategoriesAdmin($search, $status);
        include_once 'Views/admin/admin_category.php';
    }

    public function addCategory() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn_add_category'])) {
            $name = $_POST['name'];
            $slug = !empty($_POST['slug']) ? $_POST['slug'] : strtolower(str_replace(' ', '-', $name));
            $desc = $_POST['description'];
            $status = $_POST['status']; // published / hidden
            
            $icon = '';
            // Xử lý upload Icon
            if (isset($_FILES['icon']) && $_FILES['icon']['error'] == 0) {
                $target_dir = "uploads/categories/";
                if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
                $ext = pathinfo($_FILES["icon"]["name"], PATHINFO_EXTENSION);
                $file_name = "cat_" . time() . "." . $ext;
                move_uploaded_file($_FILES["icon"]["tmp_name"], $target_dir . $file_name);
                $icon = $target_dir . $file_name;
            }

            $this->CategoryModel->createCategory($name, $slug, $desc, $status, $icon);
            header("Location: " . BASE_URL . "index.php/admin/categories");
            exit;
        }
    }

    public function updateCategory() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn_update_category'])) {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $slug = !empty($_POST['slug']) ? $_POST['slug'] : strtolower(str_replace(' ', '-', $name));
            $desc = $_POST['description'];
            $status = $_POST['status'];
            
            $icon = null;
            if (isset($_FILES['icon']) && $_FILES['icon']['error'] == 0) {
                $target_dir = "uploads/categories/";
                if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
                $ext = pathinfo($_FILES["icon"]["name"], PATHINFO_EXTENSION);
                $file_name = "cat_" . time() . "." . $ext;
                move_uploaded_file($_FILES["icon"]["tmp_name"], $target_dir . $file_name);
                $icon = $target_dir . $file_name;
            }

            $this->CategoryModel->updateCategory($id, $name, $slug, $desc, $status, $icon);
            header("Location: " . BASE_URL . "index.php/admin/categories");
            exit;
        }
    }

    public function deleteCategory() {
        $id = $_GET['id'] ?? 0;
        if ($id) {
            $result = $this->CategoryModel->deleteCategory($id);
            if (!$result) {
                echo "<script>alert('Không thể xóa danh mục này vì đang có sản phẩm!'); window.location.href='" . BASE_URL . "index.php/admin/categories';</script>";
                exit;
            }
        }
        header("Location: " . BASE_URL . "index.php/admin/categories");
        exit;
    }

    // (Giữ nguyên các hàm products, addProduct, updateProduct, variants, updateVariant, orders, account, user...)
    public function products() {
        $cate_id = isset($_GET['cate_id']) && $_GET['cate_id'] != '' ? [$_GET['cate_id']] : [];
        $brand_id = isset($_GET['brand_id']) && $_GET['brand_id'] != '' ? [$_GET['brand_id']] : [];
        $stock = $_GET['stock'] ?? '';
        $status = $_GET['status'] ?? '';
        $search = $_GET['search'] ?? '';

        $categoriesAll = $this->CategoryModel->getAll();
        $brandsAll = $this->BrandModel->getAll();
        $products = $this->productModel->getProducts(16, $cate_id, $brand_id, $search, $status, $stock);

        include_once 'Views/admin/admin_products.php';
    }

    public function addProduct() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn_add'])) {
            $name = $_POST['name'];
            $cate_id = $_POST['category_id'];
            $brand_id = $_POST['brand_id'];
            $desc = $_POST['description'];
            $status = $_POST['status'];
            $slug = $_POST['slug'];

            $new_product_id = $this->productModel->createProduct($name, $cate_id, $brand_id, $desc, $status, $slug);

            if ($new_product_id) {
                if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                    $target_dir = "uploads/products/";
                    if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
                    $ext = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
                    $file_name = $new_product_id . "_" . time() . "." . $ext;
                    move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . $file_name);
                    $this->productModel->addProductImage($new_product_id, $target_dir . $file_name);
                }
                header("Location: " . BASE_URL . "index.php/admin/variants?product_id=" . $new_product_id);
                exit;
            }
        }
    }

    public function updateProduct() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn_update'])) {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $cate_id = $_POST['category_id'];
            $brand_id = $_POST['brand_id'];
            $desc = $_POST['description'];
            $status = $_POST['status'];
            $slug = !empty($_POST['slug']) ? $_POST['slug'] : '';

            $this->productModel->updateProduct($id, $name, $cate_id, $brand_id, $desc, $status, $slug);

            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $target_dir = "uploads/products/";
                if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
                $ext = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
                $file_name = $id . "_" . time() . "." . $ext;
                move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . $file_name);
                $this->productModel->updateProductImage($id, $target_dir . $file_name);
            }
            header("Location: " . BASE_URL . "index.php/admin/products");
            exit;
        }
    }

    public function variants() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn_add_variant'])) {
            $product_id = $_POST['product_id'];
            $sku        = $_POST['sku'];
            $color_id   = $_POST['color_id'];
            $size_id    = $_POST['size_id'];
            $price      = $_POST['price'];
            $quantity   = $_POST['quantity'];
            $image_path = '';

            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $target_dir = "uploads/variants/";
                if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }
                $ext = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
                $file_name = "var_" . preg_replace('/[^A-Za-z0-9]/', '', $sku) . "_" . time() . "." . $ext;
                $target_file = $target_dir . $file_name;
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    $image_path = $target_file;
                }
            }
            $this->productModel->addVariant($product_id, $color_id, $size_id, $price, $quantity, $sku, $image_path);
            header("Location: " . BASE_URL . "index.php/admin/variants?product_id=" . $product_id);
            exit;
        }

        $product_id = $_GET['product_id'] ?? 0;
        if (!$product_id) {
            header("Location: index.php?act=products");
            exit;
        }
        $product  = $this->productModel->getProductById($product_id);
        $variants = $this->productModel->getVariantsById_product($product_id);
        $sizes    = $this->productModel->getAllSizes();
        $colors   = $this->productModel->getAllColors();
        include_once 'Views/admin/admin_variants.php';
    }

    public function updateVariant() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn_update_variant'])) {
            $variant_id = $_POST['variant_id'];
            $product_id = $_POST['product_id'];
            $sku        = $_POST['sku'];
            $color_id   = $_POST['color_id'];
            $size_id    = $_POST['size_id'];
            $price      = $_POST['price'];
            $quantity   = $_POST['quantity'];
            $image_path = null;

            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $target_dir = "uploads/variants/";
                if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }
                $ext = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
                $file_name = "var_" . preg_replace('/[^A-Za-z0-9]/', '', $sku) . "_" . time() . "." . $ext;
                $target_file = $target_dir . $file_name;
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    $image_path = $target_file;
                }
            }
            $this->productModel->updateVariant($variant_id, $color_id, $size_id, $price, $quantity, $sku, $image_path);
            header("Location: " . BASE_URL . "index.php/admin/variants?product_id=" . $product_id);
            exit;
        } else {
            header("Location: " . BASE_URL . "index.php/admin");
        }
    }

    public function orders() {
        $status = $_GET['status'] ?? '';
        $keyword = $_GET['keyword'] ?? '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $orders = $this->OrderModel->getAllOrdersAdmin($status, $keyword, $page, $limit);
        $totalOrders = $this->OrderModel->countOrdersAdmin($status, $keyword);
        $totalPages = ceil($totalOrders / $limit);
        include_once 'Views/admin/admin_orders.php';
    }

    public function account() {
        include_once 'Views/admin/admin_account.php';
    }
    
    public function user() {
        include_once 'Views/admin/user_profile.php';
    }
}
?>