<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Модерация отзывов</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>

<nav class="navbar navbar-dark bg-dark premium-navbar">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="admin.php">Админ-панель</a>
        <div class="d-flex">
            <a href="admin.php?action=logout" class="btn btn-outline-light rounded-pill btn-sm px-3">Выйти</a>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-3">
            <div class="list-group rounded-4 shadow-sm border-0">
                <a href="admin.php?action=dashboard" class="list-group-item list-group-item-action border-0">Главная</a>
                <a href="#" class="list-group-item list-group-item-action border-0">Товары</a>
                <a href="#" class="list-group-item list-group-item-action border-0">Категории</a>
                <a href="admin.php?action=reviews" class="list-group-item list-group-item-action active border-0 d-flex justify-content-between align-items-center">
                    Отзывы
                </a>
                <a href="#" class="list-group-item list-group-item-action border-0 d-flex justify-content-between align-items-center">
                    Заказы
                </a>
            </div>
        </div>
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold mb-0">Отзывы</h2>
            </div>
            
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Товар</th>
                                    <th>Автор</th>
                                    <th>Оценка</th>
                                    <th style="max-width: 300px;">Текст</th>
                                    <th>Статус</th>
                                    <th class="text-end pe-4">Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($allReviews)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">Нет отзывов</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach($allReviews as $rev): ?>
                                        <tr>
                                            <td class="ps-4"><a href="/product.php?id=<?= $rev['product_id'] ?>" target="_blank"><?= htmlspecialchars($rev['product_name']) ?></a></td>
                                            <td><?= htmlspecialchars($rev['author_name']) ?></td>
                                            <td>
                                                <div class="text-warning">
                                                    <?php for($i=1; $i<=5; $i++): ?>
                                                        <i class="bi <?= $i <= $rev['rating'] ? 'bi-star-fill' : 'bi-star' ?>"></i>
                                                    <?php endfor; ?>
                                                </div>
                                            </td>
                                            <td style="max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= htmlspecialchars($rev['review_text']) ?>">
                                                <?= htmlspecialchars($rev['review_text']) ?>
                                            </td>
                                            <td>
                                                <?php if($rev['is_approved']): ?>
                                                    <span class="badge bg-success rounded-pill px-3">Одобрен</span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning text-dark rounded-pill px-3">Новый</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end pe-4">
                                                <?php if(!$rev['is_approved']): ?>
                                                    <a href="admin.php?action=approve_review&id=<?= $rev['id'] ?>" class="btn btn-sm btn-success rounded-pill" title="Одобрить">
                                                        <i class="bi bi-check-lg"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <a href="admin.php?action=delete_review&id=<?= $rev['id'] ?>" class="btn btn-sm btn-danger rounded-pill" title="Удалить" onclick="return confirm('Точно удалить?')">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

</body>
</html>
