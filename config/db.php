<?php
// config/db.php

$host = 'db'; // Имя сервиса в docker-compose
$dbname = 'diplom_shop';
$user = 'user';
$password = 'password';

$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Бросать исключения при ошибках
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Возвращать ассоциативные массивы
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Использовать нативные подготовленные запросы
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4'"   // Устанавливаем кодировку для соединения
];

try {
    $pdo = new PDO($dsn, $user, $password, $options);
} catch (PDOException $e) {
    // В продакшене лучше писать ошибку в лог, а не выводить на экран
    die("Ошибка подключения к базе данных. Пожалуйста, обратитесь к администратору.");
}
