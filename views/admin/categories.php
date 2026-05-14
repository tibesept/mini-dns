<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Управление категориями</title>
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
                <a href="admin.php?action=categories" class="list-group-item list-group-item-action active border-0">Категории</a>
                <a href="admin.php?action=reviews" class="list-group-item list-group-item-action border-0">Отзывы</a>
                <a href="admin.php?action=orders" class="list-group-item list-group-item-action border-0">Заказы</a>
            </div>
        </div>
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold mb-0">Категории</h2>
                <button class="btn btn-accent rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                    <i class="bi bi-plus-lg me-1"></i> Добавить
                </button>
            </div>
            
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4" style="width: 100px;">ID</th>
                                    <th>Название</th>
                                    <th class="text-end pe-4" style="width: 150px;">Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($allCategories)): ?>
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted">Нет категорий</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach($allCategories as $cat): ?>
                                        <tr>
                                            <td class="ps-4"><?= $cat['id'] ?></td>
                                            <td class="fw-semibold"><?= htmlspecialchars($cat['name']) ?></td>
                                            <td class="text-end pe-4">
                                                <a href="admin.php?action=category_delete&id=<?= $cat['id'] ?>" class="btn btn-sm btn-outline-danger rounded-pill" onclick="return confirm('Удалить категорию? Все товары в ней тоже удалятся!')">
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

<!-- Модальное окно добавления категории -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg premium-modal">
      <div class="modal-header border-bottom-0">
        <h5 class="modal-title fw-bold">Новая категория</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="admin.php?action=category_add" method="POST">
        <div class="modal-body">
            <div class="mb-3">
                <label class="form-label text-muted small text-uppercase fw-bold">Название категории</label>
                <input type="text" name="name" class="form-control premium-input" required>
            </div>
        </div>
        <div class="modal-footer border-top-0">
            <button type="submit" class="btn btn-accent rounded-pill px-4">Сохранить</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
