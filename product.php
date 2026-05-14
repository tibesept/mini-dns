<?php
// product.php
require_once 'config/db.php';
require_once 'models/Product.php';
require_once 'models/Review.php';

$productModel = new Product($pdo);
$reviewModel = new Review($pdo);

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = $productModel->getById($id);

if (!$product) {
    header("HTTP/1.0 404 Not Found");
    die("Товар не найден.");
}

// Обработка формы отзыва
$reviewMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_review') {
    $authorName = trim($_POST['author_name']);
    $reviewText = trim($_POST['review_text']);
    $rating = (int)$_POST['rating'];

    if ($authorName && $reviewText && $rating >= 1 && $rating <= 5) {
        // htmlspecialchars будет применяться при выводе
        $reviewModel->add($id, $authorName, $reviewText, $rating);
        $reviewMessage = '<div class="alert alert-success">Ваш отзыв успешно отправлен и появится после проверки модератором.</div>';
    } else {
        $reviewMessage = '<div class="alert alert-danger">Пожалуйста, заполните все поля корректно.</div>';
    }
}

$reviews = $reviewModel->getApprovedByProductId($id);
$ratingData = $reviewModel->getAverageRating($id);
$avgRating = $ratingData['avg_rating'] ? round($ratingData['avg_rating'], 1) : 0;
$totalReviews = $ratingData['total_reviews'];

?>
<?php include 'views/layout/header.php'; ?>

<nav aria-label="breadcrumb" class="mb-4">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-muted">Главная</a></li>
    <li class="breadcrumb-item"><a href="catalog.php?category=<?= $product['category_id'] ?>" class="text-decoration-none text-muted"><?= htmlspecialchars($product['category_name']) ?></a></li>
    <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($product['name']) ?></li>
  </ol>
</nav>

<div class="row mb-5 bg-white rounded-4 shadow-sm p-4 border border-light">
    <!-- Изображение -->
    <div class="col-md-5 text-center mb-4 mb-md-0">
        <?php if ($product['image'] && file_exists('public/uploads/' . $product['image'])): ?>
            <img src="public/uploads/<?= htmlspecialchars($product['image']) ?>" class="img-fluid rounded-3" alt="<?= htmlspecialchars($product['name']) ?>" style="max-height: 400px; object-fit: contain;">
        <?php else: ?>
            <div class="d-flex align-items-center justify-content-center bg-light rounded-3 h-100" style="min-height: 300px;">
                <i class="bi bi-image fs-1 text-muted"></i>
            </div>
        <?php endif; ?>
    </div>

    <!-- Детали -->
    <div class="col-md-7 d-flex flex-column justify-content-center">
        <h1 class="fw-bold mb-3"><?= htmlspecialchars($product['name']) ?></h1>
        
        <div class="d-flex align-items-center mb-4">
            <div class="text-warning me-2 fs-5">
                <?php for($i=1; $i<=5; $i++): ?>
                    <i class="bi <?= $i <= round($avgRating) ? 'bi-star-fill' : 'bi-star' ?>"></i>
                <?php endfor; ?>
            </div>
            <span class="text-muted"><?= $avgRating ?> (<?= $totalReviews ?> отзывов)</span>
        </div>

        <div class="mb-4">
            <h4 class="fw-bold mb-2">Описание</h4>
            <p class="text-muted lh-lg"><?= nl2br(htmlspecialchars($product['description'])) ?></p>
        </div>

        <div class="mt-auto pt-3 border-top d-flex align-items-center justify-content-between">
            <div class="fs-2 fw-bold text-dark"><?= number_format($product['price'], 0, '', ' ') ?> ₽</div>
            <button class="btn btn-accent rounded-pill px-5 py-3 fs-5" onclick="addToCart(<?= $product['id'] ?>)">
                <i class="bi bi-cart-plus me-2"></i> В корзину
            </button>
        </div>
    </div>
</div>

<!-- Блок отзывов -->
<div class="row">
    <div class="col-lg-8 mx-auto">
        <h3 class="fw-bold mb-4 border-bottom pb-3">Отзывы покупателей</h3>
        
        <?= $reviewMessage ?>

        <!-- Форма добавления отзыва -->
        <div class="card bg-light border-0 rounded-4 mb-5 p-4">
            <h5 class="fw-bold mb-3">Оставить отзыв</h5>
            <form action="product.php?id=<?= $id ?>" method="POST">
                <input type="hidden" name="action" value="add_review">
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Ваше имя</label>
                        <input type="text" name="author_name" class="form-control premium-input" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Оценка</label>
                        <select name="rating" class="form-select premium-input" required>
                            <option value="5">5 - Отлично</option>
                            <option value="4">4 - Хорошо</option>
                            <option value="3">3 - Нормально</option>
                            <option value="2">2 - Плохо</option>
                            <option value="1">1 - Ужасно</option>
                        </select>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-semibold">Комментарий</label>
                    <textarea name="review_text" class="form-control premium-input" rows="4" required></textarea>
                </div>
                
                <button type="submit" class="btn btn-accent rounded-pill px-4">Отправить на модерацию</button>
            </form>
        </div>

        <!-- Список отзывов -->
        <?php if (empty($reviews)): ?>
            <p class="text-muted text-center py-4">Пока нет одобренных отзывов. Будьте первым!</p>
        <?php else: ?>
            <?php foreach ($reviews as $rev): ?>
                <div class="card border-0 shadow-sm rounded-4 mb-3 p-4">
                    <div class="d-flex justify-content-between mb-2">
                        <strong class="fs-5"><?= htmlspecialchars($rev['author_name']) ?></strong>
                        <div class="text-warning">
                            <?php for($i=1; $i<=5; $i++): ?>
                                <i class="bi <?= $i <= $rev['rating'] ? 'bi-star-fill' : 'bi-star' ?>"></i>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <div class="text-muted small mb-3"><?= date('d.m.Y H:i', strtotime($rev['created_at'])) ?></div>
                    <p class="mb-0 text-dark"><?= nl2br(htmlspecialchars($rev['review_text'])) ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>
