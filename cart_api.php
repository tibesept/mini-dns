<?php
// cart_api.php
session_start();
require_once 'config/db.php';
require_once 'models/Product.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$productModel = new Product($pdo);

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

switch ($action) {
    case 'add':
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $product = $productModel->getById($id);
            if ($product) {
                if (isset($_SESSION['cart'][$id])) {
                    $_SESSION['cart'][$id]['quantity']++;
                } else {
                    $_SESSION['cart'][$id] = [
                        'id' => $product['id'],
                        'name' => $product['name'],
                        'price' => $product['price'],
                        'image' => $product['image'],
                        'quantity' => 1
                    ];
                }
                echo json_encode(['status' => 'success', 'cart_count' => count($_SESSION['cart'])]);
                exit;
            }
        }
        echo json_encode(['status' => 'error']);
        break;

    case 'remove':
        $id = (int)($_POST['id'] ?? 0);
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
        echo json_encode(['status' => 'success']);
        break;

    case 'update':
        $id = (int)($_POST['id'] ?? 0);
        $qty = (int)($_POST['qty'] ?? 1);
        if ($qty > 0 && isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity'] = $qty;
        }
        echo json_encode(['status' => 'success']);
        break;

    case 'get':
        $total = 0;
        $items = [];
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
            $items[] = $item;
        }
        echo json_encode([
            'status' => 'success',
            'items' => $items,
            'total' => $total,
            'cart_count' => count($_SESSION['cart'])
        ]);
        break;

    default:
        echo json_encode(['status' => 'invalid_action']);
}
