<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админ-панель</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>

<nav class="navbar navbar-dark bg-dark premium-navbar">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#">Админ-панель</a>
        <div class="d-flex">
            <a href="admin.php?action=logout" class="btn btn-outline-light rounded-pill btn-sm px-3">Выйти</a>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-3">
            <div class="list-group rounded-4 shadow-sm border-0">
                <a href="admin.php?action=dashboard" class="list-group-item list-group-item-action active border-0">Главная</a>
                <a href="admin.php?action=products" class="list-group-item list-group-item-action border-0">Товары</a>
                <a href="admin.php?action=categories" class="list-group-item list-group-item-action border-0">Категории</a>
                <a href="admin.php?action=reviews" class="list-group-item list-group-item-action border-0 d-flex justify-content-between align-items-center">
                    Отзывы
                    <?php if($newReviews > 0): ?>
                        <span class="badge bg-danger rounded-pill"><?= $newReviews ?></span>
                    <?php endif; ?>
                </a>
                <a href="admin.php?action=orders" class="list-group-item list-group-item-action border-0 d-flex justify-content-between align-items-center">
                    Заказы
                    <?php if($newOrders > 0): ?>
                        <span class="badge bg-danger rounded-pill"><?= $newOrders ?></span>
                    <?php endif; ?>
                </a>
            </div>
        </div>
        <div class="col-md-9">
            <h2 class="fw-bold mb-4">Сводка</h2>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted text-uppercase fw-bold mb-1">Новые заказы</h6>
                                <h2 class="fw-bold mb-0"><?= $newOrders ?></h2>
                            </div>
                            <div class="bg-accent-light rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="bi bi-box-seam fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted text-uppercase fw-bold mb-1">Отзывы на модерации</h6>
                                <h2 class="fw-bold mb-0"><?= $newReviews ?></h2>
                            </div>
                            <div class="bg-light rounded-circle text-warning p-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="bi bi-star fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="alert alert-info border-0 rounded-4 mt-5 bg-accent-light">
                <i class="bi bi-info-circle me-2"></i> Это базовая версия админ-панели. Функции CRUD добавляются в соответствующих роутах `admin.php?action=...`
            </div>
        </div>
    </div>
</div>

</body>
</html>
