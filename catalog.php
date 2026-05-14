<?php
// catalog.php
require_once 'config/db.php';
require_once 'models/Product.php';
require_once 'models/Category.php';

$categoryModel = new Category($pdo);
$productModel = new Product($pdo);

$categories = $categoryModel->getAll();

$currentCategoryId = isset($_GET['category']) ? (int)$_GET['category'] : null;
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'id DESC';

$products = $productModel->getAll($currentCategoryId, $sort);
?>
<?php include 'views/layout/header.php'; ?>

<div class="row">
    <!-- Sidebar: Фильтры -->
    <aside class="col-lg-3 mb-4">
        <div class="card border-0 shadow-sm premium-modal">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4">Категории</h5>
                <ul class="list-unstyled mb-4">
                    <li class="mb-2">
                        <a href="catalog.php" class="text-decoration-none <?= !$currentCategoryId ? 'text-accent fw-bold' : 'text-muted' ?>">
                            Все товары
                        </a>
                    </li>
                    <?php foreach ($categories as $cat): ?>
                        <li class="mb-2">
                            <a href="catalog.php?category=<?= $cat['id'] ?>" class="text-decoration-none <?= $currentCategoryId === (int)$cat['id'] ? 'text-accent fw-bold' : 'text-muted' ?>">
                                <?= htmlspecialchars($cat['name']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <h5 class="fw-bold mb-3 mt-4">Сортировка</h5>
                <form action="catalog.php" method="GET">
                    <?php if ($currentCategoryId): ?>
                        <input type="hidden" name="category" value="<?= $currentCategoryId ?>">
                    <?php endif; ?>
                    <select name="sort" class="form-select premium-input mb-3" onchange="this.form.submit()">
                        <option value="id DESC" <?= $sort == 'id DESC' ? 'selected' : '' ?>>Сначала новые</option>
                        <option value="price ASC" <?= $sort == 'price ASC' ? 'selected' : '' ?>>Сначала дешевые</option>
                        <option value="price DESC" <?= $sort == 'price DESC' ? 'selected' : '' ?>>Сначала дорогие</option>
                        <option value="name ASC" <?= $sort == 'name ASC' ? 'selected' : '' ?>>По алфавиту</option>
                    </select>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content: Сетка товаров -->
    <div class="col-lg-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">Каталог товаров</h2>
            <span class="text-muted">Найдено: <?= count($products) ?> шт.</span>
        </div>

        <?php if (empty($products)): ?>
            <div class="alert alert-light text-center p-5 border-0 rounded-4">
                <i class="bi bi-emoji-frown fs-1 text-muted mb-3 d-block"></i>
                <h5>Товары не найдены</h5>
                <p class="text-muted">В данной категории пока нет товаров.</p>
            </div>
        <?php else: ?>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php foreach ($products as $product): ?>
                    <div class="col">
                        <div class="product-card h-100">
                            <a href="product.php?id=<?= $product['id'] ?>" class="card-img-wrapper d-block text-center">
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
        <?php endif; ?>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>
