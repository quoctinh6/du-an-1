<?php
    include_once 'Models/Order.php';
    include_once 'Models/Products.php';
    class CheckoutCtrl{
        
        // Hiển thì trang thanh toán
        public function index() {
            //Kiểm tra giỏ hàng
            $cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
            if (empty($cartItems)) {
                echo "<script> alert('Giỏ hàng đang trống!'); window.location.href=' " . BASE_URL . "index.php/products/all';</script>";
            }

            //Tính toàn tiền
            $subtotal = 0;
            foreach ($cartItems as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }
            $shipping = ($subtotal > 1000000) ? 0 : 30000;
            $totalPrice = $subtotal + $shipping;

            include_once 'Views/checkout.php';
        }

        public function process() {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                //Lấy dữ liệu từ form
                $fullname = $_POST['fullname'] ?? '';
                $phone = $_POST['phone'] ?? '';
                $address = $_POST['address'] ?? '';
                $paymentMethod = $_POST['payment_method'] ?? 'cod';

                //Map payment method sang ID (1: cod, 2: Banking) 
                $paymentMethodID = ($paymentMethod == 'banking') ? 2 : 1 ;
                $shippingMethod = 1; // Mặc định giao hàng tiêu chuẩn

                //Lấy ID User (Tạm thời = 1 nếu chưa login)
                $userID = isset($_SESSION['user']) ? $_SESSION['user']['id'] : 1;

                //Tỉnh lại tổng tiền (Bảo mật)
                $cart = $_SESSION['cart'] ?? [];
                $totalPrice = 0;
                foreach ($cart as $item) {
                    $totalPrice += $item['price'] * $item['quantity'];
                }
                $shipping = ($totalPrice > 10000000) ? 0 : 30000;
                $totalPrice += $shipping;

                //====Bắt đầu lưu DB=====
                $orderModel = new Order();
                $productModel = new Products();

                //Bước 1: Tạo đơn hàng
                $orderId = $orderModel->createOrder($userID, $totalPrice, $address, $phone, $paymentMethodID, null);

                if($orderId) {
                    //Bước 2: Lưu chi tiết
                    foreach ($cart as $item){
                        //Logic chữa cháy -> Tìm variant_id từ product_id (Lấy biến thể đầu tiền của sản phẩm)
                        $variants = $productModel->getVariantsById_product($item['id']);

                        if (!empty($variants)) {
                            $firstVariant = $variants[0]; // Lấy cái đầu tiên
                            $variantId = $firstVariant['id'];

                            //Gọi hàm thêm item của Model Order
                            $orderModel->addOrderItem($orderId, $variantId, $item['quantity'], $item['price']);
                        }
                    }

                    //Bước 3: xóa giỏ hàng và thông báo
                    unset($_SESSION['cart']);
                    echo "

                        <script>
                            alert('Đặt hàng thành công! Mã đơn: #$orderId');
                            window.location.href='" . BASE_URL . "';
                        </script>

                        ";
                }else {
                    echo "
                        <script>
                            alert('Lỗi hệ thống, không tạo được đơn hàng');
                            window.history.back();
                        </script>
                        ";
                }
            }
        }
    }
?>