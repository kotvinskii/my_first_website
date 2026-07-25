<?php
$pdo = new PDO('mysql:host=localhost;dbname=x91147go_base', 'x91147go_base', 'Sport_for!u34');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$errors = [];
require_once 'auth_check.php';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $team_name = trim($_POST['team_name'] ?? '');
    $sport_id = trim($_POST['sport_id'] ?? '');
    $club_id = trim($_POST['club_id'] ?? '');
    $coach_id = trim($_POST['coach_id'] ?? '');
    // Валидация заполнения полей
    if(empty($team_name)){ $errors['team_name'] = 'Заполните обязательные поля'; }
    if(empty($sport_id)){ $errors['sport_id'] = 'Заполните обязательные поля'; }
    if(empty($club_id)){ $errors['club_id'] = 'Заполните обязательные поля'; }
    if(empty($coach_id)){ $errors['coach_id'] = 'Заполните обязательные поля'; }
    
    if(empty($errors)) {
        // Проверка существования организатора с таким же email
        $stmt = $pdo->prepare("SELECT * FROM Teams WHERE team_name = ?");
        $stmt->execute([$team_name]); 
        
        if ($stmt->fetch()) {
            $errors['team_name'] = 'Запись с таким team_name уже существует';
        } else {
            // Исправлено: опечатка в названии колонки organzier_id -> organizer_id
            $sql = "INSERT INTO Teams (team_name, sport_id, club_id, coach_id) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            
            try {
                $stmt->execute([$team_name, $sport_id, $club_id, $coach_id]);
                header("Location: teams.php");
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
    <title>Форма добавления команд</title>
    <link rel='stylesheet' href="styles_competitions.css">
    <style>
        .error-box { color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 10px; margin: 10px auto; width: 50%; border-radius: 4px; text-align: left; }
        table { margin-top: 20px; }
        td { padding: 5px; }
    </style>
</head>
<body>
    <h1>Информация о командах</h1>
    <div align="center">
        <h2>Форма добавления команд</h2>
        
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
                    <td align="right">Название команды</td>
                    <td><input type="text" name="team_name" required value="<?= htmlspecialchars($_POST['team_name'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">ID спорта</td>
                    <td><input type="number" name="sport_id" min="1" required value="<?= htmlspecialchars($_POST['sport_id'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">ID клуба</td>
                    <td><input type="number" name="club_id" min="1" required value="<?= htmlspecialchars($_POST['club_id'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">ID тренера</td>
                    <!-- Исправлено: изменен тип на email для базовой браузерной проверки -->
                    <td><input type="number" name="coach_id" min="1" required value="<?= htmlspecialchars($_POST['coach_id'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <button type="submit">Добавить команду</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>