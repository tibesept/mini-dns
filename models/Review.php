<?php
// models/Review.php

class Review {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getApprovedByProductId($productId) {
        $stmt = $this->pdo->prepare("SELECT * FROM reviews WHERE product_id = ? AND is_approved = 1 ORDER BY created_at DESC");
        $stmt->execute([$productId]);
        return $stmt->fetchAll();
    }

    public function getAverageRating($productId) {
        $stmt = $this->pdo->prepare("SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews FROM reviews WHERE product_id = ? AND is_approved = 1");
        $stmt->execute([$productId]);
        return $stmt->fetch();
    }

    public function add($productId, $authorName, $text, $rating) {
        $stmt = $this->pdo->prepare("INSERT INTO reviews (product_id, author_name, review_text, rating, is_approved) VALUES (?, ?, ?, ?, 0)");
        return $stmt->execute([$productId, $authorName, $text, $rating]);
    }
}
