<?php
$pdo = new PDO('mysql:host=localhost;dbname=x91147go_base', 'x91147go_base', 'Sport_for!u34');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
require_once 'auth_check.php';
$errors = [];
$athlete_id = trim($_POST['athlete_id'] ?? $_GET['athlete_id'] ?? '');
$athlete = null;

if (!empty($athlete_id)) {
    $stmt = $pdo->prepare("SELECT * FROM Athletes WHERE athlete_id = ?");
    $stmt->execute([$athlete_id]);
    $athlete = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$athlete) { $errors['athlete_id'] = "Спортсмен не найден."; $athlete_id = ''; }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_save'])) {
    $last_name = trim($_POST['last_name'] ?? '');
    $first_name = trim($_POST['first_name'] ?? '');
    $middle_name = trim($_POST['middle_name'] ?? '');
    $birth_date = trim($_POST['birth_date'] ?? '');
    $rank = trim($_POST['rank'] ?? '');
    $achievements = trim($_POST['achievements'] ?? '');
    $club_id = trim($_POST['club_id'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $atl_image = trim($_POST['athlet_image'] ?? '');
    if(empty($last_name)){ $errors['last_name'] = 'Заполните фамилию'; }
    if(empty($first_name)){ $errors['first_name'] = 'Заполните имя'; }

    if (empty($errors)) {
        try {
            $pdo->prepare("UPDATE Athletes SET last_name=?, first_name=?, middle_name=?, birth_date=?, `rank`=?, achievements=?, club_id=?, phone=?, email=?, athlet_image=?WHERE athlete_id=?")
                ->execute([$last_name, $first_name, $middle_name, $birth_date, $rank, $achievements, $club_id ?: null, $phone, $email, $atl_image]);
            header("Location: sportsmans.php?msg=updated");
            exit;
        } catch(PDOException $e) { $errors['db'] = $e->getMessage(); }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head><meta charset='utf-8'><title>Редактирование спортсмена</title><style>.error-box { color: #721c24; background-color: #f8d7da; padding: 10px; margin: 10px auto; width: 50%; } .btn { padding: 6px 12px; text-decoration: none; } .btn-primary { background-color: #007bff; color: white; border: none; } .btn-secondary { background-color: #6c757d; color: white; margin-left: 10px; }</style></head>
<body>
<div align="center">
    <h1>Редактирование спортсмена</h1>
    <?php if(!empty($errors)): ?><div class="error-box"><ul><?php foreach($errors as $e): ?><li><?=htmlspecialchars($e)?></li><?php endforeach; ?></ul></div><?php endif; ?>

    <?php if (empty($athlete_id)): ?>
        <form method="POST">
            <table>
                <tr><td>Введите ID спортсмена:</td><td><input type="number" name="athlete_id" required></td></tr>
                <tr><td colspan="2" align="center"><button type="submit" class="btn btn-primary">Найти</button><a href="competitions.php" class="btn btn-secondary">Отмена</a></td></tr>
            </table>
        </form>
    <?php else: ?>
        <form method="POST">
            <input type="hidden" name="athlete_id" value="<?=htmlspecialchars($athlete_id)?>">
            <input type="hidden" name="action_save" value="1">
            <table>
                <tr><td>Фамилия:</td><td><input type="text" name="last_name" required value="<?=htmlspecialchars($_POST['last_name'] ?? $athlete['last_name'] ?? '')?>"></td></tr>
                <tr><td>Имя:</td><td><input type="text" name="first_name" required value="<?=htmlspecialchars($_POST['first_name'] ?? $athlete['first_name'] ?? '')?>"></td></tr>
                <tr><td>Отчество:</td><td><input type="text" name="middle_name" value="<?=htmlspecialchars($_POST['middle_name'] ?? $athlete['middle_name'] ?? '')?>"></td></tr>
                <tr><td>Дата рождения:</td><td><input type="date" name="birth_date" value="<?=htmlspecialchars(substr($_POST['birth_date'] ?? $athlete['birth_date'] ?? '', 0, 10))?>"></td></tr>
                <tr><td>Разряд:</td><td><input type="text" name="rank" value="<?=htmlspecialchars($_POST['rank'] ?? $athlete['rank'] ?? '')?>"></td></tr>
                <tr><td>Достижения:</td><td><input type="text" name="achievements" value="<?=htmlspecialchars($_POST['achievements'] ?? $athlete['achievements'] ?? '')?>"></td></tr>
                <tr><td>ID Клуба:</td><td><input type="number" name="club_id" value="<?=htmlspecialchars($_POST['club_id'] ?? $athlete['club_id'] ?? '')?>"></td></tr>
                <tr><td>Телефон:</td><td><input type="text" name="phone" value="<?=htmlspecialchars($_POST['phone'] ?? $athlete['phone'] ?? '')?>"></td></tr>
                <tr><td>Email:</td><td><input type="email" name="email" value="<?=htmlspecialchars($_POST['email'] ?? $athlete['email'] ?? '')?>"></td></tr>
                <tr><td>Фото:</td><td><input type="file" name="athlet_image" value="<?=htmlspecialchars($_POST['athlet_image'] ?? $athlete['athlet_image'] ?? '')?>"></td></tr>
                <tr><td colspan="2" align="center"><button type="submit" class="btn btn-primary">Сохранить</button><a href="competitions.php" class="btn btn-secondary">Отмена</a></td></tr>
            </table>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
