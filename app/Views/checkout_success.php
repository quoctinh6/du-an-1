<style>
    /* Container chính */
    .success-wrapper {
        background-color: #000;
        color: #fff;
        min-height: 80vh; /* Tăng chiều cao tối thiểu lên để cân đối hơn */
        display: flex;
        justify-content: center;
        /* align-items: center;  <-- Bỏ dòng này để margin-top hoạt động hiệu quả hơn với Header fixed */
        padding: 20px;
        font-family: sans-serif;
    }

    /* Thẻ Card chứa thông tin đơn hàng */
    .success-card {
        background-color: #1a1a1a;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(255, 165, 0, 0.1);
        text-align: center;
        max-width: 500px;
        width: 100%;
        border: 1px solid #333;
        
        /* --- CẬP NHẬT Ở ĐÂY --- */
        margin-top: 100px; /* Đẩy khung xuống để tránh header */
        height: fit-content; /* Đảm bảo chiều cao vừa đủ nội dung */
    }

    /* Icon chúc mừng */
    .success-icon {
        font-size: 4rem;
        margin-bottom: 20px;
        display: block;
    }

    /* Tiêu đề */
    .success-card h2 {
        color: #ffa500;
        margin-bottom: 25px;
        font-size: 1.8rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* Thông tin chi tiết */
    .order-details {
        text-align: left;
        background: #000;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 30px;
        border: 1px solid #333;
    }

    .order-details p {
        margin: 10px 0;
        font-size: 1.1rem;
        color: #ccc;
        display: flex;
        justify-content: space-between;
        border-bottom: 1px dashed #333;
        padding-bottom: 10px;
    }

    .order-details p:last-child {
        border-bottom: none;
    }

    .order-details strong {
        color: #fff;
    }

    /* Nút về trang chủ */
    .btn-home {
        display: inline-block;
        background-color: #ffa500;
        color: #000;
        padding: 12px 30px;
        text-decoration: none;
        border-radius: 50px;
        font-weight: bold;
        text-transform: uppercase;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(255, 165, 0, 0.3);
    }

    .btn-home:hover {
        background-color: #fff;
        color: #000;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 255, 255, 0.3);
    }
</style>

<div class="success-wrapper">
    <div class="success-card">
        <span class="success-icon">🎉</span>
        <h2>Đặt hàng thành công</h2>
        
        <p style="color: #999; margin-bottom: 20px;">Cảm ơn bạn đã mua sắm tại Zero Watch.</p>

        <div class="order-details">
            <p>
                <span>Mã đơn hàng:</span>
                <strong>#<?= $order['id'] ?></strong>
            </p>
            <p>
                <span>Số tiền:</span>
                <strong style="color: #ffa500;"><?= number_format($order['total_price']) ?> VND</strong>
            </p>
            <p>
                <span>Trạng thái:</span>
                <span style="text-transform: capitalize; color: #28a745; font-weight: bold;">
                    <?= $order['status'] ?>
                </span>
            </p>
        </div>

        <a href="<?= BASE_URL ?>" class="btn-home">Tiếp tục mua sắm</a>
    </div>
</div>