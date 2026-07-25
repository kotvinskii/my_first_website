<?php
session_start();
include 'connect.php';

// Удаляем токен из БД
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("UPDATE tUsers SET RememberToken = NULL WHERE fidUser = ?");
    $stmt->execute([$_SESSION['user_id']]);
}

// Очищаем сессию
$_SESSION = array();

// Удаляем cookie
if (isset($_COOKIE['remember_token'])) {
    setcookie('remember_token', '', time() - 3600, "/");
}

// Уничтожаем сессию
session_destroy();

// Перенаправляем на вход
header("Location: index.php");
exit();
?>