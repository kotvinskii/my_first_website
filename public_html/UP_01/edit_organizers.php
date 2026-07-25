<?php
$pdo = new PDO('mysql:host=localhost;dbname=x91147go_base', 'x91147go_base', 'Sport_for!u34');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
require_once 'auth_check.php';
$errors = [];
$organizer_id = trim($_POST['organizer_id'] ?? $_GET['organizer_id'] ?? '');
$organizer = null;

if (!empty($organizer_id)) {
    $stmt = $pdo->prepare("SELECT * FROM Organizers WHERE organizer_id = ?");
    $stmt->execute([$organizer_id]);
    $organizer = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$organizer) { $errors['organizer_id'] = "Организатор не найден."; $organizer_id = ''; }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_save'])) {
    $name = trim($_POST['name'] ?? '');
    $contact_person = trim($_POST['contact_person'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');

    if(empty($name)){ $errors['name'] = 'Заполните название организации'; }

    if (empty($errors)) {
        try {
            $pdo->prepare("UPDATE Organizers SET name=?, contact_person=?, phone=?, email=?, address=? WHERE organizer_id=?")
                ->execute([$name, $contact_person, $phone, $email, $address, $organizer_id]);
            header("Location: organizers.php?msg=updated");
            exit;
        } catch(PDOException $e) { $errors['db'] = $e->getMessage(); }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head><meta charset='utf-8'><title>Редактирование организатора</title><style>.error-box { color: #721c24; background-color: #f8d7da; padding: 10px; margin: 10px auto; width: 50%; } .btn { padding: 6px 12px; text-decoration: none; } .btn-primary { background-color: #007bff; color: white; border: none; } .btn-secondary { background-color: #6c757d; color: white; margin-left: 10px; }</style></head>
<body>
<div align="center">
    <h1>Редактирование организатора</h1>
    <?php if(!empty($errors)): ?><div class="error-box"><ul><?php foreach($errors as $e): ?><li><?=htmlspecialchars($e)?></li><?php endforeach; ?></ul></div><?php endif; ?>

    <?php if (empty($organizer_id)): ?>
        <form method="POST">
            <table>
                <tr><td>Введите ID организатора:</td><td><input type="number" name="organizer_id" required></td></tr>
                <tr><td colspan="2" align="center"><button type="submit" class="btn btn-primary">Найти</button><a href="competitions.php" class="btn btn-secondary">Отмена</a></td></tr>
            </table>
        </form>
    <?php else: ?>
        <form method="POST">
            <input type="hidden" name="organizer_id" value="<?=htmlspecialchars($organizer_id)?>">
            <input type="hidden" name="action_save" value="1">
            <table>
                <tr><td>Организация:</td><td><input type="text" name="name" required value="<?=htmlspecialchars($_POST['name'] ?? $organizer['name'] ?? '')?>"></td></tr>
                <tr><td>Контактное лицо:</td><td><input type="text" name="contact_person" value="<?=htmlspecialchars($_POST['contact_person'] ?? $organizer['contact_person'] ?? '')?>"></td></tr>
                <tr><td>Телефон:</td><td><input type="text" name="phone" value="<?=htmlspecialchars($_POST['phone'] ?? $organizer['phone'] ?? '')?>"></td></tr>
                <tr><td>Email:</td><td><input type="email" name="email" value="<?=htmlspecialchars($_POST['email'] ?? $organizer['email'] ?? '')?>"></td></tr>
                <tr><td>Адрес:</td><td><input type="text" name="address" value="<?=htmlspecialchars($_POST['address'] ?? $organizer['address'] ?? '')?>"></td></tr>
                <tr><td colspan="2" align="center"><button type="submit" class="btn btn-primary">Сохранить</button><a href="competitions.php" class="btn btn-secondary">Отмена</a></td></tr>
            </table>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
