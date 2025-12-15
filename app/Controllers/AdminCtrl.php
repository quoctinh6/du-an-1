<?php
class AdminCtrl
{
    private $CategoryModel;
    private $productModel;
    private $BrandModel;
    private $OrderModel;
    private $StatsModel;
    private $UserModel;
    private $CommentModel;

    public function __construct()
    {
        include_once __DIR__ . "/../Models/Products.php";
        $this->productModel = new Products();

        include_once __DIR__ . "/../Models/Category.php";
        $this->CategoryModel = new Category();

        include_once __DIR__ . "/../Models/Brand.php";
        $this->BrandModel = new Brand();

        include_once __DIR__ . "/../Models/Order.php";
        $this->OrderModel = new Order();

        include_once __DIR__ . "/../Models/StatsModel.php";
        $this->StatsModel = new StatsModel();

        include_once __DIR__ . "/../Models/User.php";
        $this->UserModel = new Users();

        include_once __DIR__ . "/../Models/Comments.php";
        $this->CommentModel = new Comments();
    }

    public function index()
    {
        // BƯỚC 1: LẤY DỮ LIỆU KPIs
        $totalRevenue = $this->StatsModel->getTotalRevenueToday();
        $pendingOrders = $this->StatsModel->countPendingOrders();
        $newUsers = $this->StatsModel->countNewUsersLast7Days(); // Hàm đã được thêm ở lần trước

        // BƯỚC 2: LẤY DỮ LIỆU CHO BẢNG & LIST
        $latestOrders = $this->StatsModel->getLatestOrders(4);
        $bestSellers = $this->StatsModel->getTopSellingProducts(5);

        // Tìm sản phẩm bán chạy nhất (Dòng đầu tiên trong bestSellers)
        $topSellerName = !empty($bestSellers) ? $bestSellers[0]['product_name'] : 'Chưa có';

        // DỮ LIỆU CHO BIỂU ĐỒ TRÒN (Tỉ lệ Danh mục)
        $rawCategoryData = $this->StatsModel->countProductsByCategory();

        $categoryLabels = [];
        $categoryCounts = [];

        // Tách dữ liệu thô thành 2 mảng riêng biệt (Labels và Data)
        // Đây là logic Presentation, nó chuyển đổi dữ liệu thô thành định dạng cần thiết cho View/JS
        foreach ($rawCategoryData as $row) {
            $categoryLabels[] = $row['name']; // Tên danh mục (ví dụ: 'Áo Khoác')
            $categoryCounts[] = (int) $row['product_count']; // Số lượng sản phẩm
        }

        // Chuyển 2 mảng PHP thành JSON để JavaScript có thể đọc được
        $categoryLabelsJson = json_encode($categoryLabels);
        $categoryDataJson = json_encode($categoryCounts);

        $rawRevenueData = $this->StatsModel->getRevenueLast30Days(); // Lấy dữ liệu

        $revenueLabels = [];
        $revenueCounts = [];

        // 1. Chuẩn bị dữ liệu:
        // Cần lấy tất cả các ngày từ 30 ngày trước đến hôm nay
        $revenueMap = [];
        foreach ($rawRevenueData as $row) {
            // Chuẩn bị mảng Map: ['2025-12-01' => 12345000, '2025-12-02' => 0, ...]
            $revenueMap[$row['order_date']] = (float) $row['total_daily_revenue'];
        }

        // 2. Điền đầy đủ 30 ngày (Dù không có doanh thu)
        $date = new DateTime('-30 days'); // Bắt đầu từ 30 ngày trước
        $interval = new DateInterval('P1D'); // Mỗi lần lặp là 1 ngày
        $period = new DatePeriod($date, $interval, new DateTime()); // Lặp cho đến hôm nay

        foreach ($period as $day) {
            $currentDate = $day->format('Y-m-d'); // Định dạng ngày (vd: 2025-11-08)
            $label = $day->format('d/m'); // Định dạng cho Label (vd: 08/11)

            $revenueLabels[] = $label;
            // Nếu ngày này có trong Map (có doanh thu), lấy giá trị, ngược lại là 0
            $revenueCounts[] = $revenueMap[$currentDate] ?? 0;
        }

        // 3. Chuyển thành JSON
        $revenueLabelsJson = json_encode($revenueLabels ?? []);
        $revenueDataJson = json_encode($revenueCounts ?? []);

        // Chuyển 2 mảng PHP thành JSON để JavaScript có thể đọc được
        $categoryLabelsJson = json_encode($categoryLabels ?? []);
        $categoryDataJson = json_encode($categoryCounts ?? []);

        // Truyền thêm biến mới vào view
        include_once 'Views/admin/admin.php';
    }



