<?php
include_once 'Models/Products.php';
include_once 'Models/Coupon.php';

class CartCtrl {

    // 1. Hiển thị giỏ hàng
    public function index() {
        // Lấy giỏ hàng từ Session
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

        // fetch variant details for display if available
        $productModel = new Products();
        foreach ($cart as $key => $item) {
            if (!empty($item['variant_id'])) {
                $v = $productModel->getVariantById((int)$item['variant_id']);
                if ($v) {
                    $cart[$key]['display_price'] = $v['price'];
                    $cart[$key]['variant_meta'] = [
                        'size' => $v['size_name'] ?? null,
                        'color' => $v['color_name'] ?? null,
                        'sku' => $v['sku'] ?? null,
                        'stock' => $v['quantity'] ?? null
                    ];
                }
            }
        }
        
        // Tính toán tổng tiền
        $subtotal = 0;
        foreach ($cart as $item) {
            // SỬA LỖI QUAN TRỌNG: 'quantity' chứ không phải 'quatity'
            // Nếu sai chữ này, tổng tiền luôn bằng 0
            $qty = isset($item['quantity']) ? $item['quantity'] : 0;
            $price = isset($item['display_price']) ? $item['display_price'] : $item['price'];
            $subtotal += ((float)$price) * $qty;
        }
        
        // Logic phí ship
        $shipping = ($subtotal > 1000000) ? 0 : 30000;
        if($subtotal == 0) $shipping = 0; 

        // ==== Logic ma giam gia ====
        $discount = 0;
        if (isset($_SESSION['applied_coupon'])) {
            $coupon = $_SESSION['applied_coupon'];

            // Tinh tong tien giam
            if ($coupon['type'] == 'percent') {
                $discount = ($subtotal * $coupon['value']) / 100 ;
            }else {
                $discount = $coupon['value'];
            }

            //Khong cho giam qua so tien hang - Tranh viec bi am tien
            if($discount > $subtotal) $discount = $subtotal;
        }

        //Tong tien cuoi cung
        $total = ($subtotal + $shipping) - $discount;
        if ($total < 0) $total = 0;

        // Gọi View và truyền biến sang
        include_once "Views/cart.php";
    }

    // === Xu ly ap dung ma giam gia ===
    public function applyCoupon() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = $_POST['coupon_code'] ?? '';

            // Gọi Model
            $couponModel = new Coupon();
            
            // BƯỚC 1: Lấy dữ liệu thô (kể cả mã đã hết hạn) để kiểm tra tồn tại
            $coupon = $couponModel->getRawByCode($code);

            if ($coupon) {
                // Mã có tồn tại trong Database -> Bắt đầu kiểm tra điều kiện
                
                $now = date('Y-m-d H:i:s');
                
                // BƯỚC 2: Kiểm tra Hạn sử dụng
                // Nếu có ngày hết hạn VÀ ngày hiện tại đã vượt quá ngày hết hạn
                if (!empty($coupon['expires_at']) && $coupon['expires_at'] < $now) {
                    echo "<script>alert('Mã giảm giá đã hết hạn!'); window.location.href='../cart';</script>";
                    exit;
                }

                // BƯỚC 3: Kiểm tra Lượt dùng (Số lượng)
                // Nếu có giới hạn số lượng VÀ số lượng <= 0
                if (isset($coupon['usage_limit']) && $coupon['usage_limit'] !== null && $coupon['usage_limit'] <= 0) {
                     echo "<script>alert('Mã giảm giá đã hết lượt sử dụng!'); window.location.href='../cart';</script>";
                     exit;
                }

                // BƯỚC 4: Hợp lệ (Thỏa mãn mọi điều kiện)
                // Trường hợp này bao gồm cả "Sắp hết hạn" và "Chưa hết hạn"
                
                $_SESSION['applied_coupon'] = $coupon;
                echo "<script>alert('Áp dụng mã thành công!'); window.location.href='../cart';</script>";

            } else {
                // Trường hợp mã hoàn toàn không có trong database
                echo "<script>alert('Mã giảm giá không tồn tại!'); window.location.href='../cart';</script>";
            }
        }
    }

    // === Huy ma giam gia
    public function removeCoupon() {
        if (isset($_SESSION['applied_coupon'])) {
            unset($_SESSION['applied_coupon']);
        }
        header("Location: " . BASE_URL . "index.php/cart");
        exit;
    }

    // 2. Thêm vào giỏ
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $variantId = $_POST['variant_id'] ?? null;
            $name = $_POST['name'];
            $price = $_POST['price'] ?? 0;
            $image = $_POST['image'] ?? '';
            $quantity = max(1, (int)$_POST['quantity']);

            // If variant_id provided, validate / fetch server-managed price
            $productModel = new Products();
            $variantInfo = null;
            if ($variantId) {
                $variantInfo = $productModel->getVariantById((int)$variantId);
                if (!$variantInfo) {
                    // Invalid variant submitted
                    if (isset($_POST['is_ajax']) && $_POST['is_ajax'] == 1) {
                        header('Content-Type: application/json; charset=utf-8');
                        echo json_encode(['success' => false, 'message' => 'Variant không tồn tại']);
                        exit();
                    }
                    // For non-AJAX, just redirect back
                    header("Location: " . BASE_URL . "index.php/cart");
                    exit;
                }
                // use server-side price & data
                $price = $variantInfo['price'];
                $image = $image ?: ($variantInfo['image'] ?? $image);
                // name can include variant details
                $name = $variantInfo['product_name'] . ' — ' . ($variantInfo['color_name'] ?? '') . ' / ' . ($variantInfo['size_name'] ?? '');
            }

            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            // Use key based on variant if provided so different variants don't overwrite each other
            $key = $variantId ? 'v_' . $variantId : 'p_' . $id;

            if (isset($_SESSION['cart'][$key])) {
                $_SESSION['cart'][$key]['quantity'] += $quantity;
            } else {
                $_SESSION['cart'][$key] = [
                    'product_id' => $id,
                    'variant_id' => $variantId,
                    'name' => $name,
                    'price' => $price,
                    'image' => $image,
                    'quantity' => $quantity,
                ];
            }

            //Kiểm tra nếu là AJAX gửi lên thì trả về số lượng rồi dừng
            if(isset($_POST['is_ajax']) && $_POST['is_ajax'] == 1) {
                $totalQty = 0;
                foreach ($_SESSION['cart'] as $item) {
                    $totalQty += $item['quantity'];
                }
                // return JSON for AJAX callers
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => true, 'totalQty' => $totalQty]);
                exit();
            }
        }
        // SỬA LỖI HEADER: Thêm dấu : sau Location và nối chuỗi đúng cách
        // Đảm bảo BASE_URL không bị dính liền
        header("Location: " . BASE_URL . "index.php/cart");
        exit;
    }

    // 3. Cập nhật số lượng
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $type = $_POST['type'];

            if (isset($_SESSION['cart'][$id])) {
                if ($type == 'inc') {
                    $_SESSION['cart'][$id]['quantity']++;
                } else {
                    $_SESSION['cart'][$id]['quantity']--;
                }

                if ($_SESSION['cart'][$id]['quantity'] <= 0) {
                    unset($_SESSION['cart'][$id]);
                }
            }
        }
        header("Location: " . BASE_URL . "index.php/cart");
        exit;
    }

    // 4. Xóa sản phẩm
    public function remove($id) {
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
        header("Location: " . BASE_URL . "index.php/cart");
        exit();
    }
}
?>