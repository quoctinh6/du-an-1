<h2>🎉 Đặt hàng thành công</h2>

<p>Mã đơn hàng: <strong><?= $order['id'] ?></strong></p>
<p>Số tiền: <?= number_format($order['total_price']) ?> VND</p>
<p>Trạng thái: <?= $order['status'] ?></p>

<a href="<?= BASE_URL ?>">Về trang chủ</a>