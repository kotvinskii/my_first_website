<?php
$pdo = new PDO('mysql:host=localhost;dbname=x91147go_base', 'x91147go_base', 'Sport_for!u34');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
require_once 'auth_check.php';
$errors = [];
$team_id = trim($_POST['team_id'] ?? $_GET['team_id'] ?? '');
$team = null;

if (!empty($team_id)) {
    $stmt = $pdo->prepare("SELECT * FROM Teams WHERE team_id = ?");
    $stmt->execute([$team_id]);
    $team = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$team) { $errors['team_id'] = "Команда не найдена."; $team_id = ''; }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_save'])) {
    $team_name = trim($_POST['team_name'] ?? '');
    $sport_id = trim($_POST['sport_id'] ?? '');
    $club_id = trim($_POST['club_id'] ?? '');
    $coach_id = trim($_POST['coach_id'] ?? '');

    if(empty($team_name)){ $errors['team_name'] = 'Заполните название команды'; }

    if (empty($errors)) {
        try {
            $pdo->prepare("UPDATE Teams SET team_name=?, sport_id=?, club_id=?, coach_id=? WHERE team_id=?")
                ->execute([$team_name, $sport_id ?: null, $club_id ?: null, $coach_id ?: null, $team_id]);
            header("Location: teams.php?msg=updated");
            exit;
        } catch(PDOException $e) { $errors['db'] = $e->getMessage(); }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head><meta charset='utf-8'><title>Редактирование команды</title><style>.error-box { color: #721c24; background-color: #f8d7da; padding: 10px; margin: 10px auto; width: 50%; } .btn { padding: 6px 12px; text-decoration: none; } .btn-primary { background-color: #007bff; color: white; border: none; } .btn-secondary { background-color: #6c757d; color: white; margin-left: 10px; }</style></head>
<body>
<div align="center">
    <h1>Редактирование команды</h1>
    <?php if(!empty($errors)): ?><div class="error-box"><ul><?php foreach($errors as $e): ?><li><?=htmlspecialchars($e)?></li><?php endforeach; ?></ul></div><?php endif; ?>

    <?php if (empty($team_id)): ?>
        <form method="POST">
            <table>
                <tr><td>Введите ID команды:</td><td><input type="number" name="team_id" required></td></tr>
                <tr><td colspan="2" align="center"><button type="submit" class="btn btn-primary">Найти</button><a href="competitions.php" class="btn btn-secondary">Отмена</a></td></tr>
            </table>
        </form>
    <?php else: ?>
        <form method="POST">
            <input type="hidden" name="team_id" value="<?=htmlspecialchars($team_id)?>">
            <input type="hidden" name="action_save" value="1">
            <table>
                <tr><td>Название команды:</td><td><input type="text" name="team_name" required value="<?=htmlspecialchars($_POST['team_name'] ?? $team['team_name'] ?? '')?>"></td></tr>
                <tr><td>ID Спорта:</td><td><input type="number" name="sport_id" value="<?=htmlspecialchars($_POST['sport_id'] ?? $team['sport_id'] ?? '')?>"></td></tr>
                <tr><td>ID Клуба:</td><td><input type="number" name="club_id" value="<?=htmlspecialchars($_POST['club_id'] ?? $team['club_id'] ?? '')?>"></td></tr>
                <tr><td>ID Тренера (Coach ID):</td><td><input type="number" name="coach_id" value="<?=htmlspecialchars($_POST['coach_id'] ?? $team['coach_id'] ?? '')?>"></td></tr>
                <tr><td colspan="2" align="center"><button type="submit" class="btn btn-primary">Сохранить</button><a href="competitions.php" class="btn btn-secondary">Отмена</a></td></tr>
            </table>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
