<?php
$pdo = new PDO('mysql:host=localhost;dbname=x91147go_base', 'x91147go_base', 'Sport_for!u34');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
require_once 'auth_check.php';
$errors = [];
$result_id = trim($_POST['result_id'] ?? $_GET['result_id'] ?? '');
$result = null;

if (!empty($result_id)) {
    $stmt = $pdo->prepare("SELECT * FROM Results WHERE result_id = ?");
    $stmt->execute([$result_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$result) { $errors['result_id'] = "Результат не найден."; $result_id = ''; }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_save'])) {
    $competition_id = trim($_POST['competition_id'] ?? '');
    $athlete_id = trim($_POST['athlete_id'] ?? '');
    $place = trim($_POST['place'] ?? '');
    $result_value = trim($_POST['result_value'] ?? '');
    $result_unit = trim($_POST['result_unit'] ?? '');
    $medal = trim($_POST['medal'] ?? '');
    $record_status = trim($_POST['record_status'] ?? '');

    if(empty($competition_id) || empty($athlete_id)){ $errors['ids'] = 'Заполните ID соревнований и спортсмена'; }

    if (empty($errors)) {
        try {
            $pdo->prepare("UPDATE Results SET competition_id=?, athlete_id=?, place=?, result_value=?, result_unit=?, medal=?, record_status=? WHERE result_id=?")
                ->execute([$competition_id, $athlete_id, $place ?: null, $result_value, $result_unit, $medal, $record_status, $result_id]);
            header("Location: results.php?msg=updated");
            exit;
        } catch(PDOException $e) { $errors['db'] = $e->getMessage(); }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head><meta charset='utf-8'><title>Редактирование результатов</title><style>.error-box { color: #721c24; background-color: #f8d7da; padding: 10px; margin: 10px auto; width: 50%; } .btn { padding: 6px 12px; text-decoration: none; } .btn-primary { background-color: #007bff; color: white; border: none; } .btn-secondary { background-color: #6c757d; color: white; margin-left: 10px; }</style></head>
<body>
<div align="center">
    <h1>Редактирование результата</h1>
    <?php if(!empty($errors)): ?><div class="error-box"><ul><?php foreach($errors as $e): ?><li><?=htmlspecialchars($e)?></li><?php endforeach; ?></ul></div><?php endif; ?>

    <?php if (empty($result_id)): ?>
        <form method="POST">
            <table>
                <tr><td>Введите ID результата:</td><td><input type="number" name="result_id" required></td></tr>
                <tr><td colspan="2" align="center"><button type="submit" class="btn btn-primary">Найти</button><a href="competitions.php" class="btn btn-secondary">Отмена</a></td></tr>
            </table>
        </form>
    <?php else: ?>
        <form method="POST">
            <input type="hidden" name="result_id" value="<?=htmlspecialchars($result_id)?>">
            <input type="hidden" name="action_save" value="1">
            <table>
                <tr><td>ID Соревнования:</td><td><input type="number" name="competition_id" required value="<?=htmlspecialchars($_POST['competition_id'] ?? $result['competition_id'] ?? '')?>"></td></tr>
                <tr><td>ID Спортсмена:</td><td><input type="number" name="athlete_id" required value="<?=htmlspecialchars($_POST['athlete_id'] ?? $result['athlete_id'] ?? '')?>"></td></tr>
                <tr><td>Занятое место:</td><td><input type="number" name="place" value="<?=htmlspecialchars($_POST['place'] ?? $result['place'] ?? '')?>"></td></tr>
                <tr><td>Значение результата:</td><td><input type="text" name="result_value" value="<?=htmlspecialchars($_POST['result_value'] ?? $result['result_value'] ?? '')?>"></td></tr>
                <tr><td>Ед. измерения:</td><td><input type="text" name="result_unit" value="<?=htmlspecialchars($_POST['result_unit'] ?? $result['result_unit'] ?? '')?>"></td></tr>
                <tr><td>Медаль:</td><td><input type="text" name="medal" value="<?=htmlspecialchars($_POST['medal'] ?? $result['medal'] ?? '')?>"></td></tr>
                <tr><td>Статус рекорда:</td><td><input type="text" name="record_status" value="<?=htmlspecialchars($_POST['record_status'] ?? $result['record_status'] ?? '')?>"></td></tr>
                <tr><td colspan="2" align="center"><button type="submit" class="btn btn-primary">Сохранить</button><a href="competitions.php" class="btn btn-secondary">Отмена</a></td></tr>
            </table>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
