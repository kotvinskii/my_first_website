<?php
$pdo = new PDO('mysql:host=localhost;dbname=x91147go_base', 'x91147go_base', 'Sport_for!u34');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$errors = [];
$team_competition_id = trim($_POST['team_competition_id'] ?? $_GET['team_competition_id'] ?? '');
$team_comp = null;
require_once 'auth_check.php';
if (!empty($team_competition_id)) {
    $stmt = $pdo->prepare("SELECT * FROM TeamCompetitions WHERE team_competition_id = ?");
    $stmt->execute([$team_competition_id]);
    $team_comp = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$team_comp) {
        $errors['team_competition_id'] = "Запись командного соревнования с ID $team_competition_id не найдена.";
        $team_competition_id = '';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_delete'])) {
    if (!empty($team_competition_id) && $team_comp) {
        try {
            $stmt = $pdo->prepare("DELETE FROM TeamCompetitions WHERE team_competition_id = ?");
            $stmt->execute([$team_competition_id]);
            header("Location: team_competitions.php?msg=deleted");
            exit;
        } catch(PDOException $e) {
            $errors['db'] = 'Ошибка удаления из базы данных: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset='utf-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Удаление командного результата</title>
    <style>
        .error-box { color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 10px; margin: 10px auto; width: 50%; border-radius: 4px; text-align: left; }
        .warning-box { color: #856404; background-color: #fff3cd; border: 1px solid #ffeeba; padding: 15px; margin: 10px auto; width: 50%; border-radius: 4px; text-align: left; }
        table { margin: 20px auto; border-collapse: collapse; }
        td { padding: 8px; border: 1px solid #ddd; }
        td.label { font-weight: bold; background-color: #f9f9f9; text-align: right; width: 40%; }
        .btn { padding: 8px 16px; text-decoration: none; border-radius: 4px; cursor: pointer; border: 1px solid transparent; font-size: 14px; display: inline-block; }
        .btn-danger { background-color: #dc3545; color: white; border-color: #dc3545; }
        .btn-secondary { background-color: #6c757d; color: white; border-color: #6c757d; margin-left: 10px; }
    </style>
</head>
<body>
<div align="center">
    <h1>Управление результатами команд</h1>
    <?php if(!empty($errors)): ?>
        <div class="error-box">
            <strong>Ошибка:</strong>
            <ul>
                <?php foreach($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (empty($team_competition_id)): ?>
        <h2>Форма удаления командного результата</h2>
        <form method="POST">
            <table style="border: none;">
                <tr style="border: none;">
                    <td style="border: none;" align="right">Введите ID записи для удаления:</td>
                    <td style="border: none;"><input type="number" name="team_competition_id" min="1" required></td>
                </tr>
                <tr style="border: none;">
                    <td colspan="2" align="center" style="border: none;">
                        <br>
                        <button type="submit" class="btn btn-danger">Найти запись</button>
                        <a href="team_competitions.php" class="btn btn-secondary">Отмена</a>
                    </td>
                </tr>
            </table>
        </form>
    <?php else: ?>
        <div class="warning-box">
            <h3 style="margin-top:0; color: #721c24;">⚠️ Предупреждение!</h3>
            <p>Вы действительно хотите безвозвратно удалить следующую запись?</p>
        </div>
        <h3>Информация о командном результате №<?= htmlspecialchars($team_competition_id) ?></h3>
        <table>
            <tr><td class="label">ID Командного результата:</td><td><?= htmlspecialchars($team_comp['team_competition_id']) ?></td></tr>
            <tr><td class="label">ID Соревнования:</td><td><?= htmlspecialchars($team_comp['competition_id']) ?></td></tr>
            <tr><td class="label">ID Команды:</td><td><?= htmlspecialchars($team_comp['team_id']) ?></td></tr>
            <tr><td class="label">Место:</td><td><?= htmlspecialchars($team_comp['place']) ?></td></tr>
            <tr><td class="label">Результат:</td><td><?= htmlspecialchars($team_comp['result']) ?></td></tr>
        </table>
        <form method="POST">
            <input type="hidden" name="team_competition_id" value="<?= htmlspecialchars($team_competition_id) ?>">
            <input type="hidden" name="action_delete" value="1">
            <br>
            <button type="submit" class="btn btn-danger" onclick="return confirm('Вы абсолютно уверены?');">Да, удалить запись</button>
            <a href="team_competitions.php" class="btn btn-secondary">Отмена</a>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
