<?php
include_once 'Models/Order.php';
include_once 'Models/Products.php';

class CheckoutCtrl
{
    // VNPAY configuration - set your merchant credentials here
    private $vnp_TmnCode = 'VNPAY_MERCHANT'; // merchant code
    private $vnp_HashSecret = 'VNPAY_SECRET'; // secret key
    private $vnp_Url = 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html';
    private $vnp_ReturnUrl = '';
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

        $totalPrice = $subtotal + $shipping;

        // Nếu đã áp mã giảm giá (từ session), hiển thị tổng đã trừ
        $discountApplied = 0;
        if (isset($_SESSION['applied_coupon']) && is_array($_SESSION['applied_coupon'])) {
            $discountApplied = floatval($_SESSION['applied_coupon']['discount'] ?? 0);
            $totalPrice = round($totalPrice - $discountApplied, 2);
        }

        // Load available coupons to render in the view
        include_once __DIR__ . '/../Models/Coupon.php';
        $couponModel = new Coupon();
        $availableCoupons = $couponModel->getAllAvailable();

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
        $paymentMethodID = ($paymentMethod == 'vnpay') ? 2 : 1;
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

        $totalPrice = $subtotal + $shipping;

        // ====== Coupon handling ======
        $coupon_id = null;
        $discount = 0;

        // If user clicked "Áp dụng" button, validate and store applied coupon in session and redirect back
        if (isset($_POST['apply_coupon'])) {
            $coupon_code = trim($_POST['coupon_code'] ?? '');
            if ($coupon_code !== '') {
                include_once __DIR__ . '/../Models/Coupon.php';
                $couponModel = new Coupon();
                $coupon = $couponModel->getByCode($coupon_code);
                if (!$coupon) {
                    $_SESSION['coupon_error'] = 'Mã giảm giá không tồn tại.';
                    header('Location: ' . BASE_URL . 'index.php/checkout');
                    exit;
                }
                if (!empty($coupon['expires_at']) && strtotime($coupon['expires_at']) < time()) {
                    $_SESSION['coupon_error'] = 'Mã giảm giá đã hết hạn.';
                    header('Location: ' . BASE_URL . 'index.php/checkout');
                    exit;
                }
                if (!is_null($coupon['usage_limit']) && intval($coupon['usage_limit']) <= 0) {
                    $_SESSION['coupon_error'] = 'Mã giảm giá đã đạt giới hạn sử dụng.';
                    header('Location: ' . BASE_URL . 'index.php/checkout');
                    exit;
                }
                // compute discount
                if ($coupon['type'] === 'percent') {
                    $discount = ($totalPrice * (float)$coupon['value']) / 100.0;
                } else {
                    $discount = (float)$coupon['value'];
                }
                if ($discount > $totalPrice) $discount = $totalPrice;

                $_SESSION['applied_coupon'] = [
                    'id' => $coupon['id'],
                    'code' => $coupon['code'],
                    'discount' => round($discount, 2)
                ];
                $_SESSION['coupon_success'] = 'Áp dụng mã giảm giá thành công.';
            }
            header('Location: ' . BASE_URL . 'index.php/checkout');
            exit;
        }

        // If there's an applied coupon in session, use it for the order
        if (isset($_SESSION['applied_coupon']) && is_array($_SESSION['applied_coupon'])) {
            $couponInfo = $_SESSION['applied_coupon'];
            $discount = floatval($couponInfo['discount'] ?? 0);
            $totalPrice = round($totalPrice - $discount, 2);
            $coupon_id = $couponInfo['id'] ?? null;
        } else {
            // fallback: if user provided coupon code directly on place order (without hitting apply)
            $coupon_code = trim($_POST['coupon_code'] ?? '');
            if ($coupon_code !== '') {
                include_once __DIR__ . '/../Models/Coupon.php';
                $couponModel = new Coupon();
                $coupon = $couponModel->getByCode($coupon_code);
                if ($coupon) {
                    if (!empty($coupon['expires_at']) && strtotime($coupon['expires_at']) >= time() && (is_null($coupon['usage_limit']) || intval($coupon['usage_limit']) > 0)) {
                        if ($coupon['type'] === 'percent') {
                            $discount = ($totalPrice * (float)$coupon['value']) / 100.0;
                        } else {
                            $discount = (float)$coupon['value'];
                        }
                        if ($discount > $totalPrice) $discount = $totalPrice;
                        $totalPrice = round($totalPrice - $discount, 2);
                        $coupon_id = $coupon['id'];
                        if ($paymentMethod !== 'vnpay') {
                            $couponModel->decrementUsage($coupon_id);
                        }
                        $_SESSION['coupon_success'] = 'Áp dụng mã giảm giá thành công.';
                    }
                }
            }
        }

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

