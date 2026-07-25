<?php
$host = 'localhost';
$dbname = 'x91147go_base';
$username = 'x91147go_base';
$password = 'Sport_for!u34';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
try {
    // Все текстовые параметры теперь обернуты в одинарные кавычки
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8", // Здесь нужны двойные кавычки для работы переменных внутри строки
        $username, 
        $password, 
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch(PDOException $e) {
    // Текст ошибки обернут в кавычки
    die("Ошибка подключения: " . $e->getMessage());
}
?>
