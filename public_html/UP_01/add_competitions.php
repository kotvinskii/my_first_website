<?php
$pdo = new PDO('mysql:host=localhost;dbname=x91147go_base', 'x91147go_base', 'Sport_for!u34');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$errors = [];
$success = ''; 
require_once 'auth_check.php';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $comp_name = trim($_POST['competition_name'] ?? '');
    $sport_id = trim($_POST['sport_id'] ?? '');
    $facility_id = trim($_POST['facility_id'] ?? '');
    $org_id = trim($_POST['organizer_id'] ?? ''); 
    $comp_date = trim($_POST['competition_date'] ?? '');
    $comp_level = trim($_POST['competition_level'] ?? '');
    $participants_count = trim($_POST['participants_count'] ?? '');
    $descr = trim($_POST['description'] ?? '');

    // Валидация
    if(empty($comp_name)){ $errors['competition_name'] = 'Заполните обязательные поля'; }
    if(empty($sport_id)){ $errors['sport_id'] = 'Заполните обязательные поля'; }
    if(empty($facility_id)){ $errors['facility_id'] = 'Заполните обязательные поля'; }
    if(empty($org_id)){ $errors['organizer_id'] = 'Заполните обязательные поля'; }
    if(empty($comp_date)){ $errors['competition_date'] = 'Заполните обязательные поля'; }
    if(empty($comp_level)){ $errors['competition_level'] = 'Заполните обязательные поля'; }
    if(empty($participants_count)){ $errors['participants_count'] = 'Заполните обязательные поля'; }
    if(empty($descr)){ $errors['description'] = 'Заполните обязательные поля'; }

    if(empty($errors)) {
        $stmt = $pdo->prepare("SELECT * FROM Competitions WHERE competition_name = ?");
        $stmt->execute([$comp_name]); 
        
        if ($stmt->fetch()) {
            $errors['competition_name'] = 'Запись с таким именем уже существует';
        } else {
            $sql = "INSERT INTO Competitions_new (competition_name, sport_id, facility_id, organizer_id, competition_date, competition_level, participants_count, description) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $pdo->prepare($sql);
            try {
                $stmt->execute([$comp_name, $sport_id, $facility_id, $org_id, $comp_date, $comp_level, $participants_count, $descr]);
                $success = 'Запись успешно добавлена!';
                $_POST = [];
                header("Location: competitions.php");
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
    <title>Форма добавления соревнований</title>
    <link rel='stylesheet' href="styles_competitions.css">
    <style>
        .error-box { color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 10px; margin: 10px auto; width: 50%; border-radius: 4px; text-align: left; }
        .success-box { color: #155724; background-color: #d4edda; border: 1px solid #c3e6cb; padding: 10px; margin: 10px auto; width: 50%; border-radius: 4px; }
        table { margin-top: 20px; }
        td { padding: 5px; }
    </style>
</head>
<body>
    <h1>Информация о соревнованиях</h1>
    <div align="center">
        <h2>Форма добавления соревнований</h2>
        
        <!-- Вывод сообщения об успехе -->
        <?php if(!empty($success)): ?>
            <div class="success-box">
                <p><?= htmlspecialchars($success) ?></p>
            </div>
        <?php endif; ?>

        <!-- Вывод списка ошибок -->
        <?php if(!empty($errors)): ?>
            <div class="error-box">
                <strong>Пожалуйста, исправьте следующие ошибки:</strong>
                <ul>
                    <?php foreach($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?> <!-- Исправлено: теперь корректно закрывает foreach -->
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST">
            <table>
                <tr>
                    <td align="right">Название</td>
                    <td><input type="text" name="competition_name" required value="<?= htmlspecialchars($_POST['competition_name'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">ID спорта</td>
                    <td><input type="number" name="sport_id" min="1" step="1" required value="<?= htmlspecialchars($_POST['sport_id'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">ID объекта</td>
                    <td><input type="number" name="facility_id" min="1" step="1" required value="<?= htmlspecialchars($_POST['facility_id'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">ID организатора</td>
                    <td><input type="number" name="organizer_id" min="1" step="1" required value="<?= htmlspecialchars($_POST['organizer_id'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">Дата соревнований</td>
                    <td><input type="datetime-local" name="competition_date" min="2000-01-01T00:00" max="2026-12-31T23:59" required value="<?= htmlspecialchars($_POST['competition_date'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">Уровень соревнований</td>
                    <td><input type="text" name="competition_level" required value="<?= htmlspecialchars($_POST['competition_level'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">Количество участников</td>
                    <td><input type="number" name="participants_count" min="1" required value="<?= htmlspecialchars($_POST['participants_count'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">Описание</td>
                    <td><input type="text" name="description" required value="<?= htmlspecialchars($_POST['description'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <button type="submit">Добавить соревнование</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>

