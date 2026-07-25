<?php
$pdo = new PDO('mysql:host=localhost;dbname=x91147go_base', 'x91147go_base', 'Sport_for!u34');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$errors = [];
require_once 'auth_check.php';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $sport_name = trim($_POST['sport_name'] ?? '');
    $sport_category = trim($_POST['sport_category'] ?? '');
    $descr = trim($_POST['description'] ?? '');
    $olympic_sport = trim($_POST['olympic_sport'] ?? '');
    $sport_img = trim($_POST['sport_image'] ?? ''); // исправлено на trainer_photo

    if(empty($sport_name)){ $errors['sport_name'] = 'Заполните название Спорта'; }
    if(empty($sport_category)){ $errors['sport_category'] = 'Заполните категорию спорта'; }
    if(empty($descr)){ $errors['description'] = 'Заполните поле Описание'; }
    if(empty($olympic_sport)){ $errors['olympic_sport'] = 'Заполните значение олимпийского спорта'; }

    if(empty($errors)) {
        // Проверка существования пользователя
        $stmt = $pdo->prepare("SELECT * FROM Sports WHERE sport_name = ?");
        $stmt->execute([$sport_name]); 
        
        if ($stmt->fetch()) {
            $errors['sport_name'] = 'Пользователь с таким sport_name уже существует';
        } else {
            $sql = "INSERT INTO Sports (sport_name, sport_category, description, olympic_sport, sport_image) VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$sport_name, $sport_category, $descr, $olympic_sport, $sport_img]);
            header("Location: sports.php");
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
    <title>Форма добавления спорта</title>
    <link rel='stylesheet' href="styles_competitions.css">
    <style>
        .error { color: red; font-size: 0.9em; }
    </style>
</head>
<body>
    <h1>Информация о видах спорта</h1>
    <div align="center">
        <h2>Форма добавления видов спорта</h2>
        
        <!-- Вывод общих ошибок (по желанию) -->
        <?php if(!empty($errors)): ?>
            <div style="color: red;">
                <?php foreach($errors as $error) { echo "<p>$error</p>"; } ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <table>
                <tr>
                    <td align="right">Название спорта</td>
                    <td><input type="text" name="sport_name" required value="<?= htmlspecialchars($_POST['sport_name'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">Категория спорта</td>
                    <td><input type="text" name="sport_category" required value="<?= htmlspecialchars($_POST['sport_category'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">Описание</td>
                    <td><input type="text" name="description" required value="<?= htmlspecialchars($_POST['description'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">Олимпийский спорт</td>
                    <td><input type="number" name="olympic_sport" required value="<?= htmlspecialchars($_POST['olympic_sport'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">Изображение спорта</td>
                    <td><input type="file" name="sport_image" value="<?= htmlspecialchars($_POST['sport_image'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <button type="submit">Добавить спорт</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>