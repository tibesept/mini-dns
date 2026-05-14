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

    case 'products':
        require_once 'models/Product.php';
        require_once 'models/Category.php';
        $productModel = new Product($pdo);
        $categoryModel = new Category($pdo);
        $allProducts = $productModel->getAll();
        $allCategories = $categoryModel->getAll();
        include 'views/admin/products.php';
        break;

    case 'product_add':
        require_once 'models/Product.php';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $categoryId = (int)$_POST['category_id'];
            $price = (float)$_POST['price'];
            $description = trim($_POST['description']);
            $imageName = 'default.png';

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $tmpName = $_FILES['image']['tmp_name'];
                $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $imageName = uniqid() . '.' . $ext;
                move_uploaded_file($tmpName, 'public/uploads/' . $imageName);
            }

            $productModel = new Product($pdo);
            $productModel->add($categoryId, $name, $description, $price, $imageName);
        }
        header("Location: admin.php?action=products");
        exit;

    case 'product_delete':
        require_once 'models/Product.php';
        $id = (int)$_GET['id'];
        $productModel = new Product($pdo);
        // В идеале тут нужно удалять и картинку, но для простоты оставим так
        $productModel->delete($id);
        header("Location: admin.php?action=products");
        exit;

    case 'orders':
        require_once 'models/Order.php';
        $orderModel = new Order($pdo);
        $allOrders = $orderModel->getAllOrders();
        include 'views/admin/orders.php';
        break;

    case 'categories':
        require_once 'models/Category.php';
        $categoryModel = new Category($pdo);
        $allCategories = $categoryModel->getAll();
        include 'views/admin/categories.php';
        break;

    case 'category_add':
        require_once 'models/Category.php';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            if ($name) {
                $categoryModel = new Category($pdo);
                $categoryModel->add($name);
            }
        }
        header("Location: admin.php?action=categories");
        exit;

    case 'category_delete':
        require_once 'models/Category.php';
        $id = (int)$_GET['id'];
        $categoryModel = new Category($pdo);
        $categoryModel->delete($id);
        header("Location: admin.php?action=categories");
        exit;

    default:
        echo "Страница не найдена";
}
