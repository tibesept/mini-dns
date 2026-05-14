<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Заказы</title>
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
                <a href="admin.php?action=products" class="list-group-item list-group-item-action border-0">Товары</a>
                <a href="admin.php?action=categories" class="list-group-item list-group-item-action border-0">Категории</a>
                <a href="admin.php?action=reviews" class="list-group-item list-group-item-action border-0">Отзывы</a>
                <a href="admin.php?action=orders" class="list-group-item list-group-item-action active border-0">Заказы</a>
            </div>
        </div>
        <div class="col-md-9">
            <h2 class="fw-bold mb-4">История заказов</h2>
            
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">№ Заказа</th>
                                    <th>Дата</th>
                                    <th>Имя клиента</th>
                                    <th>Телефон</th>
                                    <th>Статус</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($allOrders)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">Нет заказов</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach($allOrders as $order): ?>
                                        <tr>
                                            <td class="ps-4 fw-bold">#<?= $order['id'] ?></td>
                                            <td><?= date('d.m.Y H:i', strtotime($order['created_at'])) ?></td>
                                            <td><?= htmlspecialchars($order['customer_name']) ?></td>
                                            <td><?= htmlspecialchars($order['phone']) ?></td>
                                            <td>
                                                <span class="badge bg-primary rounded-pill px-3"><?= htmlspecialchars($order['status']) ?></span>
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
