<?php
// View: Hiển thị tất cả sản phẩm
// Biến mong đợi từ Controller: $products (mảng associative)
?>
<main class="products-page">
	<div class="container">
		<h1><?= isset($categoryName) && $categoryName ? htmlspecialchars($categoryName) : 'Tất cả sản phẩm' ?></h1>

		<?php if (!empty($products) && is_array($products)): ?>
			<div class="product-grid">
				<?php foreach ($products as $p):
					$name = $p['name'] ?? 'Sản phẩm';
					$slug = $p['slug'] ?? ($p['id'] ?? '');
					$price = $p['price'] ?? 0;
					$img = $p['image_url'] ?? ($p['image'] ?? '');

					// Chuẩn hóa url ảnh: nếu không có http thì gắn BASE_URL
					if ($img) {
						if (strpos($img, 'http') !== 0) {
							$img = BASE_URL . ltrim($img, '/');
						}
					} else {
						$img = BASE_URL . 'Views/assets/image/placeholder.png';
					}
					?>
					<div class="product-box">
						<a href="<?= BASE_URL ?>index.php/products/detail/<?= $slug ?>">
							<img class="product-img" src="<?= $img ?>" alt="<?= htmlspecialchars($name) ?>">
							<div class="product-name"><?= htmlspecialchars($name) ?></div>
							<div class="product-price"><?= number_format($price) ?>₫</div>
							<button class="buy-btn">Mua ngay</button>
						</a>
					</div>
				<?php endforeach; ?>
			</div>
		<?php else: ?>
			<p>Không có sản phẩm nào.</p>
		<?php endif; ?>
	</div>
</main>