        // Bước 1: tạo đơn hàng (truyền shipping method và coupon nếu có)
        $orderId = $orderModel->createOrder($userID, $totalPrice, $address, $phone, $paymentMethodID, $shippingMethod, $coupon_id);

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

        // Nếu phương thức thanh toán là VNPAY -> chuyển hướng tới cổng VNPAY
        if ($paymentMethod === 'vnpay') {
            // Build VNPAY payment URL
            // Ensure return URL is set (use site base)
            $this->vnp_ReturnUrl = BASE_URL . 'index.php/Checkout/vnpay_return';

            $vnp_TmnCode = $this->vnp_TmnCode;
            $vnp_HashSecret = $this->vnp_HashSecret;
            $vnp_Url = $this->vnp_Url;

            $vnp_Params = [];
            $vnp_Params['vnp_Version'] = '2.1.0';
            $vnp_Params['vnp_TmnCode'] = $vnp_TmnCode;
            // amount must be integer (VND * 100)
            $vnp_Params['vnp_Amount'] = (int) round($totalPrice * 100); // amount in smallest currency unit
            $vnp_Params['vnp_Command'] = 'pay';
            // Ensure server time is Vietnam timezone for VNPAY
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $vnp_Params['vnp_CreateDate'] = date('YmdHis');
            // Set expiry (15 minutes from now)
            $vnp_Params['vnp_ExpireDate'] = date('YmdHis', time() + 15 * 60);
            $vnp_Params['vnp_CurrCode'] = 'VND';
            // Normalize IPv6 loopback to IPv4 for VNPAY
            $ipAddr = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
            if ($ipAddr === '::1' || $ipAddr === '0:0:0:0:0:0:0:1') {
                $ipAddr = '127.0.0.1';
            }
            $vnp_Params['vnp_IpAddr'] = $ipAddr;
            $vnp_Params['vnp_Locale'] = 'vn';
            $vnp_Params['vnp_OrderInfo'] = 'Thanh toan don hang #' . $orderId;
            $vnp_Params['vnp_OrderType'] = 'other';
            $vnp_Params['vnp_ReturnUrl'] = $this->vnp_ReturnUrl;
            $vnp_Params['vnp_TxnRef'] = $orderId;

            ksort($vnp_Params);
            $query = '';
            $hashdata = '';
            foreach ($vnp_Params as $key => $value) {
                if ($hashdata !== '') {
                    $hashdata .= '&' . $key . '=' . $value;
                } else {
                    $hashdata .= $key . '=' . $value;
                }
                $query .= urlencode($key) . '=' . urlencode($value) . '&';
            }

            $vnp_Url = $vnp_Url . '?' . $query;
            // Use HMAC SHA512 which is recommended by VNPAY for SecureHash
            $secureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            // Ensure SecureHashType is explicit
            $vnp_Url .= 'vnp_SecureHashType=HMACSHA512&vnp_SecureHash=' . $secureHash;

            // Simple debug log to help troubleshooting (appends to storage/vnpay.log)
            $logDir = __DIR__ . '/../../storage';
            if (!is_dir($logDir)) @mkdir($logDir, 0755, true);
            $logFile = $logDir . '/vnpay.log';
            $logData = "[" . date('Y-m-d H:i:s') . "] VNPAY Request URL: " . $vnp_Url . "\nParams: " . json_encode($vnp_Params) . "\nHashData: " . $hashdata . "\nSecureHash: " . $secureHash . "\n\n";
            @file_put_contents($logFile, $logData, FILE_APPEND);

            // Lưu order id tạm để kiểm tra khi return
            $_SESSION['last_order_id'] = $orderId;

            // Redirect to VNPAY
            header('Location: ' . $vnp_Url);
            exit;
        }

