<?php
    session_start();
?>
<h2>🛵 Đặt hàng thành công (COD)</h2>

<p>Mã đơn hàng: <strong><?= $_SESSION['order']['order_id'] ?></strong></p>
<p>Trạng thái: Thanh toán khi nhận hàng</p>

<a href="index.php">Về trang chủ</a>
