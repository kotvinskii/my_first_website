<?php
$pdo = new PDO('mysql:host=localhost;dbname=x91147go_base', 'x91147go_base', 'Sport_for!u34');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$errors = [];
require_once 'auth_check.php';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $comp_id = trim($_POST['competition_id'] ?? '');
    $team_id = trim($_POST['team_id'] ?? '');
    $place = trim($_POST['place'] ?? '');
    $result = trim($_POST['result'] ?? '');

    // Валидация заполнения полей
    if(empty($comp_id)){ $errors['competition_id'] = 'Заполните обязательные поля'; }
    if(empty($team_id)){ $errors['team_id'] = 'Заполните обязательные поля'; }
    if(empty($place)){ $errors['place'] = 'Заполните обязательные поля'; }
    if(empty($result)){ $errors['result'] = 'Заполните обязательные поля'; }

    if(empty($errors)) {
        // Проверка существования организатора с таким же email
        $stmt = $pdo->prepare("SELECT competition_id, team_id, place, result FROM TeamCompetitions WHERE team_id = ?");
        $stmt->execute([$team_id]); 
        
        if ($stmt->fetch()) {
            $errors['team_id'] = 'Запись с таким team_id уже существует';
        } else {
            // Исправлено: опечатка в названии колонки organzier_id -> organizer_id
            $sql = "INSERT INTO TeamCompetitions (competition_id, team_id, place, result) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            
            try {
                $stmt->execute([$comp_id, $team_id, $place, $result]);
                header("Location: teamcompetitions.php");
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
    <title>Форма добавления командных соревнований</title>
    <link rel='stylesheet' href="styles_competitions.css">
    <style>
        .error-box { color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 10px; margin: 10px auto; width: 50%; border-radius: 4px; text-align: left; }
        table { margin-top: 20px; }
        td { padding: 5px; }
    </style>
</head>
<body>
    <h1>Информация о командных соревнований</h1>
    <div align="center">
        <h2>Форма добавления командных соревнований</h2>
        
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
                    <td align="right">ID соревнований</td>
                    <td><input type="number" name="competition_id" min="1" required value="<?= htmlspecialchars($_POST['competition_id'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">ID команды</td>
                    <td><input type="number" name="team_id" min="1" required value="<?= htmlspecialchars($_POST['team_id'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">Место</td>
                    <td><input type="number" name="place" min="1" required value="<?= htmlspecialchars($_POST['place'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">Результат</td>
                    <!-- Исправлено: изменен тип на email для базовой браузерной проверки -->
                    <td><input type="text" name="result" required value="<?= htmlspecialchars($_POST['result'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <button type="submit">Добавить командные соревнования</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>