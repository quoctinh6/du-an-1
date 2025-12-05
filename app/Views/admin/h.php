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
    public function index()
    {
        include_once 'Views/admin/admin.php';
    }

    public function account()
    {
        include_once 'Views/admin/admin_account.php';
    }

    public function products()
    {
        // Bước 1: Hiển thị toàn bộ 

        // Hiển thị toàn bộ sản phẩm
        $productsAll = $this->productModel->getAll();
        // Hiển thị toàn bộ danh mục Categories
        $categoriesAll = $this->CategoryModel->getAll();
        // Hiển thị toàn bộ thương hiệu Brands
        $brandsAll = $this->BrandModel->getAll();

        // Kiểm tra xem user có bấm nút Lưu không
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn_add'])) {
            $name = $_POST['name'];
            $cate_id = $_POST['category_id'];
            $brand_id = $_POST['brand_id'];
            $desc = $_POST['description'];
            $status = $_POST['status'];
            $slug = $_POST['slug'];

            // Gọi Model tạo sản phẩm cha trước để lấy cái ID
            $new_product_id = $this->productModel->createProduct($name, $cate_id, $brand_id, $desc, $status, $slug);

            if ($new_product_id) {
                // Nếu tạo thành công -> Xử lý upload ảnh
                if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {

                    // --- QUAN TRỌNG: Đường dẫn lưu ảnh cha ---
                    $target_dir = "uploads/products/";

                    // Tạo tên file ngẫu nhiên: id_time.jpg (để ko bị trùng)
                    $ext = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
                    $file_name = $new_product_id . "_" . time() . "." . $ext;
                    $target_file = $target_dir . $file_name;

                    // Di chuyển file
                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                        // Lưu đường dẫn ảnh vào bảng product_images
                        $this->productModel->addProductImage($new_product_id, $target_file);
                    }
                }

                // Redirect ngay lập tức sang trang thêm biến thể cho tiện
                header("Location: " . BASE_URL . "index.php/admin/variants?product_id=" . $new_product_id);
                exit;
            }
        }

        // Bước 2: Lấy tham số từ URL (Giống như hứng biến đầu vào)
        // Nếu không có thì mặc định là mảng rỗng hoặc chuỗi rỗng
        $cate_id = isset($_GET['cate_id']) && $_GET['cate_id'] != '' ? [$_GET['cate_id']] : [];
        $brand_id = isset($_GET['brand_id']) && $_GET['brand_id'] != '' ? [$_GET['brand_id']] : [];
        $stock = $_GET['stock'] ?? '';
        $status = $_GET['status'] ?? '';
        $search = $_GET['search'] ?? '';


        // Bước 3: Gọi Model (Truyền thêm status và stock vào hàm getProducts)

        // Lấy sản phẩm với các điều kiện lọc: Danh mục, Thương hiệu, Tình trạng kho, Trạng thái bán, Từ khóa tìm kiếm
        $products = $this->productModel->getProducts(16, $cate_id, $brand_id, $search, $status, $stock);


        include_once 'Views/admin/admin_products.php';
    }

    public function orders()
    {
        // 1. Hứng tham số
        $status = $_GET['status'] ?? '';
        $keyword = $_GET['keyword'] ?? ''; // <--- Thêm dòng này (Từ khóa tìm kiếm)
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;

        // 2. Gọi Model (Truyền thêm $keyword vào)
        $orders = $this->OrderModel->getAllOrdersAdmin($status, $keyword, $page, $limit);
        $totalOrders = $this->OrderModel->countOrdersAdmin($status, $keyword);

        // 3. Tính toán phân trang
        $totalPages = ceil($totalOrders / $limit);

        // 4. View
        include_once 'Views/admin/admin_orders.php';
    }


    public function categories()
    {
        include_once 'Views/admin/admin_category.php';
    }

    public function variants()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn_add_variant'])) {
            $product_id = $_POST['product_id'];
            $sku = $_POST['sku'];
            $color_id = $_POST['color_id'];
            $size_id = $_POST['size_id'];
            $price = $_POST['price'];
            $quantity = $_POST['quantity'];
            
            $image_path = ''; // Mặc định rỗng nếu ko up ảnh

            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                
                // --- QUAN TRỌNG: Đường dẫn lưu ảnh biến thể ---
                $target_dir = "uploads/variants/"; 
                
                $ext = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
                $file_name = "var_" . $sku . "_" . time() . "." . $ext;
                $target_file = $target_dir . $file_name;

                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    $image_path = $target_file;
                }
            }

            // Gọi Model lưu biến thể
            $this->productModel->addVariant($product_id, $color_id, $size_id, $price, $quantity, $sku, $image_path);
            
            // Quay lại trang biến thể
            header("Location: " . BASE_URL . "index.php/admin/variants?product_id=" . $product_id);
            exit;
        }

        // 1. Lấy ID sản phẩm cha từ URL
        $product_id = $_GET['product_id'] ?? 0;

        // 2. Nếu không có ID, đá về trang danh sách sản phẩm
        if (!$product_id) {
            header("Location: index.php?act=products");
            exit;
        }

        // 3. Lấy thông tin sản phẩm cha (để hiện tên: Áo Khoác Dù...)
        $product = $this->productModel->getProductById($product_id);

        // 4. Lấy danh sách biến thể của sản phẩm đó
        $variants = $this->productModel->getVariantsById_product($product_id);

        // 5. Lấy danh sách Size và Màu (Để nạp vào Modal Thêm/Sửa)
        $sizes = $this->productModel->getAllSizes();
        $colors = $this->productModel->getAllColors();

        // 6. Gửi dữ liệu sang View
        include_once 'Views/admin/admin_variants.php';
    }

    public function user()
    {
        include_once 'Views/admin/user_profile.php';
    }
}
