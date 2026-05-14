<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход в админ-панель</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">

<div class="card border-0 shadow-lg premium-modal" style="width: 100%; max-width: 400px;">
    <div class="card-body p-5">
        <h3 class="text-center fw-bold mb-4">Вход в систему</h3>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form action="admin.php?action=login" method="POST">
            <div class="mb-3">
                <label class="form-label text-muted small text-uppercase fw-bold">Логин</label>
                <input type="text" name="username" class="form-control premium-input" required>
            </div>
            <div class="mb-4">
                <label class="form-label text-muted small text-uppercase fw-bold">Пароль</label>
                <input type="password" name="password" class="form-control premium-input" required>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-accent rounded-pill py-2">Войти</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>
