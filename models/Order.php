<?php
// models/Order.php

class Order {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function createOrder($customerName, $phone, $cartItems) {
        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare("INSERT INTO orders (customer_name, phone, status) VALUES (?, ?, 'new')");
            $stmt->execute([$customerName, $phone]);
            $orderId = $this->pdo->lastInsertId();

            $itemStmt = $this->pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price_at_purchase) VALUES (?, ?, ?, ?)");
            
            foreach ($cartItems as $item) {
                // В реальном проекте цену нужно брать из БД, а не из сессии! Но для диплома и "псевдо-оплаты" сойдет.
                $itemStmt->execute([$orderId, $item['id'], $item['quantity'], $item['price']]);
            }

            $this->pdo->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function getAllOrders() {
        $stmt = $this->pdo->query("SELECT * FROM orders ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }
}
