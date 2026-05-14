-- init.sql
-- Инициализация базы данных магазина
SET NAMES utf8mb4;
CREATE TABLE IF NOT EXISTS `categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `products` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `category_id` INT NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `price` DECIMAL(10, 2) NOT NULL,
    `image` VARCHAR(255) DEFAULT 'default.png',
    FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `reviews` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `product_id` INT NOT NULL,
    `author_name` VARCHAR(255) NOT NULL,
    `review_text` TEXT NOT NULL,
    `rating` TINYINT NOT NULL CHECK (`rating` >= 1 AND `rating` <= 5),
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `is_approved` TINYINT(1) DEFAULT 0,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `orders` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `customer_name` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(50) NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `status` ENUM('new', 'processing', 'completed', 'cancelled') DEFAULT 'new'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `order_items` (
    `order_id` INT NOT NULL,
    `product_id` INT NOT NULL,
    `quantity` INT NOT NULL,
    `price_at_purchase` DECIMAL(10, 2) NOT NULL,
    PRIMARY KEY (`order_id`, `product_id`),
    FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Тестовые данные (пароль админа 'admin123')
INSERT INTO `users` (`username`, `password_hash`) VALUES
('admin', '$2y$10$x.IxKwgX6/tr0fOY14wAweiTyY2FG8Xqb/AYdb6Dft7L9vmRgJ3KK');

INSERT INTO `categories` (`name`) VALUES 
('Процессоры'), 
('Материнские платы'), 
('Видеокарты'), 
('Оперативная память');

INSERT INTO `products` (`category_id`, `name`, `description`, `price`, `image`) VALUES 
(1, 'AMD Ryzen 5 5600X', 'Отличный процессор для игр', 15000.00, 'ryzen5.png'),
(1, 'Intel Core i5-12400F', 'Народный выбор от Intel', 14500.00, 'i5.png'),
(3, 'NVIDIA GeForce RTX 3060', 'Видеокарта для Full HD', 32000.00, 'rtx3060.png'),
(4, 'Kingston FURY Beast 16GB', 'Надежная оперативная память', 4500.00, 'ram.png');

INSERT INTO `reviews` (`product_id`, `author_name`, `review_text`, `rating`, `is_approved`) VALUES
(1, 'Иван', 'Топ за свои деньги!', 5, 1),
(3, 'Алексей', 'Тянет все игры, но шумновата', 4, 1);
