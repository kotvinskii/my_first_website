<?php
$pdo = new PDO('mysql:host=localhost;dbname=x91147go_base', 'x91147go_base', 'Sport_for!u34');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$errors = [];
$team_id = trim($_POST['team_id'] ?? $_GET['team_id'] ?? '');
$team = null;
require_once 'auth_check.php';
if (!empty($team_id)) {
    $stmt = $pdo->prepare("SELECT * FROM Teams WHERE team_id = ?");
    $stmt->execute([$team_id]);
    $team = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$team) {
        $errors['team_id'] = "Команда с ID $team_id не найдена.";
        $team_id = '';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_delete'])) {
    if (!empty($team_id) && $team) {
        try {
            $stmt = $pdo->prepare("DELETE FROM Teams WHERE team_id = ?");
            $stmt->execute([$team_id]);
            header("Location: teams.php?msg=deleted");
            exit;
        } catch(PDOException $e) {
            $errors['db'] = 'Ошибка удаления: возможно, запись используется в командных соревнованиях. ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset='utf-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Удаление команды</title>
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
    <h1>Управление командами</h1>
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

    <?php if (empty($team_id)): ?>
        <h2>Форма удаления команды</h2>
        <form method="POST">
            <table style="border: none;">
                <tr style="border: none;">
                    <td style="border: none;" align="right">Введите ID команды для удаления:</td>
                    <td style="border: none;"><input type="number" name="team_id" min="1" required></td>
                </tr>
                <tr style="border: none;">
                    <td colspan="2" align="center" style="border: none;">
                        <br>
                        <button type="submit" class="btn btn-danger">Найти запись</button>
                        <a href="teams.php" class="btn btn-secondary">Отмена</a>
                    </td>
                </tr>
            </table>
        </form>
    <?php else: ?>
        <div class="warning-box">
            <h3 style="margin-top:0; color: #721c24;">⚠️ Предупреждение!</h3>
            <p>Вы действительно хотите безвозвратно удалить следующую запись?</p>
        </div>
        <h3>Информация о команде №<?= htmlspecialchars($team_id) ?></h3>
        <table>
            <tr><td class="label">ID Команды:</td><td><?= htmlspecialchars($team['team_id']) ?></td></tr>
            <tr><td class="label">Название команды:</td><td><?= htmlspecialchars($team['team_name']) ?></td></tr>
            <tr><td class="label">ID Спорта:</td><td><?= htmlspecialchars($team['sport_id']) ?></td></tr>
            <tr><td class="label">ID Клуба:</td><td><?= htmlspecialchars($team['club_id']) ?></td></tr>
            <tr><td class="label">ID Тренера:</td><td><?= htmlspecialchars($team['coach_id']) ?></td></tr>
        </table>
        <form method="POST">
            <input type="hidden" name="team_id" value="<?= htmlspecialchars($team_id) ?>">
            <input type="hidden" name="action_delete" value="1">
            <br>
            <button type="submit" class="btn btn-danger" onclick="return confirm('Вы абсолютно уверены?');">Да, удалить запись</button>
            <a href="teams.php" class="btn btn-secondary">Отмена</a>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
