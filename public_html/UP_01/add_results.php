<?php
$pdo = new PDO('mysql:host=localhost;dbname=x91147go_base', 'x91147go_base', 'Sport_for!u34');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$errors = [];
require_once 'auth_check.php';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $comp_id = trim($_POST['competition_id'] ?? '');
    $athlete_id = trim($_POST['athlete_id'] ?? '');
    $place = trim($_POST['place'] ?? '');
    $result_value = trim($_POST['result_value'] ?? '');
    $res_unit = trim($_POST['result_unit'] ?? '');
    $medal = trim($_POST['medal'] ?? '');
    $rec_status = trim($_POST['record_status'] ?? '');

    // Валидация (исправлена проверка на пустую строку для полей, где возможен 0)
    if($comp_id === ''){ $errors['competition_id'] = 'Заполните обязательные поля'; }
    if($athlete_id === ''){ $errors['athlete_id'] = 'Заполните обязательные поля'; }
    if($place === ''){ $errors['place'] = 'Заполните обязательные поля'; }
    if($result_value === ''){ $errors['result_value'] = 'Заполните обязательные поля'; }
    if($res_unit === ''){ $errors['result_unit'] = 'Заполните обязательные поля'; }
    if($medal === ''){ $errors['medal'] = 'Заполните обязательные поля'; }
    if($rec_status === ''){ $errors['record_status'] = 'Заполните обязательные поля'; }

    if(empty($errors)) {
        // Проверка существования записи по ID результата
        $stmt = $pdo->prepare("SELECT * FROM Results WHERE result_id = ?");
        $stmt->execute([$res_id]); 
        
        if ($stmt->fetch()) {
            $errors['result_id'] = 'Запись с таким id уже существует';
        } else {
            $sql = "INSERT INTO Results (competition_id, athlete_id, place, result_value, result_unit, medal, record_status) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            
            try {
                // Исправлено: передаются правильные переменные вместо клубных
                $stmt->execute([$comp_id, $athlete_id, $place, $result_value, $res_unit, $medal, $rec_status]);
                header("Location: results.php");
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
    <title>Форма добавления результата</title>
    <link rel='stylesheet' href="styles_competitions.css">
    <style>
        .error-box { color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 10px; margin: 10px auto; width: 50%; border-radius: 4px; text-align: left; }
        table { margin-top: 20px; }
        td { padding: 5px; }
    </style>
</head>
<body>
    <h1>Информация о результатах</h1>
    <div align="center">
        <h2>Форма добавления результатов</h2>
        
        <!-- Вывод списка ошибок -->
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
                    <td><input type="number" name="competition_id" min="1" step="1" required value="<?= htmlspecialchars($_POST['competition_id'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">ID спортсмена</td>
                    <td><input type="number" name="athlete_id" min="1" step="1" required value="<?= htmlspecialchars($_POST['athlete_id'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">Место</td>
                    <td><input type="number" name="place" min="1" step="1" required value="<?= htmlspecialchars($_POST['place'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">Результат</td>
                    <td><input type="text" name="result_value" required value="<?= htmlspecialchars($_POST['result_value'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">Тип результата</td>
                    <td><input type="text" name="result_unit" required value="<?= htmlspecialchars($_POST['result_unit'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">Медаль</td>
                    <td><input type="text" name="medal" required value="<?= htmlspecialchars($_POST['medal'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td align="right">Статус записи</td>
                    <!-- Исправлено: добавлен обязательный атрибут required -->
                    <td><input type="number" name="record_status" min="0" max="1" step="1" required value="<?= htmlspecialchars($_POST['record_status'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <button type="submit">Добавить результат</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>
