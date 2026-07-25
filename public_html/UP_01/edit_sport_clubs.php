<?php
$pdo = new PDO('mysql:host=localhost;dbname=x91147go_base', 'x91147go_base', 'Sport_for!u34');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
require_once 'auth_check.php';
$errors = [];
$club_id = trim($_POST['club_id'] ?? $_GET['club_id'] ?? '');
$club = null;

if (!empty($club_id)) {
    $stmt = $pdo->prepare("SELECT * FROM SportsClubs WHERE club_id = ?");
    $stmt->execute([$club_id]);
    $club = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$club) { $errors['club_id'] = "Клуб не найден."; $club_id = ''; }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_save'])) {
    $club_name = trim($_POST['club_name'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $founded_year = trim($_POST['founded_year'] ?? '');
    $president = trim($_POST['president'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $club_image = trim($_POST['sportclub_image'] ?? '');
    if(empty($club_name)){ $errors['club_name'] = 'Заполните название'; }

    if (empty($errors)) {
        try {
            $pdo->prepare("UPDATE SportsClubs SET club_name=?, city=?, founded_year=?, president=?, phone=?, email=?, sportclub_image=? WHERE club_id=?")
                ->execute([$club_name, $city, $founded_year, $president, $phone, $email, $club_id, $club_image]);
            header("Location: sports_clubs.php?msg=updated");
            exit;
        } catch(PDOException $e) { $errors['db'] = $e->getMessage(); }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head><meta charset='utf-8'><title>Редактирование клуба</title><style>.error-box { color: #721c24; background-color: #f8d7da; padding: 10px; margin: 10px auto; width: 50%; } .btn { padding: 6px 12px; text-decoration: none; } .btn-primary { background-color: #007bff; color: white; border: none; } .btn-secondary { background-color: #6c757d; color: white; margin-left: 10px; }</style></head>
<body>
<div align="center">
    <h1>Редактирование клуба</h1>
    <?php if(!empty($errors)): ?><div class="error-box"><ul><?php foreach($errors as $e): ?><li><?=htmlspecialchars($e)?></li><?php endforeach; ?></ul></div><?php endif; ?>

    <?php if (empty($club_id)): ?>
        <form method="POST">
            <table>
                <tr><td>Введите ID клуба:</td><td><input type="number" name="club_id" required></td></tr>
                <tr><td colspan="2" align="center"><button type="submit" class="btn btn-primary">Найти</button><a href="competitions.php" class="btn btn-secondary">Отмена</a></td></tr>
            </table>
        </form>
    <?php else: ?>
        <form method="POST">
            <input type="hidden" name="club_id" value="<?=htmlspecialchars($club_id)?>">
            <input type="hidden" name="action_save" value="1">
            <table>
                <tr><td>Название:</td><td><input type="text" name="club_name" required value="<?=htmlspecialchars($_POST['club_name'] ?? $club['club_name'] ?? '')?>"></td></tr>
                <tr><td>Город:</td><td><input type="text" name="city" value="<?=htmlspecialchars($_POST['city'] ?? $club['city'] ?? '')?>"></td></tr>
                <tr><td>Год основания:</td><td><input type="number" name="founded_year" value="<?=htmlspecialchars($_POST['founded_year'] ?? $club['founded_year'] ?? '')?>"></td></tr>
                <tr><td>Президент:</td><td><input type="text" name="president" value="<?=htmlspecialchars($_POST['president'] ?? $club['president'] ?? '')?>"></td></tr>
                <tr><td>Телефон:</td><td><input type="text" name="phone" value="<?=htmlspecialchars($_POST['phone'] ?? $club['phone'] ?? '')?>"></td></tr>
                <tr><td>Email:</td><td><input type="email" name="email" value="<?=htmlspecialchars($_POST['email'] ?? $club['email'] ?? '')?>"></td></tr>
                <tr><td>Фото:</td><td><input type="file" name="sportclub_image" value="<?=htmlspecialchars($_POST['sportclub_image'] ?? $club['sportclub_image'] ?? '')?>"></td></tr>
                <tr><td colspan="2" align="center"><button type="submit" class="btn btn-primary">Сохранить</button><a href="competitions.php" class="btn btn-secondary">Отмена</a></td></tr>
            </table>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
