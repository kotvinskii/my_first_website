<?php
$pdo = new PDO('mysql:host=localhost;dbname=x91147go_base', 'x91147go_base', 'Sport_for!u34');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$errors = [];
require_once 'auth_check.php';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $club_name = trim($_POST['club_name'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $founded_year = trim($_POST['founded_year'] ?? '');
    $president = trim($_POST['president'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $club_img = trim($_POST['sportclub_image'] ?? ''); // исправлено на trainer_photo

    // Валидация
    if(empty($club_name)){ $errors['club_name'] = 'Заполните обязательные поля'; }
    if(empty($city)){ $errors['city'] = 'Заполните обязательные поля'; }
    if(empty($founded_year)){ $errors['founded_year'] = 'Заполните обязательные поля'; }
    if(empty($president)){ $errors['president'] = 'Заполните обязательные поля'; }
    if(empty($phone)){ $errors['phone'] = 'Заполните обязательные поля'; }
    if(empty($email)){ $errors['year_built'] = 'Заполните обязательные поля'; }

    if(empty($errors)) {
        // Проверка существования пользователя
        $stmt = $pdo->prepare("SELECT * FROM SportsClubs WHERE email = ?");
        $stmt->execute([$email]); 
        
        if ($stmt->fetch()) {
            $errors['email'] = 'Пользователь с таким name уже существует';
        } else {
            $sql = "INSERT INTO SportsClubs (club_name, city, founded_year, president, phone, email, sportclub_image) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$club_name, $city, $founded_year, $president, $phone, $email, $club_img]);
            header("Location: sports_clubs.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset='utf-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Форма добавления спортклуба</title>
    <link rel='stylesheet' href="styles_competitions.css">
    <style>
        .error { color: red; font-size: 0.9em; }
    </style>
</head>
<body>
    <h1>Информация о клубах</h1>
    <div align="center">
        <h2>Форма добавления клубов</h2>
        
        <!-- Вывод общих ошибок (по желанию) -->
        <?php if(!empty($errors)): ?>
            <div style="color: red;">
                <?php foreach($errors as $error) { echo "<p>$error</p>"; } ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <table>
                <tr>
                    <td align="right">Название клуба</td>
                    <td><input type="text" name="club_name" required value="<?= htmlspecialchars($_POST['club_name'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">Город</td>
                    <td><input type="city" name="city" required value="<?= htmlspecialchars($_POST['city'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">Год основания</td>
                    <td><input type="number" min="1900" max="2026" step="1" name="founded_year" required value="<?= htmlspecialchars($_POST['founded_year'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">Президент</td>
                    <td><input type="text" name="president" required value="<?= htmlspecialchars($_POST['president'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">Телефон для контакта</td>
                    <td><input type="tel" name="phone" required value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">Email</td>
                    <td><input type="text" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">Изображение клуба</td>
                    <td><input type="file" name="sportclub_image" value="<?= htmlspecialchars($_POST['sportclub_image'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <button type="submit">Добавить клуб</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>