<?php
// models/Product.php

class Product {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll($categoryId = null, $sort = 'id DESC') {
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id";
        
        $params = [];
        if ($categoryId) {
            $sql .= " WHERE p.category_id = ?";
            $params[] = $categoryId;
        }
        
        // Simple sanitization for sort order
        $allowedSort = ['id DESC', 'price ASC', 'price DESC', 'name ASC'];
        if (!in_array($sort, $allowedSort)) {
            $sort = 'id DESC';
        }
        
        $sql .= " ORDER BY " . $sort;
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getPopular($limit = 4) {
        // Placeholder for popular logic. Currently returns newest.
        $stmt = $this->pdo->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC LIMIT ?");
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
