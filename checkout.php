<?php
// checkout.php
session_start();
require_once 'config/db.php';
require_once 'models/Order.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Неверный метод']);
    exit;
}

$name = trim($_POST['name'] ?? '');
$phone = trim($_POST['phone'] ?? '');

if (empty($name) || empty($phone)) {
    echo json_encode(['status' => 'error', 'message' => 'Заполните все поля']);
    exit;
}

if (empty($_SESSION['cart'])) {
    echo json_encode(['status' => 'error', 'message' => 'Корзина пуста']);
    exit;
}

$orderModel = new Order($pdo);
$orderId = $orderModel->createOrder($name, $phone, $_SESSION['cart']);

if ($orderId) {
    // Очищаем корзину после успешного заказа
    $_SESSION['cart'] = [];
    echo json_encode(['status' => 'success', 'order_id' => $orderId]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Ошибка при создании заказа']);
}
