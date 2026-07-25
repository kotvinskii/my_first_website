<?php
$pdo = new PDO('mysql:host=localhost;dbname=x91147go_base', 'x91147go_base', 'Sport_for!u34');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
require_once 'auth_check.php';
$errors = [];
$trainer_id = trim($_POST['trainer_id'] ?? $_GET['trainer_id'] ?? '');
$trainer = null;

if (!empty($trainer_id)) {
    $stmt = $pdo->prepare("SELECT * FROM Trainers WHERE trainer_id = ?");
    $stmt->execute([$trainer_id]);
    $trainer = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$trainer) { $errors['trainer_id'] = "Тренер не найден."; $trainer_id = ''; }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_save'])) {
    $last_name = trim($_POST['last_name'] ?? '');
    $first_name = trim($_POST['first_name'] ?? '');
    $middle_name = trim($_POST['middle_name'] ?? '');
    $qualification = trim($_POST['qualification'] ?? '');
    $experience_years = trim($_POST['experience_years'] ?? '');
    $achievements = trim($_POST['achievements'] ?? '');
    $club_id = trim($_POST['club_id'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $photo = trim($_POST['trainer_image'] ?? '');

    if(empty($last_name)){ $errors['last_name'] = 'Заполните фамилию'; }
    if(empty($first_name)){ $errors['first_name'] = 'Заполните имя'; }

    if (empty($errors)) {
        try {
            $pdo->prepare("UPDATE Trainers SET last_name=?, first_name=?, middle_name=?, qualification=?, experience_years=?, achievements=?, club_id=?, phone=?, email=?, trainer_image=? WHERE trainer_id=?")
                ->execute([$last_name, $first_name, $middle_name, $qualification, $experience_years ?: 0, $achievements, $club_id ?: null, $phone, $email, $photo]);
            header("Location: trainers.php?msg=updated");
            exit;
        } catch(PDOException $e) { $errors['db'] = $e->getMessage(); }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head><meta charset='utf-8'><title>Редактирование тренера</title><style>.error-box { color: #721c24; background-color: #f8d7da; padding: 10px; margin: 10px auto; width: 50%; } .btn { padding: 6px 12px; text-decoration: none; } .btn-primary { background-color: #007bff; color: white; border: none; } .btn-secondary { background-color: #6c757d; color: white; margin-left: 10px; }</style></head>
<body>
<div align="center">
    <h1>Редактирование тренера</h1>
    <?php if(!empty($errors)): ?><div class="error-box"><ul><?php foreach($errors as $e): ?><li><?=htmlspecialchars($e)?></li><?php endforeach; ?></ul></div><?php endif; ?>

    <?php if (empty($trainer_id)): ?>
        <form method="POST">
            <table>
                <tr><td>Введите ID тренера:</td><td><input type="number" name="trainer_id" required></td></tr>
                <tr><td colspan="2" align="center"><button type="submit" class="btn btn-primary">Найти</button><a href="competitions.php" class="btn btn-secondary">Отмена</a></td></tr>
            </table>
        </form>
    <?php else: ?>
        <form method="POST">
            <input type="hidden" name="trainer_id" value="<?=htmlspecialchars($trainer_id)?>">
            <input type="hidden" name="action_save" value="1">
            <table>
                <tr><td>Фамилия:</td><td><input type="text" name="last_name" required value="<?=htmlspecialchars($_POST['last_name'] ?? $trainer['last_name'] ?? '')?>"></td></tr>
                <tr><td>Имя:</td><td><input type="text" name="first_name" required value="<?=htmlspecialchars($_POST['first_name'] ?? $trainer['first_name'] ?? '')?>"></td></tr>
                <tr><td>Отчество:</td><td><input type="text" name="middle_name" value="<?=htmlspecialchars($_POST['middle_name'] ?? $trainer['middle_name'] ?? '')?>"></td></tr>
                <tr><td>Квалификация:</td><td><input type="text" name="qualification" value="<?=htmlspecialchars($_POST['qualification'] ?? $trainer['qualification'] ?? '')?>"></td></tr>
                <tr><td>Стаж (лет):</td><td><input type="number" name="experience_years" value="<?=htmlspecialchars($_POST['experience_years'] ?? $trainer['experience_years'] ?? '')?>"></td></tr>
                <tr><td>Достижения:</td><td><input type="text" name="achievements" value="<?=htmlspecialchars($_POST['achievements'] ?? $trainer['achievements'] ?? '')?>"></td></tr>
                <tr><td>ID Клуба:</td><td><input type="number" name="club_id" value="<?=htmlspecialchars($_POST['club_id'] ?? $trainer['club_id'] ?? '')?>"></td></tr>
                <tr><td>Телефон:</td><td><input type="text" name="phone" value="<?=htmlspecialchars($_POST['phone'] ?? $trainer['phone'] ?? '')?>"></td></tr>
                <tr><td>Email:</td><td><input type="email" name="email" value="<?=htmlspecialchars($_POST['email'] ?? $trainer['email'] ?? '')?>"></td></tr>
                <tr><td>Фото:</td><td><input type="file" name="trainer_image" value="<?=htmlspecialchars($_POST['trainer_image'] ?? $trainer['trainer_image'] ?? '')?>"></td></tr>
                <tr><td colspan="2" align="center"><button type="submit" class="btn btn-primary">Сохранить</button><a href="competitions.php" class="btn btn-secondary">Отмена</a></td></tr>
            </table>
        </form>
    <?php endif; ?>
</div>
</body>
</html>

