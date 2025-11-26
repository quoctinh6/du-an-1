<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <title>DarkTime | Modern Watch Seller</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>Views/assets/css/style.css" />
</head>

<body>
    <header>
        <div class="header-container">
            <!-- Cột 1: Logo (Left) -->
            <div class="logo" style="color: white;"><a href="<?= BASE_URL; ?>">Zero Watch</a></div>

            <!-- Cột 2: Menu Navigation (Center) -->
            <nav>
                <!-- Chỉnh sửa lại các menu link cho phù hợp hơn với bố cục 3 cột ở giữa -->
                <a href="<?= BASE_URL; ?>index.php/products/all">Sản phẩm</a>
                <a href="favorites.html">Yêu thích</a>
                <a href="login.html">Liên hệ</a>
            </nav>

            <!-- Cột 3: Actions (Right) -->
            <div class="header-actions">
                <!-- Nút Giỏ hàng (Có icon và viền) -->
                <a href="<?= BASE_URL; ?>index.php/products/cart" class="action-button cart-btn">
                    <!-- Icon Giỏ hàng (dùng SVG) -->
                    <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" fill="currentColor"
                        viewBox="0 0 24 24">
                        <path
                            d="M7 18c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm10 0c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm1.604-2.083l2.396-9.917h-16v-2h-3v2h1.604l3.452 13.917a2 2 0 0 0 1.944 1.25h10.192a2 2 0 0 0 1.944-1.25l.588-2.333zm-13.604-11.083v-2h16v2h-16z" />
                    </svg>
                    Giỏ hàng <span id="cart-count" style="font-weight: 400;">0</span>
                </a>

                <!-- Nút Đăng nhập (Không viền) -->
                <?php
                if (isset($_SESSION['user'])) {
                    ?>
                    <a href="<?= BASE_URL ?>index.php/User/login" class="action-button login-btn">Xin chào
                        <?= $_SESSION['user'] ?></a>
                <?php } else { ?>
                    <a href="<?= BASE_URL ?>index.php/User/login" class="action-button login-btn">Đăng nhập</a>
                <?php } ?>
            </div>
        </div>
    </header>