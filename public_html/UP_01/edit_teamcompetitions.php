<?php
$pdo = new PDO('mysql:host=localhost;dbname=x91147go_base', 'x91147go_base', 'Sport_for!u34');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
require_once 'auth_check.php';
$errors = [];
$team_competition_id = trim($_POST['team_competition_id'] ?? $_GET['team_competition_id'] ?? '');
$tc = null;

if (!empty($team_competition_id)) {
    $stmt = $pdo->prepare("SELECT * FROM TeamCompetitions WHERE team_competition_id = ?");
    $stmt->execute([$team_competition_id]);
    $tc = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$tc) { $errors['id'] = "Запись не найдена."; $team_competition_id = ''; }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_save'])) {
    $competition_id = trim($_POST['competition_id'] ?? '');
    $team_id = trim($_POST['team_id'] ?? '');
    $place = trim($_POST['place'] ?? '');
    $result = trim($_POST['result'] ?? '');

    if(empty($competition_id) || empty($team_id)){ $errors['fields'] = 'Заполните ID соревнований и команды'; }

    if (empty($errors)) {
        try {
            $pdo->prepare("UPDATE TeamCompetitions SET competition_id=?, team_id=?, place=?, result=? WHERE team_competition_id=?")
                ->execute([$competition_id, $team_id, $place ?: null, $result, $team_competition_id]);
            header("Location: teamcompetitions.php?msg=updated");
            exit;
        } catch(PDOException $e) { $errors['db'] = $e->getMessage(); }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head><meta charset='utf-8'><title>Редактирование командных результатов</title><style>.error-box { color: #721c24; background-color: #f8d7da; padding: 10px; margin: 10px auto; width: 50%; } .btn { padding: 6px 12px; text-decoration: none; } .btn-primary { background-color: #007bff; color: white; border: none; } .btn-secondary { background-color: #6c757d; color: white; margin-left: 10px; }</style></head>
<body>
<div align="center">
    <h1>Редактирование командного результата матча</h1>
    <?php if(!empty($errors)): ?><div class="error-box"><ul><?php foreach($errors as $e): ?><li><?=htmlspecialchars($e)?></li><?php endforeach; ?></ul></div><?php endif; ?>

    <?php if (empty($team_competition_id)): ?>
        <form method="POST">
            <table>
                <tr><td>Введите ID записи командного матча:</td><td><input type="number" name="team_competition_id" required></td></tr>
                <tr><td colspan="2" align="center"><button type="submit" class="btn btn-primary">Найти</button><a href="competitions.php" class="btn btn-secondary">Отмена</a></td></tr>
            </table>
        </form>
    <?php else: ?>
        <form method="POST">
            <input type="hidden" name="team_competition_id" value="<?=htmlspecialchars($team_competition_id)?>">
            <input type="hidden" name="action_save" value="1">
            <table>
                <tr><td>ID Соревнования:</td><td><input type="number" name="competition_id" required value="<?=htmlspecialchars($_POST['competition_id'] ?? $tc['competition_id'] ?? '')?>"></td></tr>
                <tr><td>ID Команды:</td><td><input type="number" name="team_id" required value="<?=htmlspecialchars($_POST['team_id'] ?? $tc['team_id'] ?? '')?>"></td></tr>
                <tr><td>Занятое место:</td><td><input type="number" name="place" value="<?=htmlspecialchars($_POST['place'] ?? $tc['place'] ?? '')?>"></td></tr>
                <tr><td>Результат:</td><td><input type="text" name="result" value="<?=htmlspecialchars($_POST['result'] ?? $tc['result'] ?? '')?>"></td></tr>
                <tr><td colspan="2" align="center"><button type="submit" class="btn btn-primary">Сохранить</button><a href="competitions.php" class="btn btn-secondary">Отмена</a></td></tr>
            </table>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
