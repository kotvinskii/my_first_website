<?php
$pdo = new PDO('mysql:host=localhost;dbname=x91147go_base', 'x91147go_base', 'Sport_for!u34');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
require_once 'auth_check.php';
$errors = [];
$sport_id = trim($_POST['sport_id'] ?? $_GET['sport_id'] ?? '');
$sport = null;

if (!empty($sport_id)) {
    $stmt = $pdo->prepare("SELECT * FROM Sports WHERE sport_id = ?");
    $stmt->execute([$sport_id]);
    $sport = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$sport) { $errors['sport_id'] = "Вид спорта не найден."; $sport_id = ''; }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_save'])) {
    $sport_name = trim($_POST['sport_name'] ?? '');
    $sport_category = trim($_POST['sport_category'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $olympic_sport = isset($_POST['olympic_sport']) ? 1 : 0;
    $sport_img = trim($_POST['sport_image'] ?? '');
    if(empty($sport_name)){ $errors['sport_name'] = 'Заполните название'; }

    if (empty($errors)) {
        try {
            $pdo->prepare("UPDATE Sports SET sport_name=?, sport_category=?, description=?, olympic_sport=?, sport_image=? WHERE sport_id=?")
                ->execute([$sport_name, $sport_category, $description, $olympic_sport, $sport_id, $sport_img]);
            header("Location: sports.php?msg=updated");
            exit;
        } catch(PDOException $e) { $errors['db'] = $e->getMessage(); }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head><meta charset='utf-8'><title>Редактирование спорта</title><style>.error-box { color: #721c24; background-color: #f8d7da; padding: 10px; margin: 10px auto; width: 50%; } .btn { padding: 6px 12px; text-decoration: none; } .btn-primary { background-color: #007bff; color: white; border: none; } .btn-secondary { background-color: #6c757d; color: white; margin-left: 10px; }</style></head>
<body>
<div align="center">
    <h1>Редактирование вида спорта</h1>
    <?php if(!empty($errors)): ?><div class="error-box"><ul><?php foreach($errors as $e): ?><li><?=htmlspecialchars($e)?></li><?php endforeach; ?></ul></div><?php endif; ?>

    <?php if (empty($sport_id)): ?>
        <form method="POST">
            <table>
                <tr><td>Введите ID спорта:</td><td><input type="number" name="sport_id" required></td></tr>
                <tr><td colspan="2" align="center"><button type="submit" class="btn btn-primary">Найти</button><a href="competitions.php" class="btn btn-secondary">Отмена</a></td></tr>
            </table>
        </form>
    <?php else: ?>
        <form method="POST">
            <input type="hidden" name="sport_id" value="<?=htmlspecialchars($sport_id)?>">
            <input type="hidden" name="action_save" value="1">
            <table>
                <tr><td>Название:</td><td><input type="text" name="sport_name" required value="<?=htmlspecialchars($_POST['sport_name'] ?? $sport['sport_name'] ?? '')?>"></td></tr>
                <tr><td>Категория:</td><td><input type="text" name="sport_category" value="<?=htmlspecialchars($_POST['sport_category'] ?? $sport['sport_category'] ?? '')?>"></td></tr>
                <tr><td>Описание:</td><td><input type="text" name="description" value="<?=htmlspecialchars($_POST['description'] ?? $sport['description'] ?? '')?>"></td></tr>
                <tr><td>Олимпийский спорт:</td><td><input type="checkbox" name="olympic_sport" value="1" <?=($_POST['olympic_sport'] ?? $sport['olympic_sport'] ?? 0) ? 'checked' : ''?>></td></tr>
                <tr><td>Фото:</td><td><input type="file" name="sport_image" required value="<?=htmlspecialchars($_POST['sport_image'] ?? $sport['sport_image'] ?? '')?>"></td></tr>
                <tr><td colspan="2" align="center"><button type="submit" class="btn btn-primary">Сохранить</button><a href="competitions.php" class="btn btn-secondary">Отмена</a></td></tr>
            </table>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
