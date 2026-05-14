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

    public function add($categoryId, $name, $description, $price, $image) {
        $stmt = $this->pdo->prepare("INSERT INTO products (category_id, name, description, price, image) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$categoryId, $name, $description, $price, $image]);
    }

    public function update($id, $categoryId, $name, $description, $price, $image = null) {
        if ($image) {
            $stmt = $this->pdo->prepare("UPDATE products SET category_id = ?, name = ?, description = ?, price = ?, image = ? WHERE id = ?");
            return $stmt->execute([$categoryId, $name, $description, $price, $image, $id]);
        } else {
            $stmt = $this->pdo->prepare("UPDATE products SET category_id = ?, name = ?, description = ?, price = ? WHERE id = ?");
            return $stmt->execute([$categoryId, $name, $description, $price, $id]);
        }
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
