<?php
require_once 'auth_check.php';
session_start();
require_once 'connect.php';
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $patronymic = trim($_POST['patronymic'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');
    $birth_date = trim($_POST['birth_date'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    // Валидация
    if (empty($first_name)) { $errors['first_name'] = 'Заполните это поле'; }
    if (empty($last_name)) { $errors['last_name'] = 'Заполните это поле'; }
    if (empty($patronymic)) { $errors['patronymic'] = 'Заполните это поле'; }
    if (empty($email)) { $errors['email'] = 'Заполните это поле'; }
    if (empty($password)) { $errors['password'] = 'Заполните это поле'; }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Неверный формат email';
    }
    if (strlen($password) < 6) {
        $errors['password'] = 'Пароль должен быть не менее 6 символов';
    }
    if ($password !== $confirm_password) {
        $errors['confirm_password'] = 'Пароли не совпадают';
    }

    if (empty($errors)) {
        // Проверка существования пользователя
        $stmt = $pdo->prepare("SELECT user_id FROM tUsers WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->fetch()) {
            $errors['email'] = 'Пользователь с таким email уже существует';
        } else {
            // Хешируем пароль
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Исправлено: берем первый элемент массива (строку) в качестве логина
            $email_parts = explode('@', $email);
            $login = $email_parts[0]; 
    
            // Вставляем данные в БД (строго 8 полей и 8 плейсхолдеров)
            $sql = "INSERT INTO tUsers (login, password, first_name, last_name, patronymic, email, birth_date, fidRole) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, 1)";
            $stmt = $pdo->prepare($sql);
            
            // Исправлено: передаем ровно 7 параметров в массив, так как 8-й параметр (fidRole) жестко прописан как цифра 1 в самом SQL
            $stmt->execute([
                $login, 
                $hashed_password, 
                $first_name, 
                $last_name, 
                $patronymic, 
                $email, 
                $birth_date
            ]);
    
            // Успешная регистрация, перенаправление на страницу авторизации
            header("Location: authorization_user.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
    <style>
        .error { color: red; font-size: 0.9em; }
    </style>
</head>
<body>
<div align="center">
    <h2>Регистрация</h2>

    <!-- Вывод общих ошибок -->
    <?php if(!empty($errors)): ?>
        <div style="color: red;">
            <?php foreach($errors as $error) { echo "<p>$error</p>"; } ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <table>
            <tr>
                <td align="right">Имя (обязательное поле)</td>
                <td><input type="text" name="first_name" required value="<?= htmlspecialchars($_POST['first_name'] ?? '') ?>"></td>
            </tr>
            <tr>
                <td align="right">Фамилия (обязательное поле)</td>
                <td><input type="text" name="last_name" required value="<?= htmlspecialchars($_POST['last_name'] ?? '') ?>"></td>
            </tr>
            <tr>
                <td align="right">Отчество (обязательное поле)</td>
                <td><input type="text" name="patronymic" required value="<?= htmlspecialchars($_POST['patronymic'] ?? '') ?>"></td>
            </tr>
            <tr>
                <td align="right">Email (обязательное поле)</td>
                <td><input type="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"></td>
            </tr>
            <tr>
                <td align="right">Пароль (обязательное поле)</td>
                <td><input type="password" name="password" required></td>
            </tr>
            <tr>
                <td align="right">Подтвердите пароль (обязательное поле)</td>
                <td><input type="password" name="confirm_password" required></td>
            </tr>
            <tr>
                <td align="right">Телефон</td>
                <td><input type="tel" name="phone" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"></td>
            </tr>
            <tr>
                <td align="right">Дата рождения</td>
                <td><input type="date" name="birth_date" max="<?= date('Y-m-d') ?>" value="<?= htmlspecialchars($_POST['birth_date'] ?? '') ?>"></td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <br>
                    <button type="submit">Зарегистрироваться</button>
                </td>
            </tr>
        </table>
    </form>

    <a href="authorization_user.php">Уже есть аккаунт? Войти</a>
</div>
</body>
</html>



