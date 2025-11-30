<?php
include_once 'Models/Order.php';
include_once 'Models/Products.php';

class CheckoutCtrl
{
    // Hiển thị trang thanh toán
    public function index()
    {
        // Lấy giỏ hàng từ session
        $cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

        if (empty($cartItems)) {
            // Redirect back to products list when cart is empty
            header("Location: " . BASE_URL . "index.php/products/all");
            exit;
        }

        // Tính toán tổng tiền
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $qty = isset($item['quantity']) ? $item['quantity'] : 0;
            $subtotal += ($item['price'] * $qty);
        }

        // Use the same shipping rule across controller: free when subtotal > 1,000,000
        $shipFreeThreshold = 1000000;
        $shipping = ($subtotal > $shipFreeThreshold) ? 0 : 30000;
        if ($subtotal == 0) $shipping = 0;

        $totalPrice = $subtotal + $shipping;

        include_once 'Views/checkout.php';
    }

    public function process()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        // Lấy dữ liệu từ form
        $fullname = trim($_POST['fullname'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $paymentMethod = $_POST['payment_method'] ?? 'cod';

        // Map payment method sang ID (1: cod, 2: Banking)
        $paymentMethodID = ($paymentMethod == 'banking') ? 2 : 1;
        $shippingMethod = 1; // Mặc định giao hàng tiêu chuẩn

        // Lấy ID User (Tạm thời = 1 nếu chưa login)
        $userID = isset($_SESSION['user']) ? $_SESSION['user']['id'] : 1;

        // Lấy giỏ hàng và tính lại tổng giá phòng trường hợp bị chỉnh client-side
        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            echo "<script>alert('Giỏ hàng đang trống.'); window.location.href='" . BASE_URL . "index.php/products/all';</script>";
            exit;
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $qty = isset($item['quantity']) ? $item['quantity'] : 0;
            $subtotal += ($item['price'] * $qty);
        }

        // same shipping threshold as in index
        $shipFreeThreshold = 1000000;
        $shipping = ($subtotal > $shipFreeThreshold) ? 0 : 30000;
        if ($subtotal == 0) $shipping = 0;

        $totalPrice = $subtotal + $shipping;

        // Server-side validation
        if (empty($fullname) || empty($phone) || empty($address)) {
            echo "<script>alert('Vui lòng nhập đầy đủ họ tên, số điện thoại và địa chỉ giao hàng.'); window.history.back();</script>";
            exit;
        }

        // Verify items have variants before creating the order
        $productModel = new Products();
        $missingVariants = [];
        foreach ($cart as $item) {
            $variants = $productModel->getVariantsById_product((int)$item['id']);
            if (empty($variants)) {
                $missingVariants[] = $item['name'] ?? ('#' . $item['id']);
            }
        }

        if (!empty($missingVariants)) {
            $badList = implode(', ', $missingVariants);
            echo "<script>alert('Không thể đặt hàng. Những sản phẩm sau chưa có biến thể: " . addslashes($badList) . "'); window.history.back();</script>";
            exit;
        }

        // ====Bắt đầu lưu DB=====
        $orderModel = new Order();

        // Bước 1: tạo đơn hàng (truyền shipping method)
        $orderId = $orderModel->createOrder($userID, $totalPrice, $address, $phone, $paymentMethodID, $shippingMethod);

        if (!$orderId) {
            echo "<script>alert('Lỗi hệ thống, không tạo được đơn hàng'); window.history.back();</script>";
            exit;
        }

        // Bước 2: lưu chi tiết
        $failedAdd = [];
        foreach ($cart as $item) {
            $variants = $productModel->getVariantsById_product((int)$item['id']);
            $firstVariant = $variants[0];
            $variantId = $firstVariant['id'];

            $added = $orderModel->addOrderItem($orderId, $variantId, $item['quantity'], $item['price']);
            if (!$added) {
                $failedAdd[] = $item['name'] ?? ('#' . $item['id']);
            }
        }

        if (!empty($failedAdd)) {
            // Nếu lỗi lưu chi tiết -> hủy order
            $orderModel->updateOrderStatus($orderId, 'cancelled');
            $list = implode(', ', $failedAdd);
            echo "<script>alert('Có lỗi khi lưu chi tiết đơn hàng cho: " . addslashes($list) . " . Đơn hàng đã bị hủy.'); window.history.back();</script>";
            exit;
        }

        // Bước 3: xóa giỏ hàng và thông báo thành công
        unset($_SESSION['cart']);
        echo "<script>alert('Đặt hàng thành công! Mã đơn: #" . $orderId . "'); window.location.href='" . BASE_URL . "';</script>";
    }
}

?>