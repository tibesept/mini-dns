<?php
// admin.php
session_start();
require_once 'config/db.php';
require_once 'models/User.php';

$action = $_GET['action'] ?? 'dashboard';

// Логика авторизации
if ($action === 'login') {
    $error = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        
        $userModel = new User($pdo);
        $userId = $userModel->login($username, $password);
        
        if ($userId) {
            $_SESSION['admin_id'] = $userId;
            header("Location: admin.php");
            exit;
        } else {
            $error = 'Неверный логин или пароль';
        }
    }
    include 'views/admin/login.php';
    exit;
}

if ($action === 'logout') {
    unset($_SESSION['admin_id']);
    header("Location: admin.php?action=login");
    exit;
}

// Защита админки
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin.php?action=login");
    exit;
}

// Простой роутинг (в реальном проекте выносим в контроллеры)
switch ($action) {
    case 'dashboard':
        $stmt = $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'new'");
        $newOrders = $stmt->fetchColumn();
        
        $stmt = $pdo->query("SELECT COUNT(*) FROM reviews WHERE is_approved = 0");
        $newReviews = $stmt->fetchColumn();
        
        include 'views/admin/dashboard.php';
        break;
        
    case 'reviews':
        $stmt = $pdo->query("SELECT r.*, p.name as product_name FROM reviews r JOIN products p ON r.product_id = p.id ORDER BY r.created_at DESC");
        $allReviews = $stmt->fetchAll();
        include 'views/admin/reviews.php';
        break;

    case 'approve_review':
        $id = (int)$_GET['id'];
        $stmt = $pdo->prepare("UPDATE reviews SET is_approved = 1 WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: admin.php?action=reviews");
        exit;

    case 'delete_review':
        $id = (int)$_GET['id'];
        $stmt = $pdo->prepare("DELETE FROM reviews WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: admin.php?action=reviews");
        exit;

    // Здесь будут роуты для products, categories, orders
    default:
        echo "Страница не найдена";
}