        // Bước 3: xử lý coupon (nếu có) và xóa giỏ hàng, thông báo thành công (COD hoặc các phương thức khác)
        if (!empty($coupon_id) && $paymentMethod !== 'vnpay') {
            include_once __DIR__ . '/../Models/Coupon.php';
            $couponModel = new Coupon();
            $couponModel->decrementUsage($coupon_id);
        }

        // Xóa session coupon đã áp
        if (isset($_SESSION['applied_coupon'])) unset($_SESSION['applied_coupon']);

        unset($_SESSION['cart']);
        echo "<script>alert('Đặt hàng thành công! Mã đơn: #" . $orderId . "'); window.location.href='" . BASE_URL . "';</script>";


    }

    // Xử lý khi VNPAY redirect về
    public function vnpay_return()
    {
        // Lấy các tham số trả về
        $data = $_GET;
        $vnp_SecureHash = $data['vnp_SecureHash'] ?? '';
        // Loại bỏ các tham số không dùng để tính hash
        unset($data['vnp_SecureHash']);
        unset($data['vnp_SecureHashType']);

        // Sắp xếp theo key và nối thành chuỗi
        ksort($data);
        $hashdata = '';
        foreach ($data as $key => $value) {
            if ($hashdata !== '') {
                $hashdata .= '&' . $key . '=' . $value;
            } else {
                $hashdata .= $key . '=' . $value;
            }
        }

        $vnp_HashSecret = $this->vnp_HashSecret;
        // Use HMAC SHA512 to verify
        $checkHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);

        // Log return data for debugging
        $logDir = __DIR__ . '/../../storage';
        if (!is_dir($logDir)) @mkdir($logDir, 0755, true);
        $logFile = $logDir . '/vnpay.log';
        $logData = "[" . date('Y-m-d H:i:s') . "] VNPAY Return Params: " . json_encode($_GET) . "\nComputedHash: " . $checkHash . "\nReceivedHash: " . ($vnp_SecureHash ?? '') . "\n\n";
        @file_put_contents($logFile, $logData, FILE_APPEND);

        $orderModel = new Order();

        $txnRef = $_GET['vnp_TxnRef'] ?? null;
        $responseCode = $_GET['vnp_ResponseCode'] ?? null;

        if ($checkHash === $vnp_SecureHash) {
            // signature hợp lệ
            if ($responseCode === '00') {
                // thanh toán thành công
                if ($txnRef) {
                    $orderModel->updateOrderStatus($txnRef, 'paid');
                    // Nếu order có coupon_id, giảm usage_limit (đối với VNPAY giảm khi thanh toán thành công)
                    $order = $orderModel->getOrderDetail($txnRef);
                    if ($order && !empty($order['coupon_id'])) {
                        include_once __DIR__ . '/../Models/Coupon.php';
                        $couponModel = new Coupon();
                        $couponModel->decrementUsage($order['coupon_id']);
                    }
                }
                // Xóa session coupon và giỏ hàng
                if (isset($_SESSION['applied_coupon'])) unset($_SESSION['applied_coupon']);
                unset($_SESSION['cart']);
                echo "<script>alert('Thanh toán thành công. Cảm ơn bạn.'); window.location.href='" . BASE_URL . "';</script>";
                exit;
            } else {
                // thanh toán không thành công
                if ($txnRef) {
                    $orderModel->updateOrderStatus($txnRef, 'failed');
                }
                if (isset($_SESSION['applied_coupon'])) unset($_SESSION['applied_coupon']);
                echo "<script>alert('Thanh toán thất bại hoặc bị hủy. Mã lỗi: " . htmlspecialchars($responseCode) . "'); window.location.href='" . BASE_URL . "';</script>";
                exit;
            }
        } else {
            // invalid signature
            if ($txnRef) {
                $orderModel->updateOrderStatus($txnRef, 'failed');
            }
            if (isset($_SESSION['applied_coupon'])) unset($_SESSION['applied_coupon']);
            echo "<script>alert('Không thể xác minh chữ ký giao dịch VNPAY.'); window.location.href='" . BASE_URL . "';</script>";
            exit;
        }
    }
}

?>