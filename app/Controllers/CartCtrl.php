<?php
class CartCtrl
{

    // 1. Hiển thị giỏ hàng
    public function index()
    {
        // Lấy giỏ hàng từ Session
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

        // Tính toán tổng tiền
        $subtotal = 0;
        foreach ($cart as $item) {
            // SỬA LỖI QUAN TRỌNG: 'quantity' chứ không phải 'quatity'
            // Nếu sai chữ này, tổng tiền luôn bằng 0
            $qty = isset($item['quantity']) ? $item['quantity'] : 0;
            $subtotal += $item['price'] * $qty;
        }

        // Logic phí ship
        $shipping = ($subtotal > 1000000) ? 0 : 30000;
        if ($subtotal == 0)
            $shipping = 0;

        $total = $subtotal + $shipping;

        // Gọi View và truyền biến sang
        include_once "Views/cart.php";
    }

    // 2. Thêm vào giỏ
    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $is_ajax = $_POST['is_ajax'] ?? 0;
            $id = $_POST['id'] ?? 0;
            $name = $_POST['name'] ?? '';
            $price = $_POST['price'] ?? 0;
            $image = $_POST['image'] ?? '';
            $quantity = $_POST['quantity'] ?? 1;

            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            if (isset($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id]['quantity'] += $quantity;
            } else {
                $_SESSION['cart'][$id] = [
                    'id' => $id,
                    'name' => $name,
                    'price' => $price,
                    'image' => $image,
                    'quantity' => $quantity
                ];
            }

            // Nếu AJAX, return count thay vì redirect
            if ($is_ajax) {
                echo count($_SESSION['cart']);
                exit;
            }
        }

        header("Location: " . BASE_URL . "index.php/cart");
        exit;
    }

    // 3. Cập nhật số lượng
    public function update()
    {
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
    public function remove($id)
    {
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
        header("Location: " . BASE_URL . "index.php/cart");
        exit();
    }
}
?>