    // --- QUẢN LÝ DANH MỤC ---
    public function categories()
    {
        $search = $_GET['search'] ?? '';
        $categories = $this->CategoryModel->getCategoriesAdmin($search);
        include_once 'Views/admin/admin_category.php';
    }

    public function addCategory()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn_add_category'])) {
            $name = $_POST['name'];
            $slug = !empty($_POST['slug']) ? $_POST['slug'] : strtolower(str_replace(' ', '-', $name));
            $status = $_POST['status']; // Lấy status từ form

            $this->CategoryModel->createCategory($name, $slug, $status);
            header("Location: " . BASE_URL . "index.php/admin/categories");
            exit;
        }
    }

    public function updateCategory()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn_update_category'])) {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $slug = !empty($_POST['slug']) ? $_POST['slug'] : strtolower(str_replace(' ', '-', $name));
            $status = $_POST['status']; // Lấy status từ form

            $this->CategoryModel->updateCategory($id, $name, $slug, $status);
            header("Location: " . BASE_URL . "index.php/admin/categories");
            exit;
        }
    }

    
    // (Giữ nguyên các hàm products, addProduct, updateProduct, variants, updateVariant, orders, account, user...)
    public function products()
    {
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

    public function addProduct()
    {
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
                    if (!file_exists($target_dir))
                        mkdir($target_dir, 0777, true);
                    $ext = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
                    $file_name = $new_product_id . "_" . time() . "." . $ext;
                    move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . $file_name);
                    $this->productModel->addProductImage($new_product_id, $file_name);
                }
                header("Location: " . BASE_URL . "index.php/admin/variants?product_id=" . $new_product_id);
                exit;
            }
        }
    }

    public function updateProduct()
    {
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
                if (!file_exists($target_dir))
                    mkdir($target_dir, 0777, true);
                $ext = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
                $file_name = $id . "_" . time() . "." . $ext;
                move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . $file_name);
                $this->productModel->updateProductImage($id, $file_name);
            }
            header("Location: " . BASE_URL . "index.php/admin/products");
            exit;
        }
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
            $image_path = '';

            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $target_dir = "uploads/variants/";
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
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
        $product = $this->productModel->getProductById($product_id);
        $variants = $this->productModel->getVariantsById_product($product_id);
        $sizes = $this->productModel->getAllSizes();
        $colors = $this->productModel->getAllColors();
        include_once 'Views/admin/admin_variants.php';
    }

    public function updateVariant()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn_update_variant'])) {
            $variant_id = $_POST['variant_id'];
            $product_id = $_POST['product_id'];
            $sku = $_POST['sku'];
            $color_id = $_POST['color_id'];
            $size_id = $_POST['size_id'];
            $price = $_POST['price'];
            $quantity = $_POST['quantity'];
            $image_path = null;

            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $target_dir = "uploads/variants/";
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
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

    public function orders()
    {
        $status = $_GET['status'] ?? '';
        $keyword = $_GET['keyword'] ?? '';
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit = 10;
        $orders = $this->OrderModel->getAllOrdersAdmin($status, $keyword, $page, $limit);
        $totalOrders = $this->OrderModel->countOrdersAdmin($status, $keyword);
        $totalPages = ceil($totalOrders / $limit);
        include_once 'Views/admin/admin_orders.php';
    }

    public function account()
    {
        // Áp dụng Flash Message cho trang Admin
        $error = $_SESSION['error_admin'] ?? null;
        $success = $_SESSION['success_admin'] ?? null;
        unset($_SESSION['error_admin']);
        unset($_SESSION['success_admin']);

        // 1. LẤY THAM SỐ LỌC & TÌM KIẾM TỪ URL ($_GET)
        $role = $_GET['role'] ?? 'all';         // Vai trò: 'all', 'admin', 'client'
        $status = $_GET['status'] ?? 'all';     // Trạng thái: 'all', 'active', 'locked'
        $search = $_GET['search'] ?? '';        // Từ khóa tìm kiếm

        // 2. THAM SỐ PHÂN TRANG
        $page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
        $limit = 10;

        // 3. GỌI MODEL LẤY DỮ LIỆU
        // Dữ liệu hiển thị
        $users = $this->UserModel->getFilteredUsersAdmin($role, $status, $search, $page, $limit);
        // Tổng số user (để tính phân trang)
        $totalUsers = $this->UserModel->countFilteredUsersAdmin($role, $status, $search);

        // Tính tổng số trang
        $totalPages = ceil($totalUsers / $limit);

        // 4. TRUYỀN DỮ LIỆU VÀO VIEW
        $data = [
            'users' => $users,
            'totalUsers' => $totalUsers,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'currentLimit' => $limit,
            'currentRole' => $role,
            'currentStatus' => $status,
            'currentSearch' => $search,
            'error' => $error,
            'success' => $success
        ];

        // 'extract' các biến cho View sử dụng (Ví dụ: $users, $totalPages, $error...)
        extract($data);

        include_once 'Views/admin/admin_account.php';
    }

    /**
     * Xử lý thêm tài khoản người dùng mới (từ Admin)
     */
    public function addUser()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn_add_user'])) {
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $phone = trim($_POST['phone']);
            $role = $_POST['role'];
            $password = $_POST['password']; // Admin tự đặt hoặc dùng mật khẩu mặc định
            $is_active = $_POST['status'] == 'active' ? 1 : 0;

            // 1. Validation Cơ bản
            if (empty($name) || empty($email) || empty($phone) || empty($password)) {
                $_SESSION['error_admin'] = 'Vui lòng điền đầy đủ thông tin bắt buộc.';
            }
            // 2. Kiểm tra Email đã tồn tại
            elseif ($this->UserModel->getByEmail($email)) {
                $_SESSION['error_admin'] = 'Email này đã được sử dụng. Vui lòng chọn Email khác.';
            }
            // 3. Tạo User
            else {
                $result = $this->UserModel->createAdminUser($name, $email, $phone, $password, $role, $is_active);
                if ($result) {
                    $_SESSION['success_admin'] = "Thêm tài khoản **$name** ($email) thành công!";
                } else {
                    $_SESSION['error_admin'] = 'Có lỗi xảy ra trong quá trình thêm tài khoản.';
                }
            }
        }
        // Redirect về trang quản lý tài khoản để thấy kết quả
        header("Location: " . BASE_URL . "index.php/admin/account");
        exit();
    }

    /**
     * Xử lý Khóa/Mở khóa tài khoản (Cập nhật is_active)
     */
    public function updateStatus()
    {
        if (isset($_GET['id']) && isset($_GET['status'])) {
            $user_id = (int) $_GET['id'];
            $new_status = (int) $_GET['status']; // 1: Active, 0: Locked

            // ⚠️ Kiểm tra: Không cho khóa chính tài khoản Admin đang dùng
            if ($user_id == ($_SESSION['user']['id'] ?? 0)) {
                $_SESSION['error_admin'] = 'Không thể khóa tài khoản của chính bạn!';
                header("Location: " . BASE_URL . "index.php/admin/account");
                exit();
            }

            $user = $this->UserModel->getById($user_id);
            if ($user) {
                $this->UserModel->updateActiveStatus($user_id, $new_status);
                $status_text = ($new_status == 1) ? 'mở khóa' : 'khóa';
                $_SESSION['success_admin'] = "Đã **$status_text** tài khoản **{$user['name']}** thành công.";
            } else {
                $_SESSION['error_admin'] = 'Không tìm thấy tài khoản cần cập nhật.';
            }
        }
        header("Location: " . BASE_URL . "index.php/admin/account");
        exit();
    }

    /**
     * Xử lý Phân quyền tài khoản (Cập nhật role)
     */
    public function updateRole()
    {
        if (isset($_GET['id']) && isset($_GET['role'])) {
            $user_id = (int) $_GET['id'];
            $new_role = $_GET['role']; // 'admin' hoặc 'client'

            // ⚠️ Kiểm tra: Không cho tự đổi quyền của chính tài khoản Admin đang dùng
            if ($user_id == ($_SESSION['user']['id'] ?? 0) && $new_role == 'client') {
                $_SESSION['error_admin'] = 'Không thể tự hạ cấp quyền của chính bạn khi đang đăng nhập.';
                header("Location: " . BASE_URL . "index.php/admin/account");
                exit();
            }

            $user = $this->UserModel->getById($user_id);
            if ($user) {
                $this->UserModel->updateUserRole($user_id, $new_role);
                $role_text = ($new_role == 'admin') ? 'Quản trị viên' : 'Khách hàng';
                $_SESSION['success_admin'] = "Đã phân quyền tài khoản **{$user['name']}** thành **$role_text** thành công.";
            } else {
                $_SESSION['error_admin'] = 'Không tìm thấy tài khoản cần cập nhật.';
            }
        }
        header("Location: " . BASE_URL . "index.php/admin/account");
        exit();
    }

    public function user()
    {
        include_once 'Views/admin/user_profile.php';
    }
    public function brands()
    {
        include_once 'Views/admin/admin_brands.php';
    }
    public function comments()
    {
        // 1. LẤY THAM SỐ LỌC, TÌM KIẾM, PHÂN TRANG
        $currentSearch = $_GET['search'] ?? '';
        $currentRating = $_GET['rating'] ?? 'all'; // 'all', '1', '2', ..., '5'
        $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit = 10; // Số bình luận trên mỗi trang

        // 2. GỌI MODEL LẤY DỮ LIỆU
        
        // Lấy tổng số để tính totalPages
        $totalComments = $this->CommentModel->countCommentsAdmin($currentSearch, $currentRating);

        // 3. TÍNH TOÁN PHÂN TRANG
        $totalPages = ceil($totalComments / $limit);

        // Đảm bảo trang hiện tại hợp lệ
        if ($currentPage < 1)
            $currentPage = 1;
        if ($currentPage > $totalPages && $totalPages > 0)
            $currentPage = $totalPages;
        if ($totalPages == 0)
            $currentPage = 1; // Nếu không có dữ liệu, page = 1

        // Lấy dữ liệu theo phân trang đã chuẩn hóa
        $comments = $this->CommentModel->getCommentsAdmin($currentSearch, $currentRating, $currentPage, $limit);

        // 4. TRUYỀN DỮ LIỆU SANG VIEW
        include_once 'Views/admin/admin_comments.php';
    }

    public function deleteComment()
    {
        if (isset($_GET['id'])) {
            $comment_id = (int) $_GET['id'];
            // Ghi nhớ: Hãy ví von qua PHP. Trong PHP, việc xóa comment cũng tương tự như trong Laravel/CodeIgniter, bạn chỉ cần gọi phương thức DELETE của Model
            $result = $this->CommentModel->deleteComment($comment_id);

            if ($result) {
                $_SESSION['success'] = "Xóa bình luận thành công!";
            } else {
                $_SESSION['error'] = "Không thể xóa bình luận hoặc bình luận không tồn tại.";
            }
        } else {
            $_SESSION['error'] = "Thiếu ID bình luận để xóa.";
        }

        // Quay lại trang quản lý bình luận
        header("Location: " . BASE_URL . "index.php/admin/comments");
        exit();
    }

    // --- QUẢN LÝ MÃ GIẢM GIÁ (COUPONS) - TỪ DÒNG 499 ---
    public function coupons()
    {
        // Load Model thủ công (do không được sửa Constructor)
        include_once __DIR__ . "/../Models/Coupon.php";
        $couponModel = new Coupon();

        // 1. Lấy tham số lọc từ URL
        $search = $_GET['search'] ?? '';
        $type = $_GET['type'] ?? 'all';
        $status = $_GET['status'] ?? 'all';
        
        // 2. Gọi Model lấy dữ liệu
        // (Bỏ qua phân trang theo yêu cầu, lấy 100 mã mới nhất)
        $coupons = $couponModel->getCouponsAdmin($search, $type, $status, 1, 100); 

        // 3. Load View
        include_once 'Views/admin/admin_coupons.php';
    }

    public function addCoupon()
    {
        include_once __DIR__ . "/../Models/Coupon.php";
        $couponModel = new Coupon();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Lấy dữ liệu từ Form
            $code = strtoupper(trim($_POST['code'])); // Viết hoa mã
            $type = $_POST['type'];
            $value = $_POST['value'];
            // Nếu để trống thì set là null
            $usage_limit = !empty($_POST['usage_limit']) ? $_POST['usage_limit'] : null;
            $expires_at = !empty($_POST['expires_at']) ? $_POST['expires_at'] : null;

            $couponModel->createCoupon($code, $type, $value, $usage_limit, $expires_at);
            
            // Quay lại trang danh sách
            header("Location: " . BASE_URL . "index.php/admin/coupons");
            exit;
        }
    }

    public function updateCoupon()
    {
        include_once __DIR__ . "/../Models/Coupon.php";
        $couponModel = new Coupon();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $code = strtoupper(trim($_POST['code']));
            $type = $_POST['type'];
            $value = $_POST['value'];
            $usage_limit = !empty($_POST['usage_limit']) ? $_POST['usage_limit'] : null;
            $expires_at = !empty($_POST['expires_at']) ? $_POST['expires_at'] : null;

            $couponModel->updateCoupon($id, $code, $type, $value, $usage_limit, $expires_at);
            
            header("Location: " . BASE_URL . "index.php/admin/coupons");
            exit;
        }
    }

    public function deleteCoupon()
    {
        include_once __DIR__ . "/../Models/Coupon.php";
        $couponModel = new Coupon();

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $couponModel->deleteCoupon($id);
        }
        header("Location: " . BASE_URL . "index.php/admin/coupons");
        exit;
    }
}
