<?php
include_once 'Models/Order.php';
include_once 'Models/Products.php';
include_once 'Models/Coupon.php';

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

        // Tính toán tổng tiền — dùng giá từ DB nếu có variant
        $productModel = new Products();
        $subtotal = 0;
        foreach ($cartItems as $key => $item) {
            $qty = isset($item['quantity']) ? (int)$item['quantity'] : 0;
            if (!empty($item['variant_id'])) {
                $v = $productModel->getVariantById((int)$item['variant_id']);
                if ($v) {
                    $cartItems[$key]['display_price'] = $v['price'];
                    $cartItems[$key]['variant_meta'] = [
                        'size'=>$v['size_name'] ?? null,
                        'color'=>$v['color_name'] ?? null,
                        'sku'=>$v['sku'] ?? null
                    ];
                    $subtotal += ((float)$v['price']) * $qty;
                    continue;
                }
            }
            // fallback: use price stored in session
            $cartItems[$key]['display_price'] = $item['price'] ?? 0;
            $subtotal += ((float)($item['price'] ?? 0)) * $qty;
        }

        // Use the same shipping rule across controller: free when subtotal > 1,000,000
        $shipFreeThreshold = 1000000;
        $shipping = ($subtotal > $shipFreeThreshold) ? 0 : 30000;
        if ($subtotal == 0) $shipping = 0;

        $discount = 0;
        if (isset($_SESSION['applied_coupon'])) {
            $coupon = $_SESSION['applied_coupon'];
            if ($coupon['type'] == 'percent') {
                $discount = ($subtotal * $coupon['value']) / 100;
            } else {
                $discount = $coupon['value'];
            }
            // Không giảm quá tổng tiền
            if ($discount > $subtotal) $discount = $subtotal;
        }

        // Tinh tong tien
        $totalPrice = ($subtotal + $shipping) - $discount;
        if ($totalPrice < 0) $totalPrice = 0;

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
        // $userID = isset($_SESSION['user']) ? $_SESSION['user']['id'] : 1;
        if(!isset($_SESSION['user'])) {
            //Chưa đăng nhập thì đá sang trang login
            header('location: ' . BASE_URL . 'index.php/User/login');
            exit;
        }
        $userID = $_SESSION['user']['id'];

        // Lấy giỏ hàng và tính lại tổng giá phòng trường hợp bị chỉnh client-side
        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            echo "<script>alert('Giỏ hàng đang trống.'); window.location.href='" . BASE_URL . "index.php/products/all';</script>";
            exit;
        }

        // Recompute subtotal using server-side variant prices if available
        $productModel = new Products();
        $subtotal = 0;

        $missingVariants = [];
        $insufficientStock = [];
        
        foreach ($cart as $key => $item) {
            $qty = isset($item['quantity']) ? (int)$item['quantity'] : 0;

            if (!empty($item['variant_id'])) {
                $v = $productModel->getVariantById((int)$item['variant_id']);
                if (!$v) {
                    $missingVariants[] = $item['name'] ?? ('#' . ($item['product_id'] ?? $item['id']));
                    continue;
                }

                // check stock
                if (isset($v['quantity']) && $v['quantity'] < $qty) {
                    $insufficientStock[] = ($v['product_name'] ?? $item['name']) . ' (' . ($v['size_name'] ?? '') . ' / ' . ($v['color_name'] ?? '') . ')';
                }

                $subtotal += ((float)$v['price']) * $qty;
            } else {
                // backward compatible: use price in session
                $subtotal += ((float)($item['price'] ?? 0)) * $qty;
            }
        }

        // same shipping threshold as in index
        $shipFreeThreshold = 1000000;
        $shipping = ($subtotal > $shipFreeThreshold) ? 0 : 30000;
        if ($subtotal == 0) $shipping = 0;

        // === TÍNH LẠI GIẢM GIÁ (MỚI THÊM) ===
        $discount = 0;
        $couponId = null;
        if (isset($_SESSION['applied_coupon'])) {
            $coupon = $_SESSION['applied_coupon'];
            $couponId = $coupon['id']; // Lấy ID để lưu vào đơn
            
            if ($coupon['type'] == 'percent') {
                $discount = ($subtotal * $coupon['value']) / 100;
            } else {
                $discount = $coupon['value'];
            }
            if ($discount > $subtotal) $discount = $subtotal;
        }

        $totalPrice = ($subtotal + $shipping) - $discount;
        if ($totalPrice < 0) $totalPrice = 0;

        // Server-side validation
        if (empty($fullname) || empty($phone) || empty($address)) {
            echo "<script>alert('Vui lòng nhập đầy đủ họ tên, số điện thoại và địa chỉ giao hàng.'); window.history.back();</script>";
            exit;
        }

        // Check results from the subtotal phase
        if (!empty($missingVariants)) {
            $badList = implode(', ', $missingVariants);
            echo "<script>alert('Không thể đặt hàng. Những sản phẩm sau chưa có biến thể: " . addslashes($badList) . "'); window.history.back();</script>";
            exit;
        }

        if (!empty($insufficientStock)) {
            $list = implode(', ', $insufficientStock);
            echo "<script>alert('Một số biến thể không còn đủ số lượng: " . addslashes($list) . "'); window.history.back();</script>";
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
            // prefer explicit variant stored in cart
            if (!empty($item['variant_id'])) {
                $variantId = $item['variant_id'];
                $variant = $productModel->getVariantById((int)$variantId);
                $priceToSave = $variant ? $variant['price'] : $item['price'];
            } else {
                // fallback: try to take price from session
                $variantId = null;
                $priceToSave = $item['price'];
            }

            $added = $orderModel->addOrderItem($orderId, $variantId, $item['quantity'], $priceToSave);
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

        // Bước 3: xử lý theo phương thức thanh toán
        unset($_SESSION['cart']);

        // Fake Payment: Online
        if ($paymentMethod === 'vnpay') {
            // Giả lập thanh toán thành công
            $orderModel->updateOrderStatus($orderId, 'paid');

            echo "<script>
                alert('Thanh toán Online (Fake) thành công! Mã đơn: #{$orderId}');
                window.location.href='" . BASE_URL . "index.php/checkout/success?order_id={$orderId}';
            </script>";
            exit;
        }

        // COD hoặc Banking
        $orderModel->updateOrderStatus($orderId, 'pending');

        if ($paymentMethod === 'banking') {
            echo "<script>
                alert('Đặt hàng thành công! Vui lòng chuyển khoản theo hướng dẫn. Mã đơn: #{$orderId}');
                window.location.href='" . BASE_URL . "index.php/checkout/banking?order_id={$orderId}';
            </script>";
            exit;
        }

        // COD
        echo "<script>
            alert('Đặt hàng thành công! Thanh toán khi nhận hàng. Mã đơn: #{$orderId}');
            window.location.href='" . BASE_URL . "index.php/checkout/success?order_id={$orderId}';
        </script>";
        exit;
    }
    public function success()
    {
        $orderId = $_GET['order_id'] ?? null;

        if (!$orderId) {
            die("Không tìm thấy đơn hàng");
        }

        $orderModel = new Order();
        $order = $orderModel->getOrderById($orderId);

        if (!$order) {
            die("Đơn hàng không tồn tại");
        }

        include_once 'Views/checkout_success.php';
    }
}

?>