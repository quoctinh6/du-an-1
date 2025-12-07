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

    // --- QUẢN LÝ SẢN PHẨM ---
    public function products() {
        // ... (Giữ nguyên logic products cũ của mày) ...
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

    // --- LOGIC THÊM SẢN PHẨM CHA (Giữ nguyên) ---
    public function addProduct() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn_add'])) {
            // ... (Code cũ của mày ok rồi) ...
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

    // --- LOGIC SỬA SẢN PHẨM CHA (Giữ nguyên) ---
    public function updateProduct() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn_update'])) {
            // ... (Code cũ của mày ok rồi) ...
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
                $ext = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
                $file_name = $id . "_" . time() . "." . $ext;
                move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . $file_name);
                $this->productModel->updateProductImage($id, $target_dir . $file_name);
            }
            header("Location: " . BASE_URL . "index.php/admin/products");
            exit;
        }
    }

    // =========================================================================
    // === PHẦN QUAN TRỌNG: QUẢN LÝ BIẾN THỂ (VARIANTS) ===
    // =========================================================================

    // 1. Hiển thị danh sách & Xử lý Thêm mới (Gộp chung vì form action của mày trỏ về variants)
    public function variants()
    {
        // --- XỬ LÝ FORM THÊM BIẾN THỂ (POST) ---
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn_add_variant'])) {
            $product_id = $_POST['product_id'];
            $sku        = $_POST['sku'];
            $color_id   = $_POST['color_id'];
            $size_id    = $_POST['size_id'];
            $price      = $_POST['price'];
            $quantity   = $_POST['quantity'];
            
            $image_path = ''; // Mặc định rỗng

            // Xử lý upload ảnh biến thể
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $target_dir = "uploads/variants/";
                // Tạo thư mục nếu chưa có (để tránh lỗi)
                if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }

                $ext = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
                // Tên file: var_SKU_Time.jpg
                $file_name = "var_" . preg_replace('/[^A-Za-z0-9]/', '', $sku) . "_" . time() . "." . $ext;
                $target_file = $target_dir . $file_name;

                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    $image_path = $target_file;
                }
            }

            // Gọi Model thêm mới
            $this->productModel->addVariant($product_id, $color_id, $size_id, $price, $quantity, $sku, $image_path);

            // Refresh lại trang để thấy dữ liệu mới
            header("Location: " . BASE_URL . "index.php/admin/variants?product_id=" . $product_id);
            exit;
        }

        // --- HIỂN THỊ DANH SÁCH (GET) ---
        $product_id = $_GET['product_id'] ?? 0;

        if (!$product_id) {
            header("Location: index.php?act=products");
            exit;
        }

        // Lấy dữ liệu cần thiết
        $product  = $this->productModel->getProductById($product_id);
        $variants = $this->productModel->getVariantsById_product($product_id);
        $sizes    = $this->productModel->getAllSizes();
        $colors   = $this->productModel->getAllColors();

        include_once 'Views/admin/admin_variants.php';
    }

    // 2. Xử lý Cập nhật Biến thể (Action mới)
    public function updateVariant() {
        // Chỉ chạy khi có method POST và nút btn_update_variant
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn_update_variant'])) {
            
            $variant_id = $_POST['variant_id']; // ID của biến thể đang sửa
            $product_id = $_POST['product_id']; // Để redirect về đúng chỗ
            
            $sku        = $_POST['sku'];
            $color_id   = $_POST['color_id'];
            $size_id    = $_POST['size_id'];
            $price      = $_POST['price'];
            $quantity   = $_POST['quantity'];

            $image_path = null; // Mặc định null (nghĩa là không đổi ảnh)

            // Kiểm tra có up ảnh mới không
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $target_dir = "uploads/variants/";
                if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }

                $ext = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
                $file_name = "var_" . preg_replace('/[^A-Za-z0-9]/', '', $sku) . "_" . time() . "." . $ext;
                $target_file = $target_dir . $file_name;

                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    $image_path = $target_file; // Có ảnh mới
                }
            }

            // Gọi Model Update
            // Nếu $image_path là null -> Model sẽ giữ nguyên ảnh cũ (theo logic trong Model mày đã viết)
            $this->productModel->updateVariant($variant_id, $color_id, $size_id, $price, $quantity, $sku, $image_path);

            // Quay về trang danh sách biến thể
            header("Location: " . BASE_URL . "index.php/admin/variants?product_id=" . $product_id);
            exit;
        } else {
            // Nếu ai đó cố tình truy cập link này bằng GET -> đá về trang chủ
            header("Location: " . BASE_URL . "index.php/admin");
        }
    }

    // --- CÁC HÀM KHÁC (Giữ nguyên) ---
    public function categories() {
        include_once 'Views/admin/admin_category.php';
    }
    
    public function orders() {
        // (Code orders của mày)
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