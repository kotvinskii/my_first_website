<?php
// Проверяем, запущены ли сессии. Если нет — запускаем
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Получаем имя текущего файла (например: 'login.php' или 'register.php')
$current_page = basename($_SERVER['PHP_SELF']);

// Список страниц, к которым разрешен доступ БЕЗ авторизации
$allowed_pages = ['authorization_user.php', 'validation.php', 'index.php', 'auth_check.php']; 

// Если пользователь НЕ авторизован и пытается зайти на любую другую страницу
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    
    // Если текущая страница НЕ находится в списке разрешенных
    if (!in_array($current_page, $allowed_pages)) {
        // Перенаправляем на страницу авторизации
        header('Location: index.php');
        exit(); // Останавливаем выполнение скрипта
    }
}
