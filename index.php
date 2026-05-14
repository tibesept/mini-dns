<?php
// index.php
require_once 'config/db.php';
require_once 'models/Product.php';
require_once 'models/Category.php';

$productModel = new Product($pdo);
$popularProducts = $productModel->getPopular(8); // Получаем до 8 товаров
?>
<?php include 'views/layout/header.php'; ?>

<!-- Hero Section -->
<div class="row align-items-center mb-5 pb-4">
    <div class="col-lg-6 mb-4 mb-lg-0">
        <h1 class="display-4 fw-bold mb-3">Топовые <span class="text-accent">комплектующие</span> для вашего ПК</h1>
        <p class="lead text-muted mb-4">Собери машину своей мечты с нашими компонентами. Лучшие цены и быстрая доставка.</p>
        <a href="catalog.php" class="btn btn-accent rounded-pill px-5 py-3 fs-5">Перейти в каталог <i class="bi bi-arrow-right ms-2"></i></a>
    </div>
    <div class="col-lg-6 text-center">
        <!-- В реальном проекте тут красивый рендер ПК, используем иконку -->
        <i class="bi bi-gpu-card text-accent opacity-50" style="font-size: 15rem;"></i>
    </div>
</div>

<!-- Витрина популярных товаров -->
<div class="d-flex justify-content-between align-items-end mb-4">
    <h2 class="fw-bold mb-0">Популярные товары</h2>
    <a href="catalog.php" class="text-decoration-none text-accent fw-semibold">Смотреть все</a>
</div>

<div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
    <?php foreach ($popularProducts as $product): ?>
        <div class="col">
            <div class="product-card h-100">
                <a href="product.php?id=<?= $product['id'] ?>" class="card-img-wrapper d-block text-center">
                    <!-- Заглушка, если нет картинки, ставим иконку или placeholder -->
                    <?php if ($product['image'] && file_exists('public/uploads/' . $product['image'])): ?>
                        <img src="public/uploads/<?= htmlspecialchars($product['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
                    <?php else: ?>
                        <div class="d-flex align-items-center justify-content-center h-100 w-100 bg-light text-muted">
                            <i class="bi bi-box-seam fs-1"></i>
                        </div>
                    <?php endif; ?>
                </a>
                <div class="card-body">
                    <small class="text-muted text-uppercase fw-semibold mb-2 d-block"><?= htmlspecialchars($product['category_name'] ?? 'Без категории') ?></small>
                    <a href="product.php?id=<?= $product['id'] ?>" class="product-title"><?= htmlspecialchars($product['name']) ?></a>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="product-price"><?= number_format($product['price'], 0, '', ' ') ?> ₽</span>
                        <button class="btn btn-accent rounded-circle shadow-sm" style="width: 40px; height: 40px; padding: 0;" title="В корзину" onclick="addToCart(<?= $product['id'] ?>)">
                            <i class="bi bi-plus-lg"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php include 'views/layout/footer.php'; ?>
