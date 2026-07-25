<?php
$pdo = new PDO('mysql:host=localhost;dbname=x91147go_base', 'x91147go_base', 'Sport_for!u34');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
require_once 'auth_check.php';
$errors = [];
$competition_id = trim($_POST['competition_id'] ?? $_GET['competition_id'] ?? '');
$competition = null;

if (!empty($competition_id)) {
    $stmt = $pdo->prepare("SELECT * FROM Competitions_new WHERE competition_id = ?");
    $stmt->execute([$competition_id]);
    $competition = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$competition) { $errors['competition_id'] = "Соревнование не найдено."; $competition_id = ''; }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_save'])) {
    $competition_name = trim($_POST['competition_name'] ?? '');
    $sport_id = trim($_POST['sport_id'] ?? '');
    $facility_id = trim($_POST['facility_id'] ?? '');
    $organizer_id = trim($_POST['organizer_id'] ?? '');
    $competition_date = trim($_POST['competition_date'] ?? '');
    $competition_level = trim($_POST['competition_level'] ?? '');
    $participants_count = trim($_POST['participants_count'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if(empty($competition_name)){ $errors['competition_name'] = 'Заполните название'; }

    if (empty($errors)) {
        try {
            $pdo->prepare("UPDATE Competitions_new SET competition_name=?, sport_id=?, facility_id=?, organizer_id=?, competition_date=?, competition_level=?, participants_count=?, description=? WHERE competition_id=?")
                ->execute([$competition_name, $sport_id, $facility_id, $organizer_id, $competition_date, $competition_level, $participants_count ?: 0, $description, $competition_id]);
            header("Location: competitions.php?msg=updated");
            exit;
        } catch(PDOException $e) { $errors['db'] = $e->getMessage(); }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head><meta charset='utf-8'><title>Редактирование соревнований</title><style>.error-box { color: #721c24; background-color: #f8d7da; padding: 10px; margin: 10px auto; width: 50%; } .btn { padding: 6px 12px; text-decoration: none; } .btn-primary { background-color: #007bff; color: white; border: none; } .btn-secondary { background-color: #6c757d; color: white; margin-left: 10px; }</style></head>
<body>
<div align="center">
    <h1>Редактирование соревнования</h1>
    <?php if(!empty($errors)): ?><div class="error-box"><ul><?php foreach($errors as $e): ?><li><?=htmlspecialchars($e)?></li><?php endforeach; ?></ul></div><?php endif; ?>

    <?php if (empty($competition_id)): ?>
        <form method="POST">
            <table>
                <tr><td>Введите ID соревнований:</td><td><input type="number" name="competition_id" required></td></tr>
                <tr><td colspan="2" align="center"><button type="submit" class="btn btn-primary">Найти</button><a href="competitions.php" class="btn btn-secondary">Отмена</a></td></tr>
            </table>
        </form>
    <?php else: ?>
        <form method="POST">
            <input type="hidden" name="competition_id" value="<?=htmlspecialchars($competition_id)?>">
            <input type="hidden" name="action_save" value="1">
            <table>
                <tr><td>Название:</td><td><input type="text" name="competition_name" required value="<?=htmlspecialchars($_POST['competition_name'] ?? $competition['competition_name'] ?? '')?>"></td></tr>
                <tr><td>ID Спорта:</td><td><input type="number" name="sport_id" required value="<?=htmlspecialchars($_POST['sport_id'] ?? $competition['sport_id'] ?? '')?>"></td></tr>
                <tr><td>ID Объекта:</td><td><input type="number" name="facility_id" required value="<?=htmlspecialchars($_POST['facility_id'] ?? $competition['facility_id'] ?? '')?>"></td></tr>
                <tr><td>ID Организатора:</td><td><input type="number" name="organizer_id" required value="<?=htmlspecialchars($_POST['organizer_id'] ?? $competition['organizer_id'] ?? '')?>"></td></tr>
                <tr><td>Дата проведения:</td><td><input type="datetime-local" name="competition_date" required value="<?=htmlspecialchars(str_replace(' ', 'T', substr($_POST['competition_date'] ?? $competition['competition_date'] ?? '', 0, 16)))?>"></td></tr>
                <tr><td>Уровень:</td><td><input type="text" name="competition_level" value="<?=htmlspecialchars($_POST['competition_level'] ?? $competition['competition_level'] ?? '')?>"></td></tr>
                <tr><td>Кол-во участников:</td><td><input type="number" name="participants_count" value="<?=htmlspecialchars($_POST['participants_count'] ?? $competition['participants_count'] ?? '')?>"></td></tr>
                <tr><td>Описание:</td><td><input type="text" name="description" value="<?=htmlspecialchars($_POST['description'] ?? $competition['description'] ?? '')?>"></td></tr>
                <tr><td colspan="2" align="center"><button type="submit" class="btn btn-primary">Сохранить</button><a href="competitions.php" class="btn btn-secondary">Отмена</a></td></tr>
            </table>
        </form>
    <?php endif; ?>
</div>
</body>
</html>

