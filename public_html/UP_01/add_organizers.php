<?php
$pdo = new PDO('mysql:host=localhost;dbname=x91147go_base', 'x91147go_base', 'Sport_for!u34');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$errors = [];
require_once 'auth_check.php';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = trim($_POST['name'] ?? '');
    $contact_person = trim($_POST['contact_person'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');

    // Валидация заполнения полей
    if(empty($name)){ $errors['name'] = 'Заполните обязательные поля'; }
    if(empty($contact_person)){ $errors['contact_person'] = 'Заполните обязательные поля'; }
    if(empty($phone)){ $errors['phone'] = 'Заполните обязательные поля'; }
    if(empty($address)){ $errors['address'] = 'Заполните обязательные поля'; }
    
    // Дополнительная валидация формата Email
    if(empty($email)){ 
        $errors['email'] = 'Заполните обязательные поля'; 
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Введите корректный адрес электронной почты';
    }

    if(empty($errors)) {
        // Проверка существования организатора с таким же email
        $stmt = $pdo->prepare("SELECT * FROM Organizers WHERE email = ?");
        $stmt->execute([$email]); 
        
        if ($stmt->fetch()) {
            $errors['email'] = 'Запись с таким email уже существует';
        } else {
            // Исправлено: опечатка в названии колонки organzier_id -> organizer_id
            $sql = "INSERT INTO Organizers (name, contact_person, phone, email, address) VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            
            try {
                $stmt->execute([$name, $contact_person, $phone, $email, $address]);
                header("Location: organizers.php");
                exit;
            } catch(PDOException $e) {
                $errors['db'] = 'Ошибка базы данных: ' . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset='utf-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Форма добавления организаторов</title>
    <link rel='stylesheet' href="styles_competitions.css">
    <style>
        .error-box { color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 10px; margin: 10px auto; width: 50%; border-radius: 4px; text-align: left; }
        table { margin-top: 20px; }
        td { padding: 5px; }
    </style>
</head>
<body>
    <h1>Информация об организаторах</h1>
    <div align="center">
        <h2>Форма добавления организаторов</h2>
        
        <!-- Вывод списка ошибок в красивом блоке -->
        <?php if(!empty($errors)): ?>
            <div class="error-box">
                <strong>Пожалуйста, исправьте следующие ошибки:</strong>
                <ul>
                    <?php foreach($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST">
            <table>
                <tr>
                    <td align="right">Имя (Название)</td>
                    <td><input type="text" name="name" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">Контактное лицо</td>
                    <td><input type="text" name="contact_person" required value="<?= htmlspecialchars($_POST['contact_person'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">Номер телефона</td>
                    <td><input type="tel" name="phone" required value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">Email</td>
                    <!-- Исправлено: изменен тип на email для базовой браузерной проверки -->
                    <td><input type="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">Адрес</td>
                    <td><input type="text" name="address" required value="<?= htmlspecialchars($_POST['address'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <button type="submit">Добавить организатора</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>
